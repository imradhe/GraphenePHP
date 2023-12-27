<?php

errors(0);
require('models/migrator.php');
require('models/db.php');

// Task 2: Display form to update database details
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    ?>
    <!doctype html>
    <html lang="en">

    <head>

        <!-- Bootstrap core CSS -->
        <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>
        <!-- Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
        <!-- Jquery -->
        <script src="assets/jquery/jquery.min.js"></script>

        <!-- Custom styles for this template -->
        <link rel="stylesheet" href="assets/css/app.css">
        <style>
            .form-setup {
                width: 100%;
                max-width: 330px;
                padding: 15px;
                margin-top: 2vh !important;
                margin: auto;
            }

            .form-setup .checkbox {
                font-weight: 400;
            }

            .form-setup .form-control {
                position: relative;
                box-sizing: border-box;
                height: auto;
                padding: 10px;
                font-size: 16px;
            }

            .form-setup .form-control:focus {
                z-index: 2;
            }

            .form-setup input[type="email"] {
                margin-bottom: -1px;
                border-bottom-right-radius: 0;
                border-bottom-left-radius: 0;
            }

            .form-setup input[type="password"] {
                margin-bottom: 10px;
                border-top-left-radius: 0;
                border-top-right-radius: 0;
            }

            #eye {
                cursor: pointer;
            }

            .logo img {
                max-height: 12vh;
            }
        </style>
    </head>

    <body class="text-center mt-5">
        <h2>GraphenePHP App Setup</h2>
        <form method="post" class="form-setup">

            <?php

            // Assuming you have a function to read the existing config.php
            require('config_example.php');

            if(isset($_REQUEST['error'])){ ?>
            <div class="alert alert-danger">

                <?php 
                if($_REQUEST['error'] == "db") echo "Please check the database name";
                elseif($_REQUEST['error'] == "copy") echo "config file couldn't be copied";
                ?>
        </div>
            <?php
            }
            ?>
            <div class="mb-3 row fs-4">
                <span class="col-5">Local</span>
                <span class="form-check form-switch col">
                    <input class="form-check-input text-right" type="checkbox" id="productionSwitch">
                    <label class="form-check-label" for="productionSwitch"></label>
                </span>
                <span class="col-4">Production</span>
            </div>
            <div class="my-3" id="appURLField">
                <label for="APP_URL" class="form-label">App URL</label>
                <input type="text" name="APP_URL" class="form-control" value="<?=(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https://".$_SERVER['HTTP_HOST']."/" : "http://".$_SERVER['HTTP_HOST']."/" ?>">
                <strong>Ex: https://mycoolapp.com/</strong>
            </div>
            <div class="mb-3" id="appSlugField">
                <label for="APP_SLUG" class="form-label">App Slug </label>
                <input type="text" name="APP_SLUG" id="appSlug" class="form-control" value="<?= basename(getcwd()) ?>">
                <strong>What is slug? <i class="bi bi-info-circle fs-5 text-primary" type="button" data-bs-toggle="tooltip"
                        data-bs-placement="right" data-bs-custom-class="custom-tooltip" data-bs-html="true"
                        data-bs-title="Slug is the name of your directory.<br>For example, if your directory name is <b>'MyCoolApp'</b>, it is your slug<br> <br> Note: If you are hosting it in a server and the app is in the root directory, leave this empty"></i>
                </strong>
            </div>

            <div class="mb-3">
                <label for="db_host" class="form-label">Database Host</label>
                <input type="text" name="db_host" class="form-control" value="<?= $config['DB_HOST'] ?>" required>
            </div>

            <div class="mb-3">
                <label for="db_name" class="form-label">Database Name</label>
                <input type="text" name="db_name" class="form-control" value="<?= $config['DB_DATABASE'] ?>" required>
            </div>

            <div class="mb-3">
                <label for="db_user" class="form-label">Database User</label>
                <input type="text" name="db_user" class="form-control" value="<?= $config['DB_USERNAME'] ?>" required>
            </div>

            <div class="text-end">
        <span class="text-graphene user-select-none" id="eye"></span>
      </div>
            <div class="mb-3">
                <label for="db_password" class="form-label">Database Password</label>
                <input type="password" id="db_password" name="db_password" class="form-control" value="<?= $config['DB_PASSWORD'] ?>">
            </div>


            <button type="submit" class="btn btn-graphene">Finish</button>
        </form>
        <script>

            
    let password = document.querySelector("#db_password");
    let eye = document.querySelector('#eye')
    eye.innerHTML = '<i class="bi bi-eye-fill"></i> Show Password'
    eye.addEventListener('click', passwordToggle)
    function passwordToggle() {
      if (password.type == "password") {
        password.type = "text";
        eye.innerHTML = '<i class="bi bi-eye-slash-fill"></i> Hide Password'
      } else {
        password.type = "password";
        eye.innerHTML = '<i class="bi bi-eye-fill"></i> Show Password'
      }
    }

         const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
const tooltipList = Array.from(tooltipTriggerList).map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

const productionSwitch = document.querySelector('#productionSwitch');
const appSlugInput = document.getElementById('appSlug');
const appSlugField = document.querySelector('#appSlugField');

productionSwitch.addEventListener('change', function () {
    appSlugField.classList.toggle('d-none', this.checked);
    appSlugInput.value = this.checked ? '' : '<?= basename(getcwd()) ?>';
});


        </script>
    </body>

    </html>

    <?php
}

// Task 3: Process form submission and update database details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Assuming you have a function to handle database details update
    updateDatabaseDetails($_POST);

    // Task 4: Copy config_example.php after updating database details
    if (copy('config_example.php', 'config.php')) {

        // Task 5: Remove config_example.php
        unlink('config_example.php');

        // Task 6: Create a new database
        createDatabase($_POST['db_name']);
    } else {
        echo 'Error copying config_example.php';
    }
}

// Function to update database details in config.php
function updateDatabaseDetails($data)
{
    // Include the config file with $config as an array
    require('config_example.php');

    // Update database details
    $config['DB_HOST'] = $data['db_host'];
    $config['DB_DATABASE'] = $data['db_name'];
    $config['DB_USERNAME'] = $data['db_user'];
    $config['DB_PASSWORD'] = $data['db_password'];
    $config['APP_URL'] = $data['APP_URL'];
    $config['APP_SLUG'] = $data['APP_SLUG'];

    // Write updated config.php
    $content = "<?php\n\n";
    $content .= '/**' . PHP_EOL;
    $content .= ' * GraphenePHP Configuration' . PHP_EOL;
    $content .= ' *' . PHP_EOL;
    $content .= ' * This file contains the configuration settings for the GraphenePHP framework.' . PHP_EOL;
    $content .= ' * It includes settings such as the application name, database connection details,' . PHP_EOL;
    $content .= ' * SMTP configuration for email, SEO settings, and more.' . PHP_EOL;
    $content .= ' *' . PHP_EOL;
    $content .= ' * @package GraphenePHP' . PHP_EOL;
    $content .= ' * @version 1.0.0' . PHP_EOL;
    $content .= ' */' . PHP_EOL;
    $content .= PHP_EOL;
    $content .= '$config = [' . PHP_EOL;

    foreach ($config as $key => $value) {
        if (in_array($key, ['DB_HOST', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD', 'APP_SLUG'])) {
            $content .= sprintf("    '%s' => '%s',\n", $key, addslashes($config[$key]));
        } else {
            $content .= sprintf("    '%s' => %s,\n", $key, var_export($config[$key], true));
        }
    }

    $content .= '];' . PHP_EOL;
    $content .= PHP_EOL;
    $content .= 'return $config;';

    file_put_contents('config_example.php', $content);
}

// Function to create a new database
function createDatabase($dbName)
{
    require('config.php');
    // Assuming you have a function to establish a database connection
    $mysqli = new mysqli($config['DB_HOST'], $config['DB_USERNAME'], $config['DB_PASSWORD']);

    // Check connection
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    // Create database
    $createDbQuery = "CREATE DATABASE IF NOT EXISTS $dbName";
    if ($mysqli->query($createDbQuery) === TRUE) {

        $tables = [
            'users' => 'auth/users.sql',
            'logs' => 'auth/logs.sql',
            // Add more tables as needed
        ];

        // Migrate tables
        $migrate = Migrator::migrate($tables);

        unset($_REQUEST);
        ?>
        <script>
            window.location.href = "";
        </script>
        <?php
    } else {
        echo "Error creating database: " . $mysqli->error;

        //  Copy config.php 
        if (copy('config.php', 'config_example.php')) {
    
            // Task 5: Remove config_example.php
            unlink('config.php');
        } else {
            echo 'Error copying config.php';
            redirect('/?error=copy', 2);
            
        unset($_REQUEST);
        }
        sleep(2);
        redirect('/?error=db', 2);
        
    unset($_REQUEST);
    }

    // Close connection
    $mysqli->close();
}


// Redirect to a specific route with optional delay
function redirect($route, $delay = 0){
    $regex = "/^(https?|ftp):\/\/[a-z0-9+!*(),;?&=\$_.-]+(\.[a-z0-9+!*(),;?&=\$_.-]+)*(:[0-9]{2,5})?(\/([a-z0-9+_\$_-]\.?)+)*\/?(\?[a-z+&\$_.-][a-z0-9;:@&%=+\/\$_.-]*)?(#[a-z_.-][a-z0-9+\$_.-]*)?$/i";
    ?>
    <script>
      setTimeout(function() {
        window.location.href = "<?php if(preg_match($regex, $route)) echo $route; else echo route($route);?>";
      }, <?php echo $delay; ?>);
    </script>
    <?php
  }
  
  // Get url for a route
  function route($path)
  {
    return home() . $path;
  }
  
// Home URL
function home()
{
  require('config_example.php');
  return (empty($config['APP_SLUG'])) ? $config['APP_URL'] : $config['APP_URL'] . $config['APP_SLUG'] . "/";
}

function errors($enable = true)
{
  if ($enable) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
  } else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
  }
  return $enable;
}

?>