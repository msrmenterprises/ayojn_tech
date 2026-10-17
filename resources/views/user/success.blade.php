@extends('layout')


@section('content')
<style>
    ._success {
    box-shadow: 0 6px 23px 10px #00000019;
    padding: 25px;
    width: 100%;
    margin-left:auto;
    margin-right:auto;
    text-align: center;
    margin: 40px auto;
    padding-top:0px;
    border-bottom: solid 4px #28a745;
}

._success i {
    font-size: 65px;
    color: #28a745;
    margin-left:auto;
    margin-right:auto;
    position: relative;
    width: 80px;
    height: 80px;
    top: -35px;
    background: white;
    border: 2px solid #e9ecef;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

._success h2 {
    margin-bottom: 12px;
    font-size: 40px;
    font-weight: 500;
    line-height: 1.2;
    margin-top: 10px;
}

._success p {
    margin-bottom: 0px;
    font-size: 18px;
    color: #495057;
    font-weight: 500;
    padding-top: 0px;
}

.text-secondary {
    --bs-text-opacity: 1;
    color: rgba(var(--bs-secondary-rgb), var(--bs-text-opacity)) !important;
}

.amount-badge[_ngcontent-ng-c259542078] {
    background: #f8f9fa;
    padding: 1rem 2rem;
    border-radius: 1rem;
    max-width: 250px;

}
.mx-auto {
    margin-right: auto !important;
    margin-left: auto !important;
}
.fs-3 {
        font-size: 2.75rem !important;
        font-family:'Poppins';
    }
    .text-success {
    --bs-text-opacity: 1;
    color: green;
}
.fw-bold {
    font-weight: 700 !important;
}
.d-block {
    display: block !important;
}
.mb-3 {
    margin-bottom: 1rem !important;
}
h1, .h1 {
        font-size: 1.5rem;
        line-height:30px;
    }

.info-grid[_ngcontent-ng-c259542078] {
        display: flex;
        /* grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); */
        gap: 1rem;
        margin-top: 2rem;
        /* flex-direction: column; */
        flex-wrap: wrap;
        
    }
.info-item[_ngcontent-ng-c259542078] {
    padding: 1rem;
    border: 1px solid #e9ecef;
    border-radius: .75rem;
    width: 48%;
    text-align:left;
}
.info-item[_ngcontent-ng-c259542078] .label[_ngcontent-ng-c259542078] {
   
    color: #6c757d;
    font-size: 12px;
    margin-bottom: .25rem;
    padding-left: 0px;
    font-weight:normal;
}

.info-item[_ngcontent-ng-c259542078] .value[_ngcontent-ng-c259542078] {
    display: block;
    font-weight: 600;
    font-size: 14px;
    color: #000;
}
</style>

<div class="container">
        <div class="row justify-content-center" style="display: flex; justify-content: center; align-items: center;">
            <div class="col-md-5">
            <div class="message-box _success">
                    <i class="fa fa-check-circle" aria-hidden="true"></i>
                    <h1 _ngcontent-ng-c259542078="" class="fw-bold mb-3"><!----><span _ngcontent-ng-c259542078=""> Transaction Success! </span><!----></h1>
                <div _ngcontent-ng-c259542078="" class="amount-badge mx-auto"><span _ngcontent-ng-c259542078="" class="text-secondary fs-6">Total Payment</span><span _ngcontent-ng-c259542078="" class="d-block fs-3 fw-bold text-success">USD {{ $paymentData['amount'] ?? 0 }}</span><!----></div>
                <div _ngcontent-ng-c259542078="" class="info-grid"><div _ngcontent-ng-c259542078="" class="info-item"><span _ngcontent-ng-c259542078="" class="label">Order Number</span><span _ngcontent-ng-c259542078="" class="value">AYOJN-{{ $collectionId ?? 0 }}</span></div>
                <div _ngcontent-ng-c259542078="" class="info-item"><span _ngcontent-ng-c259542078="" class="label">Ref Number</span><span _ngcontent-ng-c259542078="" class="value">{{ $paymentData['transaction_reference'] ?? '' }}</span></div>
                <div _ngcontent-ng-c259542078="" class="info-item"><span _ngcontent-ng-c259542078="" class="label">Payment Time</span><span _ngcontent-ng-c259542078="" class="value">{{ $paymentData['payment_date'] ?? '' }}</span></div>
                <div _ngcontent-ng-c259542078="" class="info-item"><span _ngcontent-ng-c259542078="" class="label">Payment Method</span><span _ngcontent-ng-c259542078="" class="value">{{ $paymentData['payment_mode'] ?? '' }}</span></div>
                <div _ngcontent-ng-c259542078="" class="info-item"><span _ngcontent-ng-c259542078="" class="label">Receiver Name</span><span _ngcontent-ng-c259542078="" class="value">{{ $paymentData['receiver_name'] ?? '' }}</span></div>
                <div _ngcontent-ng-c259542078="" class="info-item"><span _ngcontent-ng-c259542078="" class="label">Payer Name</span><span _ngcontent-ng-c259542078="" class="value">{{ $paymentData['payer_name'] ?? '' }}</span></div></div>
            
                <br/>
                <a href="{{$redirection}}" class="btn btn-success"> Back</a>

            </div> 
        </div> 
    </div> 

  
    <script>
    if (document.referrer) {
        console.log("Referred from: " + document.referrer);
    } else {
        console.log("No referrer available");
    }
</script>

<script type="text/javascript">
if (document.referrer.includes('dev-pay')) {
   // alert('Yes, from Google');
} else {
   // alert('Not using Google');
}
</script>

@endsection