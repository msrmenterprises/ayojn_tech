@extends('layout')
@php  $buttons = $db_buttons['Partner_offer_details'];  @endphp
@section('content')
    <!-- banner section start -->
    <?php
    $f = Request::get('f');
    $cn = Request::get('c');
    $b = Request::get('b');
    $i = Request::get('i');
    $cdu = Request::get('cu');
    use App\Country;use App\Currency;use App\Industry;
    $countries = Country::all();
    $Currency = Currency::all();
    $industries = Industry::all();
    ?>

<style>

.btn-group-sm>.btn, .btn-sm {
    padding: 2px 5px !important;
    font-size: 10px !important;
    line-height: 1.5;
    border-radius: 3px;
}
    .star-rating form {
    display: none;
}
.star-rating .thanks-msg {
    display: none;
    font-size: 20px;
    margin: 40px auto;
    color: #4caf50;
    background-color: rgba(76, 175, 80, 0.1411764705882353);
    padding: 8px 20px;
    border-left: 3px solid #4caf50;
    border-radius: 20px;
}
.star-rating input {
    display: none;
}
.star-rating {
   
    display: table;
    width:100%;
   
}
.star-rating label {
    padding: 10px;
    float: right;
    font-size: 44px;
    color: #eee;
}
.star-rating input:not(:checked) ~ label:hover,
.star-rating input:not(:checked) ~ label:hover ~ label {
    color: #ffc107;
}
.star-rating input:checked ~ label {
    color: #ffc107;
}
.star-rating form .rating-reaction:before {
    width: 100%;
    float: left;
    color: #ffc107;
}
.star-rating #rating-1:checked ~ form .rating-reaction:before {
    content: "I hate it";
}
.star-rating #rating-2:checked ~ form .rating-reaction:before {
    content: "I don't like it";
}
.star-rating #rating-3:checked ~ form .rating-reaction:before {
    content: "It is good";
}
.star-rating #rating-4:checked ~ form .rating-reaction:before {
    content: "I like it";
}
.star-rating #rating-5:checked ~ form .rating-reaction:before {
    content: "I love it";
}
.star-rating input:checked ~ form {
    border-top: 1px solid #ddd;
    width: 100%;
    padding-top: 15px;
    margin-top: 15px;
    display: inline-block;
}
.star-rating form .rating-reaction {
    font-size: 24px;
    float: left;
    text-transform: capitalize;
}
.star-rating form .submit-rating {
    border: none;
    outline: none;
    background: #795548;
    color: #ffc107;
    font-size: 18px;
    border-radius: 4px;
    padding: 5px 15px;
    cursor: pointer;
    float: right;
}
form .submit-rating:hover {
    background-color: #333;
}



.page {
  width: 100%;
  height: 100%;
  align-items: center;
  justify-content: center;
}

.marvel-device .screen {
  text-align: left;
}

.screen-container {
  height: 100%;
}

/* Status Bar */

.status-bar {
  height: 25px;
  background: #004e45;
  color: #fff;
  font-size: 14px;
  padding: 0 8px;
}

.status-bar:after {
  content: "";
  display: table;
  clear: both;
}

.status-bar div {
  float: right;
  position: relative;
  top: 50%;
  transform: translateY(-50%);
  margin: 0 0 0 8px;
  font-weight: 600;
}

/* Chat */

.chat {
  height: calc(100% - 69px);
}

.chat-container {
  height: 100%;
}

/* User Bar */

.user-bar {
  height: 55px;
  background: #005e54;
  color: #fff;
  padding: 0 8px;
  font-size: 24px;
  position: relative;
  z-index: 1;
}

.user-bar:after {
  content: "";
  display: table;
  clear: both;
}

.user-bar div {
  float: left;
  transform: translateY(-50%);
  position: relative;
  top: 50%;
}

.user-bar .actions {
  float: right;
  margin: 0 0 0 20px;
}

.user-bar .actions.more {
  margin: 0 12px 0 32px;
}

.user-bar .actions.attachment {
  margin: 0 0 0 30px;
}

.user-bar .actions.attachment i {
  display: block;
  transform: rotate(-45deg);
}

.user-bar .avatar {
  margin: 0 0 0 5px;
  width: 36px;
  height: 36px;
}

.user-bar .avatar img {
  border-radius: 50%;
  box-shadow: 0 1px 0 rgba(255, 255, 255, 0.1);
  display: block;
  width: 100%;
}

.user-bar .name {
  font-size: 17px;
  font-weight: 600;
  text-overflow: ellipsis;
  letter-spacing: 0.3px;
  margin: 0 0 0 8px;
  overflow: hidden;
  white-space: nowrap;
  width: 110px;
}

.user-bar .status {
  display: block;
  font-size: 13px;
  font-weight: 400;
  letter-spacing: 0;
}

/* Conversation */

.conversation {
  height: calc(100% - 12px);
  position: relative;
  z-index: 0;
}

.conversation ::-webkit-scrollbar {
  transition: all .5s;
  width: 5px;
  height: 1px;
  z-index: 10;
}

.conversation ::-webkit-scrollbar-track {
  background: transparent;
}

.conversation ::-webkit-scrollbar-thumb {
  background: #b3ada7;
}

.conversation .conversation-container {
 /* height: calc(100% - 68px);*/
 height:300px; 
 overflow-y:scroll;
  overflow-x: hidden;
  padding: 0 16px;
  margin-bottom: 5px;
}

.conversation .conversation-container:after {
  content: "";
  display: table;
  clear: both;
}

/* Messages */

.message {
  color: #000;
  clear: both;
  line-height: 18px;
  font-size: 15px;
  padding: 8px;
  position: relative;
  margin: 8px 0;
  max-width: 85%;
  word-wrap: break-word;
  z-index: -1;
}

.message:after {
  position: absolute;
  content: "";
  width: 0;
  height: 0;
  border-style: solid;
}

.metadata {
  display: block;
  padding: 0 0 0 0px;
  position: relative;
  bottom: -4px;
}

.metadata .time {
  color: rgba(0, 0, 0, .45);
  font-size: 11px;
  display: inline-block;
}

.metadata .tick {
  display: inline-block;
  margin-left: 2px;
  position: relative;
  top: 4px;
  height: 16px;
  width: 16px;
}

.metadata .tick svg {
  position: absolute;
  transition: .5s ease-in-out;
}

.metadata .tick svg:first-child {
  -webkit-backface-visibility: hidden;
          backface-visibility: hidden;
  -webkit-transform: perspective(800px) rotateY(180deg);
          transform: perspective(800px) rotateY(180deg);
}

.metadata .tick svg:last-child {
  -webkit-backface-visibility: hidden;
          backface-visibility: hidden;
  -webkit-transform: perspective(800px) rotateY(0deg);
          transform: perspective(800px) rotateY(0deg);
}

.metadata .tick-animation svg:first-child {
  -webkit-transform: perspective(800px) rotateY(0);
          transform: perspective(800px) rotateY(0);
}

.metadata .tick-animation svg:last-child {
  -webkit-transform: perspective(800px) rotateY(-179.9deg);
          transform: perspective(800px) rotateY(-179.9deg);
}

.message:first-child {
  margin: 16px 0 8px;
}

.message.received {
  background: #e4e4e4;
  border-radius: 0px 5px 5px 5px;
  float: left;
  width:55%;
}

.message.received .metadata {
  padding: 0 0 0 0px;
}

.message.received:after {
  border-width: 0px 10px 10px 0;
  border-color: transparent #fff transparent transparent;
  top: 0;
  left: -10px;
}

.message.sent {
  background: #e1ffc7;
  border-radius: 5px 0px 5px 5px;
  float: right;
}

.message.sent:after {
  border-width: 0px 0 10px 10px;
  border-color: transparent transparent transparent #e1ffc7;
  top: 0;
  right: -10px;
}

/* Compose */

.conversation-compose {
  display: flex;
  flex-direction: row;
  align-items: flex-end;
  overflow: hidden;
  height: 50px;
  width: 100%;
  z-index: 2;
}

.conversation-compose div,
.conversation-compose input {
  background: #fff;
  height: 100%;
}

.conversation-compose .emoji {
  display: flex;
  align-items: center;
  justify-content: center;
  background: white;
  border-radius: 5px 0 0 5px;
  flex: 0 0 auto;
  margin-left: 8px;
  width: 48px;
}

.conversation-compose .input-msg {
    border: solid 1px #e4e4e4;
    flex: 1 1 auto;
    font-size: 16px;
    margin: 0;
    border-radius: 10px;
    outline: none;
    min-width: 50px;
}

.conversation-compose .photo {
  flex: 0 0 auto;
  border-radius: 0 0 5px 0;
  text-align: center;
  position: relative;
  width: 48px;
}

.conversation-compose .photo:after {
  border-width: 0px 0 10px 10px;
  border-color: transparent transparent transparent #fff;
  border-style: solid;
  position: absolute;
  width: 0;
  height: 0;
  content: "";
  top: 0;
  right: -10px;
}

.conversation-compose .photo i {
  display: block;
  color: #7d8488;
  font-size: 24px;
  transform: translate(-50%, -50%);
  position: relative;
  top: 50%;
  left: 50%;
}

.conversation-compose .send {
  background: transparent;
  border: 0;
  cursor: pointer;
  flex: 0 0 auto;
  margin-left: 8px;
  margin-right: 8px;
  padding: 0;
  position: relative;
  outline: none;
}

.conversation-compose .send .circle {
  background: #008a7c;
  border-radius: 50%;
  color: #fff;
  position: relative;
  width: 48px;
  height: 48px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.conversation-compose .send .circle i {
  font-size: 24px;
 
}

/* Small Screens */

@media (max-width: 768px) {
  .marvel-device.nexus5 {
    border-radius: 0;
    flex: none;
    padding: 0;
    max-width: none;
    overflow: hidden;
    height: 100%;
    width: 100%;
  }

  .marvel-device > .screen .chat {
    visibility: visible;
  }

  .marvel-device {
    visibility: hidden;
  }

  .marvel-device .status-bar {
    display: none;
  }

  .screen-container {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
  }


  .conversation {
    height: calc(100vh - 55px);
  }
  .conversation .conversation-container {
    height: calc(100vh - 120px);
  }
}


.count{
    width: 15px;
    height: 15px;
    position: absolute;
    background: #000;
    color: #fff;
    font-weight: bold;
    padding: 2px;
    border-radius: 100%;
    margin-top: -9px;
  }

    </style>
    
    <section>

   
        <div class="container" style="padding-top: 109px;">
          
            <div class="row">
                <a href="{{ url('partner/home') }}" class="btn btn-primary float-left" data-tooltip="true" data-placement="top"  title="{!!$buttons[0]['hover']!!}"
                >{!!$buttons[0]['icon']!!} {!!$buttons[0]['name']!!}</a>

            </div>
            <div class="row">
                <div class="col-md-12" style="padding-top: 15px;">
                    @if(session()->has('success'))
                        <div class="alert alert-success">
                            {{ session()->get('success') }}
                        </div>
                    @endif
                </div>
            </div>
                
            <div class="row">

                <table class="table table-bordered ">
                    <tr>
                        <th style="background-color:#efefef;">Offer For</th>
                        <td>{{$offers[0]->offer_for}} ({{ $offers[0]->country->country_name??'' }})</td>
                        <th style="background-color:#efefef;">Deal Type</th>
                        <td>{{ $offers[0]->identity }}</td>
                        <th style="background-color:#efefef;">Suited For</th>
                        <td>{{ (!empty($offers[0]->industry) ? $offers[0]->industry->name :'-') }}</td>
                        <th style="background-color:#efefef;">Title</th>
                        <td>{{ $offers[0]->title }}</td>
                    </tr>


                    <tr>
                        <th style="background-color:#efefef;">Currency</th>
                        <td>{{ $offers[0]->currency??'' }}</td>
                        <th style="background-color:#efefef;">Discount (%)</th>
                        <td>{{ $offers[0]->discount??'' }}</td>
                        <th style="background-color:#efefef;">Ayojn's Incentive(%)</th>
                        <td>{{ $offers[0]->incentive??'' }}</td>
                        <th style="background-color:#efefef;">Sigma Deal Amount</th>
                        <td>{{ $offers[0]->deal_amount_sigma }}</td>
                    </tr>

                    <tr>
                   
                        <th style="background-color:#efefef;">Alpha Deal Amount</th>
                        <td>{{ $offers[0]->deal_amount_alpha }}</td>
                         <th style="background-color:#efefef;">Delta Deal Amount</th>
                    <td>{{ $offers[0]->deal_amount_delta }}</td>
                        <th style="background-color:#efefef;">Omega Deal Amount</th>
                        <td>{{ $offers[0]->deal_amount_omega }}</td>
                    </tr>

                 

                </table>

<br/>

                <table id="table_id" class="display">
                    <thead>
                    <tr>
                      <th>Id</th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Phone</th>
                      <th>Quantity</th>
                      <th>Buy Date</th>
                      <th>Status</th>
                     <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    
                    @if(!empty($orders))
                        @foreach($orders as $order)
                        

                            <tr class="tr_id" data-id="{{ $order->mid }}">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $order->entity }} 
                                @if($order->sponsor_type==1)
                                     <small class="text-success">Offer user</small>
                                @elseif($order->sponsor_type==2)
                                     <small class="text-success">Rec User</small>
                                @endif
                                </td>
                                <td>{{ $order->email }}</td>
                                <td>{{ $order->phone_no }}</td>
                                <td>{{ $order->qty }}</td>
                                <td>{{date('Y-m-d h:i A',strtotime($order->created_at)) }}</td>
                                <td>
                                    @if($order->status==0)
                                    <span class="">Pending</span>
                                    @elseif($order->status==1)
                                    <span class="">Progress</span>
                                    @elseif($order->status==2)
                                    <span class="">Sent</span>
                                    @elseif($order->status==3)
                                    <span class="">Query</span>
                                    @elseif($order->status==4)
                                    <span class="">Delivered</span>
                                    @elseif($order->status==5)
                                    <span class="">Paid</span>
                                    @endif

                                </td>
                                <td>
                                    @if($order->status==0)
                                
                                        <select class="change_status">
                                        <option value="0" selected>Pending</option>
                                        <option value="1">Progress</option>
                                        <option value="2">Sent</option>
                                        <option value="3" >Query</option>
                                        <option value="4">Delivered</option>
                                        </select>
                                    @elseif($order->status==1)
                                  
                                    <select class="change_status">
                                        <option value="0" disabled>Pending</option>
                                        <option value="1" selected>Progress</option>
                                        <option value="2">Sent</option>
                                        <option value="3" >Query</option>
                                        <option value="4">Delivered</option>
                                      
                                    </select>
                                    @elseif($order->status==2)
                                    <select class="change_status">
                                        <option value="0" disabled>Pending</option>
                                        <option value="1" disabled>Progress</option>
                                        <option value="2" selected>Sent</option>
                                        <option value="3" >Query</option>
                                        <option value="4">Delivered</option>
                                      
                                    </select>
                                    @elseif($order->status==3)
                                    <select class="change_status">
                                        <option value="0" disabled>Pending</option>
                                        <option value="1" disabled>Progress</option>
                                        <option value="2" disabled>Sent</option>
                                        <option value="3" selected>Query</option>
                                        <option value="4">Delivered</option>
                                      
                                    </select>
                                    <a data-id="{{$order->id}}" data-partner="{{$offers[0]->partner_id}}" class="btn btn-primary query_button btn-sm" title="query"><i class="fa fa-question-circle"></i> Query</a>
                                      
                                    @elseif($order->status==4)
                                    <a data-id="{{$order->id}}" data-partner="{{$offers[0]->partner_id}}" class="btn btn-primary query_button btn-sm" title="query"><i class="fa fa-question-circle"></i> Query</a>
                                    <a data-id="{{$order->id}}" class="btn btn-success feedback_button btn-sm" title="Feedback"><i class="fa fa-comments"></i> Feedback</a>
                                    @elseif($order->status==5)
                                    <a data-id="{{$order->id}}" data-partner="{{$offers[0]->partner_id}}" class="btn btn-primary query_button btn-sm" title="query"><i class="fa fa-question-circle"></i> Query</a>
                                    <a data-id="{{$order->id}}" class="btn btn-success feedback_button btn-sm" title="Feedback"><i class="fa fa-comments"></i> Feedback</a>
                                    <a data-id="{{$order->id}}" class="btn btn-primary payment btn-sm" title="Payment"><i class="fa fa-usd"></i> Payment</a>
                                    @endif

                                
                                </td>
                               
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>

        </div>
    </section>


 <!-- The Modal -->
 <div class="modal" id="query">
    <div class="modal-dialog">
        <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Communication</h4>
            <button type="button" class="close" data-dismiss="modal" style="position:absolute; right:15px; top:20px;">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
          <input type="hidden" value="" id="query_id" />
          <input type="hidden" value="" id="partner_id" />
        <div class="page">
  <div class="marvel-device nexus5">
   
    <div class="screen">
      <div class="screen-container">
    
        <div class="chat">
          <div class="chat-container">
            
            <div class="conversation">
              <div class="conversation-container" id="items-container">
               </div>
              <div class="conversation-compose">
               
                <input class="input-msg" name="input" id="query_msg" placeholder="Type a message" autocomplete="off" autofocus></input>
               
                <a class="send" id="query_button_save">
                    <div class="circle">
                      <i class="fa fa-send"></i>
                    </div>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
        </div>

      

        </div>
    </div>
    </div>




    <div class="modal" id="feedback">
    <div class="modal-dialog">
        <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Customer Feedback</h4>
            <button type="button" class="close" data-dismiss="modal" style="position:absolute; right:15px; top:20px;">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">

        <div class="star-rating">
            <div class="thanks-msg">Thanks for your feedback !!!</div>
            <div class="star-input">
                <input type="radio" name="rating" id="rating-5" class="rating" value="5">
                <label for="rating-5" class="fa fa-star"><i class="fa fa-start"></i></label>
                <input type="radio" name="rating" id="rating-4" class="rating" value="4">
                <label for="rating-4" class="fa fa-star"></label>
                <input type="radio" name="rating" id="rating-3" class="rating" value="3">
                <label for="rating-3" class="fa fa-star"></label>
                <input type="radio" name="rating" id="rating-2" class="rating" value="2">
                <label for="rating-2" class="fa fa-star"></label>
                <input type="radio" name="rating" id="rating-1" class="rating" value="1">
                <label for="rating-1" class="fa fa-star"></label>

                <textarea class="form-control feedback_text" id="feedback_text" disabled></textarea>
            
            </div>
        </div>

        </div>

        
        </div>
    </div>
    </div>

    <div class="modal" id="payment">
    <div class="modal-dialog">
        <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Payment Details</h4>
            <button type="button" class="close" data-dismiss="modal" style="position:absolute; right:15px; top:20px;">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">

            <lable>Payment Date</lable>
            <input type="text" id="payment_date" class="form-control" value="" readonly />
            <br/>
            <lable>Remark</lable>
            <textarea class="form-control" id="payment_remark" readonly></textarea>

        </div>

        <div class="modal-footer">
       
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
        </div>
    </div>
    </div>




    <script src="{{ asset('js/jquery1.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/typed.min.js') }}"></script>
    <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.0/clipboard.min.js"></script>
    <script>
        $(".share-opp-btn").on('click', function (e) {
            toastr.success('Web Link Copied', 'Success');
        });

        function copyToClipboard(element) {
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val($(element).text()).select();
            document.execCommand("copy");
            $temp.remove();
        }

        function changeOffer(id, status) {
            $.ajax({
                type: 'POST',
                url: '{{ url('partner/change-offer-status') }}',
                headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
                data: {
                    status: status,
                    id: id
                },
                success: function (response) {
                    var data = response;
                    if (data.status) {
                        toastr.success(response.message, "Success");
                        window.location.reload();
                    } else {
                        toastr.error(response.errors, "Error");
                    }
                },
                error: function (response) {
                    toastr.error(response.responseJSON.msg, "Error");
                    $(':input[type="submit"]').prop('disabled', false);
                },
            });
        }

        $(document).ready(function () {
            $('#table_id').DataTable({
                "fnDrawCallback": function () {
                    // $('.my_switch').bootstrapToggle();
                    $('.my_switch').bootstrapToggle({})
                }
            });
        });

        $(document).on('click', '.query_button', function (e) {
          
          $('#query_id').val($(this).attr('data-id'));
          $('#partner_id').val($(this).attr('data-partner'));

          $.ajax({
                url:'{{route("query-get")}}',
                method:'post',
                data:{'id': $(this).attr('data-id'),'from':'partner'},
                dataType: 'json',
                success:function(data)
                {
                  console.log(data);
                  var html_to_append = '';
                  var css = '';
                  var pname = '';
                  $.each(data, function(i, item) {
                    if(item.type == 'right'){css = 'sent'; pname = item.entity;} else{css = 'received'; pname = 'Me';}
                    html_to_append +=
                      '<div class="message '+ css +'">' +
                      item.query + 
                      '<span class="metadata"><span class="time">' +
                      item.created_date +
                      '</span><br/><span><i class="fa fa-user"></i> '+ pname +' </span></span>' +
                      '</div>';
                  });
                  $("#items-container").html(html_to_append);
                }
             });

          $("#query").modal('show');
            
        });

        $(document).on('click', '#query_button_save', function (e) {
          var query_id = $("#query_id").val();
          var partner_id = $('#partner_id').val();
          var query_msg = $('#query_msg').val();
          $.ajax({
                url:'{{route("query-store")}}',
                method:'post',
                data:{'query_id':query_id, 'partner_id':partner_id, 'query_msg':query_msg, 'from':'partner' },
                success:function(data)
                {
                  $("#query_msg").val("");
                  $("#query").modal('hide');
                }
             });
      });


      $(document).on('click', '.feedback_button', function (e) {
            var id = $(this).attr('data-id');
            $("#offer_item_id").val(id);

            $("#feedback_text").val('');
            
            $.ajax({
                url:'{{route("show-feedback-user")}}',
                type:'POST',
                data:{'id':id},
                success:function(fulldata)
                {
                  
                  if(fulldata){
                    var data = $.parseJSON(fulldata);
                    $('.rating').each(function(index,item){
                      if($(item).val() == data.rating){
                          $(item).attr('checked',true);
                      }
                  });
                  $(".rating").attr('disabled','disabled');
                  $("#feedback_text").val(data.feedback);
                 
                  }
                  
                }
             });
            $("#feedback").modal('show');
            
        });

        $(document).on('click', '.payment', function (e) {

            var id = $(this).attr('data-id');
            var url = "{{ route('show-payment') }}";
            $.ajax({
                url: url,
                method: "POST",
                data: {id: id},
                headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
                success: function (data) {
                    if(jQuery.parseJSON(data).length){
                        var rst = jQuery.parseJSON(data);
                        $("#payment_date").val(rst[0]['appove_date']);
                        $("#payment_remark").val(rst[0]['payment_remark']);
                        $("#payment").modal('show');
                    }
                    else{
                        alert('No Payment yet');
                    }
                   
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    
                }
            });
            
            
        });

        
        $(document).on('change', '.change_status', function (e) {
            var status = $(this).val();
            var id = $(this).closest('tr').attr('data-id');
            

            var url = "{{ route('change-status-user') }}";
            $.ajax({
                url: url,
                method: "POST",
                data: {id: id, status:status},
                headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
                success: function (data) {
                    console.log(data);
                    location.reload();
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    
                }
            });


        });




    </script>


<script>
    var memes = [
	'Dude, you smashed my turtle saying "I\'M MARIO BROS!"',
	'Dude, you grabed seven oranges and yelled "I GOT THE DRAGON BALLS!"',
	'Dude, you threw my hamster across the room and said "PIKACHU I CHOOSE YOU!"',
	'Dude, you congratulated a potato for getting a part in Toy Story',
	'Dude, you were hugging an old man with a beard screaming "DUMBLEDORE YOU\'RE ALIVE!"',
	'Dude, you were cutting all my pinapples yelling "SPONGEBOB! I KNOW YOU\'RE THERE!"',
];

var random = document.querySelector('#random');

random.innerHTML = memes[Math.floor(Math.random() * memes.length)];

/* Time */

var deviceTime = document.querySelector('.status-bar .time');
var messageTime = document.querySelectorAll('.message .time');

deviceTime.innerHTML = moment().format('h:mm');

setInterval(function() {
	deviceTime.innerHTML = moment().format('h:mm');
}, 1000);

for (var i = 0; i < messageTime.length; i++) {
	messageTime[i].innerHTML = moment().format('h:mm A');
}

/* Message */

var form = document.querySelector('.conversation-compose');
var conversation = document.querySelector('.conversation-container');

form.addEventListener('submit', newMessage);

function newMessage(e) {
	var input = e.target.input;

	if (input.value) {
		var message = buildMessage(input.value);
		conversation.appendChild(message);
		animateMessage(message);
	}

	input.value = '';
	conversation.scrollTop = conversation.scrollHeight;

	e.preventDefault();
}

function buildMessage(text) {
	var element = document.createElement('div');

	element.classList.add('message', 'sent');

	element.innerHTML = text +
		'<span class="metadata">' +
			'<span class="time">' + moment().format('h:mm A') + '</span>' +
			'<span class="tick tick-animation">' +
				'<svg xmlns="http://www.w3.org/2000/svg" width="16" height="15" id="msg-dblcheck" x="2047" y="2061"><path d="M15.01 3.316l-.478-.372a.365.365 0 0 0-.51.063L8.666 9.88a.32.32 0 0 1-.484.032l-.358-.325a.32.32 0 0 0-.484.032l-.378.48a.418.418 0 0 0 .036.54l1.32 1.267a.32.32 0 0 0 .484-.034l6.272-8.048a.366.366 0 0 0-.064-.512zm-4.1 0l-.478-.372a.365.365 0 0 0-.51.063L4.566 9.88a.32.32 0 0 1-.484.032L1.892 7.77a.366.366 0 0 0-.516.005l-.423.433a.364.364 0 0 0 .006.514l3.255 3.185a.32.32 0 0 0 .484-.033l6.272-8.048a.365.365 0 0 0-.063-.51z" fill="#92a58c"/></svg>' +
				'<svg xmlns="http://www.w3.org/2000/svg" width="16" height="15" id="msg-dblcheck-ack" x="2063" y="2076"><path d="M15.01 3.316l-.478-.372a.365.365 0 0 0-.51.063L8.666 9.88a.32.32 0 0 1-.484.032l-.358-.325a.32.32 0 0 0-.484.032l-.378.48a.418.418 0 0 0 .036.54l1.32 1.267a.32.32 0 0 0 .484-.034l6.272-8.048a.366.366 0 0 0-.064-.512zm-4.1 0l-.478-.372a.365.365 0 0 0-.51.063L4.566 9.88a.32.32 0 0 1-.484.032L1.892 7.77a.366.366 0 0 0-.516.005l-.423.433a.364.364 0 0 0 .006.514l3.255 3.185a.32.32 0 0 0 .484-.033l6.272-8.048a.365.365 0 0 0-.063-.51z" fill="#4fc3f7"/></svg>' +
			'</span>' +
		'</span>';

	return element;
}

function animateMessage(message) {
	setTimeout(function() {
		var tick = message.querySelector('.tick');
		tick.classList.remove('tick-animation');
	}, 500);
}
    </script>
@endsection
