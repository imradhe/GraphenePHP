<head>

    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="<?php echo $config['APP_DESC']; ?>" />
    <meta name="keywords" content="<?php echo $config['APP_KEYWORDS']; ?>" />
    <meta name="mobile-web-app-capable" content="yes" />
    <meta name="author" content="<?php echo $config['APP_AUTHOR']; ?>" />
    <meta name="robots" content="follow" />
    <link rel="canonical" href="<?php echo url(); ?>" />
    <link rel="icon" href="<?php echo route($config['APP_ICON']); ?>" />
    <meta name="theme-color" content="<?php echo $config['APP_THEME_COLOR'];?>" />

    <!-- OPEN GRAPH -->
    <meta property="og:type" content="website" />
    <meta property="og:title" content="<?php echo $config['APP_TITLE']?>" />
    <meta property="og:url" content="<?php echo url(); ?>" />
    <meta property="og:description" content="<?php echo $config['APP_DESC']; ?>" />
    <meta property="og:image" itemprop="image" content="<?php echo route($config['APP_OG_ICON_MOBILE']); ?>" />
    <meta property="og:image:secure_url" itemprop="image" content="<?php echo route($config['APP_OG_ICON_MOBILE']); ?>" />
    <meta property="og:site_name" content="<?php echo $config['APP_NAME']; ?>" />

    <!-- Twitter Meta Tags -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="<?php echo $config['APP_TITLE']; ?>" />
    <meta name="twitter:description" content="<?php echo $config['APP_DESC']; ?>" />
    <meta name="twitter:image" content="<?php echo route($config['APP_OG_ICON_MOBILE']); ?>" />
    <meta name="twitter:creator" content="<?php echo $config['APP_TWITTER_CREATOR']; ?>">
    
    <!-- Bootstrap core CSS -->
    <link href="<?php assets("bootstrap/css/bootstrap.min.css");?>" rel="stylesheet">
    <script src="<?php assets("bootstrap/js/bootstrap.bundle.min.js");?>"></script>

    <!-- Jquery -->
    <script src="<?php assets("jquery/jquery.min.js");?>"></script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">    

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet" />

    <!-- Custom styles for this template -->
    <link rel="stylesheet" href="<?php assets("css/app.css");?>">

    <title><?php echo $config['APP_TITLE']; ?></title>
</head>