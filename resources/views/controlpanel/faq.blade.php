<!-- filepath: resources/views/faq/index.blade.php -->
@extends('adminlte::page')
<!--Add title-->
@section('title',  'Ayojn')
@section('content')


  



<section class="content">
    <h3>FAQs</h3>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

     <div class="box box-primary">
                    <div class="box-body">

    <!-- Button to open modal -->
    <button class="btn btn-primary" data-toggle="modal" data-target="#faqModal">Add FAQ</button>

    <table class="table mt-3">
        <thead>
            <tr>
                <th>Question</th>
                <th>Answer</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($faqs as $faq)
            <tr>
                <td>{{ $faq->question }}</td>
                <td>{{ $faq->answer }}</td>
                <td>
                    <button class="btn btn-warning btn-sm edit-faq-btn"
            data-id="{{ $faq->id }}"
            data-question="{{ $faq->question }}"
            data-answer="{{ $faq->answer }}"
            data-toggle="modal"
            data-target="#editFaqModal">
            Edit
        </button>
                    <form action="{{ route('faqs.delete', $faq->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button class="btn btn-danger btn-sm" onclick="return confirm('Delete this FAQ?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
</div>

<!-- FAQ Modal -->
 <!-- FAQ Modal -->
<div class="modal fade" id="faqModal" tabindex="-1" role="dialog" aria-labelledby="faqModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="{{ route('faqs.store') }}" method="POST">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="faqModalLabel">Add FAQ</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Question</label>
            <input type="text" name="question" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Answer</label>
            <textarea name="answer" class="form-control" required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Save FAQ</button>
        </div>
      </div>
    </form>
  </div>
</div>
<!-- Add this modal to your Blade file -->
<div class="modal fade" id="editFaqModal" tabindex="-1" role="dialog" aria-labelledby="editFaqModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="editFaqForm">
      @csrf
      <input type="hidden" name="id" id="edit_faq_id">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editFaqModalLabel">Edit FAQ</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Question</label>
            <input type="text" name="question" id="edit_faq_question" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Answer</label>
            <textarea name="answer" id="edit_faq_answer" class="form-control" required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Update FAQ</button>
        </div>
      </div>
    </form>
  </div>
</div>
</section>
<script>
$('.edit-faq-btn').on('click', function() {
    $('#edit_faq_id').val($(this).data('id'));
    $('#edit_faq_question').val($(this).data('question'));
    $('#edit_faq_answer').val($(this).data('answer'));
});

$('#editFaqForm').on('submit', function(e) {
    e.preventDefault();
    $.ajax({
        url: "{{ url('controlpanel/faqs/update') }}",
        type: "POST",
        data: $(this).serialize(),
        success: function(response) {
            if(response.status) {
                $('#editFaqModal').modal('hide');
                location.reload(); // Or update the row dynamically
            } else {
                alert('Update failed!');
            }
        }
    });
});
</script>
@endsection