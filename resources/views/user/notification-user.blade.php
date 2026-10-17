@extends('layout')


@section('content')



<div class="container">
        <table class="table table-bordred">
            <tr>
                <th>Sr.</th>
                <th>Notification</th>
                <th>Action</th>
            </tr>
@if(!empty($notifications->first()))
    @foreach($notifications as $notification)

        @if($notification->is_read ==1)
            <tr style="background-color:#f6f6f6;">
        @else
            <tr>    
        @endif
      
    <td>{{ $loop->index+1 }}</td>
            <td>
                {{ strip_tags($notification->message) }}
            </td>
            <td>
            @if(Auth::user()->userstatus == 1)<a
                        href="{{ $notification->link }}"
                        onclick="updateStatusNotification({{ $notification->id }})" class='btn btn-success btn-xs'>view</a>@endif

            </td>
        </tr>
    @endforeach
@endif
</table>
</div> 

  


@endsection