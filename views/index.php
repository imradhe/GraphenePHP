<!DOCTYPE html>
<html lang="en">
<?php include("views/partials/head.php") ?>
<style>
    
#app {
    margin-top: 12vh;
    max-height: 100vh !important;
}
</style>

<body>

    <?php require('views/partials/nav.php');?>

    <div id="app" class="container text-center">
        <img src="<?php assets("img/GraphenePHP.png");?>" alt="GraphenePHP logo" title="GraphenePHP logo" class="img-fluid mb-4">
        <h1>Welcome to your first <b>GraphenePHP</b> App</h1> 
        <a href="https://github.com/imradhe/GraphenePHP/wiki/GraphenePHP" class="btn btn-graphene" rel="noopener"
            target="_blank">Documentation</a>
        <p><?php if($user = getSession()) echo $user['email']." Logged In"; else "Logged Out"; ?></p>
        <?php include('views/partials/footer.php') ;?>
    </div>
</body>

</html>