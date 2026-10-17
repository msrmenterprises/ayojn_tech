import os
import logging
from typing import Dict, Any, List
import mysql.connector
from dotenv import load_dotenv

load_dotenv()
logger = logging.getLogger(__name__)

def _get_db_config():
    """Get database configuration"""
    cfg = {
        "host": os.environ.get("DB_HOST", "127.0.0.1"),
        "user": os.environ.get("DB_USERNAME", "root"),
        "password": os.environ.get("DB_PASSWORD", ""),
        "database": os.environ.get("DB_DATABASE", "ayojn"),
    }
    if os.environ.get("DB_PORT"):
        cfg["port"] = int(os.environ.get("DB_PORT"))
    if os.environ.get("DB_SOCKET"):
        cfg["unix_socket"] = os.environ.get("DB_SOCKET")
    return cfg


def _get_active_agents() -> List[Dict[str, Any]]:
    """Fetch all active agents (role_id = 3)"""
    try:
        print(f"[DEBUG] Fetching active agents with role_id=3...")
        cfg = _get_db_config()
        
        conn = mysql.connector.connect(**cfg)
        cursor = conn.cursor(dictionary=True)
        
        # Using role_id = 3 for agents
        cursor.execute(
            "SELECT id, name, email FROM users WHERE role_id = %s ORDER BY id",
            (3,)
        )
        agents = cursor.fetchall()
        print(f"[DEBUG] Found {len(agents or [])} agents: {agents}")
        cursor.close()
        conn.close()
        return agents or []
    except Exception as e:
        print(f"[ERROR] Error fetching agents: {e}")
        logger.error(f"Error fetching agents: {e}")
        return []


def _get_next_agent_round_robin() -> int:
    """
    Get next agent using round-robin assignment.
    Uses agent_counter table to track last assigned agent.
    """
    agents = _get_active_agents()
    if not agents:
        logger.warning("No active agents found for escalation")
        raise RuntimeError("No active agents available")

    agent_ids = [a["id"] for a in agents]
    
    try:
        conn = mysql.connector.connect(**_get_db_config())
        cursor = conn.cursor(dictionary=True)
        
        # Get last assigned agent
        cursor.execute("SELECT last_agent_id FROM agent_counter WHERE id = 1")
        row = cursor.fetchone()
        last_id = row.get("last_agent_id") if row else 0
        
        # Find current index
        try:
            current_idx = agent_ids.index(last_id)
        except ValueError:
            current_idx = -1
        
        # Get next agent (round-robin)
        next_idx = (current_idx + 1) % len(agent_ids)
        next_agent_id = agent_ids[next_idx]
        
        # Update counter
        cursor.execute(
            "UPDATE agent_counter SET last_agent_id = %s WHERE id = 1",
            (next_agent_id,)
        )
        conn.commit()
        cursor.close()
        conn.close()
        
        logger.info(f"Assigned ticket to agent {next_agent_id} (round-robin)")
        return next_agent_id
    except Exception as e:
        logger.error(f"Error in round-robin assignment: {e}")
        return agent_ids[0]  # Fallback to first agent


def create_support_ticket(
    user_question: str,
    match_score: int,
    language: str = "en",
    faq_status: str = "no_match"
) -> Dict[str, Any]:
    """
    Create a support ticket and assign to next available agent.
    
    Returns:
        {
            'ticket_id': int,
            'assigned_agent_id': int,
            'assigned_agent_name': str,
            'status': 'open' | 'assigned'
        }
    """
    try:
        # Get next agent via round-robin
        assigned_agent_id = _get_next_agent_round_robin()
        
        # Create ticket
        conn = mysql.connector.connect(**_get_db_config())
        cursor = conn.cursor()
        
        cursor.execute("""
            INSERT INTO support_tickets 
            (user_question, user_language, match_score, faq_status, assigned_agent_id, status)
            VALUES (%s, %s, %s, %s, %s, %s)
        """, (user_question, language, match_score, faq_status, assigned_agent_id, "assigned"))
        
        ticket_id = cursor.lastrowid
        
        # Insert initial user message
        cursor.execute("""
            INSERT INTO support_messages (ticket_id, sender_type, sender_id, message)
            VALUES (%s, %s, %s, %s)
        """, (ticket_id, "user", None, user_question))
        
        conn.commit()
        cursor.close()
        conn.close()
        
        # Get agent info
        agents = _get_active_agents()
        agent = next((a for a in agents if a["id"] == assigned_agent_id), None)
        agent_name = agent["name"] if agent else "Support Agent"
        
        logger.info(f"Created support ticket {ticket_id}, assigned to agent {assigned_agent_id}")
        
        return {
            "ticket_id": ticket_id,
            "assigned_agent_id": assigned_agent_id,
            "assigned_agent_name": agent_name,
            "status": "assigned"
        }
    except Exception as e:
        logger.error(f"Error creating support ticket: {e}")
        raise


def get_ticket_messages(ticket_id: int) -> List[Dict[str, Any]]:
    """Fetch all messages for a ticket"""
    try:
        conn = mysql.connector.connect(**_get_db_config())
        cursor = conn.cursor(dictionary=True)
        
        cursor.execute("""
            SELECT m.id, m.sender_type, m.sender_id, m.message, m.created_at,
                   u.name as sender_name
            FROM support_messages m
            LEFT JOIN users u ON m.sender_id = u.id
            WHERE m.ticket_id = %s
            ORDER BY m.created_at ASC
        """, (ticket_id,))
        
        messages = cursor.fetchall()
        cursor.close()
        conn.close()
        
        return messages or []
    except Exception as e:
        logger.error(f"Error fetching messages for ticket {ticket_id}: {e}")
        return []


def add_agent_message(ticket_id: int, message: str, agent_id: int) -> bool:
    """Save agent reply to ticket"""
    try:
        conn = mysql.connector.connect(**_get_db_config())
        cursor = conn.cursor()
        
        # Insert message
        cursor.execute("""
            INSERT INTO support_messages (ticket_id, sender_type, sender_id, message)
            VALUES (%s, %s, %s, %s)
        """, (ticket_id, "agent", agent_id, message))
        
        # Update ticket status
        cursor.execute(
            "UPDATE support_tickets SET status = %s WHERE id = %s",
            ("assigned", ticket_id)
        )
        
        conn.commit()
        cursor.close()
        conn.close()
        
        logger.info(f"Agent {agent_id} added message to ticket {ticket_id}")
        return True
    except Exception as e:
        logger.error(f"Error adding agent message: {e}")
        return False


def add_customer_message(ticket_id: int, message: str) -> bool:
    """Save customer follow-up message to existing ticket"""
    try:
        conn = mysql.connector.connect(**_get_db_config())
        cursor = conn.cursor()
        
        # Insert customer message
        cursor.execute("""
            INSERT INTO support_messages (ticket_id, sender_type, sender_id, message)
            VALUES (%s, %s, %s, %s)
        """, (ticket_id, "user", None, message))
        
        # Update ticket timestamp
        cursor.execute(
            "UPDATE support_tickets SET updated_at = NOW() WHERE id = %s",
            (ticket_id,)
        )
        
        conn.commit()
        cursor.close()
        conn.close()
        
        logger.info(f"Customer added follow-up message to ticket {ticket_id}")
        return True
    except Exception as e:
        logger.error(f"Error adding customer message: {e}")
        return False


def get_agent_tickets(agent_id: int) -> List[Dict[str, Any]]:
    """Get all open tickets assigned to an agent"""
    try:
        conn = mysql.connector.connect(**_get_db_config())
        cursor = conn.cursor(dictionary=True)
        
        cursor.execute("""
            SELECT id, user_question, match_score, status, created_at
            FROM support_tickets
            WHERE assigned_agent_id = %s AND status IN ('open', 'assigned')
            ORDER BY created_at DESC
        """, (agent_id,))
        
        tickets = cursor.fetchall()
        cursor.close()
        conn.close()
        
        return tickets or []
    except Exception as e:
        logger.error(f"Error fetching agent tickets: {e}")
        return []


def get_ticket_detail(ticket_id: int) -> Dict[str, Any]:
    """Get single ticket details"""
    try:
        conn = mysql.connector.connect(**_get_db_config())
        cursor = conn.cursor(dictionary=True)
        
        cursor.execute("""
            SELECT t.id, t.user_question, t.user_language, t.match_score, t.faq_status, 
                   t.assigned_agent_id, t.status, t.created_at,
                   u.name as agent_name, u.email as agent_email
            FROM support_tickets t
            LEFT JOIN users u ON t.assigned_agent_id = u.id
            WHERE t.id = %s
        """, (ticket_id,))
        
        ticket = cursor.fetchone()
        cursor.close()
        conn.close()
        
        return ticket or {}
    except Exception as e:
        logger.error(f"Error fetching ticket {ticket_id}: {e}")
        return {}