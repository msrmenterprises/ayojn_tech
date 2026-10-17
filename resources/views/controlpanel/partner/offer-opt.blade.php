@extends('adminlte::page')
<!--Add title-->
@section('title',  'Ayojn')
<!--Main Body content-->
@section('content')
    <!-- //banner-top -->
    <!-- banner -->
    <!-- Content Header (Page header) -->

    <style>

.star-rating form {
    /* display: none; */
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
 height:400px; 
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

    <section class="content-header">
        <h1>
            Offer
            <small>List</small>

        </h1>


        <ol class="breadcrumb">


            <li><a href="{{ url('admin/dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Offer List</li>

        </ol>
    </section>
    <style type="text/css">
        .mb20 {
            margin-bottom: 20px;
        }

        .checkbox {
            margin-top: 0px;
        }
    </style>
    <!-- Main content -->
    <section class="content">

    <div class="box">
        <div class="box box-body">
        

    <div class="row" style="margin-bottom:15px;">
               
                    <div class="col-md-2">
                        <select class="form-control" name="f">
                            <option value="">Offer For</option>
                            <option value="Clients">Clients
                            </option>
                            <option value="Services">Services
                            </option>
                            <option value="Both">Both
                            </option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-control" name="c" id="c">
                            <option value="">Country</option>
                                                                                                <option value="AF">Afghanistan</option>
                                                                    <option value="AL">Albania</option>
                                                                    <option value="DZ">Algeria</option>
                                                                    <option value="DS">American Samoa</option>
                                                                    <option value="AD">Andorra</option>
                                                                    <option value="AO">Angola</option>
                                                                    <option value="AI">Anguilla</option>
                                                                    <option value="AQ">Antarctica</option>
                                                                    <option value="AG">Antigua and Barbuda</option>
                                                                    <option value="AR">Argentina</option>
                                                                    <option value="AM">Armenia</option>
                                                                    <option value="AW">Aruba</option>
                                                                    <option value="AU">Australia</option>
                                                                    <option value="AT">Austria</option>
                                                                    <option value="AZ">Azerbaijan</option>
                                                                    <option value="BS">Bahamas</option>
                                                                    <option value="BH">Bahrain</option>
                                                                    <option value="BD">Bangladesh</option>
                                                                    <option value="BB">Barbados</option>
                                                                    <option value="BY">Belarus</option>
                                                                    <option value="BE">Belgium</option>
                                                                    <option value="BZ">Belize</option>
                                                                    <option value="BJ">Benin</option>
                                                                    <option value="BM">Bermuda</option>
                                                                    <option value="BT">Bhutan</option>
                                                                    <option value="BO">Bolivia</option>
                                                                    <option value="BA">Bosnia and Herzegovina</option>
                                                                    <option value="BW">Botswana</option>
                                                                    <option value="BV">Bouvet Island</option>
                                                                    <option value="BR">Brazil</option>
                                                                    <option value="IO">British Indian Ocean Territory</option>
                                                                    <option value="BN">Brunei Darussalam</option>
                                                                    <option value="BG">Bulgaria</option>
                                                                    <option value="BF">Burkina Faso</option>
                                                                    <option value="BI">Burundi</option>
                                                                    <option value="KH">Cambodia</option>
                                                                    <option value="CM">Cameroon</option>
                                                                    <option value="CA">Canada</option>
                                                                    <option value="CV">Cape Verde</option>
                                                                    <option value="KY">Cayman Islands</option>
                                                                    <option value="CF">Central African Republic</option>
                                                                    <option value="TD">Chad</option>
                                                                    <option value="CL">Chile</option>
                                                                    <option value="CN">China</option>
                                                                    <option value="CX">Christmas Island</option>
                                                                    <option value="CC">Cocos (Keeling) Islands</option>
                                                                    <option value="CO">Colombia</option>
                                                                    <option value="KM">Comoros</option>
                                                                    <option value="CG">Congo</option>
                                                                    <option value="CK">Cook Islands</option>
                                                                    <option value="CR">Costa Rica</option>
                                                                    <option value="HR">Croatia (Hrvatska)</option>
                                                                    <option value="CU">Cuba</option>
                                                                    <option value="CY">Cyprus</option>
                                                                    <option value="CZ">Czech Republic</option>
                                                                    <option value="DK">Denmark</option>
                                                                    <option value="DJ">Djibouti</option>
                                                                    <option value="DM">Dominica</option>
                                                                    <option value="DO">Dominican Republic</option>
                                                                    <option value="TP">East Timor</option>
                                                                    <option value="EC">Ecuador</option>
                                                                    <option value="EG">Egypt</option>
                                                                    <option value="SV">El Salvador</option>
                                                                    <option value="GQ">Equatorial Guinea</option>
                                                                    <option value="ER">Eritrea</option>
                                                                    <option value="EE">Estonia</option>
                                                                    <option value="ET">Ethiopia</option>
                                                                    <option value="FK">Falkland Islands (Malvinas)</option>
                                                                    <option value="FO">Faroe Islands</option>
                                                                    <option value="FJ">Fiji</option>
                                                                    <option value="FI">Finland</option>
                                                                    <option value="FR">France</option>
                                                                    <option value="FX">France, Metropolitan</option>
                                                                    <option value="GF">French Guiana</option>
                                                                    <option value="PF">French Polynesia</option>
                                                                    <option value="TF">French Southern Territories</option>
                                                                    <option value="GA">Gabon</option>
                                                                    <option value="GM">Gambia</option>
                                                                    <option value="GE">Georgia</option>
                                                                    <option value="DE">Germany</option>
                                                                    <option value="GH">Ghana</option>
                                                                    <option value="GI">Gibraltar</option>
                                                                    <option value="GK">Guernsey</option>
                                                                    <option value="GR">Greece</option>
                                                                    <option value="GL">Greenland</option>
                                                                    <option value="GD">Grenada</option>
                                                                    <option value="GP">Guadeloupe</option>
                                                                    <option value="GU">Guam</option>
                                                                    <option value="GT">Guatemala</option>
                                                                    <option value="GN">Guinea</option>
                                                                    <option value="GW">Guinea-Bissau</option>
                                                                    <option value="GY">Guyana</option>
                                                                    <option value="HT">Haiti</option>
                                                                    <option value="HM">Heard and Mc Donald Islands</option>
                                                                    <option value="HN">Honduras</option>
                                                                    <option value="HK">Hong Kong</option>
                                                                    <option value="HU">Hungary</option>
                                                                    <option value="IS">Iceland</option>
                                                                    <option value="IN">India</option>
                                                                    <option value="IM">Isle of Man</option>
                                                                    <option value="ID">Indonesia</option>
                                                                    <option value="IR">Iran (Islamic Republic of)</option>
                                                                    <option value="IQ">Iraq</option>
                                                                    <option value="IE">Ireland</option>
                                                                    <option value="IL">Israel</option>
                                                                    <option value="IT">Italy</option>
                                                                    <option value="CI">Ivory Coast</option>
                                                                    <option value="JE">Jersey</option>
                                                                    <option value="JM">Jamaica</option>
                                                                    <option value="JP">Japan</option>
                                                                    <option value="JO">Jordan</option>
                                                                    <option value="KZ">Kazakhstan</option>
                                                                    <option value="KE">Kenya</option>
                                                                    <option value="KI">Kiribati</option>
                                                                    <option value="KP">Korea, Democratic People's Republic of</option>
                                                                    <option value="KR">Korea, Republic of</option>
                                                                    <option value="XK">Kosovo</option>
                                                                    <option value="KW">Kuwait</option>
                                                                    <option value="KG">Kyrgyzstan</option>
                                                                    <option value="LA">Lao People's Democratic Republic</option>
                                                                    <option value="LV">Latvia</option>
                                                                    <option value="LB">Lebanon</option>
                                                                    <option value="LS">Lesotho</option>
                                                                    <option value="LR">Liberia</option>
                                                                    <option value="LY">Libyan Arab Jamahiriya</option>
                                                                    <option value="LI">Liechtenstein</option>
                                                                    <option value="LT">Lithuania</option>
                                                                    <option value="LU">Luxembourg</option>
                                                                    <option value="MO">Macau</option>
                                                                    <option value="MK">Macedonia</option>
                                                                    <option value="MG">Madagascar</option>
                                                                    <option value="MW">Malawi</option>
                                                                    <option value="MY">Malaysia</option>
                                                                    <option value="MV">Maldives</option>
                                                                    <option value="ML">Mali</option>
                                                                    <option value="MT">Malta</option>
                                                                    <option value="MH">Marshall Islands</option>
                                                                    <option value="MQ">Martinique</option>
                                                                    <option value="MR">Mauritania</option>
                                                                    <option value="MU">Mauritius</option>
                                                                    <option value="TY">Mayotte</option>
                                                                    <option value="MX">Mexico</option>
                                                                    <option value="FM">Micronesia, Federated States of</option>
                                                                    <option value="MD">Moldova, Republic of</option>
                                                                    <option value="MC">Monaco</option>
                                                                    <option value="MN">Mongolia</option>
                                                                    <option value="ME">Montenegro</option>
                                                                    <option value="MS">Montserrat</option>
                                                                    <option value="MA">Morocco</option>
                                                                    <option value="MZ">Mozambique</option>
                                                                    <option value="MM">Myanmar</option>
                                                                    <option value="NA">Namibia</option>
                                                                    <option value="NR">Nauru</option>
                                                                    <option value="NP">Nepal</option>
                                                                    <option value="NL">Netherlands</option>
                                                                    <option value="AN">Netherlands Antilles</option>
                                                                    <option value="NC">New Caledonia</option>
                                                                    <option value="NZ">New Zealand</option>
                                                                    <option value="NI">Nicaragua</option>
                                                                    <option value="NE">Niger</option>
                                                                    <option value="NG">Nigeria</option>
                                                                    <option value="NU">Niue</option>
                                                                    <option value="NF">Norfolk Island</option>
                                                                    <option value="MP">Northern Mariana Islands</option>
                                                                    <option value="NO">Norway</option>
                                                                    <option value="OM">Oman</option>
                                                                    <option value="PK">Pakistan</option>
                                                                    <option value="PW">Palau</option>
                                                                    <option value="PS">Palestine</option>
                                                                    <option value="PA">Panama</option>
                                                                    <option value="PG">Papua New Guinea</option>
                                                                    <option value="PY">Paraguay</option>
                                                                    <option value="PE">Peru</option>
                                                                    <option value="PH">Philippines</option>
                                                                    <option value="PN">Pitcairn</option>
                                                                    <option value="PL">Poland</option>
                                                                    <option value="PT">Portugal</option>
                                                                    <option value="PR">Puerto Rico</option>
                                                                    <option value="QA">Qatar</option>
                                                                    <option value="RE">Reunion</option>
                                                                    <option value="RO">Romania</option>
                                                                    <option value="RU">Russian Federation</option>
                                                                    <option value="RW">Rwanda</option>
                                                                    <option value="KN">Saint Kitts and Nevis</option>
                                                                    <option value="LC">Saint Lucia</option>
                                                                    <option value="VC">Saint Vincent and the Grenadines</option>
                                                                    <option value="WS">Samoa</option>
                                                                    <option value="SM">San Marino</option>
                                                                    <option value="ST">Sao Tome and Principe</option>
                                                                    <option value="SA">Saudi Arabia</option>
                                                                    <option value="SN">Senegal</option>
                                                                    <option value="RS">Serbia</option>
                                                                    <option value="SC">Seychelles</option>
                                                                    <option value="SL">Sierra Leone</option>
                                                                    <option value="SG">Singapore</option>
                                                                    <option value="SK">Slovakia</option>
                                                                    <option value="SI">Slovenia</option>
                                                                    <option value="SB">Solomon Islands</option>
                                                                    <option value="SO">Somalia</option>
                                                                    <option value="ZA">South Africa</option>
                                                                    <option value="GS">South Georgia South Sandwich Islands</option>
                                                                    <option value="SS">South Sudan</option>
                                                                    <option value="ES">Spain</option>
                                                                    <option value="LK">Sri Lanka</option>
                                                                    <option value="SH">St. Helena</option>
                                                                    <option value="PM">St. Pierre and Miquelon</option>
                                                                    <option value="SD">Sudan</option>
                                                                    <option value="SR">Suriname</option>
                                                                    <option value="SJ">Svalbard and Jan Mayen Islands</option>
                                                                    <option value="SZ">Swaziland</option>
                                                                    <option value="SE">Sweden</option>
                                                                    <option value="CH">Switzerland</option>
                                                                    <option value="SY">Syrian Arab Republic</option>
                                                                    <option value="TW">Taiwan</option>
                                                                    <option value="TJ">Tajikistan</option>
                                                                    <option value="TZ">Tanzania, United Republic of</option>
                                                                    <option value="TH">Thailand</option>
                                                                    <option value="TG">Togo</option>
                                                                    <option value="TK">Tokelau</option>
                                                                    <option value="TO">Tonga</option>
                                                                    <option value="TT">Trinidad and Tobago</option>
                                                                    <option value="TN">Tunisia</option>
                                                                    <option value="TR">Turkey</option>
                                                                    <option value="TM">Turkmenistan</option>
                                                                    <option value="TC">Turks and Caicos Islands</option>
                                                                    <option value="TV">Tuvalu</option>
                                                                    <option value="UG">Uganda</option>
                                                                    <option value="UA">Ukraine</option>
                                                                    <option value="AE">United Arab Emirates</option>
                                                                    <option value="GB">United Kingdom</option>
                                                                    <option value="US">United States</option>
                                                                    <option value="UM">United States minor outlying islands</option>
                                                                    <option value="UY">Uruguay</option>
                                                                    <option value="UZ">Uzbekistan</option>
                                                                    <option value="VU">Vanuatu</option>
                                                                    <option value="VA">Vatican City State</option>
                                                                    <option value="VE">Venezuela</option>
                                                                    <option value="VN">Vietnam</option>
                                                                    <option value="VG">Virgin Islands (British)</option>
                                                                    <option value="VI">Virgin Islands (U.S.)</option>
                                                                    <option value="WF">Wallis and Futuna Islands</option>
                                                                    <option value="EH">Western Sahara</option>
                                                                    <option value="YE">Yemen</option>
                                                                    <option value="ZR">Zaire</option>
                                                                    <option value="ZM">Zambia</option>
                                                                    <option value="ZW">Zimbabwe</option>
                                                                    <option value="WW">Worldwide</option>
                                                                                    </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-control" name="b" id="b">
                            <option value="">Deal Type</option>
                            <option value="Tech">Tech
                            </option>
                            <option value="Non-Tech">Non-Tech
                            </option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-control" name="i" id="i">
                            <option value="">Suited For</option>

                            {
                            <option value="1">Accounting</option>
                            }
                        {
                            <option value="2">Airlines/ Aviation
</option>
                            }
                        {
                            <option value="4">Alternative Dispute Resolution</option>
                            }
                        {
                            <option value="5">Alternative Medicine</option>
                            }
                        {
                            <option value="6">Animation</option>
                            }
                        {
                            <option value="7">Apparel &amp; Fashion</option>
                            }
                        {
                            <option value="8">Architecture &amp; Planning</option>
                            }
                        {
                            <option value="9">Arts and Crafts</option>
                            }
                        {
                            <option value="10">Automotive</option>
                            }
                        {
                            <option value="11">Aviation &amp; Aerospace
</option>
                            }
                        {
                            <option value="12">Banking</option>
                            }
                        {
                            <option value="13">Biotechnology </option>
                            }
                        {
                            <option value="14">Broadcast Media</option>
                            }
                        {
                            <option value="15">Building Materials</option>
                            }
                        {
                            <option value="16">Business Supplies and Equipment</option>
                            }
                        {
                            <option value="17">Capital Markets</option>
                            }
                        {
                            <option value="18">Chemicals</option>
                            }
                        {
                            <option value="19">Civic &amp; Social Organization</option>
                            }
                        {
                            <option value="20">Civil Engineering</option>
                            }
                        {
                            <option value="21">Commercial Real Estate</option>
                            }
                        {
                            <option value="22">Computer &amp; Network Security</option>
                            }
                        {
                            <option value="23">Computer Games</option>
                            }
                        {
                            <option value="24">Computer Hardware</option>
                            }
                        {
                            <option value="25">Computer Networking</option>
                            }
                        {
                            <option value="26">Computer Software</option>
                            }
                        {
                            <option value="27">Construction</option>
                            }
                        {
                            <option value="28">Consumer Electronics</option>
                            }
                        {
                            <option value="29">Consumer Goods</option>
                            }
                        {
                            <option value="30">Consumer Services</option>
                            }
                        {
                            <option value="31">Cosmetics</option>
                            }
                        {
                            <option value="32">Dairy</option>
                            }
                        {
                            <option value="33">Defense &amp; Space</option>
                            }
                        {
                            <option value="34">Education Management</option>
                            }
                        {
                            <option value="35">E-Learning</option>
                            }
                        {
                            <option value="36">Electrical/Electronic Manufacturing</option>
                            }
                        {
                            <option value="37">Entertainment</option>
                            }
                        {
                            <option value="38">Environmental Services</option>
                            }
                        {
                            <option value="39">Events Services</option>
                            }
                        {
                            <option value="40">Executive Office</option>
                            }
                        {
                            <option value="41">Facilities Services</option>
                            }
                        {
                            <option value="42">Farming</option>
                            }
                        {
                            <option value="43">Financial Services</option>
                            }
                        {
                            <option value="44">Fine Art</option>
                            }
                        {
                            <option value="45">Fishery</option>
                            }
                        {
                            <option value="46">Food &amp; Beverages</option>
                            }
                        {
                            <option value="47">Food Production</option>
                            }
                        {
                            <option value="48">Fund-Raising</option>
                            }
                        {
                            <option value="49">Furniture</option>
                            }
                        {
                            <option value="50">Gambling &amp; Casinos</option>
                            }
                        {
                            <option value="51">Glass, Ceramics &amp; Concrete</option>
                            }
                        {
                            <option value="52">Government Administration</option>
                            }
                        {
                            <option value="53">Government Relations</option>
                            }
                        {
                            <option value="54">Graphic Design</option>
                            }
                        {
                            <option value="55">Health, Wellness and Fitness</option>
                            }
                        {
                            <option value="56">Higher Education</option>
                            }
                        {
                            <option value="57">Hospital &amp; Health Care</option>
                            }
                        {
                            <option value="58">Hospitality</option>
                            }
                        {
                            <option value="59">Human Resources</option>
                            }
                        {
                            <option value="60">Import and Export</option>
                            }
                        {
                            <option value="61">Individual &amp; Family Services</option>
                            }
                        {
                            <option value="62">Industrial Automation</option>
                            }
                        {
                            <option value="63">Information Services</option>
                            }
                        {
                            <option value="64">Information Technology and Services</option>
                            }
                        {
                            <option value="65">Insurance</option>
                            }
                        {
                            <option value="66">International Affairs</option>
                            }
                        {
                            <option value="67">International Trade and Development</option>
                            }
                        {
                            <option value="68">Internet</option>
                            }
                        {
                            <option value="69">Investment Banking</option>
                            }
                        {
                            <option value="70">Investment Management</option>
                            }
                        {
                            <option value="71">Judiciary</option>
                            }
                        {
                            <option value="72">Law Enforcement</option>
                            }
                        {
                            <option value="73">Law Practice</option>
                            }
                        {
                            <option value="74">Legal Services</option>
                            }
                        {
                            <option value="75">Legislative Office</option>
                            }
                        {
                            <option value="76">Leisure, Travel &amp; Tourism</option>
                            }
                        {
                            <option value="77">Libraries</option>
                            }
                        {
                            <option value="78">Logistics and Supply Chain</option>
                            }
                        {
                            <option value="79">Luxury Goods &amp; Jewelry</option>
                            }
                        {
                            <option value="80">Machinery</option>
                            }
                        {
                            <option value="81">Management Consulting</option>
                            }
                        {
                            <option value="82">Maritime</option>
                            }
                        {
                            <option value="83">Market Research</option>
                            }
                        {
                            <option value="84">Marketing and Advertising</option>
                            }
                        {
                            <option value="85">Mechanical or Industrial Engineering</option>
                            }
                        {
                            <option value="86">Media Production</option>
                            }
                        {
                            <option value="87">Medical Devices</option>
                            }
                        {
                            <option value="88">Medical Practice</option>
                            }
                        {
                            <option value="89">Mental Health Care</option>
                            }
                        {
                            <option value="90">Militar</option>
                            }
                        {
                            <option value="91">Mining &amp; Metals</option>
                            }
                        {
                            <option value="92">Motion Pictures and Film</option>
                            }
                        {
                            <option value="93">Museums and Institutions</option>
                            }
                        {
                            <option value="94">Music</option>
                            }
                        {
                            <option value="95">Nanotechnology</option>
                            }
                        {
                            <option value="96">Newspapers</option>
                            }
                        {
                            <option value="97">Non-Profit Organization Management</option>
                            }
                        {
                            <option value="98">Oil &amp; Energy</option>
                            }
                        {
                            <option value="99">Online Media</option>
                            }
                        {
                            <option value="100">Outsourcing/Offshoring</option>
                            }
                        {
                            <option value="101">Package/Freight Delivery</option>
                            }
                        {
                            <option value="102">Packaging and Containers</option>
                            }
                        {
                            <option value="103">Paper &amp; Forest Products</option>
                            }
                        {
                            <option value="104">Performing Arts</option>
                            }
                        {
                            <option value="105">Pharmaceuticals</option>
                            }
                        {
                            <option value="106">Philanthropy</option>
                            }
                        {
                            <option value="107">Photography</option>
                            }
                        {
                            <option value="108">Plastics</option>
                            }
                        {
                            <option value="109">Political Organization</option>
                            }
                        {
                            <option value="110">Primary/Secondary Education</option>
                            }
                        {
                            <option value="111">Printing</option>
                            }
                        {
                            <option value="112">Professional Training &amp; Coaching</option>
                            }
                        {
                            <option value="113">Program Development</option>
                            }
                        {
                            <option value="114">Public Policy</option>
                            }
                        {
                            <option value="115">Public Relations and Communications</option>
                            }
                        {
                            <option value="116">Public Safety</option>
                            }
                        {
                            <option value="117">Publishing</option>
                            }
                        {
                            <option value="118">Railroad Manufacture</option>
                            }
                        {
                            <option value="119">Ranching</option>
                            }
                        {
                            <option value="120">Real Estate</option>
                            }
                        {
                            <option value="121">Recreational Facilities and Services</option>
                            }
                        {
                            <option value="122">Religious Institutions</option>
                            }
                        {
                            <option value="123">Renewables &amp; Environment</option>
                            }
                        {
                            <option value="124">Research</option>
                            }
                        {
                            <option value="125">Restaurants</option>
                            }
                        {
                            <option value="126">Retail</option>
                            }
                        {
                            <option value="127">Security and Investigations</option>
                            }
                        {
                            <option value="128">Semiconductors</option>
                            }
                        {
                            <option value="129">Shipbuilding</option>
                            }
                        {
                            <option value="130">Sporting Goods</option>
                            }
                        {
                            <option value="131">Sports</option>
                            }
                        {
                            <option value="132">Staffing and Recruiting</option>
                            }
                        {
                            <option value="133">Supermarkets</option>
                            }
                        {
                            <option value="134">Telecommunications</option>
                            }
                        {
                            <option value="135">Textiles</option>
                            }
                        {
                            <option value="136">Think Tanks</option>
                            }
                        {
                            <option value="137">Tobacco</option>
                            }
                        {
                            <option value="138">Translation and Localization</option>
                            }
                        {
                            <option value="139">Transportation/Trucking/Railroad</option>
                            }
                        {
                            <option value="140">Utilities</option>
                            }
                        {
                            <option value="141">Venture Capital &amp; Private Equity</option>
                            }
                        {
                            <option value="142">Veterinary</option>
                            }
                        {
                            <option value="143">Warehousing</option>
                            }
                        {
                            <option value="144">Wholesale</option>
                            }
                        {
                            <option value="145">Wine and Spirits</option>
                            }
                        {
                            <option value="146">Wireless</option>
                            }
                        {
                            <option value="147">Writing and Editing</option>
                            }
                        {
                            <option value="148">Other</option>
                            }
                        {
                            <option value="149">Big Data</option>
                            }
                        {
                            <option value="150">Artificial Intelligence</option>
                            }
                        {
                            <option value="151">Cyber Security</option>
                            }
                        {
                            <option value="152">Crypto Currency</option>
                            }
                        {
                            <option value="153">Blockchain</option>
                            }
                        {
                            <option value="154">Fintech</option>
                            }
                        {
                            <option value="155">IoT</option>
                            }
                        {
                            <option value="156">E-commerce</option>
                            }
                        {
                            <option value="157">Drop Shipping</option>
                            }
                        {
                            <option value="158">Direct to consumer</option>
                            }
                        {
                            <option value="159">Cross border E-commerce</option>
                            }
                        {
                            <option value="160">Exports</option>
                            }
                        {
                            <option value="161">Personal Branding</option>
                            }
                        {
                            <option value="162">Everyone</option>
                            }
                        
                        <!-- <option value="Sales" >Sales
                            </option>
                            <option value="Marketing" >Marketing
                            </option>
                            <option value="Finance" >Finance
                            </option>
                            <option value="Legal" >Legal
                            </option>
                            <option value="Administration" >Administration
                            </option>
                            <option value="HR" >HR
                            </option> <option value="Operations" >Operations
                            </option> <option value="Others" >Others
                            </option> -->
                        </select>
                    </div>
                    

                    <div class="col-md-2">

                    <select class="form-control">
                        <option>Status</option>
                        <option value="0">Pending</option>
                        <option value="1">Progress</option>
                        <option value="2">Sent</option>
                        <option value="3" >Query</option>
                        <option value="4">Delivered</option>
                    </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary">Search
                        </button> </div>
                
            </div>
                
        </div>
    </div>




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
                        <div class="col-md-12">
                        {{--                            <a href="{{ route('vouch-create') }}" class="btn btn-primary ladda-button"--}}
                        {{--                               data-style="zoom-in">--}}
                        {{--                                <span class="ladda-label"><i class="fa fa-plus"></i> Add Vouch Code</span>--}}
                        {{--                            </a>--}}
                        </div>
                    </div>

                    <div class="box-body table-responsive">
                    
                    <table id="data-table" class="table table-bordered table-striped last-child-sm" width="100%"
                               data-page-length='100'>
                    <thead>
                    <tr>
                      <th>Id</th>
                      <th>Details</th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Phone</th>
                      <th>Quantity</th>
                      <th>Amount</th>
                      <th>Buy Date</th>
                      <th>Status</th>
                     <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                        
                    @if(!empty($orders))
                        @foreach($orders as $order)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><b>Partner:</b> {{ $order->offerred_by }}, <br/><b>Offer for:</b> {{ $order->offer_for }},<br/> <b>Delta Deal Amount:</b>{{ $order->deal_amount_delta }},<br/>
                                <b>Sigma Deal Amount:</b>{{ $order->deal_amount_sigma }},<br/>
                                <b>Alpha Deal Amount:</b>{{ $order->deal_amount_alpha }},<br/>
                                <b>Omega Deal Amount:</b>{{ $order->deal_amount_omega }},<br/>
                                </td>
                                <td>{{ $order->entity }} -{{$order->identity}}
                                    <br/>
                               
                                    @if($order->sponsor_type==1)
                                    <b>Type:</b> Offer user 
                                    @elseif($order->sponsor_type==2)
                                    <b>Type:</b> Receive user 
                                    @endif
                                </td>
                                <td>{{ $order->email }}</td>
                                <td>{{ $order->phone_no }}</td>
                                <td>{{ $order->qty }}</td>
                                <td>{{ $order->final_amt }}</td>
                                <td>{{date('d M, Y h:i A',strtotime($order->created_at)) }}</td>
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
                                    <span class="">Paid</span><br/>
                                    <b>Date:</b> {{$order->appove_date}}<br/>
                                    <b>Remark:</b> {{$order->payment_remark}}
                                    @endif

                                </td>
                             
                                <td>
                                   @if($order->status==3)
                                   
                                    <a class="btn btn-danger query_button btn-sm" title="query" data-id="{{$order->id}}" ><i class="fa fa-question-circle"></i></a>
                                    @elseif($order->status==4)
                                 
                                        <a class="btn btn-danger query_button btn-sm" data-id="{{$order->id}}" title="query"><i class="fa fa-question-circle"></i></a>
                                        <a data-id="{{$order->id}}" class="btn btn-success feedback_button btn-sm" title="Feedback"><i class="fa fa-comments"></i></a>
                                        <a data-id="{{$order->id}}" class="btn btn-primary payment btn-sm" title="Payment"><i class="fa fa-inr"></i></a>
                                    
                                        @elseif($order->status==5)
                                        <a class="btn btn-danger query_button btn-sm" title="query" data-id="{{$order->id}}" ><i class="fa fa-question-circle"></i></a>
                                        <a data-id="{{$order->id}}" class="btn btn-success feedback_button btn-sm" title="Feedback"><i class="fa fa-comments"></i></a>
                                        <!-- <a class="btn btn-primary payment btn-sm" title="Payment"><i class="fa fa-inr"></i></a> -->
                                    @endif

                                
                                </td>
                                 
                               
                            </tr>
                        @endforeach
                    @endif
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



  <!-- The Modal -->
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
                
                </form>
            </div>
        </div>

        </div>

        
        </div>
    </div>
    </div>




    <div class="modal" id="payment">
    <div class="modal-dialog">
        <div class="modal-content">
        <form id="payment_modal" method="post">
            <input type="hidden" id="modal_offer_id" name="id" value="45" />
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Payment Details</h4>
            <button type="button" class="close" data-dismiss="modal" style="position:absolute; right:15px; top:20px;">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
            <lable>Payment Amount</lable>
            <input type="text" name="amount" class="form-control" required />

            <lable>Payment Date</lable>
            <input type="date" name="date" class="form-control" required />
            <br/>
            <lable>Remark</lable>
            <textarea class="form-control" name="remark" required></textarea>

        </div>

        <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Pay Now</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                       
      </div>
      </form>
        </div>
    </div>
    </div>





    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.0/clipboard.min.js"></script>

    <script>
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
                url:'{{route("query-get-admin")}}',
                method:'post',
                data:{'id': $(this).attr('data-id')},
                dataType: 'json',
                success:function(data)
                {
                  console.log(data);
                  var html_to_append = '';
                  var css = '';
                  var pname = '';
                  $.each(data, function(i, item) {
                    if(item.type == 'right'){css = 'sent'; pname = item.entity;} else{css = 'received'; pname = item.contact_person;}
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

        $(document).on('click', '.payment', function (e) {
            var id = $(this).attr('data-id');
            $("#modal_offer_id").val(id);
            $("#payment").modal('show');
        });


        $('#payment_modal').on('submit', function(event){
           
            $.ajax({
                url:'{{route("store-payment")}}',
                method:'post',
                data:new FormData(this),
                processData: false,
                contentType: false,
                success:function(data)
                {
                    alert(data);
                    location.reload();
                }
                });

            });            

            $(document).on('click', '.feedback_button', function (e) {
            var id = $(this).attr('data-id');
            $("#offer_item_id").val(id);

            $(".submit-rating").show();
            $("#feedback_text").val('');
            
            $.ajax({
                url:'{{route("show-feedback-admin")}}',
                type:'POST',
                data:{'id':id},
                success:function(fulldata)
                {
                  console.log(fulldata);
                  $(".rating").removeAttr('checked');
                  if(fulldata){
                    var data = $.parseJSON(fulldata);
                    $('.rating').each(function(index,item){
                      if($(item).val() == data.rating){
                          $(item).attr('checked',true);
                      }
                  });

                  $("#feedback_text").val(data.feedback);
                
                  }
                  
                }
             });
            $("#feedback").modal('show');
            
        });

        
        </script>
@endsection
