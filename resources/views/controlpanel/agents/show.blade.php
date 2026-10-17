@extends('adminlte::page')

@section('title', 'Ayojn')

@section('content')

<style>
.ticket-container {
    margin: auto;
    background: #fff;
    padding: 30px;
    border-radius: 10px;
}

.ticket-header h2 {
    margin-bottom: 15px;
}

.ticket-meta p {
    margin: 6px 0;
    font-size: 15px;
}

.badge {
    padding: 4px 10px;
    font-size: 12px;
    border-radius: 12px;
    color: #fff;
}

.status-user { background-color: #28a745; }
.status-agent { background-color: #17a2b8; }

.ticket-description {
    padding: 15px;
    background: #f8f9fa;
    border-left: 4px solid #007bff;
    margin-bottom: 25px;
}

.comments-section h3 {
    margin-bottom: 15px;
}

.comment {
    margin-bottom: 15px;
    padding: 12px 15px;
    background: #f1f3f5;
    border-radius: 8px;
}

.comment.agent {
    background: #d1ecf1;
    text-align: left;
}

.comment-time {
    font-size: 12px;
    color: #555;
}
</style>

<div class="container mt-4">

    <div class="ticket-container">

        {{-- Ticket Header --}}
        <div class="ticket-header">
            <h2>Ticket #{{request()->route('id')}}</h2>
        </div>

        {{-- Ticket Meta --}}
        <div class="ticket-meta">
            <p>
                <strong>Created At:</strong>
                {{ $ticket['created_at']->format('d M Y h:i A') }}
            </p>

            <p>
                <strong>Opened By:</strong>
                <span class="badge status-user">Agent</span>
            </p>
        </div>

        {{-- Initial Ticket Message --}}
        <div class="ticket-description">
            {!! $ticket['message'] !!}
        </div>

        {{-- Conversation --}}
        <div class="comments-section">
            <h3>Conversation</h3>

            @forelse($messages as $message)
                <div class="comment {{ $message['sender_type'] === 'agent' ? 'agent' : '' }}">
                    <strong>
                        {{ $message['sender_type'] === 'user'
                            ? 'User'
                            : ($message['sender_name'] ?? 'Agent') }}
                    </strong>

                    <div class="comment-time">
                        {{ $message['created_at']->format('d M Y h:i A') }}
                    </div>

                    <p>{!! $message['message'] !!}</p>
                </div>
            @empty
                <p>No messages found.</p>
            @endforelse
        </div>

        {{-- Reply Form (UI only) --}}
        <div class="mt-4">
            <form method="POST" action="{{ route('agent.tickets.reply', request()->route('id')) }}">
                @csrf
                <div class="form-group">
                    <textarea class="form-control" rows="4" name="message" placeholder="Type your reply..." required></textarea>
                </div>
                <button class="btn btn-primary mt-2">Send Reply</button>
            </form>
        </div>

    </div>
</div>

@endsection
