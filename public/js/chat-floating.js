(function($){
    $(function(){
    var endpoint = window.__CHAT_API_ENDPOINT__ || '/api/faqs/match';
    var apiBaseUrl = window.__CHAT_API_BASE_URL__;
    var currentTicketId = null;
    var pollingInterval = null;
    var lastMessageCount = 0;
    var pollingDelay = 30000; // Start with 30 seconds
    var maxPollingDelay = 90000; // Max 90 seconds
    var pollingBackoffRate = 1.8; // Increase delay by 80% each time
    var apiCallCount = 0; // Count API calls
    var maxApiCalls = 5; // Hard limit of 5 calls

        function appendBot(text){
            var safe = $('<div>').text(text).html();
            $('#chat-messages').append('<div class="chat-message chat-message-bot">'+ safe +'</div>');
            // scroll
            var el = document.getElementById('chat-messages'); el.scrollTop = el.scrollHeight;
        }

        function appendUser(text){
            var safe = $('<div>').text(text).html();
            $('#chat-messages').append('<div class="chat-message chat-message-user">'+ safe +'</div>');
            var el = document.getElementById('chat-messages'); el.scrollTop = el.scrollHeight;
        }

        function appendAgentMessage(text, agentName){
            var safe = $('<div>').text(text).html();
            var timestamp = new Date().toLocaleTimeString();
            $('#chat-messages').append(
                '<div class="chat-message chat-message-bot chat-agent-message">' +
                '<strong>👨‍💼 ' + (agentName || 'Support Agent') + '</strong> <small>(' + timestamp + ')</small><br/>' +
                safe + 
                '</div>'
            );
            var el = document.getElementById('chat-messages'); 
            el.scrollTop = el.scrollHeight;
        }

        function startPollingForAgentReplies(ticketId){
            if(pollingInterval){
                clearTimeout(pollingInterval);
            }
            
            currentTicketId = ticketId;
            pollingDelay = 30000; // Reset to default (max 5 calls per 5 min)
            apiCallCount = 0; // Reset call counter
            console.log('[chat] Starting to poll for agent replies on ticket #' + ticketId);
            
            // Initial check
            checkForNewMessages(ticketId);
            
            // Schedule next poll
            scheduleNextPoll(ticketId);
        }
        
        function scheduleNextPoll(ticketId){
            if(pollingInterval){
                clearTimeout(pollingInterval);
            }
            
            pollingInterval = setTimeout(function(){
                checkForNewMessages(ticketId);
                scheduleNextPoll(ticketId); // Schedule next one
            }, pollingDelay);
            
            console.log('[chat] Next poll in ' + (pollingDelay / 1000) + ' seconds');
        }

        function checkForNewMessages(ticketId){
            // Hard limit: stop after 5 API calls
            apiCallCount++;
            console.log('[chat] API Call #' + apiCallCount + ' of ' + maxApiCalls);
            
            if(apiCallCount > maxApiCalls){
                console.log('[chat] HARD STOP: Max ' + maxApiCalls + ' API calls reached. Stopping polling.');
                appendBot('⏱️ Request timed out. Agent did not respond. Please try again later or contact support.');
                stopPolling();
                return;
            }
            
            $.ajax({
                url: apiBaseUrl + '/api/support/tickets/' + ticketId + '/messages',
                method: 'GET',
                timeout: 5000,
                success: function(res){
                    if(res && res.success && res.messages){
                        var messages = res.messages;
                        
                        // Check if there are new messages
                        if(messages.length > lastMessageCount){
                            console.log('[chat] New messages detected:', messages.length - lastMessageCount);
                            
                            // Show only new agent messages
                            for(var i = lastMessageCount; i < messages.length; i++){
                                var msg = messages[i];
                                if(msg.sender_type === 'agent'){
                                    appendAgentMessage(msg.message, msg.sender_name);
                                }
                            }
                            
                            lastMessageCount = messages.length;
                            
                            // Reset to default polling when new message received
                            pollingDelay = 30000;
                            console.log('[chat] New message received - reset to default polling');
                        } else {
                            // No new messages - increase delay (exponential backoff)
                            pollingDelay = Math.min(pollingDelay * pollingBackoffRate, maxPollingDelay);
                            console.log('[chat] No new messages - increased delay to ' + (pollingDelay / 1000) + 's');
                        }
                    }
                },
                error: function(xhr, status, err){
                    console.error('[chat] Error checking for messages:', err);
                    // On error, slow down polling to avoid hammering the server
                    pollingDelay = Math.min(pollingDelay * 2, maxPollingDelay);
                }
            });
        }

        function stopPolling(){
            if(pollingInterval){
                clearTimeout(pollingInterval);
                pollingInterval = null;
                pollingDelay = 30000; // Reset delay
                apiCallCount = 0; // Reset call counter
                console.log('[chat] Stopped polling');
            }
        }

        function sendMessage(text){
            console.log('[chat] sending to endpoint:', endpoint, 'message:', text);
            appendUser(text);
            
            // Check if there's an active ticket conversation
            if(currentTicketId){
                console.log('[chat] Continuing conversation on ticket #' + currentTicketId);
                // show loading placeholder
                appendBot('...');
                var lastBot = $('#chat-messages .chat-message-bot').last();
                
                // Reset to default polling when customer sends new message
                pollingDelay = 30000;
                
                // Send as follow-up message to existing ticket
                $.ajax({
                    url: apiBaseUrl + '/api/support/tickets/' + currentTicketId + '/customer-message',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({ message: text }),
                    timeout: 10000,
                    success: function(res){
                        console.log('[chat] Customer message sent to ticket:', res);
                        lastBot.text('Message sent to agent. Waiting for reply...');
                        // Continue polling for agent response
                    },
                    error: function(xhr, status, err){
                        console.error('[chat] Error sending customer message:', err);
                        lastBot.text('Failed to send message. Please try again.');
                    }
                });
                
                // re-enable send if input not empty
                $('#chat-send').prop('disabled', $('#chat-input-field').val().trim() === '');
                return;
            }
            
            // Otherwise, proceed with normal FAQ match flow
            // show loading placeholder
            appendBot('...');
            var lastBot = $('#chat-messages .chat-message-bot').last();

            var payload = {
                "question": text,
                "language": window.__CHAT_LANGUAGE__ || 'en',
                "threshold": window.__CHAT_MATCH_THRESHOLD__ || 60
            };

            $.ajax({
                url: endpoint,
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(payload),
                timeout: 10000, // 10s
                success: function(res){
                    console.log('[chat] success response:', res);
                    if(res && res.success && res.result){
                        var r = res.result;
                        
                        // Handle escalation (new feature)
                        if(res.escalated){
                            var escalationMsg = res.message || 'Your question has been escalated to a live agent.';
                            escalationMsg += '\n\nTicket ID: #' + res.ticket_id;
                            escalationMsg += '\nAssigned Agent: ' + res.assigned_agent;
                            escalationMsg += '\n\nPlease wait, the agent will respond shortly...';
                            lastBot.html(escalationMsg.replace(/\n/g, '<br/>'));
                            
                            // Start polling for agent replies
                            lastMessageCount = 1; // Customer's initial question
                            startPollingForAgentReplies(res.ticket_id);
                        }
                        // Handle FAQ match found
                        else if(r.found){
                            lastBot.text(r.answer || '');
                            stopPolling(); // Stop polling if FAQ answered
                        } 
                        // Handle FAQ match not found but no escalation
                        else {
                            var fallback = 'Sorry, I could not find a good answer.';
                            if(r.best_question){
                                fallback += ' Closest match: "' + r.best_question + '" (score: ' + r.score + ')';
                            }
                            lastBot.text(fallback);
                        }
                    } else if(res && res.result && res.result.answer) {
                        // backward compatibility: old matcher returned plain reply
                        lastBot.text(res.result.answer);
                    } else {
                        lastBot.text('Sorry, I could not find an answer.');
                    }
                    // re-enable send if input not empty
                    $('#chat-send').prop('disabled', $('#chat-input-field').val().trim() === '');
                },
                error: function(xhr, status, err){
                    console.error('[chat] ajax error', status, err, xhr && xhr.status, xhr && xhr.responseText);
                    var msg = 'Service unavailable.';
                    try{
                        if(xhr && xhr.responseText){
                            // try to show server-provided message
                            var body = JSON.parse(xhr.responseText);
                            if(body && body.detail) msg = body.detail;
                            else if(body && body.message) msg = body.message;
                            else if(body && body.error) msg = body.error;
                        }
                    } catch(e){
                        // ignore parse error
                    }
                    lastBot.text(msg + ' (status: ' + (xhr && xhr.status ? xhr.status : status) + ')');
                    // re-enable send button based on input
                    $('#chat-send').prop('disabled', $('#chat-input-field').val().trim() === '');
                }
            });
        }

        // wire up existing controls
        $(document).on('click', '#chat-send', function(){
            var txt = $('#chat-input-field').val().trim();
            if(!txt) return;
            $('#chat-input-field').val('');
            $('#chat-send').prop('disabled', true);
            sendMessage(txt);
        });

        // enable/disable send button
        $(document).on('input', '#chat-input-field', function(){
            $('#chat-send').prop('disabled', $(this).val().trim() === '');
        });

        // Send on Enter (allow Shift+Enter if user wants to implement multiline later)
        $(document).on('keydown', '#chat-input-field', function(e){
            if(e.key === 'Enter' && !e.shiftKey){
                e.preventDefault();
                // trigger the same flow as clicking send
                $('#chat-send').trigger('click');
            }
        });

    });
})(jQuery);
