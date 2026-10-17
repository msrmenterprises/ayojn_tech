<?php
use App\Country;use App\Industry;use App\SponsorrSpecify;use App\SponsorrSpecifyList;
$countries = Country::all();
$industries = Industry::all();

if (Auth::check()) {
    $sponsorrlist = SponsorrSpecifyList::where('sponsorr_type', Auth::user()->sponsor_for)->get();
    $userwisesponsor = SponsorrSpecify::where('user_id', Auth::user()->id)->get();
} else {
    $sponsorrlist = [];
    $userwisesponsor = [];
}


$userwisesponsorarray = [];
if (count($userwisesponsor) > 0) {
    foreach ($userwisesponsor as $usersponsorr) {
        $userwisesponsorarray[] = $usersponsorr->specify_name;
    }
}
?>

    <!doctype html>
<html lang="en" class="no-js">
<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords"
          content="sponsored, content, sponsor, event, marketing, sponsorship, mega, event, campaign, advertisement, international trade, business, influencer, social media, marketing, venue, sports, Trade, Delegation, Conference, Exhibition, Seminar, Tradeshow, Workshop, Offer, Receive, Deal, Sale, Festival, Interview, Infographics, Adventure, Football, Entertainment, Transport.">

    <meta name="description"
          content="Ayojn is a B2B discovery platform for Sponsored Outreach opportunities. For Events , Business Conference, Sports Team Sponsorship . Find campaign Sponsors from ayojn and we also provide event sponsorship Opportunities & Sponsorship Agreements Sports Teams. ">

    <meta name="google-site-verification" content="hr0sYvxRzuiBtjySCW2HZW3QWPl2kF8DAC3IBt0ve3Q"/>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet"/>
<!-- <link href="https://getbootstrap.com/docs/5.0/dist/css/bootstrap.min.css" rel="stylesheet" /> -->

     <link href="<?php echo e(asset('css/bootstrap.min.css')); ?>" rel="stylesheet"/> 
    <link rel="stylesheet" href="<?php echo e(asset('css/style.css?param=4')); ?>"/>
    <link rel="stylesheet" href="<?php echo e(asset('css/style1.css?param=4')); ?>"/>
    <link rel="stylesheet" href="<?php echo e(asset('css/custom.css?param=3')); ?>"/>
    <link rel="stylesheet" href="<?php echo e(asset('css/toastr.min.css')); ?>"/>
    <link rel="shortcut icon" href="<?php echo e(asset('images/favicon.ico')); ?>"/>
    <link rel="stylesheet" href="<?php echo e(asset('css/jquery.dataTables.min.css')); ?>"/>
    <link rel="stylesheet" href="<?php echo e(asset('css/select2.min.css')); ?>"/>
    <script src="<?php echo e(asset('js/jquery.min.js?param=2')); ?>"></script>
    <!-- <script src="<?php echo e(asset('js/bootstrap.min.js?param=2')); ?>"></script> -->
    <script src="<?php echo e(asset('js/toastr.min.js')); ?>"></script>
    <script src="<?php echo e(asset('js/amcharts.js')); ?>"></script>
    <script src="<?php echo e(asset('js/serial.js')); ?>"></script>
    <script src="<?php echo e(asset('js/ammap.js')); ?>"></script>
    <script src="<?php echo e(asset('js/worldLow.js')); ?>"></script>
    <script src="<?php echo e(asset('js/light.js')); ?>"></script>

    <script src="<?php echo e(asset('js/export.min.js')); ?>"></script>
    <script src="<?php echo e(asset('js/none.js')); ?>"></script>
    <script src="<?php echo e(asset('js/sweetalert.min.js')); ?>"></script>

    <script src="<?php echo e(asset('js/popper.min.js')); ?>"></script>


    <link rel="stylesheet" href="<?php echo e(asset('css/export.css')); ?>" type="text/css" media="all"/>
    <!-- Default Theme -->
    <!-- Resourcedddddd style -->
    <title>Find Campaign, Events, Business Conference, Search, Book Or Pay for any Marketing Opportunity, Sports Team Sponsorship Opportunities Near Me </title>
    <meta property="og:title" content="Planning an Outreach | Outreach Discovery Platform"/>
    <meta property="og:description" content="Outreach Discovery Platform"/>
    <meta property="og:url" content="https://ayojn.com/"/>

    <meta name="_token" content="<?php echo e(csrf_token()); ?>">
    <script>
        var base_url = "<?php echo e(url('/')); ?>";
    </script>


    <script type="text/javascript" src="https://app.getterms.io/cookie-consent/embed/202b3311-9a16-4f27-8e78-931d11ae0806/en-au"></script>

    <!-- Sponsay_Privacy Policy
    <a href="https://www.iubenda.com/terms-and-conditions/56788298" class="iubenda-white no-brand iubenda-embed" title="Terms and Conditions ">Terms and Conditions</a><script type="text/javascript">(function (w,d) {var loader = function () {var s = d.createElement("script"), tag = d.getElementsByTagName("script")[0]; s.src="https://cdn.iubenda.com/iubenda.js"; tag.parentNode.insertBefore(s,tag);}; if(w.addEventListener){w.addEventListener("load", loader, false);}else if(w.attachEvent){w.attachEvent("onload", loader);}else{w.onload = loader;}})(window, document);</script> -->

    <!--  Sponsay_Cookie Solution
    <script type="text/javascript">
        var _iub = _iub || [];
        _iub.csConfiguration = {"consentOnContinuedBrowsing":false,"lang":"en","siteId":2140621,"cookiePolicyId":56788298, "banner":{ "acceptButtonDisplay":true,"customizeButtonDisplay":true,"acceptButtonColor":"#0073CE","acceptButtonCaptionColor":"white","customizeButtonColor":"#DADADA","customizeButtonCaptionColor":"#4D4D4D","rejectButtonDisplay":true,"rejectButtonColor":"#0073CE","rejectButtonCaptionColor":"white","position":"float-top-center","textColor":"black","backgroundColor":"white" }};
    </script>
    <script type="text/javascript" src="//cdn.iubenda.com/cs/iubenda_cs.js" charset="UTF-8" async></script>
-->

    <style>
        #edituserform .form-group{margin-bottom:8px}
        .moveleft{margin-left:30px}
        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.5);
        }

        .custom-padding {
            padding-top: 5px;
        }

        .autocomplete {
            /*the container must be positioned relative:*/
            position: relative;
            display: inline-block;
            width: 100%;
        }

        input {
            border: 1px solid transparent;

            padding: 10px;
            font-size: 16px;
        }

        input[type=text] {

            width: 100%;
        }


        .autocomplete-items {
            position: absolute;
            border: 1px solid #d4d4d4;
            border-bottom: none;
            border-top: none;
            z-index: 99;
            /*position the autocomplete items to be the same width as the container:*/
            top: 100%;
            left: 0;
            right: 0;
        }

        .autocomplete-items div {
            padding: 10px;
            cursor: pointer;
            background-color: #fff;
            border-bottom: 1px solid #d4d4d4;
        }

        .autocomplete-items div:hover {
            /*when hovering an item:*/
            background-color: #e9e9e9;
        }

        .autocomplete-active {
            /*when navigating through the items using the arrow keys:*/
            background-color: DodgerBlue !important;
            color: #ffffff;
        }

        .loader {
            display: none;
            position: fixed;
            left: 0px;
            top: 0px;
            width: 100%;
            height: 100%;
            z-index: 999999999;
            background: url('https://loading.io/assets/mod/spinner/spinner/lg.gif') 50% 50% no-repeat rgb(256, 256, 256);
        }

        .cms-content ul li{ margin-left: 30px; }
    </style>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-SJ58V5DSKZ"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-SJ58V5DSKZ');
    </script>

</head>
<body>
<input type="hidden" value="<?php if(!empty(Auth::user()->id)){echo Auth::user()->id;} else{ echo 0;} ?>" id="chk_id" />
<div class="loader"></div>
<div class="body-contianer only-scroll-content" id="wrapper">
<?php echo $__env->make('includes.header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>


<!-- banner section start -->
    
    <div id="clickDiv"></div>
    <?php echo $__env->yieldContent('content'); ?>
    
<!-- banner section end -->
    </div>




<?php echo $__env->make('includes.footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<!-- siignup popup start-->
<!-- Modal -->
<?php if(Auth::check()): ?>

<!--my bank details modal-->
<div class="modal" id="userbankform" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <i class="fa fa-close"></i>
            </button>
            <h4 class="modal-title" id="myModalLabel">
                My Bank Details
            </h4>
        </div>
        <div class="modal-body">
        
        <div class="row">

        <form id="mybankform" method="post">
        <div class="col-sm-6">
            <div class="form-group">
                <label>Country Bank Account is in<span class="red">*</span></label>
                <select  name="my_bank_country" id="my_bank_country" class="form-control select2" required>
                <option value="">Select</option>
                <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?= $c->country_code?>"><?= $c->country_name?></option> 
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            </div>

            <div class="col-sm-6">
            <div class="form-group">
                <label>Currency<span class="red">*</span></label>
                <?php
                    $currencies = DB::table('currencies')->get();
                ?>
                <select name="my_currency" id="my_currency" class="form-control select2" required>
                    <option value="">Select</option>
                    <?php $__currentLoopData = $currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currencie): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?= $currencie->iso?>"><?= $currencie->name?></option> 
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
             </div>
            </div>

            <div class="col-sm-6">
            <div class="form-group">
                <label>Bank Account/IBAN<span class="red">*</span></label>
                <input type="text" placeholder="Enter Bank Account/IBAN" class="form-control" id="my_bank_account" required="" name="my_bank_account">
            </div>
            </div>

            <div class="col-sm-6">
            <div class="form-group">
                <label>Select a National bank code type<span class="red">*</span></label>
                <select required="" class="form-control select2" name="my_bank_code" id="my_bank_code">
                    <option value="">Select</option>
                    <option value="ABA/Routing Number">ABA/Routing Number</option>
                    <option value="Bank Code">Bank Code</option>
                    <option value="BSB">BSB</option>
                    <option value="IFSC">IFSC</option>
                    <option value="Other Number">Other Number</option>
                    <option value="Sort Code">Sort Code</option>
                    <option value="Transit Number & Institution Number">Transit Number & Institution Number</option>
                </select>
            </div>
            </div>

            <div class="col-sm-6">
            <div class="form-group">
                <label>Code Number<span class="red">*</span></label>
                <input type="text" class="form-control" placeholder="Enter Code Number" required="" id="my_code_no" name="my_code_no">
            </div>
            </div>

            <div class="col-sm-6">
            <div class="form-group">
                <label>Tax ID<span class="red">*</span></label>
                <input type="text" class="form-control" placeholder="Enter TaxID" required=""  id="my_taxid" name="my_taxid">
            </div>
            </div>

            <!-- <div class="col-sm-6">
            <div class="form-group">
                <label>Beneficiary Country<span class="red">*</span></label>
                <select  name="my_country" id="my_country" class="form-control select2" required="" onchange="getcityforbankpopup()">
                    <option value="">Select</option>
                    <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($c->country_code == Auth::user()->country): ?> 
                        <option value="<?= $c->country_code?>" selected><?= $c->country_name?></option>
                    <?php else: ?>
                        <option value="<?= $c->country_code?>"><?= $c->country_name?></option> 
                    <?php endif; ?>    
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            </div>

            <div class="col-sm-6">
            <div class="form-group">
                <label>Beneficiary City<span class="red">*</span></label>
                <select class="form-control" id="my_city" name="my_city" required="">
                </select>
            </div>
            </div> -->

            
            <!-- <div class="col-sm-12">
            <div class="form-group">
                <label>Beneficiary Address<span class="red">*</span></label>
                <textarea class="form-control" name="my_address" id="my_address" required=""></textarea>
                
            </div>
            </div> -->

            <div class="col-sm-12">
            <div class="form-group">
                <button class="btn btn-success" onclick="savebankform()">Update Details</button>
            </div>
            </div>
           
        </div>
    </form>

    </div>
          
    </form>
    </div>
    </div>
</div>
<style>.error{color:red; font-size:12px;}</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/additional-methods.min.js"></script>
<script>
    function savebankform() {

        $('#mybankform').validate({
            submitHandler: function(form) {
                $.ajax({
                    type: 'POST',
                    url: '<?php echo e(route('savebankform')); ?>',
                    headers: {"X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>"},
                    data: $('#mybankform').serialize(),
                    success: function (response) {
                        console.log(response.status);
                        var data = response;
                        if (response.status == 1) {
                            toastr.success(response.msg, "Success");
                            $('#userbankform').modal('hide');
                            $("#mybankform").reset();
                        } else {
                            toastr.error(response.msg, "Error");
                        }
                    },
                   
                });

                
                return false;
            }
        });

        

    }
</script>



    <?php if(Auth::user()->sponsor_type != 3): ?>
        <div class="modal" id="edituserform" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 45.56 45.559">
                                <path id="Union_1" data-name="Union 1" class="cls-1"
                                      d="M22.426,22.426,0,44.852,22.426,22.426,0,44.852,22.426,22.426,0,0,22.426,22.426,0,0,22.426,22.426,44.852,0,22.426,22.426,44.852,0,22.426,22.426,44.852,44.852,22.426,22.426,44.852,44.852ZM22.426,22.426Z"
                                      transform="translate(0.354 0.354)"/>
                            </svg>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">
                           <!-- Edit <?php echo e((Auth::user()->sponsor_type == 1)?'Offer ':'Manage or Receive'); ?> Profile -->
                            Update your Profile. Once you click submit it will be locked for a review.
                        </h4>
                    </div>
                    <div class="modal-body">
                        <form class="edituserform" id="edituserform" method="post" onsubmit="return false"
                              name="edituserform">
                              <div class="form-group" style="text-align: right;">
                              <label style="" class="badge badge-primary">
                                    <strong>Membership Type : 
                                    <?php if(Auth::user()->user_type == 1): ?>    
                                        Delta
                                    <?php endif; ?>
                                    <?php if(Auth::user()->user_type == 2): ?>    
                                        Sigma
                                    <?php endif; ?>
                                    <?php if(Auth::user()->user_type == 3): ?>    
                                        Alpha
                                    <?php endif; ?>
                                    <?php if(Auth::user()->user_type == 4): ?>    
                                        Omega
                                    <?php endif; ?>
                                    </strong>
                                </label>

                                <label style="margin-right:10px; background: #337ab7;" class="badge badge-primary">
                                <strong>User Type :
                                <?php if(Auth::user()->sponsor_type == 1): ?>
                                    Clients / Brands
                                <?php endif; ?>
                                <?php if(Auth::user()->sponsor_type == 2): ?>
                                    Networks, Agencies, Freelancers
                                <?php endif; ?>
                                <?php if(Auth::user()->sponsor_type == 3): ?>
                                    Partner
                                <?php endif; ?>
                                </strong>
                            </label>
                            </div>

                            <div class="form-group">
                                <label>
                                    <strong>Entity</strong>
                                </label>
                               

                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Entity" name="entity_edit"
                                       id="entity_edit" value="<?php echo e(Auth::user()->entity); ?>">
                            </div>
                            <?php if(Auth::user()->sponsor_type == 2): ?>
                                <div class="form-group">
                                    <label>
                                        <strong>Identify Yourself </strong>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" value="<?php echo e(Auth::user()->identity); ?>"
                                           readonly>

                                </div>
                            <?php endif; ?>

                             <div class="form-group">
                                <label>
                                    <strong>Username</strong>
                                </label>
                            </div>

                            <div class="form-group">

                                <input type="text" class="form-control" placeholder="Username" name="username_edit"
                                       id="username_edit" value="<?php echo e(Auth::user()->username); ?>" <?php if(!empty(Auth::user()->username)): ?> disabled <?php endif; ?>>
                            </div>

                            <div class="form-group">
                                <label>
                                    <strong>Email Id</strong>
                                </label>
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control" placeholder="Workplace Email" name="email_edit"
                                       id="email_edit" value="<?php echo e(Auth::user()->email); ?>" onblur="validateEmail(this);">
                                <p id="emailNew" style="color:red;">Please sign up using your work email address since
                                    we
                                    will
                                    conduct a validation.</p>
                            </div>
                            <div class="form-group">
                                <label>
                                    <strong>Mobile No</strong>
                                </label>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control"
                                       placeholder="Mobile Number (with Country code +) "
                                       name="phone_no_edit"
                                       id="phone_no_edit" value="<?php echo e(Auth::user()->phone_no); ?>">
                            </div>

                            <div class="form-group">
                                <label>
                                    <strong>Country</strong>
                                </label>
                            </div>
                            <div class="form-group">
                                <!-- <input type="text" class="form-control" placeholder="Country" name="country"
                                       id="country" value="<?php echo e(Auth::user()->country_name->country_name); ?>"> -->

                                <select class="form-control" name="country" id="profilecountry" onchange="getcityforprofile()">
                                    <option value="">Select Country</option>
                                    
                                        <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if($c->country_name == Auth::user()->country_name->country_name): ?> 
                                            <option value="<?= $c->country_code?>" selected><?= $c->country_name?></option>
                                            <?php else: ?>
                                            <option value="<?= $c->country_code?>"><?= $c->country_name?></option>
                                            <?php endif; ?>
                                            
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                 
                                </select>
                            </div>
                            <div class="form-group">
                                <label>
                                    <strong>City</strong>
                                </label>
                            </div>
                            <div class="form-group">
                                <!-- <input type="text" class="form-control" placeholder="City" name="city"
                                       id="city"
                                       value="<?php echo e((!empty(Auth::user()->city_name)) ? Auth::user()->city_name->name : ''); ?>"
                                       > -->
                                <select class="form-control" name="city" id="profileCity">
                                    <option value="">Select City</option>
                                </select>

                            </div>
                            <div class="form-group">
                                <label>
                                    <strong>Hint incase you forget Username or Password</strong>
                                </label>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Nick Name, First Job, Favourite Colour or anything"
                                       name="sq1_edit"
                                       id="sq1_edit" value="<?php echo e(Auth::user()->sq1); ?>">
                            </div>
                            
                            <button type="submit" id="editformsubmit" onclick="UpdateUserForm()"
                                    class="btn btn-default">
                                Submit
                            </button>
                        </form>

                    </div>

                </div>
            </div>
        </div>
        <script>
            function UpdateUserForm() {
                var useremail_edit = $("#email_edit").val();
                var userentity_edit = $("#entity_edit").val();
                var username_edit = $("#username_edit").val();
                var identity_edit = $("#identity_edit").val();
                var phone_no_edit = $("#phone_no_edit").val();
                var sq1_edit = $("#sq1_edit").val();
                // $('#create_post').prop('disabled', true);
                $.ajax({
                    type: 'POST',
                    url: '<?php echo e(route('edituserprofile')); ?>',
                    headers: {"X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>"},
                    data: {
                        identity_edit: identity_edit,
                        phone_no_edit: phone_no_edit,
                        sq1_edit: sq1_edit,
                        useremail_edit: useremail_edit,
                        userentity_edit: userentity_edit,
                        username_edit:username_edit,
                    },
                    //cache: false,
                    //contentType: false,
                    //processData: false,
                    success: function (response) {
                        //console.log(response.status);
                        var data = response;
                        if (response.status) {
                            toastr.success(response.msg, "Success");
                            location.href = "<?php echo e(url('/review')); ?>";
                        } else {
                            toastr.error(response.msg, "Error");
                        }
                    },
                    error: function (response) {
                        toastr.error(response.responseJSON.msg, "Error");
                        $(':input[type="submit"]').prop('disabled', false);
                    },
                });

            }
        </script>
    <?php else: ?>
        <div class="modal" id="edituserform" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 45.56 45.559">
                                <path id="Union_1" data-name="Union 1" class="cls-1"
                                      d="M22.426,22.426,0,44.852,22.426,22.426,0,44.852,22.426,22.426,0,0,22.426,22.426,0,0,22.426,22.426,44.852,0,22.426,22.426,44.852,0,22.426,22.426,44.852,44.852,22.426,22.426,44.852,44.852ZM22.426,22.426Z"
                                      transform="translate(0.354 0.354)"/>
                            </svg>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">
                            Edit Partner Profile</h4>
                    </div>
                    <div class="modal-body">
                        <form class="edituserform" id="edituserform" method="post" onsubmit="return false"
                              name="edituserform">

                            <div class="form-group">
                                <label>
                                    <strong>Company Name </strong>
                                </label>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Company Name"
                                       value="<?php echo e(Auth::user()->company_name); ?>"
                                       name="edit_company_name" id="edit_company_name">

                            </div>
                            <div class="form-group">
                                <label>
                                    <strong>Contact Person</strong>
                                </label>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Contact Person"
                                       name="contact_person"
                                       id="contact_person" value="<?php echo e(Auth::user()->contact_person); ?>">
                            </div>
                            <div class="form-group">
                                <label>
                                    <strong>HQ</strong>
                                </label>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="HQ"
                                       name="HQ_edit"
                                       id="HQ_edit" value="<?php echo e(Auth::user()->HQ); ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label>
                                    <strong>Email Id</strong>
                                </label>
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control" placeholder="Workplace Email" name="email_edit"
                                       id="email_edit" value="<?php echo e(Auth::user()->email); ?>" onblur="validateEmail(this);">
                                <p id="emailNew" style="color:red;">Please sign up using your work email address since
                                    we
                                    will
                                    conduct a validation.</p>
                            </div>
                            <div class="form-group">
                                <label>
                                    <strong>Mobile No</strong>
                                </label>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control"
                                       placeholder="Mobile Number (with Country code +) "
                                       name="phone_no_edit"
                                       id="phone_no_edit" value="<?php echo e(Auth::user()->phone_no); ?>">
                            </div>
                            <button type="submit" id="editformsubmit" onclick="UpdateUserForm()"
                                    class="btn btn-default">
                                Submit
                            </button>
                        </form>

                    </div>

                </div>
            </div>
        </div>
        <script>
            function UpdateUserForm() {
                var useremail_edit = $("#email_edit").val();
                var contact_person = $("#contact_person").val();
                var edit_company_name = $("#edit_company_name").val();
                var phone_no_edit = $("#phone_no_edit").val();
                // $('#create_post').prop('disabled', true);
                $.ajax({
                    type: 'POST',
                    url: '<?php echo e(route('updateuserprofile')); ?>',
                    headers: {"X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>"},
                    data: {
                        edit_company_name: edit_company_name,
                        phone_no_edit: phone_no_edit,
                        contact_person: contact_person,
                        useremail_edit: useremail_edit,
                    },
                    success: function (response) {
                        var data = response;
                        if (response.status) {
                            toastr.success(response.msg, "Success");
                            location.href = "<?php echo e(url('/review')); ?>";
                        } else {
                            toastr.error(response.msg, "Error");
                        }

                    },
                    error: function (response) {
                        toastr.error(response.responseJSON.msg, "Error");
                        $(':input[type="submit"]').prop('disabled', false);
                    },
                });

            }
        </script>
    <?php endif; ?>
<?php endif; ?>
<div class="modal fade formElement" id="signupForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 45.56 45.559">
                        <path id="Union_1" data-name="Union 1" class="cls-1"
                              d="M22.426,22.426,0,44.852,22.426,22.426,0,44.852,22.426,22.426,0,0,22.426,22.426,0,0,22.426,22.426,44.852,0,22.426,22.426,44.852,0,22.426,22.426,44.852,44.852,22.426,22.426,44.852,44.852ZM22.426,22.426Z"
                              transform="translate(0.354 0.354)"/>
                    </svg>
                </button>
                <h4 class="modal-title" id="myModalLabel">Create your account</h4>
                <h5 class="modal-title custom-heading" id="myModalLabel">Get started for free. No credit card
                    required.</h5>
            </div>
            <div class="modal-body">
                <form class="formClass firstForm" style="display:block">
                    <div class="form-group">
                        <div class="row alert alert-warning" id="offerOptionMessage">
                            <strong>Warning!</strong> Please Select any One
                        </div>
                        <div class="row">





                            <div class="col-md-4">
                                <select class="form-control" id="offerOption">
                                    <option value="">Select Any</option>
                                    <option value="1">Clients / Brands</option>
                                    <option value="2">Networks, Agencies, Freelancers</option>
                                </select>
                            </div>



                        </div>
                        <div class="row">

                            <input type="checkbox" class="form-control" style="width: 22px;float: left"
                                   name="firstCheckBox" id="firstCheckBox"
                                   value="1"><span style="line-height: 3;padding-left: 5px">By signing up, you agree to the <a
                                    href="#" data-target="#TermsText" data-toggle="modal">Terms
										of Service</a> and <a
                                    href="#" data-target="#privacyText" data-toggle="modal">Privacy Policy</a>.</span>

                        </div>

                    </div>
                    <button type="button" id="firstFormNext" class="btn btn-default nextOption firstFormNext"
                            onclick="checkFirstPopupValidation()">Next
                    </button>
                    <br>
                    <span style="line-height: 3;padding-left: 5px"><a
                            href="<?php echo e(url('partner/login')); ?>">Want to signup as partner?</a></span>
                </form>

                <form class="formClass thirdForm" id="thirdForm" autocomplete="off" onsubmit="return false;">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Entity (Name of Organisation)"
                               name="OfferEntity"
                               id="OfferEntity">
                    </div>

                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Username"
                               name="OfferUsername"
                               id="OfferUsername">
                    </div>

                    <div class="form-group">
                        <input type="email" class="form-control" placeholder="Workplace Email" name="OfferEmail"
                               id="OfferEmail"
                               onblur="validateEmail(this);">
                        <p id="emailNew1" style="color:red;display: none">Please sign up using your work email address
                            since we will
                            conduct a validation.</p>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Mobile Number (with Country code +) "
                               name="OfferPhoneNo"
                               id="OfferPhoneNo">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" placeholder="Password" name="OfferPassword"
                               id="OfferPassword" onkeyup="checkPasswordStrengthoffer(this);">
                               <p id="password-strength-status-off"></p>
                    </div>
                    <div class="form-group">
                        <label>
                            <strong>Country</strong>
                        </label>
                    </div>
                    <div class="form-group">
                        <select class="form-control" name="OfferCountry" id="OfferCountry" onchange="getCity(1)">
                            <option value="">Select Country</option>
                            <?php if(!empty($countries)): ?>
                                <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?= $c->country_code?>"><?= $c->country_name?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>
                            <strong>City</strong>
                        </label>
                    </div>
                    <div class="form-group">
                        <select class="form-control" name="OfferCity" id="OfferCity">
                            <option value="">Select City</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>
                            <strong>Hint incase you forget Username or Password</strong>
                        </label>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Nick Name, First Job, Favourite Colour or anything" name="OfferQuestion1"
                               id="OfferQuestion1">
                    </div>
                    <button type="button" class="btn btn-default" onclick="firstPopupPrevious()">Previous</button>
                    <button type="button" id="thirdFormId" class="btn btn-default" onclick="submitForm()">Submit
                    </button>
                </form>
                <form class="formClass forthForm" autocomplete="off" onsubmit="return false;">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Entity (Name of Organisation)"
                               name="ReceiveEntity"
                               id="ReceiveEntity">
                    </div>


                      <div class="form-group">
                        <input type="text" class="form-control" placeholder="Username"
                               name="ReceiveUsername"
                               id="ReceiveUsername">
                    </div>


                    <div class="form-group">
                        <label>
                            <strong>Identify Yourself </strong>
                        </label>
                    </div>
                    <div class="form-group">
                        <select class="form-control" name="ReceiveIdentity" id="ReceiveIdentity">
                            <option value="Freelancers">Freelancers</option>
                            <option value="Agencies">Agency</option>
                            <option value="Networks">Network</option>
                            <option value="Communities">Community</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="email" class="form-control" placeholder="Workplace Email" name="ReceivceEmail"
                               id="ReceivceEmail"
                               onblur="validateEmail(this);">
                        <p id="emailNew2" style="color:red;display: none">Please sign up using your work email address
                            since we will
                            conduct a validation.</p>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Mobile Number (with Country code +) "
                               name="ReceivePhoneNo"
                               id="ReceivePhoneNo">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" placeholder="Password" name="ReceivePassword"
                               id="ReceivePassword" onkeyup="checkPasswordStrengthrec(this);">

                               <p id="password-strength-status-rec"></p>
                    </div>
                    <div class="form-group">
                        <label>
                            <strong>Country</strong>
                        </label>
                    </div>
                    <div class="form-group">
                        <select class="form-control" name="ReceiverCountry" id="ReceiverCountry" onchange="getCity(2)">
                            <option value="">Select Country</option>
                            <?php if(!empty($countries)): ?>
                                <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?= $c->country_code?>"><?= $c->country_name?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>
                            <strong>City</strong>
                        </label>
                    </div>
                    <div class="form-group">
                        <select class="form-control" name="ReceiveCity" id="ReceiveCity">
                            <option value="">Select City</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>
                            <strong>Hint incase you forget Username or Password</strong>
                        </label>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Nick Name, First Job, Favourite Colour or anything"
                               name="ReceiveQuestion1"
                               id="ReceiveQuestion1">
                    </div>
                    <button type="button" class="btn btn-default" onclick="firstPopupPrevious()">Previous</button>
                    <button type="button" id="forthFormId" class="btn btn-default" onclick="submitForm()">Submit
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
<!-- siignup popup END-->

<!-- login popup start -->
<div class="modal fade formElement" id="loginForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 45.56 45.559">
                        <path id="Union_1" data-name="Union 1" class="cls-1"
                              d="M22.426,22.426,0,44.852,22.426,22.426,0,44.852,22.426,22.426,0,0,22.426,22.426,0,0,22.426,22.426,44.852,0,22.426,22.426,44.852,0,22.426,22.426,44.852,44.852,22.426,22.426,44.852,44.852ZM22.426,22.426Z"
                              transform="translate(0.354 0.354)"/>
                    </svg>
                </button>
                <h4 class="modal-title" id="myModalLabel">Login to your account</h4>
            </div>
            <div class="modal-body">
                <form method="post">

                    <div class="form-group">
                        <input type="email" class="form-control" placeholder="Email" id="loginEmail">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" placeholder="Password" id="loginPassword">
                    </div>

                    <div class="form-group" id="otp_div">
                        <input type="text" class="form-control" placeholder="OTP" id="otp">
                        <a href="javascript:void(0)" class="resent" style="padding-top:3px; float:right;">Resent</a>
                    </div>

                    <div class="form-group">
                        <button type="button" id="loginButtonId" class="btn btn-default" onclick="loginForm()">Submit
                        </button>
                    </div>
                    <div class="form-group">
                        <a href="<?php echo e(url('/password/reset')); ?>">Forgot password?</a> &nbsp;&nbsp; <a
                            href="<?php echo e(url('/username/reset')); ?>">Forgot username?</a>
                    </div>

                    <div class="form-group">
                        Don't have an account?<a href="javascript:void(0)" onclick="displayRegister()"> Register
                            Now</a>
                    </div>
                    <div class="form-group">
                        <a href="<?php echo e(url('partner/login')); ?>"> Want to login as Partner?</a>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
<div id="sponsorrText" class="modal " role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">

            <div class="modal-body">

                <p>Ayojn Ltd is a company registered in England and Wales (Company No. 12666870). The registered address is Kemp House 160 City Road, London, EC1V 2NX. For any enquiries please write us at connect(at)ayojn.com. </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
<div id="ConnectText" class="modal " role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">

            <div class="modal-body">
                <p>
                    Connect with us at Live Chat or alternatively write us at connect(at)Ayojn.com. You can also
                    schedule a call with us via : <a href="https://calendly.com/ayojn" target="_blank">https://calendly.com/ayojn</a>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
<div id="PricingText" class="modal " role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body">
                <p>
                    Users at Ayojn explore various features & services at different price levels. We urge you to
                    experience it by yourself and decide if it's worth paying.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
<!-- Terms of Service Modal -->
<div class="modal fade tnc" id="TermsText" tabindex="-1" role="dialog" aria-labelledby="termsModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
   <div class="modal-dialog modal-lg" role="document">
      <!-- modal-lg for wider content -->
      <div class="modal-content">
         <div class="modal-header">

            <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <!-- For Bootstrap 4 -->
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="termsModalLabel">Ayojn Consulting FZC Terms of Service</h4>
         </div>
         <div class="modal-body">
            <div class="cms-content clean" style="padding: 0 10px;">
               <!-- Paste your TOS HTML content here -->
               <!-- You already have good structure with <h2>, <p>, <ul>, etc. -->
               <!-- Just make sure it's not wrapped twice in <div class="cms-content clean"> -->
               <!-- BEGIN: TERMS CONTENT -->
                        <h2> Ayojn Consulting FZC Terms of Service</h2>
<p> These Terms of Service govern your use of the website located at <a href="https://ayojn.com">https://ayojn.com</a> and any related services provided by Ayojn Consulting FZC.</p>
<p> By accessing <a href="https://ayojn.com">https://ayojn.com</a>, you agree to abide by these Terms of Service and to comply with all applicable laws and regulations. If you do not agree with these Terms of Service, you are prohibited from using or accessing this website or using any other services provided by Ayojn Consulting FZC.</p>
<p> We, Ayojn Consulting FZC, reserve the right to review and amend any of these Terms of Service at our sole discretion. Upon doing so, we will update this page. Any changes to these Terms of Service will take effect immediately from the date of publication.</p>
<p> These Terms of Service were last updated on January 19, 2025.</p>
<h3> Limitations of Use</h3>
<p> By using this website, you warrant on behalf of yourself, your users, and other parties you represent that you will not:</p>
<ul>
   <li> modify, copy, prepare derivative works of, decompile, or reverse engineer any materials and software contained on this website;</li>
   <li> remove any copyright or other proprietary notations from any materials and software on this website;</li>
   <li> transfer the materials to another person or "mirror" the materials on any other server;</li>
   <li> knowingly or negligently use this website or any of its associated services in a way that abuses or disrupts our networks or any other service Ayojn Consulting FZC provides;</li>
   <li> use this website or its associated services to transmit or publish any harassing, indecent, obscene, fraudulent, or unlawful material;</li>
   <li> use this website or its associated services in violation of any applicable laws or regulations;</li>
   <li> use this website in conjunction with sending unauthorised advertising or spam;</li>
   <li> harvest, collect, or gather user data without the user’s consent; or</li>
   <li> use this website or its associated services in such a way that may infringe the privacy, intellectual property rights, or other rights of third parties.</li>
</ul>
<h3> Intellectual Property</h3>
<p> The intellectual property in the materials contained in this website are owned by or licensed to Ayojn Consulting FZC and are protected by applicable copyright and trademark law. We grant our users permission to download one copy of the materials for personal, non-commercial transitory use.</p>
<p> This constitutes the grant of a licence, not a transfer of title. This licence shall automatically terminate if you violate any of these restrictions or the Terms of Service, and may be terminated by Ayojn Consulting FZC at any time.</p>
<h3> User-Generated Content</h3>
<p> You retain your intellectual property ownership rights over content you submit to us for publication on our website. We will never claim ownership of your content, but we do require a licence from you in order to use it.</p>
<p> When you use our website or its associated services to post, upload, share, or otherwise transmit content covered by intellectual property rights, you grant to us a non-exclusive, royalty-free, transferable, sub-licensable, worldwide licence to use, distribute, modify, run, copy, publicly display, translate, or otherwise create derivative works of your content in a manner that is consistent with your privacy preferences and our Privacy Policy.</p>
<p> The licence you grant us can be terminated at any time by deleting your content or account. However, to the extent that we (or our partners) have used your content in connection with commercial or sponsored content, the licence will continue until the relevant commercial or post has been discontinued by us.</p>
<p> You give us permission to use your username and other identifying information associated with your account in a manner that is consistent with your privacy preferences and our Privacy Policy.</p>
<h3> Liability</h3>
<p> Our website and the materials on our website are provided on an 'as is' basis. To the extent permitted by law, Ayojn Consulting FZC makes no warranties, expressed or implied, and hereby disclaims and negates all other warranties including, without limitation, implied warranties or conditions of merchantability, fitness for a particular purpose, or non-infringement of intellectual property, or other violation of rights.</p>
<p> In no event shall Ayojn Consulting FZC or its suppliers be liable for any consequential loss suffered or incurred by you or any third party arising from the use or inability to use this website or the materials on this website, even if Ayojn Consulting FZC or an authorised representative has been notified, orally or in writing, of the possibility of such damage.</p>
<p> In the context of this agreement, "consequential loss" includes any consequential loss, indirect loss, real or anticipated loss of profit, loss of benefit, loss of revenue, loss of business, loss of goodwill, loss of opportunity, loss of savings, loss of reputation, loss of use and/or loss or corruption of data, whether under statute, contract, equity, tort (including negligence), indemnity or otherwise.</p>
<p> Because some jurisdictions do not allow limitations on implied warranties, or limitations of liability for consequential or incidental damages, these limitations may not apply to you.</p>
<h3> Accuracy of Materials</h3>
<p> The materials appearing on our website are not comprehensive and are for general information purposes only. Ayojn Consulting FZC does not warrant or make any representations concerning the accuracy, likely results, or reliability of the use of the materials on this website, or otherwise relating to such materials or on any resources linked to this website.</p>
<h3> Links</h3>
<p> Ayojn Consulting FZC has not reviewed all of the sites linked to its website and is not responsible for the contents of any such linked site. The inclusion of any link does not imply endorsement, approval or control by Ayojn Consulting FZC of the site. Use of any such linked site is at your own risk and we strongly advise you make your own investigations with respect to the suitability of those sites.</p>
<h3> Right to Terminate</h3>
<p> We may suspend or terminate your right to use our website and terminate these Terms of Service immediately upon written notice to you for any breach of these Terms of Service.</p>
<h3> Severance</h3>
<p> Any term of these Terms of Service which is wholly or partially void or unenforceable is severed to the extent that it is void or unenforceable. The validity of the remainder of these Terms of Service is not affected.</p>
<h3> Governing Law</h3>
<p> These Terms of Service are governed by and construed in accordance with the laws of United Arab Emirates. You irrevocably submit to the exclusive jurisdiction of the courts in that State or location.</p>
               <!-- END: TERMS CONTENT -->
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
         </div>
      </div>
   </div>
</div>

<!-- Privacy of Service Modal -->
<div class="modal fade prvp" id="privacyText" tabindex="-1" role="dialog" aria-labelledby="privacyTextmodal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document"> <!-- modal-lg for wider content -->
    <div class="modal-content">

      <div class="modal-header">
       
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <!-- For Bootstrap 4 -->
          <span aria-hidden="true">&times;</span>
        </button>
		 <h5 class="modal-title" id="privacyTextmodal">Ayojn Consulting FZC Privacy Policy</h5>
      </div>

      <div class="modal-body">
        <div class="cms-content clean" style="padding: 0 10px;">
          <!-- Paste your TOS HTML content here -->
          <!-- You already have good structure with <h2>, <p>, <ul>, etc. -->
          <!-- Just make sure it's not wrapped twice in <div class="cms-content clean"> -->
          <!-- BEGIN: TERMS CONTENT -->
<h2> Ayojn Consulting FZC Privacy Policy</h2>
<p> Your privacy is important to us. It is Ayojn Consulting FZC's policy to respect your privacy and comply with any applicable law and regulation regarding any personal information we may collect about you, including across our website, <a href="https://ayojn.com">https://ayojn.com</a>, and other sites we own and operate.</p>
<p> Personal information is any information about you which can be used to identify you. This includes information about you as a person (such as name, address, and date of birth), your devices, payment details, and even information about how you use a website or online service.</p>
<p> In the event our site contains links to third-party sites and services, please be aware that those sites and services have their own privacy policies. After following a link to any third-party content, you should read their posted privacy policy information about how they collect and use personal information. This Privacy Policy does not apply to any of your activities after you leave our site.</p>
<p> This policy is effective as of January 19, 2025</p>
<p> Last updated: January 19, 2025</p>
<h3> Information We Collect</h3>
<p> Information we collect falls into one of two categories: “voluntarily provided” information and “automatically collected” information.</p>
<p> “Voluntarily provided” information refers to any information you knowingly and actively provide us when using or participating in any of our services and promotions.</p>
<p> “Automatically collected” information refers to any information automatically sent by your devices in the course of accessing our products and services.</p>
<h3> Log Data</h3>
<p> When you visit our website, our servers may automatically log the standard data provided by your web browser. It may include your device’s Internet Protocol (IP) address, your browser type and version, the pages you visit, the time and date of your visit, the time spent on each page, and other details about your visit.</p>
<p> Additionally, if you encounter certain errors while using the site, we may automatically collect data about the error and the circumstances surrounding its occurrence. This data may include technical details about your device, what you were trying to do when the error happened, and other technical information relating to the problem. You may or may not receive notice of such errors, even in the moment they occur, that they have occurred, or what the nature of the error is.</p>
<p> Please be aware that while this information may not be personally identifying by itself, it may be possible to combine it with other data to personally identify individual persons.</p>
<h3> Device Data</h3>
<p> When you visit our website or interact with our services, we may automatically collect data about your device, such as:</p>
<ul>
   <li>  Device type</li>
   <li>  Operating system</li>
   <li>  Unique device identifiers</li>
   <li>  Device settings</li>
   <li>  Geo-location data</li>
</ul>
<p> Data we collect can depend on the individual settings of your device and software. We recommend checking the policies of your device manufacturer or software provider to learn what information they make available to us.</p>
<h3> Personal Information</h3>
<p> We may ask for personal information —   for example, when you submit content to us or when you contact us — which may include one or more of the following:</p>
<ul>
   <li>  Name</li>
   <li>  Email</li>
   <li>  Social media profiles</li>
   <li>  Date of birth</li>
   <li>  Phone/mobile number</li>
   <li>  Home/mailing address</li>
   <li>KYC</li>
</ul>
<h3> Sensitive Information</h3>
<p> “Sensitive information” or “special categories of data” is a subset of personal information that is given a higher level of protection. Examples of sensitive information include information relating to your racial or ethnic origin, political opinions, religion, trade union or other professional associations or memberships, philosophical beliefs, sexual orientation, sexual practices or sex life, criminal records, health information, or biometric information.</p>
<p> The types of sensitive information that we may collect about you include:</p>
<ul>
   <li>  Biometric information</li>
</ul>
<p> We will not collect sensitive information about you without first obtaining your consent, and we will only use or disclose your sensitive information as permitted, required, or authorised by law.</p>
<h3> User-Generated Content</h3>
<p> We consider “user-generated content” to be materials (text, image and/or video content) voluntarily supplied to us by our users for the purpose of publication on our website or re-publishing on our social media channels. All user-generated content is associated with the account or email address used to submit the materials.</p>
<p> Please be aware that any content you submit for the purpose of publication will be public after posting (and subsequent review or vetting process). Once published, it may be accessible to third parties not covered under this privacy policy.</p>
<h3> Legitimate Reasons for Processing Your Personal Information</h3>
<p> We only collect and use your personal information when we have a legitimate reason for doing so. In which instance, we only collect personal information that is reasonably necessary to provide our services to you.</p>
<h3> Collection and Use of Information</h3>
<p> We may collect personal information from you when you do any of the following on our website:</p>
<ul>
   <li>  Register for an account</li>
   <li>  Enter any of our competitions, contests, sweepstakes, and surveys</li>
   <li>  Sign up to receive updates from us via email or social media channels</li>
   <li>  Post a comment or review or otherwise participate in our online community</li>
   <li> Use a mobile device or web browser to access our content</li>
   <li> Contact us via email, social media, or on any similar technologies</li>
   <li> When you mention us on social media</li>
</ul>
<p> We may collect, hold, use, and disclose information for the following purposes, and personal information will not be further processed in a manner that is incompatible with these purposes:</p>
<ul>
   <li> to provide you with our platform's core features and services</li>
   <li>  to enable you to customise or personalise your experience of our website</li>
   <li>  to deliver products and/or services to you</li>
   <li>  to contact and communicate with you</li>
   <li>  for analytics, market research, and business development, including to operate and improve our website, associated applications, and associated social media platforms</li>
   <li>  for advertising and marketing, including to send you promotional information about our products and services and information about third parties that we consider may be of interest to you</li>
   <li>  to consider your employment application</li>
   <li>  to enable you to access and use our website, associated applications, and associated social media platforms</li>
   <li>  for internal record keeping and administrative purposes</li>
   <li>  to run competitions, sweepstakes, and/or offer additional benefits to you</li>
   <li>  to comply with our legal obligations and resolve any disputes that we may have</li>
   <li>  to attribute any content (e.g. posts and comments) you submit that we publish on our website</li>
   <li>  for security and fraud prevention, and to ensure that our sites and apps are safe, secure, and used in line with our terms of use</li>
   <li>  for technical assessment, including to operate and improve our app, associated applications, and associated social media platforms</li>
</ul>
<p> We may combine voluntarily provided and automatically collected personal information with general information or research data we receive from other trusted sources. For example, Our marketing and market research activities may uncover data and insights, which we may combine with information about how visitors use our site to improve our site and your experience on it.</p>
<h4> Security of Your Personal Information</h4>
<p> When we collect and process personal information, and while we retain this information, we will protect it within commercially acceptable means to prevent loss and theft, as well as unauthorised access, disclosure, copying, use or modification.</p>
<p> Although we will do our best to protect the personal information you provide to us, we advise that no method of electronic transmission or storage is 100% secure and no one can guarantee absolute data security.</p>
<p> You are responsible for selecting any password and its overall security strength, ensuring the security of your own information within the bounds of our services. For example, ensuring any passwords associated with accessing your personal information and accounts are secure and confidential.</p>
<h4> How Long We Keep Your Personal Information</h4>
<p> We keep your personal information only for as long as we need to. This time period may depend on what we are using your information for, in accordance with this privacy policy. For example, if you have provided us with personal information as part of creating an account with us, we may retain this information for the duration your account exists on our system. If your personal information is no longer required for this purpose, we will delete it or make it anonymous by removing all details that identify you.</p>
<p> However, if necessary, we may retain your personal information for our compliance with a legal, accounting, or reporting obligation or for archiving purposes in the public interest, scientific, or historical research purposes or statistical purposes.</p>
<h3> Children’s Privacy</h3>
<p> We do not aim any of our products or services directly at children under the age of 13 and we do not knowingly collect personal information about children under 13.</p>
<h3> Disclosure of Personal Information to Third Parties</h3>
<p> We may disclose personal information to:</p>
<ul>
   <li> a parent, subsidiary or affiliate of our company</li>
   <li>third-party service providers for the purpose of enabling them to provide their services, including (without limitation) IT service providers, data storage, hosting and server providers, ad networks, analytics, error loggers, debt collectors, maintenance or problem-solving providers, marketingoradvertising providers, professional advisors, and payment systems operators</li>
   <li> our employees, contractors, and/or related entities</li>
   <li> our existing or potential agents or business partners</li>
   <li>  sponsors or promoters of any competition, sweepstakes, or promotion we run</li>
   <li> credit reporting agencies, courts, tribunals, and regulatory authorities, in the event you fail to pay for goods or services we have provided to you</li>
   <li> courts, tribunals, regulatory authorities, and law enforcement officers, as required by law, in connection with any actual or prospective legal proceedings, or in order to establish, exercise, or defend our legal rights</li>
   <li> third parties, including agents or sub-contractors who assist us in providing information, products, services, or direct marketing to you</li>
   <li> third parties to collect and process data</li>
   <li> an entity that buys, or to which we transfer all or substantially all of our assets and business</li>
</ul>
<p> Third parties we currently use include:</p>
<ul>
   <li>  Google Analytics</li>
   <li>  MailChimp</li>
   <li>  TinyLetter</li>
   <li>  Mailerlite</li>
   <li>  Aweber</li>
   <li>  SendFox</li>
   <li>  CampaignMonitor</li>
   <li>  Google AdSense</li>
   <li>  Bing Ads</li>
   <li>  AdColony</li>
</ul>
<h3> International Transfers of Personal Information</h3>
<p> The personal information we collect is stored and/or processed in United Arab Emirates, or where we or our partners, affiliates, and third-party providers maintain facilities.</p>
<p> The countries to which we store, process, or transfer your personal information may not have the same data protection laws as the country in which you initially provided the information. If we transfer your personal information to third parties in other countries: (i) we will perform those transfers in accordance with the requirements of applicable law; and (ii) we will protect the transferred personal information in accordance with this privacy policy.</p>
<h3> Your Rights and Controlling Your Personal Information</h3>
<p><strong>Your choice:</strong> By providing personal information to us, you understand we will collect, hold, use, and disclose your personal information in accordance with this privacy policy. You do not have to provide personal information to us, however, if you do not, it may affect your use of our website or the products and/or services offered on or through it.</p>
<p><strong>Information from third parties:</strong> If we receive personal information about you from a third party, we will protect it as set out in this privacy policy. If you are a third party providing personal information about somebody else, you represent and warrant that you have such person’s consent to provide the personal information to us.</p>
<p><strong>Marketing permission:</strong> If you have previously agreed to us using your personal information for direct marketing purposes, you may change your mind at any time by contacting us using the details below.</p>
<p><strong>Access:</strong> You may request details of the personal information that we hold about you.</p>
<p><strong>Correction:</strong> If you believe that any information we hold about you is inaccurate, out of date, incomplete, irrelevant, or misleading, please contact us using the details provided in this privacy policy. We will take reasonable steps to correct any information found to be inaccurate, incomplete, misleading, or out of date.</p>
<p><strong>Non-discrimination:</strong> We will not discriminate against you for exercising any of your rights over your personal information. Unless your personal information is required to provide you with a particular service or offer (for example providing user support), we will not deny you goods or services and/or charge you different prices or rates for goods or services, including through granting discounts or other benefits, or imposing penalties, or provide you with a different level or quality of goods or services.</p>
<p><strong>Downloading of Personal Information:</strong> We provide a means for you to download the personal information you have shared through our site. Please contact us for more information.</p>
<p><strong>Notification of data breaches:</strong> We will comply with laws applicable to us in respect of any data breach.</p>
<p><strong>Complaints:</strong> If you believe that we have breached a relevant data protection law and wish to make a complaint, please contact us using the details below and provide us with full details of the alleged breach. We will promptly investigate your complaint and respond to you, in writing, setting out the outcome of our investigation and the steps we will take to deal with your complaint. You also have the right to contact a regulatory body or data protection authority in relation to your complaint.</p>
<p><strong>Unsubscribe:</strong> To unsubscribe from our email database or opt-out of communications (including marketing communications), please contact us using the details provided in this privacy policy, or opt-out using the opt-out facilities provided in the communication. We may need to request specific information from you to help us confirm your identity.</p>
<h3> Use of Cookies</h3>
<p> We use "cookies" to collect information about you and your activity across our site. A cookie is a small piece of data that our website stores on your computer, and accesses each time you visit, so we can understand how you use our site. This helps us serve you content based on preferences you have specified.</p>
<p> Please refer to our Cookie Policy for more information.</p>
<h3> Business Transfers</h3>
<p> If we or our assets are acquired, or in the unlikely event that we go out of business or enter bankruptcy, we would include data, including your personal information, among the assets transferred to any parties who acquire us. You acknowledge that such transfers may occur, and that any parties who acquire us may, to the extent permitted by applicable law, continue to use your personal information according to this policy, which they will be required to assume as it is the basis for any ownership or use rights we have over such information.</p>
<h3> Limits of Our Policy</h3>
<p> Our website may link to external sites that are not operated by us. Please be aware that we have no control over the content and policies of those sites, and cannot accept responsibility or liability for their respective privacy practices.</p>
<h3> Changes to This Policy</h3>
<p> At our discretion, we may change our privacy policy to reflect updates to our business processes, current acceptable practices, or legislative or regulatory changes. If we decide to change this privacy policy, we will post the changes here at the same link by which you are accessing this privacy policy.</p>
<p> If the changes are significant, or if required by applicable law, we will contact you (based on your selected preferences for communications from us) and all our registered users with the new details and links to the updated or changed policy.</p>
<p> If required by law, we will get your permission or give you the opportunity to opt in to or opt out of, as applicable, any new uses of your personal information.</p>
<h3> Additional Disclosures for Australian Privacy Act Compliance (AU)</h3>
<h4> International Transfers of Personal Information</h4>
<p> Where the disclosure of your personal information is solely subject to Australian privacy laws, you acknowledge that some third parties may not be regulated by the Privacy Act and the Australian Privacy Principles in the Privacy Act. You acknowledge that if any such third party engages in any act or practice that contravenes the Australian Privacy Principles, it would not be accountable under the Privacy Act, and you will not be able to seek redress under the Privacy Act.</p>
<h3> Additional Disclosures for General Data Protection Regulation (GDPR) Compliance (EU)</h3>
<h4> Data Controller / Data Processor</h4>
<p> The GDPR distinguishes between organisations that process personal information for their own purposes (known as "data controllers") and organisations that process personal information on behalf of other organisations (known as "data processors"). We, Ayojn Consulting FZC, located at the address provided in our Contact Us section, are a Data Controller and/or Processor with respect to the personal information you provide to us.</p>
<h4> Legal Bases for Processing Your Personal Information</h4>
<p> We will only collect and use your personal information when we have a legal right to do so. In which case, we will collect and use your personal information lawfully, fairly, and in a transparent manner. If we seek your consent to process your personal information, and you are under 16 years of age, we will seek your parent or legal guardian’s consent to process your personal information for that specific purpose.</p>
<p> Our lawful bases depend on the services you use and how you use them. This means we only collect and use your information on the following grounds:</p>
<h3> Consent From You</h3>
<p> Where you give us consent to collect and use your personal information for a specific purpose. You may withdraw your consent at any time using the facilities we provide; however this will not affect any use of your information that has already taken place. You may consent to providing your name and contact details for the purpose of entering a giveaway or promotion. While you may withdraw your entry at any time, this will not affect any selection or judging that has already taken place. If you have any further enquiries about how to withdraw your consent, please feel free to enquire using the details provided in the Contact Us section of this privacy policy.</p>
<h3> Performance of a Contract or Transaction</h3>
<p> Where you have entered into a contract or transaction with us, or in order to take preparatory steps prior to our entering into a contract or transaction with you. For example, if you contact us with an enquiry, we may require personal information such as your name and contact details in order to respond.</p>
<h3> Our Legitimate Interests</h3>
<p> Where we assess it is necessary for our legitimate interests, such as for us to provide, operate, improve and communicate our services.  We consider our legitimate interests to include research and development, understanding our audience, marketing and promoting our services, measures taken to operate our services efficiently, marketing analysis, and measures taken to protect our legal rights and interests.</p>
<h3> Compliance with Law</h3>
<p> In some cases, we may have a legal obligation to use or keep your personal information. Such cases may include (but are not limited to) court orders, criminal investigations, government requests, and regulatory obligations. If you have any further enquiries about how we retain personal information in order to comply with the law, please feel free to enquire using the details provided in the Contact Us section of this privacy policy.</p>
<h4> International Transfers Outside of the European Economic Area (EEA)</h4>
<p> We will ensure that any transfer of personal information from countries in the European Economic Area (EEA) to countries outside the EEA will be protected by appropriate safeguards, for example by using standard data protection clauses approved by the European Commission, or the use of binding corporate rules or other legally accepted means.</p>
<h4> Your Rights and Controlling Your Personal Information</h4>
<p><strong>Restrict:</strong> You have the right to request that we restrict the processing of your personal information if (i) you are concerned about the accuracy of your personal information; (ii) you believe your personal information has been unlawfully processed; (iii) you need us to maintain the personal information solely for the purpose of a legal claim; or (iv) we are in the process of considering your objection in relation to processing on the basis of legitimate interests.</p>
<p><strong>Objecting to processing:</strong> You have the right to object to processing of your personal information that is based on our legitimate interests or public interest. If this is done, we must provide compelling legitimate grounds for the processing which overrides your interests, rights, and freedoms, in order to proceed with the processing of your personal information.</p>
<p><strong>Data portability:</strong> You may have the right to request a copy of the personal information we hold about you. Where possible, we will provide this information in CSV format or other easily readable machine format. You may also have the right to request that we transfer this personal information to a third party.</p>
<p><strong>Deletion:</strong> You may have a right to request that we delete the personal information we hold about you at any time, and we will take reasonable steps to delete your personal information from our current records. If you ask us to delete your personal information, we will let you know how the deletion affects your use of our  website or products and services. There may be exceptions to this right for specific legal reasons which, if applicable, we will set out for you in response to your request.  If you terminate or delete your account, we will delete your personal information within 60 days of the deletion of your account. Please be aware that search engines and similar third parties may still retain copies of your personal information that has been made public at least once, like certain profile information and public comments, even after you have deleted the information from our services or deactivated your account.</p>
<h3> Additional Disclosures for U.S. States Privacy Law Compliance.</h3>
<p> The following section includes provisions that comply with the privacy laws of these states (California, Colorado, Delaware, Florida, Virginia, and Utah) and is applicable only to the residents of those states. Specific references to a particular state (in a heading or in the text) are only a reference to that state's law and applies only to that state's residents. Non-state specific language applies to all of the states listed above.</p>
<h4> Do Not Track</h4>
<p> Some browsers have a "Do Not Track" feature that lets you tell websites that you do not want to have your online activities tracked. At this time, we do not respond to browser "Do Not Track" signals.</p>
<p> We adhere to the standards outlined in this privacy policy, ensuring we collect and process personal information lawfully, fairly, transparently, and with legitimate, legal reasons for doing so.</p>
<h4> Cookies and Pixels</h4>
<p> At all times, you may decline cookies from our site if your browser permits. Most browsers allow you to activate settings on your browser to refuse the setting of all or some cookies. Accordingly, your ability to limit cookies is based only on your browser’s capabilities. Please refer to the Cookies section of this privacy policy for more information.</p>
<h4> California Privacy Laws - CPPA</h4>
<p> Under California Civil Code Section 1798.83, if you live in California and your business relationship with us is mainly for personal, family, or household purposes, you may ask us about the information we release to other organizations for their marketing purposes. In accordance with your right to non-discrimination, we may offer you certain financial incentives permitted by the California Consumer Privacy Act, and the California Privacy Rights Act (collectively, CCPA) that can result in different prices, rates, or quality levels for the goods or services we provide. Any CCPA-permitted financial incentive we offer will reasonably relate to the value of your personal information, and we will provide written terms that describe clearly the nature of such an offer. Participation in a financial incentive program requires your prior opt-in consent, which you may revoke at any time.</p>
<p> Under California Civil Code Section 1798.83, if you live in California and your business relationship with us is mainly for personal, family, or household purposes, you may ask us about the information we release to other organizations for their marketing purposes.  To make such a request, please contact us using the details provided in this privacy policy with “Request for California privacy information” in the subject line. You may make this type of request once every calendar year. We will email you a list of categories of personal information we revealed to other organisations for their marketing purposes in the last calendar year, along with their names and addresses. Not all personal information shared in this way is covered by Section 1798.83 of the California Civil Code.</p>
<h4> California Notice of Collection</h4>
<p> In the past 12 months, we have collected the following categories of personal information enumerated in the CCPA:</p>
<ul>
   <li>  Identifiers, such as name, email address, phone number, account name, IP address, and an ID or number assigned to your account.</li>
   <li>  Customer records, such as billing and shipping address, and credit or debit card data.</li>
   <li>  Demographics, such as your age or gender. This category includes data that may qualify as protected classifications under other California or federal laws.</li>
   <li>  Commercial information, such as products or services history and purchases.</li>
   <li>  Internet activity, such as your interactions with our service.</li>
   <li>  Audio or visual data, such as photos or videos you share with us or post on the service.</li>
   <li>  Geolocation data.</li>
   <li>  Employment and education data, such as data you provide when you apply for a job with us.</li>
   <li>  Inferences, such as information about your interests, preferences and favorites.</li>
</ul>
<p> For more information on information we collect, including the sources we receive information from, review the “Information We Collect” section. We collect and use these categories of personal information for the business purposes described in the “Collection and Use of Information” section, including to provide and manage our Service.</p>
<h4> Right to Know and Delete</h4>
<p> You have rights to delete your personal information we collected and know certain information about our data practices in the preceding 12 months. In particular, you have the right to request the following from us:</p>
<ul>
   <li> The categories of personal information we have collected about you;</li>
   <li> The categories of sources from which the personal information was collected;</li>
   <li> The categories of personal information about you we disclosed for a business purpose or sold;</li>
   <li> The categories of third parties to whom the personal information was disclosed for a business purpose or sold;</li>
   <li> The business or commercial purpose for collecting or selling the personal information; and</li>
   <li> The specific pieces of personal information we have collected about you.</li>
</ul>
<p> To exercise any of these rights, please contact us using the details provided in this privacy policy.</p>
<h4> Shine the Light</h4>
<p> In addition to the rights discussed above, you have the right to request information from us regarding the manner in which we share certain personal information as defined by applicable statute with third parties and affiliates for their own direct marketing purposes.</p>
<p> To receive this information, send us a request using the contact details provided in this privacy policy. Requests must include “Privacy Rights Request” in the first line of the description and include your name, street address, city, state, and ZIP code.</p>
<h3> Additional Disclosures for UK General Data Protection Regulation (UK GDPR) Compliance (UK)</h3>
<h4> Data Controller / Data Processor</h4>
<p> The GDPR distinguishes between organisations that process personal information for their own purposes (known as “data controllers”) and organizations that process personal information on behalf of other organizations (known as “data processors”). For the purposes covered by this Privacy Policy, we are a Data Controller with respect to the personal information you provide to us and remain compliant with our data controller obligations under GDPR.</p>
<h4> Third-Party Provided Content</h4>
<p> We may indirectly collect personal information about you from third-parties who have your permission to share it. For example, if you purchase a product or service from a business working with us, and give your permission for us to use your details in order to complete the transaction.</p>
<p> We may also collect publicly available information about you, such as from any social media and messaging platforms you may use. The availability of this information will depend on both the privacy policies and your own privacy settings on such platforms.</p>
<h4> Additional Disclosure for Collection and Use of Personal Information</h4>
<p> In addition to the aforementioned purposes warranting the collection and use of personal information, we may also conduct marketing and market research activities, including how visitors use our site, website improvement opportunities and user experience.</p>
<h4> Personal Information No Longer Required for Our Purposes</h4>
<p> If your personal information is no longer required for our stated purposes, or if you instruct us under your Data Subject Rights, we will delete it or make it anonymous by removing all details that identify you (“Anonymisation”). However, if necessary, we may retain your personal information for our compliance with a legal, accounting, or reporting obligation or for archiving purposes in the public interest, scientific, or historical research purposes or statistical purposes.</p>
<h3> Legal Bases for Processing Your Personal Information</h3>
<p> Data Protection and Privacy Laws permit us to collect and use your personal data on a limited number of grounds.. In which case, we will collect and use your personal information lawfully, fairly and in a transparent manner. We never directly market to any person(s) under 18 years of age.</p>
<p> Our lawful bases depend on the services you use and how you use them. This is a non-exhaustive list of the lawful bases we use:</p>
<h4> Consent From You</h4>
<p> Where you give us consent to collect and use your personal information for a specific purpose. You may withdraw your consent at any time using the facilities we provide; however this will not affect any use of your information that has already taken place. When you contact us, we assume your consent based on your positive action of contact, therefore you consent to your name and email address being used so we can respond to your enquiry.</p>
<p> Where you agree to receive marketing communications from us, we will do so based solely on your indication of consent or until you instruct us not to, which you can do at any time.</p>
<p> While you may request that we delete your contact details at any time, we cannot recall any email we have already sent. If you have any further enquiries about how to withdraw your consent, please feel free to enquire using the details provided in the Contact Us section of this privacy policy.</p>
<h4> Performance of a Contract or Transaction</h4>
<p> Where you have entered into a contract or transaction with us, or in order to take preparatory steps prior to our entering into a contract or transaction with you. For example, if you contact us with an enquiry, we may require personal information such as your name and contact details in order to respond.</p>
<h4> Our Legitimate Interests</h4>
<p> Where we assess it is necessary for our legitimate interests, such as for us to provide, operate, improve and communicate our services. We consider our legitimate interests to include research and development, understanding our audience, marketing and promoting our services, measures taken to operate our services efficiently, marketing analysis, and measures taken to protect our legal rights and interests.</p>
<h4> Compliance with Law</h4>
<p> In some cases, we may have a legal obligation to use or keep your personal information. Such cases may include (but are not limited to) court orders, criminal investigations, government requests, and regulatory obligations. For example, we are required to keep financial records for a period of 7 years. If you have any further enquiries about how we retain personal information in order to comply with the law, please feel free to enquire using the details provided in the Contact Us section of this privacy policy.</p>
<h4> International Transfers of Personal Information</h4>
<p> The personal information we collect is stored and/or processed in the United Kingdom by us. Following an adequacy decision by the EU Commission, the UK has been granted an essentially equivalent level of protection to that guaranteed under UK GDPR.</p>
<p> On some occasions, where we share your data with third parties, they may be based outside of the UK, or the European Economic Area (“EEA”). These countries to which we store, process, or transfer your personal information may not have the same data protection laws as the country in which you initially provided the information.</p>
<p> If we transfer your personal information to third parties in other countries:</p>
<ul>
   <li> we will perform those transfers in accordance with the requirements of the UK GDPR (Article 45) and Data Protection Act 2018;</li>
   <li> we will adopt appropriate safeguards for protecting the transferred data, including in transit, such as standard contractual clauses (“SCCs”) or binding corporate rules.</li>
</ul>
<h4> Your Data Subject Rights</h4>
<p><strong>Right to Restrict Processing:</strong> You have the right to request that we restrict the processing of your personal information if (i) you are concerned about the accuracy of your personal information; (ii) you believe your personal information has been unlawfully processed; (iii) you need us to maintain the personal information solely for the purpose of a legal claim; or (iv) we are in the process of considering your objection in relation to processing on the basis of legitimate interests.</p>
<p><strong>Right to Object:</strong> You have the right to object to processing of your personal information that is based on our legitimate interests or public interest. If this is done, we must provide compelling legitimate grounds for the processing which overrides your interests, rights, and freedoms, in order to proceed with the processing of your personal information.</p>
<p><strong>Right to be Informed:</strong> You have the right to be informed with how your data is collected, processed, shared and stored.</p>
<p><strong>Right of Access:</strong> You may request a copy of the personal information that we hold about you at any time by submitting a Data Subject Access Request (DSAR). The statutory deadline for fulfilling a DSAR request is 30 calendar days from our receipt of your request.</p>
<p><strong>Right to Erasure:</strong> In certain circumstances, you can ask for your personal data to be erased from the records held by organisations. However this is a qualified right; it is not absolute, and may only apply in certain circumstances.</p>
<p> When may the right to erasure apply?</p>
<ul>
   <li> When the personal data is no longer necessary for the purpose for which it was originally collected or processed for.</li>
   <li> If consent was the lawful basis for processing personal data and that consent has been withdrawn. Ayojn Consulting FZC relies on consent to process personal data in very few circumstances.</li>
   <li> The Company is relying on legitimate interests as a legal basis for processing personal data and an individual has exercised the right to object and it has been determined that the Company has no overriding legitimate grounds to refuse that request.</li>
   <li> Personal data are being processed for direct marketing purposes e.g. a person’s name and email address, and the individual objects to that processing.</li>
   <li> There is legislation that requires that personal data are to be destroyed.</li>
</ul>
<p><strong>Right to Portability:</strong> Individuals have the right to get some of their personal data from an organisation in a way that is accessible and machine-readable, for example as a csv file. Associated with this, individuals also have the right to ask an organisation to transfer their personal data to another organisation.</p>
<p> However, the right to portability:</p>
<ul>
   <li> only applies to personal data which a person has directly given to Ayojn Consulting FZC in electronic form; and</li>
   <li> onward transfer will only be available where this is “technically feasible”.</li>
</ul>
<p><strong>Right to Rectification:</strong> If personal data is inaccurate, out of date, or incomplete, individuals have the right to correct, update or complete that data. Collectively this is referred to as the right to rectification. Rectification may involve filling the gaps i.e. to have to have incomplete personal data completed – although this will depend on the purposes for the processing. This may involve adding a supplementary statement to the incomplete data to highlight any inaccuracy or claim thereof.</p>
<p> This right only applies to an individual’s own personal data; a person cannot seek the rectification of another person’s information.</p>
<p><strong>Notification of data breaches:</strong> Upon discovery of a data breach, we will investigate the incident and report it to the UK’s data protection regulator and yourself, if we deem it appropriate to do so.</p>
<p><strong>Complaints:</strong> You have the right, at any time, to lodge a complaint with the Information Commissioner’s Office (ICO), the UK supervisory authority for data protection issues (www.ico.org.uk). We would, however, appreciate the chance to deal with your concerns before you approach the ICO so please contact us in the first instance using the details below.  Please provide us with as much information as you can about the alleged breach. We will promptly investigate your complaint and respond to you, in writing, setting out the outcome of our investigation and the steps we will take to deal with your complaint.</p>
<h4> Enquiries, Reports and Escalation</h4>
<p> To enquire about Ayojn Consulting FZC's privacy policy, or to report violations of user privacy, you may contact our Data Protection Officer using the details in the Contact us section of this privacy policy.</p>
<p> If we fail to resolve your concern to your satisfaction, you may also contact the Information Commissioner’s Office (ICO), the UK Data Protection regulator:</p>
<p> Information Commissioner's Office
   <br>Wycliffe House
   <br>Water Lane
   <br>Wilmslow
   <br>Cheshire
   <br>SK9 5AF
   <br>
   <br>Tel: 0303 123 1113 (local rate)
   <br>Website: www.ico.org.uk
</p>
<h3> Additional Disclosures for Personal Information Protection and Electronic Documents Act (PIPEDA) Compliance (Canada)</h3>
<h4> Additional scope of personal information</h4>
<p> In accordance with PIPEDA, we broaden our definition of personal information to include any information about an individual, such as financial information, information about your appearance, your views and opinion (such as those expressed online or through a survey), opinions held about you by others, and any personal correspondences you may have with us. While this information may not directly identify you, be aware that it may be combined with other information to do so.</p>
<p> As PIPEDA refers to personal information using the term Personally Identifying Information (PII), any references to personal information and PII in this privacy policy, and in official communications from Ayojn Consulting FZC, are intended as equivalent to one another in every way, shape and form.</p>
<h4> Valid Consent</h4>
<p> Where you give us consent to collect and use your personal information for a specific purpose. You may withdraw your consent at any time using the facilities we provide; however this will not affect any use of your information that has already taken place. When you contact us, we assume your consent based on your positive action of contact, therefore you consent to your name and email address being used so we can respond to your enquiry. Under PIPEDA, consent is only valid if it is reasonable to expect that an individual to whom the organisation's activities are directed would understand the nature, purpose, and consequences of the collection, use, or disclosure of the personal information to which they are consenting.</p>
<p> Where you agree to receive marketing communications from us, we will do so based solely on your indication of consent or until you instruct us not to, which you can do at any time.</p>
<p> While you may request that we delete your contact details at any time, we cannot recall any email we have already sent. If you have any further enquiries about how to withdraw your consent, please feel free to enquire using the details provided in the Contact Us section of this privacy policy.</p>
<h4> International Transfers of Information</h4>
<p> While Ayojn Consulting FZC endeavours to keep, store and handle customer data within locations in Canada, it may use agents or service providers located in the United States (U.S.), European Economic Area (EEA) or United Kingdom (UK) to collect, use, retain and process personal information as part of providing services to you. While we use all reasonable efforts to ensure that personal information receives the same level of security in any other jurisdiction as it would in Canada, please be aware that privacy protections under U.S. laws may not be the same adequacy.</p>
<h4> Customer Data Rights</h4>
<p> Although PIPEDA does not contain an extensive set of consumer rights, it does grant consumers the right to:</p>
<ul>
   <li> Access the personal information organisations hold about them;</li>
   <li> Correct any inaccurate or outdated personal information the organisation hold about them (or, if this is not possible, delete the inaccurate personal information)</li>
   <li> Withdraw consent for any activities for which they have consented (e.g. direct marketing or cookies</li>
</ul>
<h4> Right to Withdraw Consent</h4>
<p> Where you give us consent to collect and use your personal information for a specific purpose. Subject to some restrictions, you can, at any time, refuse to consent, or continue to consent to the collection, use or disclosure of their personal information by notifying us using the email address below in the ‘Contact Us’ section. Withdrawal of consent may impact our ability to provide or continue to provide services.</p>
<p> Customers cannot refuse collection, use and disclosure of their personal information if such information is required to:</p>
<ul>
   <li> be collected, used or disclosed as required by any law;</li>
   <li> fulfil the terms of any contractual agreement; and</li>
   <li> be collected, used or disclosed as required by any regulators including self regulatory organisations</li>
</ul>
<p> While you may request that we delete your contact details at any time, we cannot recall any email we have already sent. If you have any further enquiries about how to withdraw your consent, please feel free to enquire using the details provided in the Contact Us section of this privacy policy.</p>
<h4> Right of Access under PIPEDA</h4>
<p> PIPEDA gives you a general right to access the PII held by businesses subject to this law. Under PIPEDA, you need to make your access request in writing and pay a minimal fee of $30.00.</p>
<p> If any organisational fees seem unjust, you have the right to complain about this. We retain the right to decide how we disclose the copies of your PII to you. We will take all necessary measures to fulfil your request in 30 days from receipt, otherwise we must inform you of our inability to do so before the 30-day timeframe if:</p>
<ul>
   <li> meeting the time limit would unreasonably interfere with our business activities; or</li>
   <li> the time required to undertake consultations necessary to respond to the request would make it impractical to meet the time limit.</li>
</ul>
<p> We can also extend the time limit for the length of time required to convert the personal information into an alternative format. In these circumstances, we will advise you of the delay within the first 30 days and explain the reason for it.</p>
<h4> Right of rectification under PIPEDA</h4>
<p> You may request a correction to any factual errors or omissions within your PII. We would ask you to provide some evidence to back up your claim. Under PIPEDA, an organisation must amend the information, as required, if you successfully demonstrate that it’s incomplete or inaccurate.</p>
<p> You may contact us at any time, using the information provided in the Contact Us section of this privacy policy if you believe your PII on our systems is incorrect or incomplete.</p>
<p> If we cannot agree on changing the information, you have the right to have your concerns recorded with the Office of the Privacy Commission of Canada.</p>
<h4> Compliance with PIPEDA’s Ten Principles of Privacy</h4>
<p> This privacy policy complies with the PIPEDA’s requirements and ten principles of privacy, which are as follows:</p>
<ol>
   <li><strong>Accountability.</strong> Ayojn Consulting FZC is responsible for the PII under its control and will designate one or more persons to ensure organisational accountability for compliance with the ten principles of privacy under PIPEDA, whose details are included below. All personnel are accountable for the protection of customers’ personal information.</li>
   <li><strong>Identifying purposes.</strong> Ayojn Consulting FZC identifies the purposes for which personal information is collected at or before the time the information is collected.</li>
   <li><strong>Consent.</strong> Consent is required for Ayojn Consulting FZC's collection, use or disclosure of personal information, except where required or permitted by PIPEDA or other law. In addition, when customers access a product or service offered by us, consent is deemed to be granted. Express consent may be obtained verbally, in writing or through electronic means. Alternatively, consent may be implied through the actions of customers or continued use of a product or service following Ayojn Consulting FZC's notification of changes.</li>
   <li><strong>Limiting collection.</strong> Personal information collected will be limited to that which is necessary for the purposes identified by Ayojn Consulting FZC.</li>
   <li><strong>Limiting use, disclosure and retention.</strong> We will not use or disclose personal information for purposes other than those for which the information was collected, except with your consent or as required by law. We will retain personal information only for as long as is necessary to fulfil the purposes for collecting such information and compliance with any legal requirements.</li>
   <li><strong>Accuracy.</strong> Personal information will be maintained by Ayojn Consulting FZC in an accurate, complete and up-to-date format as is necessary for the purpose(s) for which the personal information was collected.</li>
   <li><strong>Safeguards.</strong> We will protect personal information with security safeguards appropriate to the sensitivity of such information.</li>
   <li><strong>Openness.</strong> We will make our policies and practices relating to the collection and management of personal information readily available upon request, including our brochures or other information that explain our policies, standards, or codes.</li>
   <li><strong>Customer access.</strong> We will inform customers of the existence, use and disclosure of their personal information and will provide access to their personal information, subject to any legal restrictions. We may require written requests for access to personal information and in most cases, will respond within 30 days of receipt of such requests. Customers may verify the accuracy and completeness of their personal information, and may request the personal information be corrected or updated, if appropriate.</li>
   <li><strong>Challenging compliance</strong> Customers are welcome to direct any questions or inquiries concerning our compliance with this privacy policy and PIPEDA requirements using the contact information provided in the Contact Us section of this privacy policy.</li>
</ol>
<h4> Cookie Compliance</h4>
<p> Our email interactions with our customers are compliant with Canadian Anti-Spam Legislation. The Company does not send unsolicited email to persons with whom we have no relationship. We will not sell personal information, such as email addresses, to unrelated third-parties. On occasion, your personal information may be provided to our third-party partners to administer the products and services you request from us.</p>
<p> When you leave our website by linking to another website, you are subject to the privacy and security policies of the new website. We encourage you to read the privacy policies of all websites you visit, especially if you share any personal information with them.</p>
<p> Please refer to our Cookie Policy for more information.</p>
<h4> Enquiries, Reports and Escalation</h4>
<p> To enquire about Ayojn Consulting FZC's privacy policy, or to report violations of user privacy, you may contact us using the details in the Contact us section of this privacy policy.</p>
<p> If we fail to resolve your concern to your satisfaction, you may also contact the Office of the Privacy Commissioner of Canada:</p>
<p> 30 Victoria Street
   <br>Gatineau, QC  K1A 1H3
   <br>Toll Free: 1.800.282.1376
   <br>www.priv.gc.ca
</p>
<h3> Contact Us</h3>
<p> For any questions or concerns regarding your privacy, you may contact us using the following details:</p>
<p> Ayojn Consulting FZC<br>https://ayojn.com</p>
          <!-- (Insert your entire terms content here — I omitted for brevity) -->

          <!-- END: TERMS CONTENT -->
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>

<div id="howtouseText" class="modal " role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <!-- The data reflected at Ayojn is calibrated from the public domain access plus based on the inputs from the Users (Sponsors and Sponsorship Receivers/ Managers).-->
            <div class="modal-body">
                <h3 style="text-align: center"><b>How it Works!</b></h3>
                <p>1. Select if you wish to Offer/ Receive Sponsorship </p>
                <br>
                <p>2. Share what you wish to Sponsor or Get Sponsored </p>
                <br>
                <p>3. We Validate your Profile </p>
                <br>
                <p>4. Go Live - Spot Trends, Gain Insights & Discover Sponsorship Opportunities </p>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

<div id="refer-user"></div>
<!-- login popup END -->
<div id="popupcountry"></div>
<div id="popupdestination"></div>
<div id="popupindustries"></div>
<?php if(Session::has('error_message')): ?>
    <script>
        setTimeout(function () {
            toastr.error("<?php echo Session::get('error_message'); ?>", 'Error');
        }, 1000);
    </script>
<?php endif; ?>
<?php if(Session::has('success_message')): ?>
    <script>
        setTimeout(function () {
            toastr.success("<?php echo Session::get('success_message'); ?>", 'Success');
        }, 1000);
    </script>
<?php endif; ?>
<!-- <script src="<?php echo e(asset('js/jquery1.min.js')); ?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script>
<script src="<?php echo e(asset('js/bootstrap.min.js')); ?>"></script> -->
<script src="<?php echo e(asset('js/typed.min.js')); ?>"></script>
<script src="<?php echo e(asset('js/custom-jquery-new.js?v=1')); ?>"></script>

<script src="<?php echo e(asset('js/jquery.bs.gdpr.cookies.js')); ?>"></script>

<script type="text/javascript">



    function displayRegister() {
        $("#loginForm").modal('hide');
        $("#signupForm").modal('show');
    }


    //comment by Akash
    var settings = {
        message: 'We use tools, such as cookies, to enable essential services and functionality on our site and to collect data on how visitors interact with our site, products and services. By clicking Accept, you agree to our use of these tools for analytics and support.',
        messageMaxHeightPercent: 30,
        delay: 1000,
        allowAdvancedOptions: false,
        acceptButtonLabel: 'Got it',
        OnAccept: function () {

            var preferences = $.fn.bsgdprcookies.GetUserPreferences();
            console.log(preferences);

        }
    }

    $(document).ready(function () {
        // $('body').bsgdprcookies(settings);
        // $("#bs-gdpr-cookies-modal-more-link").hide();
        // $('#cookiesBtn').on('click', function () {
        //     $('body').bsgdprcookies(settings, 'reinit');
        // });
        $("#otp_div").hide();
    });

    function loginForm() {
        if ($("#loginEmail").val() == '' || $("#loginPassword").val() == '') {
            toastr.info('All Fileds are mandatory');

        } else {
            $('.loader').show();
            $.ajax({
                type: 'POST',
                url: base_url + '/login',
                headers: {'X-CSRF-Token': $('meta[name="_token"]').attr('content')},
                data: {email: $("#loginEmail").val(), password: $("#loginPassword").val(), otp: $("#otp").val()},
                success: function (data) {
                    $('.loader').hide();
                    if (data.status == 0) {
                        toastr.info('Please verify your email');
                    }
                    if (data.status == 5) {
                        $("#otp_div").show();
                        $("#loginEmail").attr('disabled','disabled');
                        $("#loginPassword").attr('disabled','disabled');
                        toastr.info(data.intended);
                    }
                    if (data.status == 6) {
                        toastr.info(data.intended);
                        if (data.auth) {
                            window.location.href = data.intended;
                        } 
                    }

                    if (data.status == 7) {
                        toastr.info(data.intended);
                    }

                    if (data.status == 8) {
                        toastr.info(data.intended);
                    }

                    if (data.status == 2) {
                        toastr.info('You have not permission to login here');
                    } else {
                        console.log(data);
                        if (data.auth) {
                            window.location.href = data.intended;
                        } else {
                           // toastr.info('Invalid credential');
                        }
                    }

                }
            })
        }
    }


    $(".resent").click(function(){
        $('.loader').show();
        $.ajax({
            type: 'POST',
            url: base_url + '/resent_mfa_user',
            headers: {'X-CSRF-Token': $('meta[name="_token"]').attr('content')},
            data: {email: $("#loginEmail").val(), password: $("#loginPassword").val()},
            success: function (data) {
                $('.loader').hide();
                if (data.status == 1) {
                    alert('OTP Resent in your email id, please check!');
                }
            }
        });
    });

    // function referFriendForm() {
    //     $("#referafriend_button").attr('disabled', true);
    //     if ($("#emailone").val() == '' && $("#emailtwo").val() == '' && $("#emailthree").val() == '' && $("#emailfour").val() == '' && $("#emailfive").val() == '' && $("#emailsix").val() == '' && $("#emailseven").val() == '' && $("#emaileight").val() == '' && $("#emailnine").val() == '' && $("#emailten").val() == '') {
    //         toastr.info('Enter Atleast One Email Address');
    //     } else {
    //         //  var refer_data = $("#referafriend_form").serialize();
    //         $('#referafriend_button').prop('disabled', true);
    //         //$('#referafriend_button').attr("disabled", "disabled");
    //         $('.loader').show();
    //         $.ajax({
    //             type: 'POST',
    //             url: base_url + '/referAFriend',
    //             headers: {'X-CSRF-Token': $('meta[name="_token"]').attr('content')},
    //             data: {
    //                 emailone: $("#emailone").val(),
    //                 emailtwo: $("#emailtwo").val(),
    //                 emailthree: $("#emailthree").val(),
    //                 emailfour: $("#emailfour").val(),
    //                 emailfive: $("#emailfive").val(),
    //                 emailsix: $("#emailsix").val(),
    //                 emailseven: $("#emailseven").val(),
    //                 emaileight: $("#emaileight").val(),
    //                 emailnine: $("#emailnine").val(),
    //                 emailten: $("#emailten").val()
    //             },
    //             success: function (data) {
    //                 $('.loader').hide();
    //                 $("#referafriend_button").attr('disabled', false);
    //                 if (data.status) {
    //                     toastr.success(data.msg);
    //                     //window.location.href = base_url+data.intended;
    //                 } else {
    //                     toastr.info(data.msg);
    //                 }
    //                 $('#referafriend').modal('hide');
    //                 $('#referafriend_button').prop('disabled', false);
    //                 //
    //             }
    //         })
    //     }
    // }
</script>

<!--Start of Tawk.to Script-->
<script type="text/javascript">
    // var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
    // (function () {
    //     var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
    //     s1.async = true;
    //     s1.src = 'https://embed.tawk.to/5bc6fd1661d0b770925179bb/default';
    //     s1.charset = 'UTF-8';
    //     s1.setAttribute('crossorigin', '*');
    //     s0.parentNode.insertBefore(s1, s0);
    // })();
    // var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
    // (function(){
    //     var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
    //     s1.async=true;
    //     s1.src='https://embed.tawk.to/5feff70bdf060f156a933aae/1er0lq1qa';
    //     s1.charset='UTF-8';
    //     s1.setAttribute('crossorigin','*');
    //     s0.parentNode.insertBefore(s1,s0);
    // })();
    var newwindow;

    function popuponclick() {
        my_window = window.open("",
            "mywindow", "status=1,width=350,height=150");
        my_window.document.write("");
    }

    function poptastic(url) {
        newwindow = window.open(url, 'name', 'height=400,width=200');
        if (window.focus) {
            newwindow.focus()
        }
    }

    function privacyWindow() {
        privacy_window = window.open("",
            "mywindow", "status=1,width=350,height=150");
        privacy_window.document.write('');
    }

    function opentop_countries() {
        var CSRF_TOKEN = $('meta[name="_token"]').attr('content');
        $.ajax({
            type: 'POST',
            url: "<?php echo e(route('topcountriesdata')); ?>",
            data: {_token: CSRF_TOKEN},
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success: function (data) {
                console.log(data);
                $('#popupcountry').html(data);
                $('#topCountriesForm').modal('show');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                toastr.error("Something went wrong", "Error");
            },
        });

    }

    function opentop_destination() {
        var CSRF_TOKEN = $('meta[name="_token"]').attr('content');
        $.ajax({
            type: 'POST',
            url: "<?php echo e(route('topdestinationdata')); ?>",
            data: {_token: CSRF_TOKEN},
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success: function (data) {
                console.log(data);
                $('#popupdestination').html(data);
                $('#topdestinationForm').modal('show');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                toastr.error("Something went wrong", "Error");
            },
        });

    }

    function opentop_industries() {
        var CSRF_TOKEN = $('meta[name="_token"]').attr('content');
        $.ajax({
            type: 'POST',
            url: "<?php echo e(route('topindustriesdata')); ?>",
            data: {_token: CSRF_TOKEN},
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success: function (data) {
                console.log(data);
                $('#popupindustries').html(data);
                $('#topindustriesForm').modal('show');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                toastr.error("Something went wrong", "Error");
            },
        });

    }

    function opentop_recipients() {
        var CSRF_TOKEN = $('meta[name="_token"]').attr('content');
        $.ajax({
            type: 'POST',
            url: "<?php echo e(route('toprecieptdsdata')); ?>",
            data: {_token: CSRF_TOKEN},
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success: function (data) {
                console.log(data);
                $('#popupindustries').html(data);
                $('#topindustriesForm').modal('show');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                toastr.error("Something went wrong", "Error");
            },
        });

    }

    function openBankform(id) {
        $('.loader').show();
        $.ajax({
            type: 'POST',
            url: "<?php echo e(route('mybankget')); ?>",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success: function (data) {
                console.log(data);
                if(data.length > 0){
                $('#my_bank_country option[value="'+data[0].my_bank_country+'"]').prop('selected', true);
                $('#my_currency option[value="'+data[0].my_currency+'"]').prop('selected', true);
                $('#my_bank_code option[value="'+data[0].my_bank_code+'"]').prop('selected', true);
                $("#my_bank_account").val(data[0].my_bank_account);
                $("#my_code_no").val(data[0].my_code_no);
                $("#my_taxid").val(data[0].my_taxid);
                } 
                
                $('.loader').hide();
                $('#userbankform').modal('show');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                toastr.error("Something went wrong", "Error");
            },
        });
    }

    function openEditForm(id) {
        $('.loader').show();
        $.ajax({
            type: 'GET',
            url: "<?php echo e(route('getUserCount')); ?>" + '/' + id,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success: function (data) {
                $('.loader').hide();
                $('#edituserform').modal('show');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                toastr.error("Something went wrong", "Error");
            },
        });
    }

   

    function openreferafriend() {
        $('.loader').show();
        $.ajax({
            type: 'GET',
            url: "<?php echo e(route('getReferFriends')); ?>",

            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success: function (data) {
                $('.loader').hide();
                console.log(data);
                $("#refer-user").html(data.html);
                $('#myModal').modal('show');


            },
            error: function (jqXHR, textStatus, errorThrown) {
                toastr.error("Something went wrong", "Error");
            },
        });

    }

    function displaySpecify(flagSpecify) {
        if (flagSpecify == 1) {
            $("#sponsorOtherSpecify").show();
            var event_type = $("#sponsorType").val();
            $('#sponsorOtherSpecifyValue').html('');
            //$('#sponsorOtherSpecifyValue').append('<option value="">Select Specify</option>');
        } else {
            $("#sponsoredOtherSpecify").show();
            var event_type = $("#sponsoredType").val();
            $('#sponsoredOtherSpecifyValue').html('');
            //$('#sponsoredOtherSpecifyValue').append('<option value="">Select Specify</option>');
        }
        $("#sponsoredOtherSpecify").show();
        var CSRF_TOKEN = $('meta[name="_token"]').attr('content');
        $.ajax({
            type: 'POST',
            url: "<?php echo e(route('getspecify')); ?>",
            data: {_token: CSRF_TOKEN, event_type: event_type},
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success: function (data) {
                console.log(data);
                if (data != '') {
                    // Loop through each of the results and append the option to the dropdown
                    $.each(data, function (k, v) {
                        if (flagSpecify == 1) {
                            $('#sponsorOtherSpecifyValue').append('<option value="' + v.id + '">' + v.specify_name + '</option>');
                        } else {
                            $('#sponsoredOtherSpecifyValue').append('<option value="' + v.id + '">' + v.specify_name + '</option>');
                        }
                    });
                }
                //$('#popupcountry').html(data);
                //$('#topCountriesForm').modal('show');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                toastr.error("Something went wrong", "Error");
            },
        });
    }

    function displaySpecifyEdit(flagSpecify) {
        if (flagSpecify == 1) {
            //$("#sponsorOtherSpecify").show();
            var event_type = $("#sponsorType_edit").val();
            $('#sponsorOtherSpecifyValue_edit').html('');
            //$('#sponsorOtherSpecifyValue_edit').append('<option value="">Select Specify</option>');
        } else {
            //$("#sponsoredOtherSpecify").show();
            var event_type = $("#sponsorType_edit").val();
            $('#sponsoredOtherSpecifyValue').html('');
            //$('#sponsorOtherSpecifyValue_edit').append('<option value="">Select Specify</option>');
        }
        //$("#sponsoredOtherSpecify").show();
        var CSRF_TOKEN = $('meta[name="_token"]').attr('content');
        $.ajax({
            type: 'POST',
            url: "<?php echo e(route('getspecify')); ?>",
            data: {_token: CSRF_TOKEN, event_type: event_type},
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success: function (data) {
                //console.log(data);
                if (data != '') {
                    // Loop through each of the results and append the option to the dropdown
                    $.each(data, function (k, v) {
                        if (flagSpecify == 1) {
                            $('#sponsorOtherSpecifyValue_edit').append('<option value="' + v.id + '">' + v.specify_name + '</option>');
                        } else {
                            $('#sponsorOtherSpecifyValue_edit').append('<option value="' + v.id + '">' + v.specify_name + '</option>');
                        }

                    });
                }
                //$('#popupcountry').html(data);
                //$('#topCountriesForm').modal('show');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                toastr.error("Something went wrong", "Error");
            },
        });
    }

    function getSpecifyBid() {
        var event_type = $("#sponsorr_type_bid").val();
        $('#specify_bid').html('');
        //$("#sponsoredOtherSpecify").show();
        var CSRF_TOKEN = $('meta[name="_token"]').attr('content');
        $.ajax({
            type: 'POST',
            url: "<?php echo e(route('getspecify')); ?>",
            data: {_token: CSRF_TOKEN, event_type: event_type},
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success: function (data) {
                //console.log(data);
                if (data != '') {
                    // $('#specify_bid').html('');
                    // Loop through each of the results and append the option to the dropdown
                    $.each(data, function (k, v) {

                        $('#specify_bid').append('<option value="' + v.id + '">' + v.specify_name + '</option>');
                    });
                }
                //$('#popupcountry').html(data);
                //$('#topCountriesForm').modal('show');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                toastr.error("Something went wrong", "Error");
            },
        });
    }

    function getSpecifyBidEdit() {
        var event_type = $("#sponsorr_type_bid_edit").val();
        var bidid = $('#bidId').val();
        $('#specify_bid_edit').html('');
        //$("#sponsoredOtherSpecify").show();
        var CSRF_TOKEN = $('meta[name="_token"]').attr('content');
        $.ajax({
            type: 'POST',
            url: "<?php echo e(route('getspecifyedit')); ?>",
            data: {_token: CSRF_TOKEN, event_type: event_type, bidid: bidid},
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success: function (data) {
                //console.log(data);
                if (data != '') {
                    // $('#specify_bid').html('');
                    // Loop through each of the results and append the option to the dropdown
                    var selected = '';
                    $.each(data, function (k, v) {
                        if (v.selected == 1) {
                            selected = 'selected';
                        } else {
                            selected = '';
                        }
                        $('#specify_bid_edit').append('<option value="' + v.id + '"' + selected + '>' + v.specify_name + '</option>');
                    });
                }
                //$('#popupcountry').html(data);
                //$('#topCountriesForm').modal('show');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                toastr.error("Something went wrong", "Error");
            },
        });
    }
    // setTimeout(function () {
    //     $(".dataTables_info").hide()
    //     var hideElm = 'entries',
    //         regex = new RegExp(hideElm, 'g');

    //     $('#table_id_wrapper').html(function(i, html){
    //         return html.replace(regex, '<span style="display:none">' + hideElm + '</span>');
    //     });

    // },500);

</script>
<!--End of Tawk.to Script-->


<script>
    
    $(document).ready(function(){
        $(document).on('hidden.bs.modal', function () {
    $('body').css('padding-right', '');
});
       
        if($("#chk_id").val() > 0)
        {
            getcityforprofiledefault($("#profilecountry").val());
           
        }
    });

    function getcityforprofiledefault(id)
    {
        $.ajax({
            type: "POST",
            url: base_url + "/get-city",
            headers: {'X-CSRF-Token': $('meta[name="_token"]').attr('content')},
            data: {countryID: id},
            success: function (res) {
                $('.loader').hide();
                if (res) {
                    $("#profileCity").empty();
                    $.each(res, function (key, value) {
                            if(value.name == '<?php echo e((!empty(Auth::user()->city_name)) ? Auth::user()->city_name->name : 0); ?>')
                            {
                                $("#profileCity").append('<option value="' + value.id + '" selected>' + value.name + '</option>');
                            }
                            else{
                                $("#profileCity").append('<option value="' + value.id + '">' + value.name + '</option>');
                            }
                    });
                    
                } else {
                    $("#profileCity").empty();
                }
            }
        });
    }

    function getcityforprofile()
    {
        var id = $("#profilecountry").val();
        $.ajax({
            type: "POST",
            url: base_url + "/get-city",
            headers: {'X-CSRF-Token': $('meta[name="_token"]').attr('content')},
            data: {countryID: id},
            success: function (res) {
                $('.loader').hide();
                if (res) {
                    $("#profileCity").empty();
                    $.each(res, function (key, value) {
                        $("#profileCity").append('<option value="' + value.id + '">' + value.name + '</option>');
                        $("#my_city").append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                    
                } else {
                    $("#profileCity").empty();
                }
            }
        });
    }


    // function getcityforbankpopup()
    // {
    //     var id = $("#my_country").val();
    //     $.ajax({
    //         type: "POST",
    //         url: base_url + "/get-city",
    //         headers: {'X-CSRF-Token': $('meta[name="_token"]').attr('content')},
    //         data: {countryID: id},
    //         success: function (res) {
    //             $('.loader').hide();
    //             if (res) {
    //                 $("#my_city").empty();
    //                 $.each(res, function (key, value) {
    //                     $("#my_city").append('<option value="' + value.id + '">' + value.name + '</option>');
    //                 });
                    
    //             } else {
    //                 $("#my_city").empty();
    //             }
    //         }
    //     });
    // }

     
</script>



</body>
</html>

<?php if(auth()->guard()->check()): ?>
    <?php if (! (Request::is('controlpanel*'))): ?>
        <link rel="stylesheet" href="<?php echo e(asset('css/chat-floating.css')); ?>" />
        <?php echo $__env->make('partials.chat-floating', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <script>
            // chat endpoint: default to FastAPI dev server if known, otherwise same-origin /chat
            window.__CHAT_API_ENDPOINT__ = window.__CHAT_API_ENDPOINT__ || '<?php echo e(env('CHAT_API_ENDPOINT', "http://127.0.0.1:8001/api/faqs/match")); ?>';
            window.__CHAT_API_BASE_URL__ = window.__CHAT_API_BASE_URL__ || '<?php echo e(env('CHAT_API_BASE_URL', "http://127.0.0.1:8001")); ?>';
        </script>
        <script src="<?php echo e(asset('js/chat-floating.js')); ?>"></script>
    <?php endif; ?>
<?php endif; ?>

</body>
</html>
