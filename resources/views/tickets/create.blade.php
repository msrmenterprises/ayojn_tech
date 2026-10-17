@extends('layout')
@section('content')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<div class="container mt-4">
    <h2>Create Ticket</h2>

   

    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible show" role="alert">
        <ul style="margin-bottom:0;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style='float: right; margin-top: -23px;'>
        <span aria-hidden="true">&times;</span>
       </button>
    </div>
@endif

<form method="POST" action="{{ route('tickets.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
        <label>Subject</label>
        <input type="text" name="subject" class="form-control" required>
    </div>
    
    <div class="form-group">
        <label>Category</label>
        <select name="category" class="form-control" required>
            <option value="">Select Category</option>
            <option value="Technical">Technical</option>
            <option value="Billing">Billing</option>
            <option value="General">General</option>
        </select>
    </div>

    <div class="form-group">
        <label>Sub Category</label>
        <select name="subcategory" class="form-control" required>
            <option value="">Select Sub Category</option>
            <option value="Login Issue">Login Issue</option>
            <option value="Payment Failure">Payment Failure</option>
            <option value="Feature Request">Feature Request</option>
            <option value="Other">Other</option>
        </select>
    </div>

    <div class="form-group">
        <label>Priority</label>
        <select name="priority" class="form-control" required>
            <option value="">Select Priority</option>
            <option value="Low">Low</option>
            <option value="Medium">Medium</option>
            <option value="High">High</option>
            <option value="Urgent">Urgent</option>
        </select>
    </div>

     <div class="form-group">
        <label>Attachment</label>
        <input type="file" name="attachment" class="form-control" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
    </div>

    <div class="form-group">
        <label>Description</label>
        <textarea id="editor" name="description" class="form-control"></textarea>
    </div>

    <div class="form-group mt-3">
    <div class="g-recaptcha" data-sitekey="6LcGJt8rAAAAAAz0cZLmisqVpk98utb5-qe98pr3"></div>
</div>

    <button class="btn btn-primary">Submit</button>
</form>
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
    })
    .catch(error => {
        console.error(error);
    });

document.querySelector('form').addEventListener('submit', function(e) {
    if (ckeditorInstance) {
        document.querySelector('#editor').value = ckeditorInstance.getData();
    }
});
</script>
@endsection