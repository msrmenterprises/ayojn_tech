@extends('adminlte::page')
<!--Add title-->
@section('title',  'Ayojn')
@section('content')


 <section class="content-header">
        <h1>
            Tickets
            <small>List</small>

        </h1>


        <ol class="breadcrumb">


            <li><a href="{{ url('admin/dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Tickets List</li>

        </ol>
    </section>


     <section class="content">


        <div class="row">
            <div class="col-sm-12">

<div class="box box-primary">
                    <div class="box-header with-border"></div>
                     <div class="box-body table-responsive">
                        <table id="data-table" class="table table-bordered table-striped last-child-sm" width="100%"
>
    <thead>
        <tr>
            <th>ID</th>
            <th>Subject</th>
            <th>Category</th>
            <th>Subcategory</th>
            <th>Priority</th>
            <th>Status</th>
            <th>Created At</th>
            @if(Auth::guard('controlpanel')->user()->role_id == 1)
            <th>Action</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach($tickets as $ticket)
            @if($ticket->assign_id == Auth::guard('controlpanel')->user()->id || Auth::guard('controlpanel')->user()->role_id == 1)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td><a href="{{ route('tickets.adminshow', $ticket->id) }}">{{ $ticket->subject }}</a></td>
            <td>{{ $ticket->category }}</td>
            <td>{{ $ticket->subcategory }}</td>
            <td>{{ $ticket->priority }}</td>
            <td>{{ $ticket->status }}</td>
            <td>{{ $ticket->created_at->format('d M Y h:i A') }}</td>
            @if(Auth::guard('controlpanel')->user()->role_id == 1)
            <td>

                @if($ticket->assign_id ==0)
                    <button type="button" class="btn btn-warning btn-sm open-assign-modal" data-ticket="{{ $ticket->id }}">
                        Assign
                    </button>

                @else
                @php
                    $assignedUser = $assignUsers->where('id', $ticket->assign_id)->first();
                @endphp
                Assigned to: {{ $assignedUser ? $assignedUser->name : 'N/A' }}   
                @endif     

                @if($ticket->status == 'New')


           

                <!-- <form method="POST" action="{{ route('tickets.assign', $ticket->id) }}">
                    @csrf
                    <button class="btn btn-warning btn-sm">Assign</button>
                </form> -->
                @elseif($ticket->status == 'In Progress')
                <!-- <form method="POST" action="{{ route('tickets.close', $ticket->id) }}">
                    @csrf
                    <button class="btn btn-success btn-sm">Close</button>
                </form> -->
              @endif
            </td>
            @endif
        </tr>
        @endif
        @endforeach
    </tbody>
</table>
</div>
</div>


</div>
 </div>
    </section>



    <!-- Modal for Assign Ticket -->
<div class="modal fade" id="assignModal" tabindex="-1" role="dialog" aria-labelledby="assignModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="assignForm" method="POST" action="">
      @csrf
      <input type="hidden" name="ticket_id" id="modal_ticket_id">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="assignModalLabel">Assign Ticket</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="assign_user">Assign to User</label>
            <select name="assign_id" id="assign_user" class="form-control" required>
              <option value="">Select User</option>
              @foreach($assignUsers as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Assign</button>
        </div>
      </div>
    </form>
  </div>
</div>

  

        <script>
$(document).ready(function() {
    $('#data-table').DataTable({
        "order": [[ 0, "desc" ]]
    });

    // Open modal and set ticket id
    $('.open-assign-modal').on('click', function() {
        var ticketId = $(this).data('ticket');
        $('#modal_ticket_id').val(ticketId);
        $('#assignForm').attr('action',  + ticketId + '/assign');
        $('#assignModal').modal('show');
    });
});
</script>

@endsection