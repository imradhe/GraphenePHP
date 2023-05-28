<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <b><a class="navbar-brand" href="<?php echo home();?>">GraphenPHP</a></b>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <?php if(url() != route('login')){?>

            


            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">

            <?php if(App::getSession()){?>
            <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-circle"></i> <?php echo App::getUser()['name'];?>
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="<?php echo route('profile');?>">Profile</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" aria-current="page" href="<?php echo route('logout');?>">Logout</a></li>
            </ul>
            </li>

            <?php } ?>
            <li class="nav-item">
                <?php if(!App::getSession()){?>
                    <a class="nav-link" aria-current="page" href="<?php echo route('login')."?back=".url();?>">Login</a>
                <?php }?>
            </li>
        </ul>
        <?php }?>
        </div>
    </div>
</nav>