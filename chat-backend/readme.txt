# 1. Install Python (if not already installed)
brew install python

# 2. Create a virtual environment
python3 -m venv venv

# 3. Activate the virtual environment
source venv/bin/activate

# 4. Install dependencies from requirements.txt
pip install -r requirements.txt

# 5. Run the FastAPI app with uvicorn on port 8001 (change host/port as needed)
# Example (from the chat-backend folder):
# Activate your virtualenv first (if not already active):
# source venv/bin/activate
# Then run:
# uvicorn app.main:app --host 127.0.0.1 --port 8001 --reload


