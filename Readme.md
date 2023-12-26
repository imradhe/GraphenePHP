A simple and light-weight PHP framework.
![GraphenePHP Home Screen](/assets/img/ss.png "GraphenePHP Home Screen")

# Installation

## 1. Create GraphenePHP Project (Git Clone the repository)

```bash
git clone https://github.com/imradhe/GraphenePHP.git
```

# Configuration

## 2. Copy config.php file
```bash
cp config_example.php config.php
```

## 3. Create a new empty database in phpmyadmin

## 4. Setup your app with config.php
Modify `config.php` file and start your server to run the app.

if you are testing it on localhost, 

APP_URL = http://localhost/

APP_SLUG = [Directory name]

> i.e. if directory path is C:\xampp\htdocs\MyCoolApp <br>
Then <br>
> APP_SLUG = MyCoolApp
## 5. Run DB Migrations
To run Database Migrations, Go to the following route and it will automatically run the migrations.

``` /migrate ```

# Directory Structure
```
.
├── ...
├── assets   
│   ├── audio                 
│   ├── css   
│   ├── fonts         
│   ├── img
│   ├── jquery
│   ├── js   
│   ├── video
│   └──      
├── controllers
│   ├── api      
├── models       
├── views   
│   ├── api
│   ├── auth
│   ├── errors
│   ├── partials 
├── .htaccess 
├── config.php 
├── db.php   
├── functions.php
├── headers.php
├── index.php
├── robots.txt
├── router.php
├── routes.json
├── sitemap.xml
└── ...
```



If you want to know the status and get updates you can follow me on [Instagram @imraadhe](https://instagram.com/imraadhe)

# Collaborators

1. @karanamakhildatta


