from fastapi import FastAPI, HTTPException, Query
from fastapi.middleware.cors import CORSMiddleware
from pydantic import BaseModel
from app.faq_matcher import get_best_faq_answer  # keep original as fallback
from app.faq_matcher_db import get_best_faq_answer as get_best_faq_answer_db
from app.escalation import (
    create_support_ticket,
    get_agent_tickets,
    get_ticket_detail,
    get_ticket_messages,
    add_agent_message,
    add_customer_message
)

app = FastAPI()

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_methods=["*"],
    allow_headers=["*"],
)

class ChatRequest(BaseModel):
    message: str
    
class FaqMatchRequest(BaseModel):
    question: str
    language: str = "en"
    threshold: int = 60

class AgentReplyRequest(BaseModel):
    message: str
    agent_id: int

@app.get("/")
def read_root():
    return {"message": "Hello World from FastAPI"}

@app.post("/chat")
def chat(request: ChatRequest):
    reply = get_best_faq_answer(request.message) 
    return {"reply": reply}

# @app.post("/api/faqs/match")
# def faq_match(request: FaqMatchRequest):
#     # basic validation
#     question = (request.question or "").strip()
#     if not question:
#         return {"success": False, "message": "Missing 'question' in request"}

#     # delegate to DB-backed matcher (use threshold and language)
#     try:
#         result = get_best_faq_answer_db(question, threshold=request.threshold, language=request.language)
#     except Exception as e:
#         return {"success": False, "error": str(e)}

#     # return structured matcher result
#     return {"success": True, "result": result}

@app.post("/api/faqs/match")
def faq_match(request: FaqMatchRequest):
    """Match FAQ or escalate to live agent"""
    question = (request.question or "").strip()
    if not question:
        return {"success": False, "message": "Missing 'question' in request"}

    try:
        result = get_best_faq_answer(question, threshold=request.threshold, language=request.language)
    except Exception as e:
        return {"success": False, "error": str(e)}

    # If no match found, escalate to live agent
    if not result.get("found"):
        try:
            ticket = create_support_ticket(
                user_question=question,
                match_score=result.get("score", 0),
                language=request.language,
                faq_status="no_match"
            )
            return {
                "success": True,
                "result": result,
                "escalated": True,
                "ticket_id": ticket["ticket_id"],
                "assigned_agent": ticket["assigned_agent_name"],
                "message": "Your question has been escalated to a live agent"
            }
        except Exception as e:
            return {
                "success": False,
                "escalated": False,
                "error": f"Escalation failed: {str(e)}"
            }

    return {"success": True, "result": result, "escalated": False}

# ===== LIVE AGENT ENDPOINTS =====

@app.get("/api/support/agents/{agent_id}/tickets")
def get_agent_support_tickets(agent_id: int):
    """Get all open tickets for an agent"""
    try:
        tickets = get_agent_tickets(agent_id)
        return {"success": True, "tickets": tickets}
    except Exception as e:
        return {"success": False, "error": str(e)}

@app.get("/api/support/tickets/{ticket_id}")
def get_support_ticket(ticket_id: int):
    """Get single ticket detail"""
    try:
        ticket = get_ticket_detail(ticket_id)
        if not ticket:
            raise HTTPException(status_code=404, detail="Ticket not found")
        return {"success": True, "ticket": ticket}
    except Exception as e:
        return {"success": False, "error": str(e)}

@app.get("/api/support/tickets/{ticket_id}/messages")
def get_support_messages(ticket_id: int):
    """Get all messages for a ticket"""
    try:
        messages = get_ticket_messages(ticket_id)
        return {"success": True, "messages": messages}
    except Exception as e:
        return {"success": False, "error": str(e)}

@app.post("/api/support/tickets/{ticket_id}/reply")
def post_support_reply(ticket_id: int, body: AgentReplyRequest):
    """Save agent reply"""
    try:
        if add_agent_message(ticket_id, body.message, body.agent_id):
            return {"success": True, "message": "Reply saved"}
        else:
            return {"success": False, "error": "Failed to save reply"}
    except Exception as e:
        return {"success": False, "error": str(e)}

class CustomerMessageRequest(BaseModel):
    message: str

@app.post("/api/support/tickets/{ticket_id}/customer-message")
def post_customer_message(ticket_id: int, body: CustomerMessageRequest):
    """Save customer follow-up message to existing ticket"""
    try:
        if add_customer_message(ticket_id, body.message):
            return {"success": True, "message": "Message saved"}
        else:
            return {"success": False, "error": "Failed to save message"}
    except Exception as e:
        return {"success": False, "error": str(e)}
