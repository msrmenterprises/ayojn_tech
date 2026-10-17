@extends('adminlte::page')
<!--Add title-->
@section('title',  'Ayojn')

   

@section('content')
    <!-- //banner-top -->
    <!-- banner -->
    <!-- Content Header (Page header) -->

  

    <section class="content-header">
        <h1>
            Button Setting
            <small>List</small>

        </h1>


        <ol class="breadcrumb">

            <li><a href="{{ url('admin/dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Setting</li>

        </ol>
    </section>
    <style type="text/css">
        .mb20 {
            margin-bottom: 20px;
        }

        .checkbox {
            margin-top: 0px;
        }
       #button_icon {
      width: 100%;
      padding: 10px;
      font-size: 16px;
      margin-bottom: 10px;
    }

    /* Modal Styles */
    .iconModal {
      display: none;
      position: fixed;
      z-index: 99999;
      padding-top: 60px;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow-y: auto;
      background-color: rgba(0, 0, 0, 0.6);
    }
    .modal-content {
      background-color: #fff;
      margin: auto;
      padding: 20px;
      border: 1px solid #888;
      width: 80%;
      max-width: 900px;
      border-radius: 6px;
    }

    .close {
      color: #aaa;
      float: right;
      font-size: 28px;
      font-weight: bold;
    }
    .close:hover, .close:focus {
      color: #000;
      text-decoration: none;
      cursor: pointer;
    }

    .icon-grid {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      max-height: 500px;
      overflow-y: auto;
    }

    .icon-grid i {
      font-size: 24px;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
      cursor: pointer;
    }

    .icon-grid i:hover {
      background-color: #f0f0f0;
    }

    .selected {
      background-color: #e0f7ff;
      border-color: #00aaff;
    }
    </style>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <div class="col-md-12">
                            @if(session()->has('success'))
                                <div class="alert alert-success">
                                    {{ session()->get('success') }}
                                </div>
                            @endif
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            @if (session('true'))
                                <div class="alert alert-success alert-dismissable">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    {{ session('true') }}
                                </div>
                            @endif
                            @if (session('false'))
                                <div class="alert alert-danger alert-dismissable">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    {{ session('false') }}
                                </div>
                            @endif
                        </div>
                      
                    </div>
                  
            

                    <div class="box-body table-responsive">
                        <table id="data-table" class="table table-bordered table-striped last-child-sm" width="100%">
                            <thead>
                            <tr>
                                <th>Sr.</th>
                                <th>Page Name</th>
                                <th>Button Name</th>
                                <th>Button Icon</th>
                                <th>Button Class</th>
                                <th>Button Hover</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                                
                                 @foreach($details as $button)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $button->page_id }}</td>
                                    <td>{{ $button->button_name }}</td>
                                    <td><i class="{{ $button->button_icon }}"></i></td>
                                    <td>{{ $button->class_name }}</td> 
                                    <td>{{ $button->button_hover }}</td> 
                                    <td><button class="btn btn-primary" onclick="EditStatus({{ $button->id }});"><i class="fa fa-pencil"></i> Edit</button></td>
                                </tr>   
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- form start -->
                    <div class="box-footer clearfix text-right">

                    </div>
                    <div id="append-form"></div>
                </div>
                <!-- /.box -->
            </div>
            <!--/.col (left) -->
        </div>
    </section>


    <!-- Modal -->
  <div id="iconModal" class="iconModal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <h3>Choose an Icon</h3>
      <div class="icon-grid" id="iconGrid">Loading icons...</div>
    </div>
  </div>


    <div class="modal" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Modal Heading</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <form id="edit-button-form" method="POST" action="{{ url('controlpanel/update-button-setting') }}">
            @csrf
            <input type="hidden" name="id" id="button-id">
            <div class="form-group">
                <label for="page_id">Page Name</label>
                <input type="text" class="form-control" id="page_id" name="page_id" >
            </div>
            <div class="form-group">
                <label for="button_name">Button Name</label>
                <input type="text" class="form-control" id="button_name" name="button_name" >  
            </div> 
             <div class="form-group">
                <label for="button_name">Button Icon</label> <a id="openModal">Select Icon</a>
                <input type="text" class="form-control iconpicker" id="button_icon" name="button_icon" >  
            </div> 
             <div class="form-group">
                <label for="button_name">Button Class</label>
                <input type="text" class="form-control" id="button_class" name="button_class" >  
            </div>
             <div class="form-group">
                <label for="button_name">Button Hover</label>
                <input type="text" class="form-control" id="button_hover" name="button_hover" >  
            </div>  

            <button type="submit" class="btn btn-primary">Update</button>
        </form>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.0/clipboard.min.js"></script>
<script>
    $(document).ready(function () {

      $("#data-table").DataTable({
        "order": [[0, "asc"]],
        "pageLength": 10,
     
      });
      // Open modal
      $('#openModal').click(function () {
        $('#iconModal').fadeIn();
      });

      // Close modal
      $('.close').click(function () {
        $('#iconModal').fadeOut();
      });

      // Click outside modal to close
      $(window).click(function (e) {
        if ($(e.target).is('#iconModal')) {
          $('#iconModal').fadeOut();
        }
      });

      // Load icons from JSON
      $.getJSON('https://gist.githubusercontent.com/ks3dev/ad0f21724b35aa9f9c8571cf5817a467/raw/font-awesome-4.7.0.json', function (data) {
        const icons = data['4.7.0'];
        $('#iconGrid').empty();
        icons.forEach(icon => {
          const iconEl = $('<i>').addClass('fa ' + icon).attr('title', icon);
          $('#iconGrid').append(iconEl);
        });

        // Click on icon to select
        $('#iconGrid').on('click', 'i', function () {
          $('#iconGrid i').removeClass('selected');
          $(this).addClass('selected');

          const iconClass = $(this).attr('class');
          $('#button_icon').val(iconClass);
          $('#iconModal').fadeOut();
        });
      });
    });
  </script>
    <script>
        function EditStatus(id) 
        {
            $.ajax({
                url: "{{ url('controlpanel/view-button-setting') }}/" + id,
                type: 'POST',
                success: function (response) {
                    console.log(response);
                    if (response.status==true) {
                        $('#button-id').val(response.data[0].id);
                        $('#page_id').val(response.data[0].page_id).attr('disabled', true);
                        $('#button_name').val(response.data[0].button_name);
                        $('#button_icon').val(response.data[0].button_icon);
                        $('#button_class').val(response.data[0].class_name);
                        $('#button_hover').val(response.data[0].button_hover);
                        $("#myModal").modal('show');
                    } else {
                        alert('Error fetching data');
                    }
                },
                error: function () {
                    alert('Error fetching data');
                }
            });
           
        }


    </script>
@endsection
