@extends('layout')

@section('content')
    <!-- banner section start -->
    <section>
        <div class="container" style="padding-top: 15px;">
            <div class="row"><h3 style="text-align: center;">Offers</h3>
            </div>
            <div class="row">

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
            <br>
            <br>
            <div class="row">
                @if(!empty($offer))
                    <table id="table_id" class="display">
                        <thead>
                        <tr>
                            <th>Offer For</th>
                            <th>Identity</th>
                            <th>Function</th>
                            <th>Title</th>
                            <th>Discount (%)</th>
                            <th>Deal Amount</th>
                            <th>Country</th>
                            <th>Currency</th>
                            @if(!empty(Auth::user()))
                                <th>Weblink</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        @if(!empty($offer))
                            <tr>
                                <td>{{ $offer->offer_for }}</td>
                                <td>{{ $offer->identity }}</td>
                                <td>{{ $offer->function }}</td>
                                <td>{{ $offer->title }}</td>
                                <td>{{ $offer->discount }}</td>
                                <td>{{ $offer->deal_amount }}</td>
                                <td>{{ $offer->country->country_name }}</td>
                                <td>{{ $offer->currency }}</td>
                                @if(!empty(Auth::user()))
                                    <td>
                                        @if(!empty($offer->weblink))
                                            <a class="btn btn-primary" href="{{ $offer->weblink }}" target="_blank">Open
                                                Link</a>
                                        @endif
                                    </td>
                                @endif


                            </tr>
                        @endif
                        </tbody>
                    </table>
                @else
                    <div class="row">
                        <div class="col-md-12" style="padding-top: 15px;">
                            Offer not available right now
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </section>
    <script src="{{ asset('js/jquery1.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/typed.min.js') }}"></script>
    <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {

            $('#table_id').DataTable({});
        });
    </script>
@endsection
