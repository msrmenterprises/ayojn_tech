@extends('layout')

@section('content')
	<div class="container">
		<br />
		<br />
		<br />
		<br />
		<div class="panel-group" id="accordion">
			<div class="faqHeader">Some of the most frequent thoughts you may have, are answered here. If NOT then we are always around via live chat or a quick call at <a href="https://calendly.com/ayojn" target="_blank">https://calendly.com/ayojn</a></div>
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">What is Sponsorship Index ? </a>
					</h4>
				</div>
				<div id="collapseOne" class="panel-collapse collapse">
					<div class="panel-body">
						It's a mapping tool to keep a track of sponsorship spending/ earning by the users at Ayojn.
					</div>
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">What is Opportunities for ? </a>
					</h4>
				</div>
				<div id="collapseTwo" class="panel-collapse collapse">
					<div class="panel-body">
						This shows existing/ browsable opportunites (to be vouched) and the opportunities you can create (to be bidded for).
					</div>
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapsethree">What is Browse Opportunities for ? </a>
					</h4>
				</div>
				<div id="collapsethree" class="panel-collapse collapse">
					<div class="panel-body">
						This refers to the opportunities created by the users who can handle the outreach for you.
					</div>
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapsefour">What is Create Opportunities for ?</a>
					</h4>
				</div>
				<div id="collapsefour" class="panel-collapse collapse">
					<div class="panel-body">
						This refers to the opportunities created by you and would receive/ invite Bids by the users who can handle the outreach for you.

					</div>
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapsefive">What does the Pending status in browse opportunites mean ?  </a>
					</h4>
				</div>
				<div id="collapsefive" class="panel-collapse collapse">
					<div class="panel-body">
						It means that no one has opened the your vouch for negotiation. The moment somone does the status will change with their contact details.
					</div>
				</div>
			</div>
		</div>
	</div>
	<style>
		.faqHeader {
			font-size: 27px;
			margin: 20px;
		}

		.panel-heading [data-toggle="collapse"]:after {
			font-family: 'Glyphicons Halflings';
			/*content: "/f061"; !* "play" icon *!*/
			float: right;
			color: #F58723;
			font-size: 18px;
			line-height: 22px;
			/* rotate "play" icon from > (right arrow) to down arrow */
			-webkit-transform: rotate(-90deg);
			-moz-transform: rotate(-90deg);
			-ms-transform: rotate(-90deg);
			-o-transform: rotate(-90deg);
			transform: rotate(-90deg);
		}

		.panel-heading [data-toggle="collapse"].collapsed:after {
			/* rotate "play" icon from > (right arrow) to ^ (up arrow) */
			-webkit-transform: rotate(90deg);
			-moz-transform: rotate(90deg);
			-ms-transform: rotate(90deg);
			-o-transform: rotate(90deg);
			transform: rotate(90deg);
			color: #454444;
		}
	</style>
@endsection
