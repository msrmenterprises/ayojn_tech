@extends('layout')
@php  $buttons = $db_buttons['directpay'];  @endphp
@section('content')
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


        .history{padding-top:30px; display:table;}
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
<!-- 
    @php
echo session()->get('payment_token')."<br/><br/>".session()->get('payment_refresh_token');
@endphp  -->
    <div class="container history" style="margin-bottom:50px;">

    &nbsp;&nbsp;<a href="{{route('beneficiary-list')}}" class="btn btn-success pull-right" data-tooltip="true" data-placement="top"  title="{!!$buttons[0]['hover']!!}">{!!$buttons[0]['icon']!!} {!!$buttons[0]['name']!!}</a>&nbsp;&nbsp;
    <a href="{{route('offline-pay')}}" class="btn btn-danger login-button pull-right" style="background-color:#d41b1b; margin-right:10px;" data-tooltip="true" data-placement="top"  title="{!!$buttons[1]['hover']!!}">{!!$buttons[1]['icon']!!} {!!$buttons[1]['name']!!} </a>&nbsp;&nbsp;

    @if(session()->get('success'))
    <div class="alert alert-success alert-dismissible" role="alert">
         {{ session()->get('success') }}
         <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

     @if(session()->get('warning'))
     <div class="alert alert-warning alert-dismissible" role="alert" >
         {{ session()->get('warning') }}
         <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    

    <form method="post" action="{{route('beneficiary-save')}}">
    {!! csrf_field() !!}
            <div class="form first">
                <div class="details personal">
                    <span class="title">Basic Details</span>

                    <div class="fields">
                        <div class="input-field">
                            <label>Company/Beneficiary Name<span class="red">*</span></label>
                            <input type="text" placeholder="Enter name" required="" name="company_name">
                        </div>

                        <div class="input-field">
                            <label>Contact Name<span class="red">*</span></label>
                            <input type="text" placeholder="Enter Contact name" required="" name="contact_name">
                        </div>

                        <div class="input-field">
                            <label>Email<span class="red">*</span></label>
                            <input type="email" placeholder="Enter your email" id="email" required="" name="email">
                        </div>

                        <div class="input-field">
                            <label>Mobile Number<span class="red">*</span></label>
                            <input type="tel" pattern="[0-9]{10,15}" maxlength="10" placeholder="Enter mobile number" required="" name="mobile_no">
                        </div>

                        <div class="input-field">
                            <label>Beneficiary Tax ID</label>
                            <input type="text" placeholder="Enter Tax ID" name="tax_id">
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
                            <select id="country" name="bank_country" class="form-control select2" required>
                            <option value="">Select</option>
                <option value="Afghanistan">Afghanistan</option>
                <option value="Åland Islands">Åland Islands</option>
                <option value="Albania">Albania</option>
                <option value="Algeria">Algeria</option>
                <option value="American Samoa">American Samoa</option>
                <option value="Andorra">Andorra</option>
                <option value="Angola">Angola</option>
                <option value="Anguilla">Anguilla</option>
                <option value="Antarctica">Antarctica</option>
                <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                <option value="Argentina">Argentina</option>
                <option value="Armenia">Armenia</option>
                <option value="Aruba">Aruba</option>
                <option value="Australia">Australia</option>
                <option value="Austria">Austria</option>
                <option value="Azerbaijan">Azerbaijan</option>
                <option value="Bahamas">Bahamas</option>
                <option value="Bahrain">Bahrain</option>
                <option value="Bangladesh">Bangladesh</option>
                <option value="Barbados">Barbados</option>
                <option value="Belarus">Belarus</option>
                <option value="Belgium">Belgium</option>
                <option value="Belize">Belize</option>
                <option value="Benin">Benin</option>
                <option value="Bermuda">Bermuda</option>
                <option value="Bhutan">Bhutan</option>
                <option value="Bolivia">Bolivia</option>
                <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                <option value="Botswana">Botswana</option>
                <option value="Bouvet Island">Bouvet Island</option>
                <option value="Brazil">Brazil</option>
                <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
                <option value="Brunei Darussalam">Brunei Darussalam</option>
                <option value="Bulgaria">Bulgaria</option>
                <option value="Burkina Faso">Burkina Faso</option>
                <option value="Burundi">Burundi</option>
                <option value="Cambodia">Cambodia</option>
                <option value="Cameroon">Cameroon</option>
                <option value="Canada">Canada</option>
                <option value="Cape Verde">Cape Verde</option>
                <option value="Cayman Islands">Cayman Islands</option>
                <option value="Central African Republic">Central African Republic</option>
                <option value="Chad">Chad</option>
                <option value="Chile">Chile</option>
                <option value="China">China</option>
                <option value="Christmas Island">Christmas Island</option>
                <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
                <option value="Colombia">Colombia</option>
                <option value="Comoros">Comoros</option>
                <option value="Congo">Congo</option>
                <option value="Congo, The Democratic Republic of The">Congo, The Democratic Republic of The</option>
                <option value="Cook Islands">Cook Islands</option>
                <option value="Costa Rica">Costa Rica</option>
                <option value="Cote D'ivoire">Cote D'ivoire</option>
                <option value="Croatia">Croatia</option>
                <option value="Cuba">Cuba</option>
                <option value="Cyprus">Cyprus</option>
                <option value="Czech Republic">Czech Republic</option>
                <option value="Denmark">Denmark</option>
                <option value="Djibouti">Djibouti</option>
                <option value="Dominica">Dominica</option>
                <option value="Dominican Republic">Dominican Republic</option>
                <option value="Ecuador">Ecuador</option>
                <option value="Egypt">Egypt</option>
                <option value="El Salvador">El Salvador</option>
                <option value="Equatorial Guinea">Equatorial Guinea</option>
                <option value="Eritrea">Eritrea</option>
                <option value="Estonia">Estonia</option>
                <option value="Ethiopia">Ethiopia</option>
                <option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
                <option value="Faroe Islands">Faroe Islands</option>
                <option value="Fiji">Fiji</option>
                <option value="Finland">Finland</option>
                <option value="France">France</option>
                <option value="French Guiana">French Guiana</option>
                <option value="French Polynesia">French Polynesia</option>
                <option value="French Southern Territories">French Southern Territories</option>
                <option value="Gabon">Gabon</option>
                <option value="Gambia">Gambia</option>
                <option value="Georgia">Georgia</option>
                <option value="Germany">Germany</option>
                <option value="Ghana">Ghana</option>
                <option value="Gibraltar">Gibraltar</option>
                <option value="Greece">Greece</option>
                <option value="Greenland">Greenland</option>
                <option value="Grenada">Grenada</option>
                <option value="Guadeloupe">Guadeloupe</option>
                <option value="Guam">Guam</option>
                <option value="Guatemala">Guatemala</option>
                <option value="Guernsey">Guernsey</option>
                <option value="Guinea">Guinea</option>
                <option value="Guinea-bissau">Guinea-bissau</option>
                <option value="Guyana">Guyana</option>
                <option value="Haiti">Haiti</option>
                <option value="Heard Island and Mcdonald Islands">Heard Island and Mcdonald Islands</option>
                <option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option>
                <option value="Honduras">Honduras</option>
                <option value="Hong Kong">Hong Kong</option>
                <option value="Hungary">Hungary</option>
                <option value="Iceland">Iceland</option>
                <option value="India">India</option>
                <option value="Indonesia">Indonesia</option>
                <option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option>
                <option value="Iraq">Iraq</option>
                <option value="Ireland">Ireland</option>
                <option value="Isle of Man">Isle of Man</option>
                <option value="Israel">Israel</option>
                <option value="Italy">Italy</option>
                <option value="Jamaica">Jamaica</option>
                <option value="Japan">Japan</option>
                <option value="Jersey">Jersey</option>
                <option value="Jordan">Jordan</option>
                <option value="Kazakhstan">Kazakhstan</option>
                <option value="Kenya">Kenya</option>
                <option value="Kiribati">Kiribati</option>
                <option value="Korea, Democratic People's Republic of">Korea, Democratic People's Republic of</option>
                <option value="Korea, Republic of">Korea, Republic of</option>
                <option value="Kuwait">Kuwait</option>
                <option value="Kyrgyzstan">Kyrgyzstan</option>
                <option value="Lao People's Democratic Republic">Lao People's Democratic Republic</option>
                <option value="Latvia">Latvia</option>
                <option value="Lebanon">Lebanon</option>
                <option value="Lesotho">Lesotho</option>
                <option value="Liberia">Liberia</option>
                <option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
                <option value="Liechtenstein">Liechtenstein</option>
                <option value="Lithuania">Lithuania</option>
                <option value="Luxembourg">Luxembourg</option>
                <option value="Macao">Macao</option>
                <option value="Macedonia, The Former Yugoslav Republic of">Macedonia, The Former Yugoslav Republic of</option>
                <option value="Madagascar">Madagascar</option>
                <option value="Malawi">Malawi</option>
                <option value="Malaysia">Malaysia</option>
                <option value="Maldives">Maldives</option>
                <option value="Mali">Mali</option>
                <option value="Malta">Malta</option>
                <option value="Marshall Islands">Marshall Islands</option>
                <option value="Martinique">Martinique</option>
                <option value="Mauritania">Mauritania</option>
                <option value="Mauritius">Mauritius</option>
                <option value="Mayotte">Mayotte</option>
                <option value="Mexico">Mexico</option>
                <option value="Micronesia, Federated States of">Micronesia, Federated States of</option>
                <option value="Moldova, Republic of">Moldova, Republic of</option>
                <option value="Monaco">Monaco</option>
                <option value="Mongolia">Mongolia</option>
                <option value="Montenegro">Montenegro</option>
                <option value="Montserrat">Montserrat</option>
                <option value="Morocco">Morocco</option>
                <option value="Mozambique">Mozambique</option>
                <option value="Myanmar">Myanmar</option>
                <option value="Namibia">Namibia</option>
                <option value="Nauru">Nauru</option>
                <option value="Nepal">Nepal</option>
                <option value="Netherlands">Netherlands</option>
                <option value="Netherlands Antilles">Netherlands Antilles</option>
                <option value="New Caledonia">New Caledonia</option>
                <option value="New Zealand">New Zealand</option>
                <option value="Nicaragua">Nicaragua</option>
                <option value="Niger">Niger</option>
                <option value="Nigeria">Nigeria</option>
                <option value="Niue">Niue</option>
                <option value="Norfolk Island">Norfolk Island</option>
                <option value="Northern Mariana Islands">Northern Mariana Islands</option>
                <option value="Norway">Norway</option>
                <option value="Oman">Oman</option>
                <option value="Pakistan">Pakistan</option>
                <option value="Palau">Palau</option>
                <option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied</option>
                <option value="Panama">Panama</option>
                <option value="Papua New Guinea">Papua New Guinea</option>
                <option value="Paraguay">Paraguay</option>
                <option value="Peru">Peru</option>
                <option value="Philippines">Philippines</option>
                <option value="Pitcairn">Pitcairn</option>
                <option value="Poland">Poland</option>
                <option value="Portugal">Portugal</option>
                <option value="Puerto Rico">Puerto Rico</option>
                <option value="Qatar">Qatar</option>
                <option value="Reunion">Reunion</option>
                <option value="Romania">Romania</option>
                <option value="Russian Federation">Russian Federation</option>
                <option value="Rwanda">Rwanda</option>
                <option value="Saint Helena">Saint Helena</option>
                <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                <option value="Saint Lucia">Saint Lucia</option>
                <option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
                <option value="Saint Vincent and The Grenadines">Saint Vincent and The Grenadines</option>
                <option value="Samoa">Samoa</option>
                <option value="San Marino">San Marino</option>
                <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                <option value="Saudi Arabia">Saudi Arabia</option>
                <option value="Senegal">Senegal</option>
                <option value="Serbia">Serbia</option>
                <option value="Seychelles">Seychelles</option>
                <option value="Sierra Leone">Sierra Leone</option>
                <option value="Singapore">Singapore</option>
                <option value="Slovakia">Slovakia</option>
                <option value="Slovenia">Slovenia</option>
                <option value="Solomon Islands">Solomon Islands</option>
                <option value="Somalia">Somalia</option>
                <option value="South Africa">South Africa</option>
                <option value="South Georgia and The South Sandwich Islands">South Georgia and The South Sandwich Islands</option>
                <option value="Spain">Spain</option>
                <option value="Sri Lanka">Sri Lanka</option>
                <option value="Sudan">Sudan</option>
                <option value="Suriname">Suriname</option>
                <option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
                <option value="Swaziland">Swaziland</option>
                <option value="Sweden">Sweden</option>
                <option value="Switzerland">Switzerland</option>
                <option value="Syrian Arab Republic">Syrian Arab Republic</option>
                <option value="Taiwan">Taiwan</option>
                <option value="Tajikistan">Tajikistan</option>
                <option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
                <option value="Thailand">Thailand</option>
                <option value="Timor-leste">Timor-leste</option>
                <option value="Togo">Togo</option>
                <option value="Tokelau">Tokelau</option>
                <option value="Tonga">Tonga</option>
                <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                <option value="Tunisia">Tunisia</option>
                <option value="Turkey">Turkey</option>
                <option value="Turkmenistan">Turkmenistan</option>
                <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
                <option value="Tuvalu">Tuvalu</option>
                <option value="Uganda">Uganda</option>
                <option value="Ukraine">Ukraine</option>
                <option value="United Arab Emirates">United Arab Emirates</option>
                <option value="United Kingdom">United Kingdom</option>
                <option value="United States">United States</option>
                <option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
                <option value="Uruguay">Uruguay</option>
                <option value="Uzbekistan">Uzbekistan</option>
                <option value="Vanuatu">Vanuatu</option>
                <option value="Venezuela">Venezuela</option>
                <option value="Viet Nam">Viet Nam</option>
                <option value="Virgin Islands, British">Virgin Islands, British</option>
                <option value="Virgin Islands, U.S.">Virgin Islands, U.S.</option>
                <option value="Wallis and Futuna">Wallis and Futuna</option>
                <option value="Western Sahara">Western Sahara</option>
                <option value="Yemen">Yemen</option>
                <option value="Zambia">Zambia</option>
                <option value="Zimbabwe">Zimbabwe</option>
            </select>
                        </div>

                        <div class="input-field">
                            <label>Currency<span class="red">*</span></label>
                            <select id="currencyList" name="currency" class="select2" required>
                                <option value="">Select</option>
    <option value="USD" label="US dollar">USD</option>
    <option value="EUR" label="Euro">EUR</option>
    <option value="JPY" label="Japanese yen">JPY</option>
    <option value="GBP" label="Pound sterling">GBP</option>
    
    <option value="AED" label="United Arab Emirates dirham">AED</option>
    <option value="AFN" label="Afghan afghani">AFN</option>
    <option value="ALL" label="Albanian lek">ALL</option>
    <option value="AMD" label="Armenian dram">AMD</option>
    <option value="ANG" label="Netherlands Antillean guilder">ANG</option>
    <option value="AOA" label="Angolan kwanza">AOA</option>
    <option value="ARS" label="Argentine peso">ARS</option>
    <option value="AUD" label="Australian dollar">AUD</option>
    <option value="AWG" label="Aruban florin">AWG</option>
    <option value="AZN" label="Azerbaijani manat">AZN</option>
    <option value="BAM" label="Bosnia and Herzegovina convertible mark">BAM</option>
    <option value="BBD" label="Barbadian dollar">BBD</option>
    <option value="BDT" label="Bangladeshi taka">BDT</option>
    <option value="BGN" label="Bulgarian lev">BGN</option>
    <option value="BHD" label="Bahraini dinar">BHD</option>
    <option value="BIF" label="Burundian franc">BIF</option>
    <option value="BMD" label="Bermudian dollar">BMD</option>
    <option value="BND" label="Brunei dollar">BND</option>
    <option value="BOB" label="Bolivian boliviano">BOB</option>
    <option value="BRL" label="Brazilian real">BRL</option>
    <option value="BSD" label="Bahamian dollar">BSD</option>
    <option value="BTN" label="Bhutanese ngultrum">BTN</option>
    <option value="BWP" label="Botswana pula">BWP</option>
    <option value="BYN" label="Belarusian ruble">BYN</option>
    <option value="BZD" label="Belize dollar">BZD</option>
    <option value="CAD" label="Canadian dollar">CAD</option>
    <option value="CDF" label="Congolese franc">CDF</option>
    <option value="CHF" label="Swiss franc">CHF</option>
    <option value="CLP" label="Chilean peso">CLP</option>
    <option value="CNY" label="Chinese yuan">CNY</option>
    <option value="COP" label="Colombian peso">COP</option>
    <option value="CRC" label="Costa Rican colón">CRC</option>
    <option value="CUC" label="Cuban convertible peso">CUC</option>
    <option value="CUP" label="Cuban peso">CUP</option>
    <option value="CVE" label="Cape Verdean escudo">CVE</option>
    <option value="CZK" label="Czech koruna">CZK</option>
    <option value="DJF" label="Djiboutian franc">DJF</option>
    <option value="DKK" label="Danish krone">DKK</option>
    <option value="DOP" label="Dominican peso">DOP</option>
    <option value="DZD" label="Algerian dinar">DZD</option>
    <option value="EGP" label="Egyptian pound">EGP</option>
    <option value="ERN" label="Eritrean nakfa">ERN</option>
    <option value="ETB" label="Ethiopian birr">ETB</option>
    <option value="EUR" label="EURO">EUR</option>
    <option value="FJD" label="Fijian dollar">FJD</option>
    <option value="FKP" label="Falkland Islands pound">FKP</option>
    <option value="GBP" label="British pound">GBP</option>
    <option value="GEL" label="Georgian lari">GEL</option>
    <option value="GGP" label="Guernsey pound">GGP</option>
    <option value="GHS" label="Ghanaian cedi">GHS</option>
    <option value="GIP" label="Gibraltar pound">GIP</option>
    <option value="GMD" label="Gambian dalasi">GMD</option>
    <option value="GNF" label="Guinean franc">GNF</option>
    <option value="GTQ" label="Guatemalan quetzal">GTQ</option>
    <option value="GYD" label="Guyanese dollar">GYD</option>
    <option value="HKD" label="Hong Kong dollar">HKD</option>
    <option value="HNL" label="Honduran lempira">HNL</option>
    <option value="HRK" label="Croatian kuna">HRK</option>
    <option value="HTG" label="Haitian gourde">HTG</option>
    <option value="HUF" label="Hungarian forint">HUF</option>
    <option value="IDR" label="Indonesian rupiah">IDR</option>
    <option value="ILS" label="Israeli new shekel">ILS</option>
    <option value="IMP" label="Manx pound">IMP</option>
    <option value="INR" label="Indian rupee">INR</option>
    <option value="IQD" label="Iraqi dinar">IQD</option>
    <option value="IRR" label="Iranian rial">IRR</option>
    <option value="ISK" label="Icelandic króna">ISK</option>
    <option value="JEP" label="Jersey pound">JEP</option>
    <option value="JMD" label="Jamaican dollar">JMD</option>
    <option value="JOD" label="Jordanian dinar">JOD</option>
    <option value="JPY" label="Japanese yen">JPY</option>
    <option value="KES" label="Kenyan shilling">KES</option>
    <option value="KGS" label="Kyrgyzstani som">KGS</option>
    <option value="KHR" label="Cambodian riel">KHR</option>
    <option value="KID" label="Kiribati dollar">KID</option>
    <option value="KMF" label="Comorian franc">KMF</option>
    <option value="KPW" label="North Korean won">KPW</option>
    <option value="KRW" label="South Korean won">KRW</option>
    <option value="KWD" label="Kuwaiti dinar">KWD</option>
    <option value="KYD" label="Cayman Islands dollar">KYD</option>
    <option value="KZT" label="Kazakhstani tenge">KZT</option>
    <option value="LAK" label="Lao kip">LAK</option>
    <option value="LBP" label="Lebanese pound">LBP</option>
    <option value="LKR" label="Sri Lankan rupee">LKR</option>
    <option value="LRD" label="Liberian dollar">LRD</option>
    <option value="LSL" label="Lesotho loti">LSL</option>
    <option value="LYD" label="Libyan dinar">LYD</option>
    <option value="MAD" label="Moroccan dirham">MAD</option>
    <option value="MDL" label="Moldovan leu">MDL</option>
    <option value="MGA" label="Malagasy ariary">MGA</option>
    <option value="MKD" label="Macedonian denar">MKD</option>
    <option value="MMK" label="Burmese kyat">MMK</option>
    <option value="MNT" label="Mongolian tögrög">MNT</option>
    <option value="MOP" label="Macanese pataca">MOP</option>
    <option value="MRU" label="Mauritanian ouguiya">MRU</option>
    <option value="MUR" label="Mauritian rupee">MUR</option>
    <option value="MVR" label="Maldivian rufiyaa">MVR</option>
    <option value="MWK" label="Malawian kwacha">MWK</option>
    <option value="MXN" label="Mexican peso">MXN</option>
    <option value="MYR" label="Malaysian ringgit">MYR</option>
    <option value="MZN" label="Mozambican metical">MZN</option>
    <option value="NAD" label="Namibian dollar">NAD</option>
    <option value="NGN" label="Nigerian naira">NGN</option>
    <option value="NIO" label="Nicaraguan córdoba">NIO</option>
    <option value="NOK" label="Norwegian krone">NOK</option>
    <option value="NPR" label="Nepalese rupee">NPR</option>
    <option value="NZD" label="New Zealand dollar">NZD</option>
    <option value="OMR" label="Omani rial">OMR</option>
    <option value="PAB" label="Panamanian balboa">PAB</option>
    <option value="PEN" label="Peruvian sol">PEN</option>
    <option value="PGK" label="Papua New Guinean kina">PGK</option>
    <option value="PHP" label="Philippine peso">PHP</option>
    <option value="PKR" label="Pakistani rupee">PKR</option>
    <option value="PLN" label="Polish złoty">PLN</option>
    <option value="PRB" label="Transnistrian ruble">PRB</option>
    <option value="PYG" label="Paraguayan guaraní">PYG</option>
    <option value="QAR" label="Qatari riyal">QAR</option>
    <option value="RON" label="Romanian leu">RON</option>
    <option value="RSD" label="Serbian dinar">RSD</option>
    <option value="RUB" label="Russian ruble">RUB</option>
    <option value="RWF" label="Rwandan franc">RWF</option>
    <option value="SAR" label="Saudi riyal">SAR</option>
    <option value="SEK" label="Swedish krona">SEK</option>
    <option value="SGD" label="Singapore dollar">SGD</option>
    <option value="SHP" label="Saint Helena pound">SHP</option>
    <option value="SLL" label="Sierra Leonean leone">SLL</option>
    <option value="SLS" label="Somaliland shilling">SLS</option>
    <option value="SOS" label="Somali shilling">SOS</option>
    <option value="SRD" label="Surinamese dollar">SRD</option>
    <option value="SSP" label="South Sudanese pound">SSP</option>
    <option value="STN" label="São Tomé and Príncipe dobra">STN</option>
    <option value="SYP" label="Syrian pound">SYP</option>
    <option value="SZL" label="Swazi lilangeni">SZL</option>
    <option value="THB" label="Thai baht">THB</option>
    <option value="TJS" label="Tajikistani somoni">TJS</option>
    <option value="TMT" label="Turkmenistan manat">TMT</option>
    <option value="TND" label="Tunisian dinar">TND</option>
    <option value="TOP" label="Tongan paʻanga">TOP</option>
    <option value="TRY" label="Turkish lira">TRY</option>
    <option value="TTD" label="Trinidad and Tobago dollar">TTD</option>
    <option value="TVD" label="Tuvaluan dollar">TVD</option>
    <option value="TWD" label="New Taiwan dollar">TWD</option>
    <option value="TZS" label="Tanzanian shilling">TZS</option>
    <option value="UAH" label="Ukrainian hryvnia">UAH</option>
    <option value="UGX" label="Ugandan shilling">UGX</option>
    <option value="USD" label="United States dollar">USD</option>
    <option value="UYU" label="Uruguayan peso">UYU</option>
    <option value="UZS" label="Uzbekistani soʻm">UZS</option>
    <option value="VES" label="Venezuelan bolívar soberano">VES</option>
    <option value="VND" label="Vietnamese đồng">VND</option>
    <option value="VUV" label="Vanuatu vatu">VUV</option>
    <option value="WST" label="Samoan tālā">WST</option>
    <option value="XAF" label="Central African CFA franc">XAF</option>
    <option value="XCD" label="Eastern Caribbean dollar">XCD</option>
    <option value="XOF" label="West African CFA franc">XOF</option>
    <option value="XPF" label="CFP franc">XPF</option>
    <option value="ZAR" label="South African rand">ZAR</option>
    <option value="ZMW" label="Zambian kwacha">ZMW</option>
    <option value="ZWB" label="Zimbabwean bonds">ZWB</option>
</select>
                        </div>

                        <div class="input-field">
                            <label>Bank Account/IBAN<span class="red">*</span></label>
                            <input type="text" placeholder="Enter Bank Account/IBAN" required="" name="bank_account">
                        </div>

                        <div class="input-field">
                            <label>Select a National bank code type<span class="red">*</span></label>
                            <select required="" class="select2" name="bank_code">
                                <option value="">Select</option>
                                <option>ABA/Routing Number</option>
                                <option>Bank Code</option>
                                <option>BSB</option>
                                <option>IFSC</option>
                                <option>Other Number</option>
                                <option>Sort Code</option>
                                <option>Transit Number & Institution Number</option>
                            </select>
                        </div>

                        <div class="input-field">
                            <label>Code Number<span class="red">*</span></label>
                            <input type="text" placeholder="Enter Code Number" required="" name="code_no">
                        </div>
                        
                        <div class="input-field">
                            <label>Beneficiary Country<span class="red">*</span></label>
                            <select id="country" name="beneficiary_country" class="form-control select2" required="">
                            <option value="">Select</option>
                <option value="Afghanistan">Afghanistan</option>
                <option value="Åland Islands">Åland Islands</option>
                <option value="Albania">Albania</option>
                <option value="Algeria">Algeria</option>
                <option value="American Samoa">American Samoa</option>
                <option value="Andorra">Andorra</option>
                <option value="Angola">Angola</option>
                <option value="Anguilla">Anguilla</option>
                <option value="Antarctica">Antarctica</option>
                <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                <option value="Argentina">Argentina</option>
                <option value="Armenia">Armenia</option>
                <option value="Aruba">Aruba</option>
                <option value="Australia">Australia</option>
                <option value="Austria">Austria</option>
                <option value="Azerbaijan">Azerbaijan</option>
                <option value="Bahamas">Bahamas</option>
                <option value="Bahrain">Bahrain</option>
                <option value="Bangladesh">Bangladesh</option>
                <option value="Barbados">Barbados</option>
                <option value="Belarus">Belarus</option>
                <option value="Belgium">Belgium</option>
                <option value="Belize">Belize</option>
                <option value="Benin">Benin</option>
                <option value="Bermuda">Bermuda</option>
                <option value="Bhutan">Bhutan</option>
                <option value="Bolivia">Bolivia</option>
                <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                <option value="Botswana">Botswana</option>
                <option value="Bouvet Island">Bouvet Island</option>
                <option value="Brazil">Brazil</option>
                <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
                <option value="Brunei Darussalam">Brunei Darussalam</option>
                <option value="Bulgaria">Bulgaria</option>
                <option value="Burkina Faso">Burkina Faso</option>
                <option value="Burundi">Burundi</option>
                <option value="Cambodia">Cambodia</option>
                <option value="Cameroon">Cameroon</option>
                <option value="Canada">Canada</option>
                <option value="Cape Verde">Cape Verde</option>
                <option value="Cayman Islands">Cayman Islands</option>
                <option value="Central African Republic">Central African Republic</option>
                <option value="Chad">Chad</option>
                <option value="Chile">Chile</option>
                <option value="China">China</option>
                <option value="Christmas Island">Christmas Island</option>
                <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
                <option value="Colombia">Colombia</option>
                <option value="Comoros">Comoros</option>
                <option value="Congo">Congo</option>
                <option value="Congo, The Democratic Republic of The">Congo, The Democratic Republic of The</option>
                <option value="Cook Islands">Cook Islands</option>
                <option value="Costa Rica">Costa Rica</option>
                <option value="Cote D'ivoire">Cote D'ivoire</option>
                <option value="Croatia">Croatia</option>
                <option value="Cuba">Cuba</option>
                <option value="Cyprus">Cyprus</option>
                <option value="Czech Republic">Czech Republic</option>
                <option value="Denmark">Denmark</option>
                <option value="Djibouti">Djibouti</option>
                <option value="Dominica">Dominica</option>
                <option value="Dominican Republic">Dominican Republic</option>
                <option value="Ecuador">Ecuador</option>
                <option value="Egypt">Egypt</option>
                <option value="El Salvador">El Salvador</option>
                <option value="Equatorial Guinea">Equatorial Guinea</option>
                <option value="Eritrea">Eritrea</option>
                <option value="Estonia">Estonia</option>
                <option value="Ethiopia">Ethiopia</option>
                <option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
                <option value="Faroe Islands">Faroe Islands</option>
                <option value="Fiji">Fiji</option>
                <option value="Finland">Finland</option>
                <option value="France">France</option>
                <option value="French Guiana">French Guiana</option>
                <option value="French Polynesia">French Polynesia</option>
                <option value="French Southern Territories">French Southern Territories</option>
                <option value="Gabon">Gabon</option>
                <option value="Gambia">Gambia</option>
                <option value="Georgia">Georgia</option>
                <option value="Germany">Germany</option>
                <option value="Ghana">Ghana</option>
                <option value="Gibraltar">Gibraltar</option>
                <option value="Greece">Greece</option>
                <option value="Greenland">Greenland</option>
                <option value="Grenada">Grenada</option>
                <option value="Guadeloupe">Guadeloupe</option>
                <option value="Guam">Guam</option>
                <option value="Guatemala">Guatemala</option>
                <option value="Guernsey">Guernsey</option>
                <option value="Guinea">Guinea</option>
                <option value="Guinea-bissau">Guinea-bissau</option>
                <option value="Guyana">Guyana</option>
                <option value="Haiti">Haiti</option>
                <option value="Heard Island and Mcdonald Islands">Heard Island and Mcdonald Islands</option>
                <option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option>
                <option value="Honduras">Honduras</option>
                <option value="Hong Kong">Hong Kong</option>
                <option value="Hungary">Hungary</option>
                <option value="Iceland">Iceland</option>
                <option value="India">India</option>
                <option value="Indonesia">Indonesia</option>
                <option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option>
                <option value="Iraq">Iraq</option>
                <option value="Ireland">Ireland</option>
                <option value="Isle of Man">Isle of Man</option>
                <option value="Israel">Israel</option>
                <option value="Italy">Italy</option>
                <option value="Jamaica">Jamaica</option>
                <option value="Japan">Japan</option>
                <option value="Jersey">Jersey</option>
                <option value="Jordan">Jordan</option>
                <option value="Kazakhstan">Kazakhstan</option>
                <option value="Kenya">Kenya</option>
                <option value="Kiribati">Kiribati</option>
                <option value="Korea, Democratic People's Republic of">Korea, Democratic People's Republic of</option>
                <option value="Korea, Republic of">Korea, Republic of</option>
                <option value="Kuwait">Kuwait</option>
                <option value="Kyrgyzstan">Kyrgyzstan</option>
                <option value="Lao People's Democratic Republic">Lao People's Democratic Republic</option>
                <option value="Latvia">Latvia</option>
                <option value="Lebanon">Lebanon</option>
                <option value="Lesotho">Lesotho</option>
                <option value="Liberia">Liberia</option>
                <option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
                <option value="Liechtenstein">Liechtenstein</option>
                <option value="Lithuania">Lithuania</option>
                <option value="Luxembourg">Luxembourg</option>
                <option value="Macao">Macao</option>
                <option value="Macedonia, The Former Yugoslav Republic of">Macedonia, The Former Yugoslav Republic of</option>
                <option value="Madagascar">Madagascar</option>
                <option value="Malawi">Malawi</option>
                <option value="Malaysia">Malaysia</option>
                <option value="Maldives">Maldives</option>
                <option value="Mali">Mali</option>
                <option value="Malta">Malta</option>
                <option value="Marshall Islands">Marshall Islands</option>
                <option value="Martinique">Martinique</option>
                <option value="Mauritania">Mauritania</option>
                <option value="Mauritius">Mauritius</option>
                <option value="Mayotte">Mayotte</option>
                <option value="Mexico">Mexico</option>
                <option value="Micronesia, Federated States of">Micronesia, Federated States of</option>
                <option value="Moldova, Republic of">Moldova, Republic of</option>
                <option value="Monaco">Monaco</option>
                <option value="Mongolia">Mongolia</option>
                <option value="Montenegro">Montenegro</option>
                <option value="Montserrat">Montserrat</option>
                <option value="Morocco">Morocco</option>
                <option value="Mozambique">Mozambique</option>
                <option value="Myanmar">Myanmar</option>
                <option value="Namibia">Namibia</option>
                <option value="Nauru">Nauru</option>
                <option value="Nepal">Nepal</option>
                <option value="Netherlands">Netherlands</option>
                <option value="Netherlands Antilles">Netherlands Antilles</option>
                <option value="New Caledonia">New Caledonia</option>
                <option value="New Zealand">New Zealand</option>
                <option value="Nicaragua">Nicaragua</option>
                <option value="Niger">Niger</option>
                <option value="Nigeria">Nigeria</option>
                <option value="Niue">Niue</option>
                <option value="Norfolk Island">Norfolk Island</option>
                <option value="Northern Mariana Islands">Northern Mariana Islands</option>
                <option value="Norway">Norway</option>
                <option value="Oman">Oman</option>
                <option value="Pakistan">Pakistan</option>
                <option value="Palau">Palau</option>
                <option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied</option>
                <option value="Panama">Panama</option>
                <option value="Papua New Guinea">Papua New Guinea</option>
                <option value="Paraguay">Paraguay</option>
                <option value="Peru">Peru</option>
                <option value="Philippines">Philippines</option>
                <option value="Pitcairn">Pitcairn</option>
                <option value="Poland">Poland</option>
                <option value="Portugal">Portugal</option>
                <option value="Puerto Rico">Puerto Rico</option>
                <option value="Qatar">Qatar</option>
                <option value="Reunion">Reunion</option>
                <option value="Romania">Romania</option>
                <option value="Russian Federation">Russian Federation</option>
                <option value="Rwanda">Rwanda</option>
                <option value="Saint Helena">Saint Helena</option>
                <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                <option value="Saint Lucia">Saint Lucia</option>
                <option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
                <option value="Saint Vincent and The Grenadines">Saint Vincent and The Grenadines</option>
                <option value="Samoa">Samoa</option>
                <option value="San Marino">San Marino</option>
                <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                <option value="Saudi Arabia">Saudi Arabia</option>
                <option value="Senegal">Senegal</option>
                <option value="Serbia">Serbia</option>
                <option value="Seychelles">Seychelles</option>
                <option value="Sierra Leone">Sierra Leone</option>
                <option value="Singapore">Singapore</option>
                <option value="Slovakia">Slovakia</option>
                <option value="Slovenia">Slovenia</option>
                <option value="Solomon Islands">Solomon Islands</option>
                <option value="Somalia">Somalia</option>
                <option value="South Africa">South Africa</option>
                <option value="South Georgia and The South Sandwich Islands">South Georgia and The South Sandwich Islands</option>
                <option value="Spain">Spain</option>
                <option value="Sri Lanka">Sri Lanka</option>
                <option value="Sudan">Sudan</option>
                <option value="Suriname">Suriname</option>
                <option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
                <option value="Swaziland">Swaziland</option>
                <option value="Sweden">Sweden</option>
                <option value="Switzerland">Switzerland</option>
                <option value="Syrian Arab Republic">Syrian Arab Republic</option>
                <option value="Taiwan">Taiwan</option>
                <option value="Tajikistan">Tajikistan</option>
                <option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
                <option value="Thailand">Thailand</option>
                <option value="Timor-leste">Timor-leste</option>
                <option value="Togo">Togo</option>
                <option value="Tokelau">Tokelau</option>
                <option value="Tonga">Tonga</option>
                <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                <option value="Tunisia">Tunisia</option>
                <option value="Turkey">Turkey</option>
                <option value="Turkmenistan">Turkmenistan</option>
                <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
                <option value="Tuvalu">Tuvalu</option>
                <option value="Uganda">Uganda</option>
                <option value="Ukraine">Ukraine</option>
                <option value="United Arab Emirates">United Arab Emirates</option>
                <option value="United Kingdom">United Kingdom</option>
                <option value="United States">United States</option>
                <option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
                <option value="Uruguay">Uruguay</option>
                <option value="Uzbekistan">Uzbekistan</option>
                <option value="Vanuatu">Vanuatu</option>
                <option value="Venezuela">Venezuela</option>
                <option value="Viet Nam">Viet Nam</option>
                <option value="Virgin Islands, British">Virgin Islands, British</option>
                <option value="Virgin Islands, U.S.">Virgin Islands, U.S.</option>
                <option value="Wallis and Futuna">Wallis and Futuna</option>
                <option value="Western Sahara">Western Sahara</option>
                <option value="Yemen">Yemen</option>
                <option value="Zambia">Zambia</option>
                <option value="Zimbabwe">Zimbabwe</option>
            </select>
                        </div>

                        <div class="input-field">
                            <label>Beneficiary City<span class="red">*</span></label>
                            <input type="text" placeholder="Beneficiary City" required="" name="beneficiary_city">
                        </div>

                        <div class="input-field">
                            <label>Beneficiary Address<span class="red">*</span></label>
                            <input type="text" required="" placeholder="Enter your Address" name="beneficiary_address" />
                        </div>

                        <div class="input-field">
                        </div>
                    </div>
                    
                </div>
            </div>

            <div class="row text-center">
            <button class="nextBtn">
                        <span class="btnText">Save Beneficiary</span>
                        <i class="uil uil-navigator"></i>
                    </button>
</div>
        </form> 




        
        <!-- <h2 style="padding-top:30px; padding-bottom:20px;">Beneficiary Details</h2> -->
        <div class="row" style="display:none;">
            <div class="col-sm-12">

            <div class="panel panel-default">
                        <!-- Default panel contents -->
                        <div class="panel-heading">Beneficiary Add</div>
                        <div class="panel-body text-center">
                        <form action="#" method="POST">   
                        <select id="options" class="form-control" name="purpose" required="">
                            <option value="">Beneficiary Type</option>
                            <option value="1">Corporate</option>
                            <option value="2">Individual</option>
                        </select>
                        <br>
                        <input type="text" name="amount" placeholder="Beneficiary Name" class="form-control" required="">
                        <br>

                       
                        <input type="text" name="amount" placeholder="Bank Name" class="form-control" required="">
                   

                        <br>
                        <input type="text" name="amount" placeholder="Account Number" class="form-control" required="">
                        

                        <br>
                        <input type="text" name="amount" placeholder="Account Type" class="form-control" required="">
                       
                        <br>
                        <input type="text" name="amount" placeholder="BIC Code" class="form-control" required="">
                      

                        <br>
                        <input type="text" name="amount" placeholder="Email" class="form-control" required="">
                       

                        <br>
                        <input type="text" name="amount" placeholder="Phone/Mobile" class="form-control" required="">
                       

                        <br>
                        <input type="text" name="amount" placeholder="Country" class="form-control" required="">
                       
                        <br>
                        <input type="text" name="amount" placeholder="City" class="form-control" required="">
                        
                        <br>
                        <textarea type="text" name="amount" placeholder="Address" class="form-control" required=""></textarea>
                        <br>


                        <input type="submit" class="btn btn-primary" value="Create">
                        </form>
                        </div>

                    </div>

            </div>
            <!--div class="col-sm-8">

            <div class="panel panel-default">
              
                <div class="panel-heading">Beneficiary List</div>
                <div class="panel-body text-center table-responsive">
                    <table class="table" id="table_id">
                        <thead>
                            <tr>
                                <td>Sr.</td>
                                <td>Type</td>
                                <td>Name</td>
                                <td>Bank Name</td>
                                <td>Account No.</td>
                                <td>Account Type</td>
                                <td>Bic Code</td>
                                <td>Email</td>
                                <td>Phone</td>
                                <td>Country/City</td>
                                <td>Address</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            </div-->
        </div>

    
    </div>
   

    <script src="{{ asset('js/jquery1.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/typed.min.js') }}"></script>
    <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.0/clipboard.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script>
    <script>
          $(document).ready(function () {

                $('#table_id').DataTable();
                $(".select2").select2();
        });
    </script>

    
@endsection
