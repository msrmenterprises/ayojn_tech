@extends('layout')

@section('content')

<style>
.btn-group-sm>.btn, .btn-sm {
    padding: 2px 5px !important;
    font-size: 10px !important;
    line-height: 1.5;
    border-radius: 3px;
}

.star-rating form {
    display: block;
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
    
   
    opacity: 0;
    position: absolute;
    z-index: -1;
    left: 65%;
    top: 25%;
    
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
.star-rating #rating-1:checked .rating-reaction:before {
    content: "I hate it";
}
.star-rating #rating-2:checked .rating-reaction:before {
    content: "I don't like it";
}
.star-rating #rating-3:checked .rating-reaction:before {
    content: "It is good";
}
.star-rating #rating-4:checked .rating-reaction:before {
    content: "I like it";
}
.star-rating #rating-5:checked .rating-reaction:before {
    content: "I love it";
}
.star-rating input:checked form {
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
    </style>
    <style>
        .history{padding-top:30px; display:table;}
        fieldset {
    font-family: sans-serif;
    border: 2px solid #e4e4e4;
    margin-bottom:30px;
    border-radius: 5px;
    padding: 15px;
}

fieldset legend {
    background: #4ab654;
    color: #fff;
    padding: 5px 10px ;
    font-size: 22px;
    border-radius: 5px;
    margin-left: 20px;
}
.table th{background-color:#e4e4e4;}
    </style>
    <div class="container history">
        <h2 style="padding-top:30px; padding-bottom:20px;">Marketplace History</h2>
        <div class="row">
        <?php 
        if($fieldset !=""){
          echo $fieldset; 
        }else{
          echo "No Data found";
        }
        ?>  

        </div>
    </div>





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
        <form action="" id="feedback_modal" method="post">

            <input type="hidden" value="{{Auth::user()->id}}" name="user_id" />
            <input type="hidden" value="" name="offer_item_id" id="offer_item_id" />

            <div class="thanks-msg">Thanks for your feedback !!!</div>
            <div class="star-input">
                <input type="radio" name="rating" id="rating-5" class="rating" value="5"  required>
                <label for="rating-5" class="fa fa-star"><i class="fa fa-start"></i></label>
                <input type="radio" name="rating" id="rating-4" class="rating" value="4" required >
                <label for="rating-4" class="fa fa-star"></label>
                <input type="radio" name="rating" id="rating-3" class="rating" value="3" required >
                <label for="rating-3" class="fa fa-star"></label>
                <input type="radio" name="rating" id="rating-2" class="rating" value="2" required>
                <label for="rating-2" class="fa fa-star"></label>
                <input type="radio" name="rating" id="rating-1" class="rating" value="1" required>
                <label for="rating-1" class="fa fa-star"></label>

                <textarea class="form-control" id="feedback_text" name="feedback" placeholder="Type your feedbcak here." required></textarea>
                <span id="msg" style="font-size: 13px; color: green; margin-top: 10px; display: inline-block; text-align: center;"></span>
                <button type="submit" class="submit-rating" style="margin-top:5px;">Submit</button> 
                </form>
            </div>
        </div>

        </div>

        
        </div>
    </div>
    </div>



 <div class="modal" id="cancel_modal">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Cancel Details</h4>
            <button type="button" class="close" data-dismiss="modal" style="position:absolute; right:15px; top:20px;">&times;</button>
        </div>
        <div class="modal-body">
        <form method="post" action="{{route('cancel-offer-user')}}">
         
            <input type="hidden" name="coffer_item_id" id="coffer_item_id" />
            <div class="form-group">
              <label>Reason <span style="color:red;">*</span></label>
              <textarea class="form-control" name="cancel_reason" required=""></textarea>
            </div>
            
            <div class="form-group">
              <input type="submit" class="btn btn-success" />
            </div>
        </form>
    </div>
  </div>
</div>
</div>


<script>

$('.cancel_btn').on('click', function(event){

   if(confirm("Are you sure?")) {
      var id = $(this).attr('data-id');
      $("#coffer_item_id").val(id);
      $("#cancel_modal").modal('show');
    } 

});

$('#feedback_modal').on('submit', function(event){
        event.preventDefault();
        $.ajax({
            url:'{{route("feedback-store")}}',
            method:'post',
            data:new FormData(this),
            processData: false,
            contentType: false,
            success:function(data)
            {
              console.log(data);
              $("#msg").html(data);
              setTimeout(function(){
                $('#feedback').modal('hide');
                $("#feedback").find('form').trigger('reset');
                $("#msg").html('');
            }, 2000);
            }
        })
 });




  $(document).ready(function () {

        $('#table_id').DataTable({
            "fnDrawCallback": function () {
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
                data:{'id': $(this).attr('data-id'),'from':'user','partner_id':$(this).attr('data-partner')},
                dataType: 'json',
                success:function(data)
                {
                  console.log(data);
                  var html_to_append = '';
                  var css = '';
                  var pname = '';
                  $.each(data, function(i, item) {
                    if(item.type == 'right'){css = 'sent'; pname = 'Me';} else{css = 'received'; pname = item.contact_person;}
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
                data:{'query_id':query_id, 'partner_id':partner_id, 'query_msg':query_msg, 'from':'user'},
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

            $(".submit-rating").show();
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

                  $("#feedback_text").val(data.feedback);
                  $(".submit-rating").hide();
                  }
                  
                }
             });
            $("#feedback").modal('show');
            
        });
    </script>

    <script src="{{ asset('js/jquery1.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/typed.min.js') }}"></script>
    <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.0/clipboard.min.js"></script>
@endsection
