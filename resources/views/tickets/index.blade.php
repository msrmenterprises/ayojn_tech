@extends('layout')
@section('content')

<div class="container mt-4">

<!-- <h2>Tickets</h2> -->
<div class="pull-right" style="margin-bottom:10px;">
    <a href="{{ route('tickets.create') }}" class="btn btn-primary">Create Ticket</a>

</div>
 <table id="table_id" class="display" style="width:100%">
    <thead>
        <tr>
            <th>ID</th>
            <th>Subject</th>
            <th>Category</th>
            <th>Subcategory</th>
            <th>Priority</th>
            <th>Status</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach($tickets as $ticket)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td><a href="{{ route('tickets.show', $ticket->id) }}">{{ $ticket->subject }}</a></td>
            <td>{{ $ticket->category }}</td>
            <td>{{ $ticket->subcategory }}</td>
            <td>{{ $ticket->priority }}</td>
            <td>{{ $ticket->status }}</td>
            <td>{{ $ticket->created_at->format('Y-m-d') }}</td>
           
        </tr>
        @endforeach
    </tbody>
</table>
</div>

 <script src="{{ asset('js/jquery1.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/typed.min.js') }}"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    
<script>
    
$(document).ready(function() {
    $('#table_id').DataTable();
} );
</script>
@endsection