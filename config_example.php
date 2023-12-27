<?php

/**
 * GraphenePHP Configuration
 *
 * This file contains the configuration settings for the GraphenePHP framework.
 * It includes settings such as the application name, database connection details,
 * SMTP configuration for email, SEO settings, and more.
 *
 * @package GraphenePHP
 * @version 2.0.0
 */

$config = [
    'APP_NAME' => 'GraphenePHP App',
    'APP_TITLE' => 'GraphenePHP',
    'APP_URL' => 'http://localhost/',
    'APP_SLUG' => 'GraphenePHP',
    'DB_CONNECTION' => 'mysql',
    'DB_HOST' => 'localhost',
    'DB_PORT' => '3306',
    'DB_DATABASE' => 'graphenephp',
    'DB_USERNAME' => 'root',
    'DB_PASSWORD' => '',
    'SMTP_DRIVER' => 'smtp',
    'SMTP_HOST' => 'smtp-relay.sendinblue.com',
    'SMTP_PORT' => '587',
    'SMTP_USERNAME' => '',
    'SMTP_PASSWORD' => '',
    'SMTP_ENCRYPTION' => 'tls',
    'OPENAI_API_KEY' => '',
    'APP_DESC' => 'A Simple and light-weight PHP MVC Framework',
    'APP_SHORT_TITLE' => 'GraphenePHP',
    'APP_AUTHOR' => 'Radhe Shyam Salopanthula',
    'APP_ICON' => 'assets/img/GraphenePHPIcon.png',
    'APP_OG_ICON' => 'assets/img/GraphenePHP.png',
    'APP_OG_ICON_MOBILE' => 'assets/img/GraphenePHP.png',
    'APP_THEME_COLOR' => '#FFFFFF',
    'APP_KEYWORDS' => 'GraphenePHP App, Radhe Shyam Salopanthula',
    'APP_TWITTER_CREATOR' => '@imraadhe',
];