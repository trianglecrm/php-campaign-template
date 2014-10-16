<?php
include 'TriangleCRM/Autoloader.php';

use TriangleCRM\Controller as api;

$controller = new Controller("boostrap");

$settings = $controller->GetModel("upsellBootstrap");  
$requiredJson = $controller->GetModel('ccFormRequired');
$required = json_decode($requiredJson);

?>

<!DOCTYPE html>
<html lang="">
	<head>
		<title></title>
		<meta charset="UTF-8">
		<meta name=description content="">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link type="text/css" rel="stylesheet" href="css/step2-order.css"/>
                <link type="text/css" rel="stylesheet" href="css/jNotify.jquery.css"/>
		<link rel="shortcut icon" href="favicon.ico" />
	</head>
	<body>
            <?php include_once('templates/offers/offer.html'); ?>
		<div class="order-block-01">
			<div class="wrapper">
                            <?php include_once('templates/headers/header.html'); ?>
                            <?php include_once('templates/contents/step2-order.php'); ?>
                            <?php include_once('templates/footers/footer.html'); ?>
			</div>
		</div>
            <script>
                var orderSettings = <?php echo $settings; ?>;
                var orderShowEl = <?php echo $requiredJson; ?>;
            </script>
            <script type="text/javascript" src="js/jquery.min.js"></script>
            <script type="text/javascript" src="js/jquery.cookie.js"></script>
            <script type="text/javascript" src='js/jNotify.jquery.min.js'></script>
            <script type="text/javascript" src="js/billing/jsbn.js"></script>
            <script type="text/javascript" src="js/billing/rsa.js"></script>
            <script type="text/javascript" src="js/exit.js"></script>
            <script type="text/javascript" src='js/global.js'></script>
	</body>
</html>