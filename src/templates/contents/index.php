<?php
    include 'TriangleCRM/Autoloader.php';
    
    require_once "TriangleCRM/formvalidator.php";
    
    use TriangleCRM\Controller as api;
    

    $controller = new Controller("boostrap");

    $settings = $controller->GetModel("indexBootstrap");  
    $requiredJson = $controller->GetModel('billingFormRequired');
    $required = json_decode($requiredJson);
    
    ?>
<script>
        var d = [];
        var downSell = '<?=json_decode($settings)->Result->downSell?>'; //indexSettings.Result.downSell; redirect
        var pageId = '1';
    </script>
    <div class="index-form-container">
        <?php include_once('templates/billingTemplate.php'); ?>
    </div>
		