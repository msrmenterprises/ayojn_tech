from dotenv import load_dotenv
import os
from typing import Dict, Any, List
from rapidfuzz import process, fuzz
load_dotenv()
import logging
logger = logging.getLogger(__name__)

try:
    import mysql.connector
except Exception:
    mysql = None


def fetch_faqs_from_db(language: str = "en") -> List[Dict[str, Any]]:
    """Fetch FAQs from MySQL and return list of dicts with keys id, question, answer."""
    if mysql is None:
        raise RuntimeError("mysql-connector-python is not installed. Install with: pip install mysql-connector-python")

    try:
        cfg = {
            "host": os.environ.get("DB_HOST", "127.0.0.1"),
            "user": os.environ.get("DB_USERNAME", "root"),
            "password": os.environ.get("DB_PASSWORD", ""),
            "database": os.environ.get("DB_DATABASE", "ayojn"),
        }

        # allow custom port and unix_socket if provided
        if os.environ.get("DB_PORT"):
            cfg["port"] = int(os.environ.get("DB_PORT"))
        if os.environ.get("DB_SOCKET"):
            cfg["unix_socket"] = os.environ.get("DB_SOCKET")

        conn = mysql.connector.connect(**cfg)
        cursor = conn.cursor(dictionary=True)
        
        cursor.execute("SELECT id, question, answer FROM faqs WHERE language = %s", (language,))
        rows = cursor.fetchall()
        
        logger.info(f"Fetched {len(rows)} FAQs for language: {language}")    
        return rows or []
    
    except mysql.connector.Error as e:
        logger.error(f"Database error: {e}")
        return []
    finally:
        if cursor:
            cursor.close()
        if conn:
            conn.close()


def get_best_faq_answer(user_question: str, threshold: int = 60, language: str = "en") -> Dict[str, Any]:
    """Return structured match result.

    Returns a dict:
      {
        'found': bool,
        'question': str | None,    # best matched FAQ question
        'answer': str | None,      # best matched FAQ answer (only if found)
        'score': int,              # 0-100
        'best_question': str | None # best question even if below threshold
      }
    """
    user_q = (user_question or "").strip()
    if not user_q:
        return {
            "found": False,
            "question": None,
            "answer": None,
            "score": 0,
            "best_question": None,
            "escalate": True  # ← NEW
        }

    faqs = fetch_faqs_from_db(language=language)
    if not faqs:
        return {
            "found": False,
            "question": None,
            "answer": None,
            "score": 0,
            "best_question": None,
            "escalate": True  # ← NEW
        }

    questions = [r.get("question", "") for r in faqs]

    match = process.extractOne(user_q, questions, scorer=fuzz.token_set_ratio)
    if not match:
        return {"found": False, "question": None, "answer": None, "score": 0, "best_question": None}

    matched_text, score, idx = match
    best = faqs[idx]
    score_int = int(score)

    logger.debug(f"User question: '{user_q}' | Best match score: {score_int}")
    if score_int >= int(threshold):
        return {
            "found": True,
            "question": best.get("question"),
            "answer": best.get("answer"),
            "score": score_int,
            "best_question": best.get("question"),
            "escalate": False  # ← NEW
        }
    else:
        return {
            "found": False,
            "question": None,
            "answer": None,
            "score": score_int,
            "best_question": best.get("question"),
            "escalate": True  # ← NEW - trigger agent escalation
        }
