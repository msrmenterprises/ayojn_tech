@extends('layout')

@section('content')

    <?php
    use App\Country;use App\Industry;
    $countries = Country::all();
    $industries = Industry::all();

    ?>
	<style>

		#chartdiv {
			height: calc(100vh - 135px);
		}

		#chartdiv {
			width: 100%;
		/ / height: 500 px;
		}

		#bardiv {
			width: 100%;
			height: 300px;
		}

		.modal {
			text-align: center;
			padding: 0 !important;
		}

		.modal:before {
			content: '';
			display: inline-block;
			height: 100%;
			vertical-align: middle;
			margin-right: -4px;
		}

		.modal-dialog {
			display: inline-block;
			text-align: left;
			vertical-align: middle;
		}

		.amcharts-chart-div a {
			display: none !important;
		}
	</style>
	<!-- Resources -->
	<div id="chartdiv" style="margin-top: 75px"></div>
	<div style="position:absolute;bottom:88px;"> 
	<!-- <span style="text-align: center;
    width: 100%;
    padding-left: 54px;
    padding-top: 10px;
    font-size: 17px;">Discover More with <a href="https://corp.whoknows.com/" target="_blank"><img src="{{ asset('images/whoknows.png') }}"></a></span> -->
	</div>
	<!-- Modal -->
	<div id="popup"></div>

	<!-- top country popup start -->
	<!-- Modal -->
	<div id="topCountriesForms" class="modal" role="dialog">
		<div class="modal-dialog">

			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Modal Header</h4>
				</div>
				<div class="modal-body">
					<p>Some text in the modal.</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>

		</div>
	</div>
	<!-- top country popup end -->
	<!-- banner section end -->

	<script>
      (function ($) {

        if ($.fn.typed) {
          var $typedStrings = $('.typed-strings');
          $typedStrings.typed({
            strings: ['an event', 'a campaign', 'a sports team', 'a gig', 'specific content'],
            typeSpeed: 130,
            loop: true,
            showCursor: false
          });
        } else {
          console.log('Animated typing: Plugin "typed" is not loaded.');
        }


      })(jQuery);

	</script>


	<!-- Chart code -->
	<script>
      var map = AmCharts.makeChart("chartdiv", {

        "type": "map",
        "theme": "light",
        "zoomOnDoubleClick": false,
        "dataProvider": {
          "map": "worldLow",
          "getAreasFromMap": true,
          "mapColor": "#fff",
          "color": "#fff"
        },
        "areasSettings": {
          "autoZoom": false,
          "selectedColor": "rgb(253, 227, 231)",
          "rollOverColor": "rgb(253, 227, 231)",
          "mapColor": "#fff",
          "color": "rgb(184, 228, 216)",
          "selectable": true
        },
        // "smallMap": {}
      });

      map.addListener("clickMapObject", function (event) {
        openpopup(event);

      });

      function openpopvup(event) {
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
          type: 'POST',
          url: "{{ route('mapdata') }}",
          data: {_token: CSRF_TOKEN},
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: function (response) {
            var data = JSON.parse(response);
            $('#myModal').modal('show');
          },
          error: function (jqXHR, textStatus, errorThrown) {
            toastr.error("Something went wrong", "Error");
          },
        });

      }


      function openpopup(event) {
        $.ajax({
          type: 'POST',
          url: '{{ route('mapdata2') }}',
          headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
          data: {countryId: event.mapObject.id},
          success: function (data) {
            $('#popup').html(data);

            $('#myModal').modal('show');
          },
        });
        return false;
      }

      function closePopup() {
        $(".modal-backdrop").removeClass('modal-backdrop');
        var map = AmCharts.makeChart("chartdiv", {

          "type": "map",
          "theme": "light",
          "zoomOnDoubleClick": false,
          "dataProvider": {
            "map": "worldLow",
            "getAreasFromMap": true,
            "mapColor": "#fff",
            "color": "#fff"
          },
          "areasSettings": {
            "autoZoom": false,
            "selectedColor": "rgb(253, 227, 231)",
            "rollOverColor": "rgb(253, 227, 231)",
            "mapColor": "#fff",
            "color": "rgb(184, 228, 216)",
            "selectable": true
          },
          // "smallMap": {}
        });
        map.addListener("clickMapObject", function (event) {

          openpopup(event);

        });
      }

      $('#myModal').on('hidden.bs.modal', function () {
        console.log("test")
      })

      function submitEditForm() {

      }
	</script>
@endsection
