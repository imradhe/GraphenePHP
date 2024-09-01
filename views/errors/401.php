<?php require('config.php'); ?>
<!DOCTYPE html>
<html lang="en">
<?php 
$config['APP_TITLE'] = "Unauthorized- ".$config['APP_NAME']
?>
<?php partial('head', ['config' => $config]); ?>

<style>
    #app {
        max-height: 100vh !important;
    }

    #app img {
        margin-top: 16vh;
        max-height: 40vh;
    }
</style>

<body>
    <div id="app" class="container text-center">
        <img src="<?php assets('img/GraphenePHP-min.png');?>" alt="GraphenePHP Logo" class="img-fluid mb-4">
        <h1><b>401 Unauthorized</b></b></h1>
        <h4>You are not authorized to access this page>
        <h6>[<?php echo getRoute() ?>]</h6>
        <a href="<?php echo home();?>" class="btn btn-graphene" rel="noopener">Go Back to Home</a>
    </div>
</body>

</html>