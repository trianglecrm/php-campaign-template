<?php

    include 'TriangleCRM/Autoloader.php';
    
    require_once "TriangleCRM/formvalidator.php";
    
    use TriangleCRM\Controller as api;
    

    $controller = new Controller("boostrap");

    $settings = $controller->GetModel("indexBootstrap");  
    $requiredJson = $controller->GetModel('billingFormRequired');
    $required = json_decode($requiredJson);

    $states = array(
        'AL'=>'Alabama',
        'AK'=>'Alaska',
        'AZ'=>'Arizona',
        'AR'=>'Arkansas',
        'CA'=>'California',
        'CO'=>'Colorado',
        'CT'=>'Connecticut',
        'DE'=>'Delaware',
        'DC'=>'District of Columbia',
        'FL'=>'Florida',
        'GA'=>'Georgia',
        'HI'=>'Hawaii',
        'ID'=>'Idaho',
        'IL'=>'Illinois',
        'IN'=>'Indiana',
        'IA'=>'Iowa',
        'KS'=>'Kansas',
        'KY'=>'Kentucky',
        'LA'=>'Louisiana',
        'ME'=>'Maine',
        'MD'=>'Maryland',
        'MA'=>'Massachusetts',
        'MI'=>'Michigan',
        'MN'=>'Minnesota',
        'MS'=>'Mississippi',
        'MO'=>'Missouri',
        'MT'=>'Montana',
        'NE'=>'Nebraska',
        'NV'=>'Nevada',
        'NH'=>'New Hampshire',
        'NJ'=>'New Jersey',
        'NM'=>'New Mexico',
        'NY'=>'New York',
        'NC'=>'North Carolina',
        'ND'=>'North Dakota',
        'OH'=>'Ohio',
        'OK'=>'Oklahoma',
        'OR'=>'Oregon',
        'PA'=>'Pennsylvania',
        'RI'=>'Rhode Island',
        'SC'=>'South Carolina',
        'SD'=>'South Dakota',
        'TN'=>'Tennessee',
        'TX'=>'Texas',
        'UT'=>'Utah',
        'VT'=>'Vermont',
        'VA'=>'Virginia',
        'WA'=>'Washington',
        'WV'=>'West Virginia',
        'WI'=>'Wisconsin',
        'WY'=>'Wyoming',
    );
    
    $show_form=true;
    
    if(isset($_GET['redirected']))
    {
         ?>
                <script>alert('You are here again because this information is needed!');</script>
                <?php
        //add redirect alert notification
    }

    if(isset($_POST['Submit']))
    {

        //Setup Validations
        $validator = new FormValidator();
        if($required->Result->firstName){$validator->addValidation("firstname","req","Please fill in First name");}
        if($required->Result->lastName){$validator->addValidation("lastname","req","Please fill in Last name");}
        if($required->Result->address){$validator->addValidation("address1","req","Please fill in address");}
        if($required->Result->address2){$validator->addValidation("address2","req","Please fill in address2");}
        if($required->Result->city){$validator->addValidation("city","req","Please fill in city");}
        if($required->Result->state){$validator->addValidation("state","req","Please fill in state");}
        if($required->Result->zip){$validator->addValidation("zip","req","Please fill in zip code");}
        if($required->Result->country){ $validator->addValidation("country","req","Please fill in country");}
        if($required->Result->phone){$validator->addValidation("phone","req","Please fill in phone");}
        if($required->Result->email){$validator->addValidation("email","email","The input for Email should be a valid email value");}
        if($required->Result->email){$validator->addValidation("email","req","Please fill in Email");}
        
        if($validator->ValidateForm())
        {
            //Validation success. 
            //Here we can proceed with processing the form 
            //(like sending email, saving to Database etc)
            // In this example, we just display a message
            $vars = array(
                "firstname"=>(isset($_POST['firstname']))? $_POST['firstname'] : '',
                "lastname"=>(isset($_POST['lastname']))? $_POST['lastname'] : '',
                "address1"=>(isset($_POST['address1']))? $_POST['address1'] : '',
                "address2"=>(isset($_POST['address2']))? $_POST['address2'] : '',
                "city"=>(isset($_POST['city']))? $_POST['city'] : '',
                "state"=>(isset($_POST['state']))? $_POST['state'] : '',
                "zip"=>(isset($_POST['zip']))? $_POST['zip'] : '',
                "country"=>(isset($_POST['country']))? $_POST['country'] : '',
                "phone"=>(isset($_POST['phone']))? $_POST['phone'] : '',
                "email"=>(isset($_POST['email']))? $_POST['email'] : '',
                "ip"=>'',
                "affiliate"=>(isset($_GET['affiliate']))? $_GET['affiliate'] : '',
                "subAffiliate"=>(isset($_GET['subAffiliate']))? $_GET['subAffiliate'] : '',
                "internalID"=>""
                );
            $action = 'createprospect';
            $result = $controller->ProcessRequest($vars,$action);
            if($result->State == 'Success'){
                $vars['prospectID']=$result->Result->ProspectID;
                setcookie("billingInfo",serialize($vars));
                echo '<script>
                        internal = 1;
                        window.location.href = "/order.php";
                      </script>';
            }
            else{
                ?>
                alert(<?=$result->Info?>);
                <?php
            }
        }
        else
        {
            $error_hash = $validator->GetErrors();
            ?>
                <script>
                    var d = [];
            <?php
            foreach($error_hash as $inpname => $inp_err)
            {
//                echo "<p>$inpname : $inp_err</p>\n";
                ?>
                     d.push('<?=$inpname?>');
                     console.log(d);
                <?php
            }    
            ?>
                </script>
            <?php
        }//else
    }
    
?>
<form Method="post" name='opt_in_form' id='opt_in_form'>
    <input type="hidden" value="submit" name="Submit"/>
        <div id="summary" class="red"></div>
        <?php if($required->Result->firstName){ ?>
            <div class="form-element form-input-full">
                <label>First Name <span class="required">*</span></label> 
                <input 
                    tabindex="1" 
                    type="text" 
                    name="firstname" 
                    id="firstname"  
                    maxlength="30" 
                    class="textInput required letterswithbasicpunc" 
                    title="Please, enter valid Shipping first name" 
                    required
            /></div>
        <?php } ?>
        <?php if($required->Result->lastName){ ?>
        <div class="form-element form-input-full">
                <label>Last Name <span class="required">*</span></label> 
                <input 
                    tabindex="2" 
                    type="text" 
                    name="lastname" 
                    id="lastname"  
                    maxlength="30" 
                    class="textInput required letterswithbasicpunc" 
                    title="Please, enter valid Shipping last name" 
                    required
                    /></div>
        <?php } ?>
        <?php if($required->Result->address){ ?>
        <div class="form-element form-input-full">
                <label>Address <span class="required">*</span></label> 
                <input 
                    tabindex="3" 
                    type="text" 
                    name="address1" 
                    id="address1" 
                    maxlength="30" 
                    class="textInput required alphanumeric" 
                    title="Please, enter valid Shipping address" 
                    required
                    /></div>
        <?php } ?>
        <?php if($required->Result->address2){ ?>
        <div class="form-element form-input-full">
                <label>Address <span class="required">*</span></label> 
                <input 
                    tabindex="3" 
                    type="text" 
                    name="address2" 
                    id="address2" 
                    maxlength="30" 
                    class="textInput required alphanumeric" 
                    title="Please, enter valid Shipping second address" 
                    required
                    /></div>
        <?php } ?>
        <?php if($required->Result->city){ ?>
        <div class="form-element form-input-full">
                <label>City <span class="required">*</span></label> 
                <input 
                    tabindex="4" 
                    type="text" 
                    name="city" 
                    id="city" 
                    maxlength="30" 
                    class="textInput required letterswithbasicpunc" 
                    title="Please, enter valid Shipping city" 
                    required
                    /></div>
        <?php } ?>
        <?php if($required->Result->state){ ?>
        <div class="form-element form-input-full">
                <label>State <span class="required">*</span></label>
                <select 
                    tabindex="5"  
                    border="0" 
                    class="selectbox" 
                    id="state" 
                    style="" 
                    name="state"  
                    size="1" 
                    tabindex=5 
                    required
                    >
                    <option value="">Select State</option>
                    <?php foreach($states as $abbr => $name){
                        echo "<option value='".$abbr."' ".$sel.">".$name." (".$abbr.")</option>";
                    }?>
                </select>
        </div>
        <?php } ?>
        <?php if($required->Result->zip){ ?>
        <div class="form-element form-input-full">
                <label>Postal Code <span class="required">*</span></label> 
                <input 
                    tabindex="6" 
                    type="text" 
                    name="zip" 
                    id="zip" 
                    maxlength="5" 
                    class="textInput shipping zip" 
                    title="Please, enter valid zip. Numbers Only" 
                    value="" 
                    required 
                    pattern="[0-9]+"
                    /></div>
        <?php } ?>
        <?php if($required->Result->country){ ?>
        <div class="form-element form-input-full">
                <label>State <span class="required">*</span></label>
                <select 
                    tabindex="5"  
                    border="0" 
                    class="selectbox" 
                    id="country" 
                    style="" 
                    name="country"  
                    size="1" 
                    tabindex=5 
                    required
                    >
                    <option value="AF">Afghanistan</option>
                    <option value="AX">Åland Islands</option>
                    <option value="AL">Albania</option>
                    <option value="DZ">Algeria</option>
                    <option value="AS">American Samoa</option>
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
                    <option value="BO">Bolivia, Plurinational State of</option>
                    <option value="BQ">Bonaire, Sint Eustatius and Saba</option>
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
                    <option value="CD">Congo, the Democratic Republic of the</option>
                    <option value="CK">Cook Islands</option>
                    <option value="CR">Costa Rica</option>
                    <option value="CI">Côte d'Ivoire</option>
                    <option value="HR">Croatia</option>
                    <option value="CU">Cuba</option>
                    <option value="CW">Curaçao</option>
                    <option value="CY">Cyprus</option>
                    <option value="CZ">Czech Republic</option>
                    <option value="DK">Denmark</option>
                    <option value="DJ">Djibouti</option>
                    <option value="DM">Dominica</option>
                    <option value="DO">Dominican Republic</option>
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
                    <option value="GF">French Guiana</option>
                    <option value="PF">French Polynesia</option>
                    <option value="TF">French Southern Territories</option>
                    <option value="GA">Gabon</option>
                    <option value="GM">Gambia</option>
                    <option value="GE">Georgia</option>
                    <option value="DE">Germany</option>
                    <option value="GH">Ghana</option>
                    <option value="GI">Gibraltar</option>
                    <option value="GR">Greece</option>
                    <option value="GL">Greenland</option>
                    <option value="GD">Grenada</option>
                    <option value="GP">Guadeloupe</option>
                    <option value="GU">Guam</option>
                    <option value="GT">Guatemala</option>
                    <option value="GG">Guernsey</option>
                    <option value="GN">Guinea</option>
                    <option value="GW">Guinea-Bissau</option>
                    <option value="GY">Guyana</option>
                    <option value="HT">Haiti</option>
                    <option value="HM">Heard Island and McDonald Islands</option>
                    <option value="VA">Holy See (Vatican City State)</option>
                    <option value="HN">Honduras</option>
                    <option value="HK">Hong Kong</option>
                    <option value="HU">Hungary</option>
                    <option value="IS">Iceland</option>
                    <option value="IN">India</option>
                    <option value="ID">Indonesia</option>
                    <option value="IR">Iran, Islamic Republic of</option>
                    <option value="IQ">Iraq</option>
                    <option value="IE">Ireland</option>
                    <option value="IM">Isle of Man</option>
                    <option value="IL">Israel</option>
                    <option value="IT">Italy</option>
                    <option value="JM">Jamaica</option>
                    <option value="JP">Japan</option>
                    <option value="JE">Jersey</option>
                    <option value="JO">Jordan</option>
                    <option value="KZ">Kazakhstan</option>
                    <option value="KE">Kenya</option>
                    <option value="KI">Kiribati</option>
                    <option value="KP">Korea, Democratic People's Republic of</option>
                    <option value="KR">Korea, Republic of</option>
                    <option value="KW">Kuwait</option>
                    <option value="KG">Kyrgyzstan</option>
                    <option value="LA">Lao People's Democratic Republic</option>
                    <option value="LV">Latvia</option>
                    <option value="LB">Lebanon</option>
                    <option value="LS">Lesotho</option>
                    <option value="LR">Liberia</option>
                    <option value="LY">Libya</option>
                    <option value="LI">Liechtenstein</option>
                    <option value="LT">Lithuania</option>
                    <option value="LU">Luxembourg</option>
                    <option value="MO">Macao</option>
                    <option value="MK">Macedonia, the former Yugoslav Republic of</option>
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
                    <option value="YT">Mayotte</option>
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
                    <option value="PS">Palestinian Territory, Occupied</option>
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
                    <option value="RE">Réunion</option>
                    <option value="RO">Romania</option>
                    <option value="RU">Russian Federation</option>
                    <option value="RW">Rwanda</option>
                    <option value="BL">Saint Barthélemy</option>
                    <option value="SH">Saint Helena, Ascension and Tristan da Cunha</option>
                    <option value="KN">Saint Kitts and Nevis</option>
                    <option value="LC">Saint Lucia</option>
                    <option value="MF">Saint Martin (French part)</option>
                    <option value="PM">Saint Pierre and Miquelon</option>
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
                    <option value="SX">Sint Maarten (Dutch part)</option>
                    <option value="SK">Slovakia</option>
                    <option value="SI">Slovenia</option>
                    <option value="SB">Solomon Islands</option>
                    <option value="SO">Somalia</option>
                    <option value="ZA">South Africa</option>
                    <option value="GS">South Georgia and the South Sandwich Islands</option>
                    <option value="SS">South Sudan</option>
                    <option value="ES">Spain</option>
                    <option value="LK">Sri Lanka</option>
                    <option value="SD">Sudan</option>
                    <option value="SR">Suriname</option>
                    <option value="SJ">Svalbard and Jan Mayen</option>
                    <option value="SZ">Swaziland</option>
                    <option value="SE">Sweden</option>
                    <option value="CH">Switzerland</option>
                    <option value="SY">Syrian Arab Republic</option>
                    <option value="TW">Taiwan, Province of China</option>
                    <option value="TJ">Tajikistan</option>
                    <option value="TZ">Tanzania, United Republic of</option>
                    <option value="TH">Thailand</option>
                    <option value="TL">Timor-Leste</option>
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
                    <option value="UM">United States Minor Outlying Islands</option>
                    <option value="UY">Uruguay</option>
                    <option value="UZ">Uzbekistan</option>
                    <option value="VU">Vanuatu</option>
                    <option value="VE">Venezuela, Bolivarian Republic of</option>
                    <option value="VN">Viet Nam</option>
                    <option value="VG">Virgin Islands, British</option>
                    <option value="VI">Virgin Islands, U.S.</option>
                    <option value="WF">Wallis and Futuna</option>
                    <option value="EH">Western Sahara</option>
                    <option value="YE">Yemen</option>
                    <option value="ZM">Zambia</option>
                    <option value="ZW">Zimbabwe</option>
        </select>
        </div>
        <?php } ?>
        <?php if($required->Result->phone){ ?>
        <div class="form-element form-input-full">
                <label>Phone <span class="required">*</span></label> 
                <input 
                    tabindex="7"
                    type="text" 
                    name="phone" 
                    id="phone" 
                    class="phone shipping textInput" 
                    maxlength="10"
                    title="Please, enter valid Shipping phone number. Numbers only"
                    value="" 
                    /></div>
        <?php } ?>
        <?php if($required->Result->email){ ?>
        <div class="form-element form-input-full">
                <label>Email <span class="required">*</span></label> 
                <input 
                    tabindex="8"
                    type="email"
                    name="email"
                    id="email" 
                    maxlength="30"
                    class="textInput required email" 
                    title="Please, enter valid Shipping e-mail"
                    required
                    /></div>
        <?php } ?>
        <input tabindex="9" class="form-button" type='submit' id="button-submit" value="" readonly/>
        <div class="button-processing" id="button-processing" style="display:none;"><img src="img/loading.gif" /><br />Processing...</div>
</form>
<script>
    $(document).ready(function(){
        for(var x = 0,y = d.length; x < y; x++){
            $('#'+d[x]).addClass("error");
        }
    });
    
</script>
