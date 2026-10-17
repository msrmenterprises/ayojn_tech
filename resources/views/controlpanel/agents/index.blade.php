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
                        <table id="data-table" class="table table-bordered table-striped last-child-sm" width="100%">
    <thead>
        <tr>
            <th>ID</th>
            <th>Question</th>
            <th>Match Score</th>
            <th>Status</th>
            <th>Created At</th>
        </tr>
    </thead>
    <tbody>
        @forelse($tickets as $ticket)
            <tr>
                <td>{{ $ticket['id'] }}</td>
                <td><a href="{{ route('agents.show', $ticket['id']) }}">{{ $ticket['user_question'] }}</a></td>
                <td>{{ $ticket['match_score'] }}</td>
                <td>{{ $ticket['status'] }}</td>
                <td>{{ \Carbon\Carbon::parse($ticket['created_at'])->format('d M Y H:i') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5">No tickets found</td>
            </tr>
        @endforelse
    </tbody>
</table>
</div>
</div>


</div>
 </div>
    </section>




        <script>
$(document).ready(function() {
    $('#data-table').DataTable({
        "order": [[ 0, "desc" ]]
    });

 
});
</script>

<script>
    $(document).ready(function () {
        setTimeout(function () {
            location.reload();
        }, 50000); // 50000 ms = 50 seconds
    });
</script>

@endsection