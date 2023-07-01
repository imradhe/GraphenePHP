<?php require('config.php'); ?>
<!DOCTYPE html>
<html lang="en">
<?php 
$config['APP_TITLE'] = "Page Not Found - ".$config['APP_NAME']
?>
<?php include('views/partials/head.php'); ?>

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
        <h1><b>404 Not Found</b></b></h1>
        <h4>The page you're looking for does not exist</h4>
        <h6>[<?php echo getRoute() ?>]</h6>
        <a href="<?php echo home();?>" class="btn btn-graphene" rel="noopener">Go Back to Home</a>
    </div>
</body>

</html>