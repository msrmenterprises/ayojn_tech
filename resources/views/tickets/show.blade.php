@extends('layout')
@section('content')


  <style>
   

    .ticket-container {
      
      margin: auto;
      background: #fff;
      padding: 30px;
      border-radius: 10px;
      
    }

    .ticket-header h2 {
      margin: 0 0 10px;
    }

    .ticket-meta {
      margin: 20px 0;
    }

    .ticket-meta p {
      margin: 6px 0;
      font-size: 15px;
    }

    .badge {
      display: inline-block;
      padding: 3px 10px;
      font-size: 12px;
      border-radius: 12px;
      color: #fff;
    }

    .status-in-progress { background-color: #17a2b8; }
    .status-closed { background-color: #dc3545; }
    .status-open { background-color: #28a745; }

    .priority-low { background-color: #6c757d; }
    .priority-medium { background-color: #ffc107; color: #000; }
    .priority-high { background-color: #dc3545; }

    .ticket-description {
      padding: 15px;
      background: #f8f9fa;
      border-left: 4px solid #007bff;
      margin-bottom: 30px;
    }

    .comments-section h3 {
      margin-bottom: 15px;
    }

    .comment {
      margin-bottom: 15px;
      padding: 10px 15px;
      background: #f1f3f5;
      border-radius: 8px;
    }

    .comment strong {
      color: #007bff;
    }

    .comment-time {
      font-size: 12px;
      color: #555;
    }

    textarea {
      width: 100%;
      height: 80px;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 14px;
    }

    .add-comment {
      margin-top: 10px;
      text-align: right;
    }

    .add-comment button {
      background-color: #007bff;
      color: #fff;
      padding: 8px 16px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
    }

    .add-comment button:hover {
      background-color: #0056b3;
    }

    @media (max-width: 600px) {
      .ticket-container {
        padding: 20px;
      }
    }
    .d-flex {
  display: flex;
}

.justify-content-end {
  justify-content: flex-end;
}

.gap-2 > * {
  margin-left: 0.5rem;
}

.mb-3 {
  margin-bottom: 1rem;
}

.flex-wrap {
  flex-wrap: wrap;
}

#editor {
  height: 400px;
}

#fullpage-loader {
    display: none;
    position: fixed;
    z-index: 9999;
    top: 0; left: 0; right: 0; bottom: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(0,0,0,0.6); /* semi-transparent black */
    display: flex;
    justify-content: center;
    align-items: center;
}
#fullpage-loader .loader-content {
    text-align: center;
}
  </style>

<div class="container mt-4">

<div id="fullpage-loader">
    <div class="loader-content">
        <img src="https://loading.io/assets/mod/spinner/spinner/lg.gif" alt="Loading..." width="100">
        <div>Loading...</div>
    </div>
</div>

<div class="ticket-container">
  <div class="ticket-header">
    <h2>Ticket #{{ $ticket->id }} - {{ $ticket->subject }}</h2>
  </div>

   <div class="d-flex justify-content-end gap-2 mb-3 flex-wrap">
    
    @if($ticket->status == 'In Progress')
        <form method="POST" action="{{ route('tickets.close', $ticket->id) }}">
            @csrf
            <button class="btn btn-success">Close Ticket</button>
        </form>
    @endif

    @if($ticket->status == 'Closed')
        <form method="POST" action="{{ route('tickets.reopen', $ticket->id) }}">
            @csrf
            <button class="btn btn-danger">Reopen Ticket</button>
        </form>
    @endif

    <a href="{{ route('ticket') }}" class="btn btn-info">Back</a>
</div>

  <div class="ticket-meta">
    <p><strong>Status:</strong> <span class="badge status-in-progress">{{ $ticket->status }}</span></p>
    <p><strong>Category:</strong> {{ $ticket->category }}</p>
    <p><strong>Subcategory:</strong> {{ $ticket->subcategory }}</p>
    <p><strong>Priority:</strong> <span class="badge priority-medium">{{ $ticket->priority }}</span></p>
     @if($ticket->attachment)
      <p>
          <strong>Attachment:</strong>
          <a href="{{ asset($ticket->attachment) }}" target="_blank" class="btn btn-sm btn-primary">
              Download
          </a>
      </p>
    @endif


    @php
      $assignedUser = DB::table('users')->where('id', $ticket->assign_id)->first();
      @endphp
      <p><strong>Assigned to:</strong><span> {{ $assignedUser ? $assignedUser->name : 'N/A' }} </span></p> 

  </div>


  <div class="ticket-description">
    {!! $ticket->description !!}
  </div>

  <div class="comments-section">
    <h3>Comments</h3>

    @foreach($ticket->comments as $comment)

     <div class="comment">
      <strong>
    {{ $comment->user->entity ?? '' }}
    (
        @if($comment->user->role_id === 1)
            Admin
        @elseif($comment->user->role_id === 2)
            Support Team
        @else
            User
        @endif
    )
</strong>
      <div class="comment-time">{{ $comment->created_at->format('d M Y h:i A') }}</div>
      <p>{!! $comment->comment !!}</p>
    </div>
    
    @endforeach

   

    <!-- Comment Form -->
<form method="POST" action="{{ route('tickets.comment', $ticket->id) }}">
    @csrf
    <div class="form-group">
        <textarea name="comment" id="editor">{{ old('comment') }}</textarea>
    </div>
    <button class="btn btn-success add-comment">Add Comment</button>
</form>



  </div>
</div>


<script src="{{ asset('js/bootstrap.min.js') }}"></script>

@stack('scripts')
<!-- CKEditor script -->
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
let ckeditorInstance;
ClassicEditor
    .create(document.querySelector('#editor'), {
        ckfinder: {
            uploadUrl: '/upload-image-ticket-comment'
        }
        
    })
    .then(editor => {
        ckeditorInstance = editor;
         editor.ui.view.editable.element.style.height = '200px';
    })
    .catch(error => {
        console.error(error);
    });

// document.querySelector('form').addEventListener('submit', function(e) {
//     if (ckeditorInstance) {
//         document.querySelector('#editor').value = ckeditorInstance.getData();
//     }
// });


$(document).ready(function() {
    $('#fullpage-loader').hide();
    $('form[action*="admincomment"]').on('submit', function(e) {
        if (ckeditorInstance) {
            $('#editor').val(ckeditorInstance.getData());
        }
        $('#fullpage-loader').show();
    });
});
</script>

@endsection
