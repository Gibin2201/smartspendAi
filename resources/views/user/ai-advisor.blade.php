@extends('layouts.usermaster')

@section('title', 'AI Financial Advisor')

@section('content')
<div class="row justify-content-center">
  <div class="col-12 col-lg-8">
    
    <!-- Page Header -->
    <div class="mb-4">
      <h4 class="mb-1 fw-bold text-dark"><i class="bi bi-robot text-primary me-2"></i>AI Financial Advisor</h4>
      <p class="text-muted mb-0">Ask questions about your spending habits, budget analysis, and personalized savings tips.</p>
    </div>

    <!-- Chat Box Card -->
    <div class="card shadow border-0 rounded-4">
      <div class="card-body p-4 d-flex flex-column" style="height: 450px;">
        
        <!-- Chat Messages Container -->
        <div id="chat-container" class="flex-grow-1 overflow-auto pe-2 mb-3" style="scroll-behavior: smooth;">
          <div class="d-flex mb-3">
            <div class="bg-light text-dark p-3 rounded-4 shadow-sm" style="max-width: 80%;">
              <strong>AI Advisor:</strong> Hello! I have reviewed your income and expense records. How can I help you manage your finances today?
            </div>
          </div>
        </div>

        <!-- Input Form -->
        <form id="chat-form" class="d-flex gap-2">
          @csrf
          <input type="text" id="user-message" class="form-control rounded-pill px-4" placeholder="e.g., How much did I spend on food this month?" required autocomplete="off">
          <button type="submit" id="send-btn" class="btn btn-primary rounded-pill px-4 fw-semibold">
            <i class="bi bi-send-fill"></i>
          </button>
        </form>

      </div>
    </div>

  </div>
</div>

<!-- AJAX Script for Real-Time Chat -->
<script>
  const chatContainer = document.getElementById('chat-container');
  const chatForm = document.getElementById('chat-form');
  const userMessageInput = document.getElementById('user-message');
  const sendBtn = document.getElementById('send-btn');

  chatForm.addEventListener('submit', function(e) {
    e.preventDefault();
    const message = userMessageInput.value.trim();
    if (!message) return;

    // Append User Message to UI
    appendMessage('You', message, 'bg-primary text-white ms-auto');
    userMessageInput.value = '';
    userMessageInput.disabled = true;
    sendBtn.disabled = true;

    // Append Typing Indicator
    const typingId = 'typing-' + Date.now();
    appendMessage('AI Advisor', 'Thinking...', 'bg-light text-muted', typingId);

    // Send AJAX POST Request to Laravel Backend
    fetch("{{ route('user.ai.ask') }}", {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
      },
      body: JSON.stringify({ message: message })
    })
    .then(response => response.json())
    .then(data => {
      // Remove Typing Indicator
      document.getElementById(typingId).remove();

      // Append AI Response
      appendMessage('AI Advisor', data.reply, 'bg-light text-dark');
      userMessageInput.disabled = false;
      sendBtn.disabled = false;
      userMessageInput.focus();
    })
    .catch(error => {
      document.getElementById(typingId).remove();
      appendMessage('System', 'Sorry, something went wrong. Please try again.', 'bg-danger text-white');
      userMessageInput.disabled = false;
      sendBtn.disabled = false;
    });
  });

  function appendMessage(sender, text, cssClass, elementId = null) {
    const div = document.createElement('div');
    if (elementId) div.id = elementId;
    div.className = 'd-flex mb-3 ' + (sender === 'You' ? 'justify-content-end' : 'justify-content-start');
    
    div.innerHTML = `
      <div class="${cssClass} p-3 rounded-4 shadow-sm" style="max-width: 80%; white-space: pre-wrap;">
        <strong>${sender}:</strong> ${text}
      </div>
    `;
    chatContainer.appendChild(div);
    chatContainer.scrollTop = chatContainer.scrollHeight;
  }
</script>
@endsection