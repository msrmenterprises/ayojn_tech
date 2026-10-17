
    <style>

.container form {
        overflow-y: scroll;
    }
    .container form {
    position: relative;
    margin-top: 16px;
    min-height: 490px;
    background-color: #fff;
    overflow: hidden;
}.container header{
    position: relative;
    font-size: 20px;
    font-weight: 600;
    color: #333;
}
.container header::before{
    content: "";
    position: absolute;
    left: 0;
    bottom: -2px;
    height: 3px;
    width: 27px;
    border-radius: 8px;
    background-color: #4070f4;

}
.container form{
    position: relative;
    margin-top: 16px;
    min-height: 490px;
    background-color: #fff;
    overflow: hidden;
}
.container form .form{
   
    background-color: #fff;
    transition: 0.3 ease;
}
.container form .form.second{
    opacity: 0;
    pointer-events: none;
    transform: translateX(100%);
}
form.secActive .form.second{
    opacity: 1;
    pointer-events: auto;
    transform: translateX(0);
}
form.secActive .form.first{
    opacity: 0;
    pointer-events: none;
    transform: translateX(-100%);
}
.container form .title{
    display: block;
    margin-bottom: 8px;
    font-size: 16px;
    font-weight: 500;
    margin: 16px 0;
    background: lavender;
    padding:5px;
    color: #333;
    border-bottom: solid 1px #a1a1a1;
}
.container form .fields{
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;

}
form .fields .input-field{
    display: flex;
    width: calc(100% / 3 - 15px);
    flex-direction: column;
    margin: 4px 0;
}
.input-field label{
    font-size: 13px;
    font-weight: 500;
    color: #2e2e2e;

}
.select2-container .select2-selection--single{height:42px;}
.select2-container--default .select2-selection--single .select2-selection__rendered{line-height:42px;}
.input-field input, .input-field select{
    outline: none;
    font-size: 14px;
    font-weight: 400;
    color: #333;
    border-radius: 5px;
    border: 1px solid #aaa;
    padding: 0 15px;
    height: 42px;
    margin: 8px 0;
}
.input-field input:is(:focus){
    box-shadow: 0 3px 6px rgba(0,0,0,0.13);
}
.input-field input[type="date"]{
    color: #707070;
}
.input-field input[type="date"]:valid{
    color: #333;
}
.container form button, .backBtn{

    align-items: center;
    justify-content: center;
    height: 45px;
    max-width: 200px;
    width: 100%;
    border: none;
    outline: none;
    color: #fff;
    border-radius: 5px;
    margin: 25px o;
    background-color: #4070f4;
    transition: all 0.3s linear;
    cursor: pointer;
}
.container form button,
.container form .backBtn{
    font-size: 14px;
    font-weight: 400;
    margin-top:20px;
}
form button:hover{
    background-color: #265df2;
}
form button i,
form .backBtn{
    margin: 0 6px;
}
form .backBtn i{
    transform: rotate(180deg);
}
form .buttons{
    display: flex;
    align-items: center;
}
form .buttons button, .backBtn{
    margin-right: 14px;
}

@media (max-width: 750px) {
    .container form{
        overflow-y: scroll;
    }
    .container form::-webkit-scrollbar{
        display: none;
    }
    form .fields .input-field{
        width: calc(100% / 2 - 15px);
    }  
}

@media (max-width: 550px) {
    form .fields .input-field{
        width: 100%;
    }  
}
.swal2-popup #swal2-content {
    text-align: left;
}

        .history{padding-top:0px; display:table;}
        fieldset {
    font-family: sans-serif;
    border: 5px solid #1F497D;
    margin-bottom:30px;
    border-radius: 5px;
    padding: 15px;
}

fieldset legend {
    background: #1F497D;
    color: #fff;
    padding: 5px 10px ;
    font-size: 22px;
    border-radius: 5px;
   
    margin-left: 20px;
}
.table th{background-color:#e4e4e4;}

.red{color:red;}
    </style>


    <div class="container history" style="margin-bottom:50px;">

    
    <form method="post" action="<?php echo e(route('beneficiary-update-admin')); ?>">
        <input type="hidden" value="<?php echo e($data->id); ?>" name="id" />
    <?php echo csrf_field(); ?>

            <div class="form first">
                <div class="details personal">
                    <span class="title">Basic Details</span>

                    <div class="fields">
                        <div class="input-field">
                            <label>Company/Beneficiary Name<span class="red">*</span></label>
                            <input type="text" placeholder="Enter name" required="" value="<?php echo e($data->company_name); ?>" name="company_name">
                        </div>

                        <div class="input-field">
                            <label>Contact Name<span class="red">*</span></label>
                            <input type="text" placeholder="Enter Contact name" required="" value="<?php echo e($data->contact_name); ?>" name="contact_name">
                        </div>

                        <div class="input-field">
                            <label>Email<span class="red">*</span></label>
                            <input type="email" placeholder="Enter your email" id="email" value="<?php echo e($data->email); ?>" required="" name="email">
                        </div>


                        
                        <div class="input-field">
                            <label>Mobile Number<span class="red">*</span></label>
                            
                            <input type="number" placeholder="Enter mobile number" required="" value="<?php echo e($data->mobile_no); ?>" name="mobile_no">
                        </div>

                        <div class="input-field">
                            <label>Beneficiary Tax ID</label>
                            <input type="text" placeholder="Enter Tax ID" name="tax_id" value="<?php echo e($data->tax_id); ?>" >
                        </div>
                        
                        <div class="input-field">
                         
                        </div>

                    </div>
                </div>


                <div class="details ID">
                    <span class="title">Bank Details</span>

                    <div class="fields">
                        <div class="input-field">
                            <label>Country Bank Account is in<span class="red">*</span></label>
                <select id="bank_country" name="bank_country" class="form-control select2" required>
                <option>Select</option>
                <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($country->country_name); ?>" <?php echo e($data->bank_country == $country->country_name ? 'selected' : ''); ?>><?php echo e($country->country_name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                
            </select>
                        </div>

                        <div class="input-field">
                            <label>Currency<span class="red">*</span></label>
                            <select id="currency" name="currency" class="select2" required>
                                <option>Select</option>
                                <?php $__currentLoopData = $currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($currency->iso); ?>" <?php echo e($data->currency == $currency->iso ? 'selected' : ''); ?>><?php echo e($currency->name); ?></option> 
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
   
</select>
                        </div>

                        <div class="input-field">
                            <label>Bank Account/IBAN<span class="red">*</span></label>
                            <input type="text" placeholder="Enter Bank Account/IBAN" required=""  value="<?php echo e($data->bank_account); ?>" name="bank_account">
                        </div>

                        <div class="input-field">
                            <label>Select a National bank code type<span class="red">*</span></label>
                            <select required="" class="select2" name="bank_code" id="bank_code">
                                <option>Select</option>

                                <option <?php if($data->bank_code=='ABA/Routing Number'): ?> selected <?php endif; ?>>ABA/Routing Number</option>
                                <option <?php if($data->bank_code=='Bank Code'): ?> selected <?php endif; ?>>Bank Code</option>
                                <option <?php if($data->bank_code=='BSB'): ?> selected <?php endif; ?>>BSB</option>
                                <option <?php if($data->bank_code=='IFSC'): ?> selected <?php endif; ?>>IFSC</option>
                                <option <?php if($data->bank_code=='Other Number'): ?> selected <?php endif; ?>>Other Number</option>
                                <option <?php if($data->bank_code=='Sort Code'): ?> selected <?php endif; ?>>Sort Code</option>
                                <option <?php if($data->bank_code=='Transit Number & Institution Number'): ?> selected <?php endif; ?>>Transit Number & Institution Number</option>
                            </select>
                        </div>

                        <div class="input-field">
                            <label>Code Number<span class="red">*</span></label>
                            <input type="text" placeholder="Enter Code Number" required="" value="<?php echo e($data->code_no); ?>" name="code_no">
                        </div>
                        
                        <div class="input-field">
                            <label>Beneficiary Country<span class="red">*</span></label>
                            <select id="beneficiary_country" name="beneficiary_country" class="form-control select2" require="">
                            <option>Select</option>

                              <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($country->country_name); ?>" <?php echo e($data->beneficiary_country == $country->country_name ? 'selected' : ''); ?>><?php echo e($country->country_name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

               </select>
                        </div>

                        <div class="input-field">
                            <label>Beneficiary City<span class="red">*</span></label>
                            <input type="text" placeholder="Beneficiary City" required="" value="<?php echo e($data->beneficiary_city); ?>" name="beneficiary_city">
                        </div>

                        <div class="input-field">
                            <label>Beneficiary Address<span class="red">*</span></label>
                            <input type="text" required="" placeholder="Enter your Address" value="<?php echo e($data->beneficiary_address); ?>" name="beneficiary_address" />
                        </div>

                        <div class="input-field">
                        </div>
                    </div>
                    
                </div>
            </div>

            <div class="row text-center">
                 <button class="nextBtn">
                        <span class="btnText" data-tooltip="true" data-placement="top"  title="">Update</span>
                        <i class="uil uil-navigator"></i>
                    </button>
                    
            </div>
        </form> 

    </div>
   

    <script src="<?php echo e(asset('js/jquery1.min.js')); ?>"></script>
    <script src="<?php echo e(asset('js/bootstrap.min.js')); ?>"></script>
    <script src="<?php echo e(asset('js/typed.min.js')); ?>"></script>
    
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.0/clipboard.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script>
    <script>
          $(document).ready(function () {
                $(".select2").select2();
                $("#bank_country").val("<?php echo e($data->bank_country); ?>").trigger('change');
                $("#beneficiary_country").val("<?php echo e($data->beneficiary_country); ?>").trigger('change');
                $("#bank_code").val("<?php echo e($data->bank_code); ?>").trigger('change');
                $("#currency").val("<?php echo e($data->currency); ?>").trigger('change');
                
        });
    </script>

