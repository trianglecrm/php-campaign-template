<?php
    include 'TriangleCRM/Autoloader.php';
    
    require_once "TriangleCRM/formvalidator.php";
    
    use TriangleCRM\Controller as api;
    

    $controller = new Controller("boostrap");

    $settings = json_decode($controller->GetModel("orderBootstrap"));  
    $requiredJson = $controller->GetModel('ccFormRequired');
    $required = json_decode($requiredJson);

?>
<script>
    var d = [];
    var downSell = '<?=$settings->Result->downSell?>'; //indexSettings.Result.downSell; redirect
    var successDownsell = '<?=$settings->Result->successDownSell?>';
    var pageId = '2';
</script>
<div class="form-middle">
    <?php include_once('templates/ccTemplate.php'); ?>
</div>
        