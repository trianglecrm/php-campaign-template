<script>
    $('document').ready(function() {
        setTimeout(function(){
            $('#paymentMethod').on('change', function() {
                console.log('cambio');
                type = this.value;
                if(type == 1){
                    $('#creditCard').attr('pattern','[0-9]{4} *[0-9]{6} *[0-9]{5}');
                    $('#creditCard').attr('maxlength','15');
                    $('#cvv').attr('pattern','[0-9]{4}');
                    $('#cvv').attr('maxlength','4');
                }
                else{
                    $('#creditCard').attr('pattern','[0-9]{13,16}');
                    $('#creditCard').attr('maxlength','16');
                    $('#cvv').attr('pattern','[0-9]{3}');
                    $('#cvv').attr('maxlength','3');
                }
            });
            }, 3000);
    });
</script>
<?php
    
    $billingInfo;
    if (isset($_COOKIE["billingInfo"])){
        $billingInfo = unserialize($_COOKIE["billingInfo"]);
    }
    else{
        echo'<script>
                window.location.href = "/index.php?redirected=1";
             </script>';
        die();
    }

    include 'TriangleCRM/Autoloader.php';
    
    require_once "TriangleCRM/formvalidator.php";
    
    use TriangleCRM\Controller as api;
    

    $controller = new Controller("boostrap");

    $settings = json_decode($controller->GetModel("orderBootstrap"));  
    $requiredJson = $controller->GetModel('ccFormRequired');
    $required = json_decode($requiredJson);

    
    $show_form=true;

    if(isset($_POST['Submit']))
    {
        //Setup Validations
        $validator = new FormValidator();
        if($required->Result->paymentType){$validator->addValidation("paymentMethod","req","Please select the payment method");}
        if($required->Result->creditCard){$validator->addValidation("creditCard","req","Please fill in Credit Card");}
        if($required->Result->creditCard){$validator->addValidation("creditCard","num","Please fill in Credit Card");}
        if($required->Result->exp){$validator->addValidation("month","req","Please select the expiration month");}
        if($required->Result->exp){$validator->addValidation("year","req","Please select the expiration year");}
        if($required->Result->cvv){$validator->addValidation("securityCode","req","Please fill in security code");}
        if($required->Result->cvv){$validator->addValidation("securityCode","num","Please fill in security code");}
        
        if($validator->ValidateForm())
        {
            $vars = array(
                "planID"=> $settings->Result->planID,
                "trialPackageID"=> $settings->Result->trialPackageID,
                "chargeForTrial"=> $settings->Result->chargeForTrial,
                "campaign_id"=> $settings->Result->campaign_id,
                "firstname"=>$billingInfo['firstname'],
                "lastname"=>$billingInfo['lastname'],
                "address1"=>$billingInfo['address1'],
                "address2"=>$billingInfo['address2'] || '',
                "city"=>$billingInfo['city'],
                "state"=>$billingInfo['state'],
                "zip"=>$billingInfo['zip'],
                "country"=>$billingInfo['country'] || 'US',
                "phone"=>$billingInfo['phone'],
                "email"=>$billingInfo['email'],
                "ip"=>"",
                "affiliate"=>$billingInfo['affiliate'],
                "subAffiliate"=>$billingInfo['subAffiliate'],
                "prospectID"=>$billingInfo['prospectID'],
                "description"=>$settings->Result->description,
                "successRedirect"=>$settings->Result->successRedirect,
                "paymentType"=>(isset($_POST['paymentMethod']))? $_POST['paymentMethod'] : '',
                "creditCard"=>(isset($_POST['creditCard']))? $_POST['creditCard'] : '',
                "expMonth"=>(isset($_POST['month']))? $_POST['month'] : '',
                "expYear"=>(isset($_POST['year']))? $_POST['year'] : '',
                "cvv"=>(isset($_POST['securityCode']))? $_POST['securityCode'] : ''
                );
            $action = 'CreateSubscription';
            $result = $controller->ProcessRequest($vars,$action);
            if($result.State == 'Success'){
                echo '<script> internal = 1 ; window.location.href = "'.$settings->Result->successRedirect.'";</script>';
            }
            else{
                echo '<script>alert('.$result->Info.')</script>';
            }
        }
        else
        {
            $error_hash = $validator->GetErrors();
            echo '<script> var d = [];';
            foreach($error_hash as $inpname => $inp_err)
            {
                ?>
                     d.push('<?=$inpname?>');
                <?php
            }
            echo '</script>';
        }
    }
?>


<form method="post" name='opt_in_form' id='opt_in_form'>
    <input type="hidden" value="submit" name="Submit"/>
    <div class="cc-form form-inner-container">
        <div class='form-element form-input-full' >
            <label>We Accept </label> 
            <img title="Visa" src="img/cc-icon-visa.png" alt="Visa">
            <img title="Mastercard" src="img/cc-icon-mastercard.png" alt="Mastercard"> 
            <img title="American Express" src="img/cc-icon-amex.png" alt="American Express"> 
            <img title="Discover Card" src="img/cc-icon-discover.png" alt="Discover Card">
        </div>
        <?php if($required->Result->paymentType){ ?>
        <div class='form-element form-input-full'>
            <label>Card type<span class='required'>*</span></label>
            <select class='' required id='paymentMethod' name="paymentMethod">
                <option value="">Select Payment Method</option>
                <option value="2">Visa</option>
                <option value="3">Master Card</option>
                <option value="4">Discover</option>
                <option value="1">American Express</option>
            </select>
        </div>
        <?php } ?>
        <?php if($required->Result->creditCard){ ?>
        <div class='form-element form-input-full'>
            <label>Credit Card #<span class='required'>*</span></label>
            <input
          id="creditCard"
          type="text"
          name="creditCard"
          required
          data-credit-card-type
          pattern="/^[0-9]+$/"
          placeholder="Card Number" >
        </div>
        <?php } ?>
        <?php if($required->Result->exp){ ?>
        <div class='form-element form-input-2col'>
            <label>Expiration Date<span class='required'>*</span></label>
            <select name="month" data-card-expiration required class='dropdown month' id='expMonth'>
                <option disabled selected value="">Month</option>
                <option value='01'>January</option>
                <option value='02'>February</option>
                <option value='03'>March</option>
                <option value='04'>April</option>
                <option value='05'>May</option>
                <option value='06'>June</option>
                <option value='07'>July</option>
                <option value='08'>August</option>
                <option value='09'>September</option>
                <option value='10'>October</option>
                <option value='11'>November</option>
                <option value='12'>December</option>
            </select>
            <select name="year" required class='dropdown year' id='expYear'>
                <option disabled selected value="">Year</option>
                <option value="2014">2014</option>
                <option value="2015">2015</option>
                <option value="2016">2016</option>
                <option value="2017">2017</option>
                <option value="2018">2018</option>
                <option value="2019">2019</option>
                <option value="2020">2020</option>
                <option value="2021">2021</option>
                <option value="2022">2022</option>
                <option value="2023">2023</option>
                <option value="2024">2024</option>
                <option value="2025">2025</option>
            </select>
        </div>
        <?php } ?>
        <?php if($required->Result->cvv){ ?>
        <div class='form-element form-input-full'>
            <label>Security Code<span class='required'>*</span></label>
            <input
                id="cvv"
                type="text"
                name="securityCode"
                placeholder="CCV"
                required
                pattern="/^[0-9]+$/"
                class="ccv">
            <a href="javascript:;" class="cvv-help">Whats This?</a>
            <div class="whatiscvv" style="" ></div>
        </div>
        <?php } ?>
        <button id="button-submit" type="submit" class='form-button'></button>
        <div class="button-processing" id="button-processing" style="display:none;"><img src="img/loading.gif" /><br />Processing...</div>
    </div>
</form>