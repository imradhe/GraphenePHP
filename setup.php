<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(1);

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
        ?>
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

    <div class="mb-3">
        <label for="db_password" class="form-label">Database Password</label>
        <input type="password" name="db_password" class="form-control" value="<?= $config['DB_PASSWORD'] ?>">
    </div>

    <button type="submit" class="btn btn-graphene">Update</button>
    </form>
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
        echo 'config.php updated successfully. config_example.php copied.';
        
        // Task 5: Remove config_example.php
        unlink('config_example.php');
        
        // Task 6: Create a new database
        createDatabase($_POST['db_name']);
    } else {
        echo 'Error copying config_example.php';
    }

}

// Function to update database details in config.php
function updateDatabaseDetails($data) {
    // Include the config file with $config as an array
    require('config_example.php');

    // Update database details
    $config['DB_HOST'] = $data['db_host'];
    $config['DB_DATABASE'] = $data['db_name'];
    $config['DB_USERNAME'] = $data['db_user'];
    $config['DB_PASSWORD'] = $data['db_password'];

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
        if (in_array($key, ['DB_HOST', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD'])) {
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
function createDatabase($dbName) {
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
        echo "Database $dbName created successfully";
        
        $tables = [
            'users' => 'auth/users.sql',
            'logs' => 'auth/logs.sql',
            // Add more tables as needed
        ];
        
        // Migrate tables
        $migrate = Migrator::migrate($tables);
        
        echo json_encode($migrate);
        unset($_REQUEST);
        ?>
        <script>
            window.location.href = "";
        </script>
        <?php
    } else {
        echo "Error creating database: " . $mysqli->error;
    }

    // Close connection
    $mysqli->close();
}