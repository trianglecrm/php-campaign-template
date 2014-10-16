
The purpose of this template is to allow our customers who have servers running PHP to use an already tested php front-end implementation

##Getting started

Copy this template to your server and just add your html, css and img

##Config explain

The config.ini file is located under TriangleCRM folder, and it is in charged of handle all the information related with the costumer, you should updated this file with all your information.

Also there are sections called *billingFormRequired* and *ccFormRequired* which are in charge of set in the template which fields should be shown. 


##Config example

User settings example
```
[Settings]
USERNAME = 'Your username'
PASSWORD = 'Your password'
DOMAIN = 'Your instance'
SITE = ''
WSDL = '/api/2.0/billing_ws.asmx?wsdl'

```

Billing form required example
```
[billingFormRequired]
firstName = true
lastName = true
address = true
address2 = false
city = true
state = true
zip = true
country = false
phone = true
email = true
```

Credit Card form required example
```
[ccFormRequired]
paymentType = true
creditCard = true
cvv = true
exp = true
```

##Bootstrap explained

This template uses a bootstrap object coming from the server, with information like:

- planID
- trialPackageID
- paymentType
- chargeForTrial
- campaign_id
- successRedirect
- downSell
- upSell

the purpose of this implementations is to avoid JS edition, only being needed the editing of the config.ini file.

##Bootstrap example

```
[indexBootstrap]
planID = '56'
trialPackageID = '2'
paymentType = ''
chargeForTrial = true
campaign_id = '117'
successRedirect = 'order.php'
downSell = 'step3.php'
upSell = 'step2-order.php'
```

##Templates explain

Because modular is always better, we have all the html files separated into templates:

- header
- footer
- Main content
- Billing Form (Information related to the costumer: First Name, Last Name...) there is no need of change this file
- Credit Card Form (Information related to the CC: CC Number, CVV...) there is no need of change this file


##Templates holders example

index.php

```
<?php include_once('templates/offers/offer.html'); ?>
<div class="wrapper">
<!-- Index Content Blocks -->
    <?php include_once('templates/headers/header.html'); ?>
    <?php include_once('templates/contents/index.php'); ?>
    <?php include_once('templates/footers/footer.html'); ?>
</div>

```


/templates/cctemplate.php

```
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
```

reviewing line by line the last example

- check if billingInfo cookie exists, if not 
```
if (isset($_COOKIE["billingInfo"])){
    $billingInfo = unserialize($_COOKIE["billingInfo"]);
}
else{
    echo'<script>
            window.location.href = "/index.php?redirected=1";
         </script>';
    die();
}
```

- Include our controller
```
include '../../TriangleCRM/Controller.php';
```

- Create a new instance of the controller saying that we are going to use that instance for boostraping 
```
$controller = new Controller("boostrap");
```

- Get the index Bootstrap model(needed information to submit) and get the Billing Form Required elements
```
$settings = json_decode($controller->GetModel("orderBootstrap"));  
$requiredJson = $controller->GetModel('ccFormRequired');
$required = json_decode($requiredJson);
```

- Setting JS variables with the content coming from the server also, create a downsell variable with the downsell page name
```
<script>
    var indexSettings = <?php echo $settings; ?>;
    var downsell = indexSettings.Result.downSell.split('.')[0];// SPA redirect without .php or .html
</script>
```

- Add custom html without removing or editing the billing form include
```
<?php include_once('templates/headers/header.html'); ?>
<?php include_once('templates/contents/index.php'); ?> // holder 
<?php include_once('templates/footers/footer.html'); ?>
```


##Full example of implementation

- Download the Repo
- Change the User settings on the config.ini

```
[Settings]
USERNAME = 'your username'
PASSWORD = 'Your password'
DOMAIN = 'Your instance'
SITE = ''
WSDL = '/api/2.0/billing_ws.asmx?wsdl'

```

- Change the required elements by from on the config.ini

  * Billing form required elements
  * Credit Card form required elements

```
[billingFormRequired]
firstName = true
lastName = true
address = true
address2 = false
city = true
state = true
zip = true
country = false
phone = true
email = true
```

```
[ccFormRequired]
paymentType = true
creditCard = true
cvv = true
exp = true
```

- Set your custom information for each bootstrap

```
[indexBootstrap]
planID = '56'  // ID of subscription plan to put customer on. Subscription Plans are configured in CRM and can be used for recurring charges.
trialPackageID = '2' // Should be ID of trial package in subscription manager.
chargeForTrial = true // Boolean option to specify whether the customer should be charged for trial price. If set to "true" function will charge customer immediately and place shipment according to subscription settings.Trial prices and products for subscription plans are configured in CRM. Function will not put the customer on subscription plan if "chargeForTrial" option is set to "true" and charge is failed for trial price.
campaign_id = '117' // Campaign ID for product being sold.
successRedirect = 'order.php' // In case of success after operation redirect here.
downSell = 'step3.php' // In case of leaving the page, redirect here.
upSell = 'step2-order.php' // After success show this other item.
```


- Update the views with your custom html, css(index.css) and JS(scripts.js) code

```
<div class="indexCont">
...add your custom html code
<?php include_once('templates/billingTemplate.php'); ?> // NOT EDIT
add your custom html code...
</div>	
```

- If needed, add new pages creating a new html content and creating a new html holder page, you can use /index.php as a template

```
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Dermarose&reg; Skin Revitalized - Special Offer!</title>
	<meta charset="UTF-8">
	<meta name=description content="">
	<meta name=viewport content="width=device-width, initial-scale=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" href="favicon.ico" />
	<link type="text/css" rel="stylesheet" href="css/index.css"/>
        <link type="text/css" rel="stylesheet" href="css/jNotify.jquery.css"/>
        <script src="js/jquery.min.js"></script>
        <script src='js/jNotify.jquery.min.js'></script>
        <script src="js/exit.js"></script>
        <script src="js/scripts.js"></script>
    </head>
    <body>
        
        <?php include_once('templates/offers/offer.html'); ?>
        <div class="wrapper">
        <!-- Index Content Blocks -->
            <?php include_once('templates/headers/header.html'); ?>
            <?php include_once('templates/contents/index.php'); ?>
            <?php include_once('templates/footers/footer.html'); ?>
        </div>
        
    </body>
</html>

```

