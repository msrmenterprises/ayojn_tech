<div id="chat-floating" class="chat-floating">
    <button id="chat-toggle" class="chat-toggle" aria-label="Open chat">
        <!-- chat bubble icon -->
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path d="M21 15a2 2 0 0 1-2 2H8l-5 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" fill="#0d6efd"/>
        </svg>
    </button>

    <div id="chat-box" class="chat-box" style="display:none;" role="dialog" aria-label="Chat dialog">
        <div class="chat-header">
            <div class="chat-title">Help & FAQs</div>
            <button id="chat-close" class="chat-close" aria-label="Close chat">✕</button>
        </div>
        <div class="chat-messages" id="chat-messages" aria-live="polite"></div>
        <div class="chat-input">
            <input type="text" id="chat-input-field" placeholder="Ask a question..." aria-label="Chat input" />
            <button id="chat-send" class="chat-send" disabled aria-label="Send message">
                <!-- send icon -->
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path d="M22 2L11 13" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M22 2l-7 20 2-9 9-9z" fill="#fff"/>
                </svg>
            </button>
        </div>
        <div class="chat-footer">Powered by Ayojn</div>
    </div>
</div>

<script>
    (function($){
        $(function(){
            function openChat(){
                $('#chat-box').show();
                $('#chat-toggle').hide();
                $('#chat-input-field').focus();
            }
            function closeChat(){
                $('#chat-box').hide();
                $('#chat-toggle').show();
            }

            $('#chat-toggle').on('click', function(){ openChat(); });
            $('#chat-close').on('click', function(){ closeChat(); });

            // input/send behavior is handled by public/js/chat-floating.js
            // keep this partial focused on UI (open/close) only

            // close on ESC
            $(document).on('keydown', function(e){ if(e.key === 'Escape'){ closeChat(); } });
        });
    })(jQuery);
</script>
