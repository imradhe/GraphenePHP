<?php
/**
 * GraphenePHP Configuration
 *
 * This file contains the configuration settings for the GraphenePHP framework.
 * It includes settings such as the application name, database connection details,
 * SMTP configuration for email, SEO settings, and more.
 *
 * @package GraphenePHP
 * @version 1.0.0
 */

$config = [
    'APP_NAME' => 'GraphenePHP',
    'APP_TITLE' => 'GraphenePHP',
    'APP_URL' => 'http://localhost/',
    // https://example.com/
    'APP_SLUG' => 'graphenephp',
    // full url: http://localhost/graphenephp

    // If the Graphene App is not hosted in the main directory of the domain add the directory name in APP_SLUG

    'DB_CONNECTION' => 'mysql',
    'DB_HOST' => 'localhost',
    'DB_PORT' => '3306',
    'DB_DATABASE' => 'graphenephp',
    'DB_USERNAME' => 'root',
    'DB_PASSWORD' => '',

    'SMTP_DRIVER' => 'smtp',
    'SMTP_HOST' => 'host',
    'SMTP_PORT' => 'port',
    'SMTP_USERNAME' => 'username',
    'SMTP_PASSWORD' => 'password',
    'SMTP_ENCRYPTION' => 'tls',

    // SEO
    'APP_DESC' => 'A Simple and light-weight PHP MVC Framework',
    'APP_SHORT_TITLE' => 'GraphenePHP',
    'APP_AUTHOR' => 'Radhe Shyam Salopanthula',
    'APP_ICON' => 'assets/img/GraphenePHPIcon.png',
    // Size 1000x1000
    'APP_OG_ICON' => 'assets/img/GraphenePHP.png',
    // Size 600x300
    'APP_OG_ICON_MOBILE' => 'assets/img/GraphenePHP.png',
    // Size 700x700
    'APP_THEME_COLOR' => '#FFFFFF',
    // Color in HEX Code
    'APP_KEYWORDS' => 'GraphenePHP App, Radhe Shyam Salopanthula',
    // Max 20 Keywords
    'APP_TWITTER_CREATOR' => '@imraadhe', // Twitter Username
];
