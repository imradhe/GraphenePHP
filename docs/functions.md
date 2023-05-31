# GraphenePHP Global Functions (functions.php)
## home()
### Syntax
```php
$homeURL = home(); // https://example.com/
```
### Explanation
The **`home()`** function is used to retrieve the home URL of the application. It reads the configuration from the config.php file and constructs the home URL based on the configuration settings. The home URL is returned by the function.

## url()
### Syntax
```php
$currentURL = url(); // https://example.com/auth/register
```
### Explanation
The **`url()`** function is used to retrieve the current URL of the request. It combines various server and request variables to construct the complete URL, including the protocol, host, and requested resource location. The function also reads the configuration from the config.php file to handle cases where the application has a slug or subdirectory in the URL. The constructed URL is returned by the function.


## unauthorized()
### Syntax:

```php
unauthorized() // Returns 401 Unauthorized header
```
### Explanation:
The **unauthorized()** function is used to handle unauthorized access. It sets the HTTP response code to 401 (Unauthorized), includes the corresponding error view file (views/errors/401.php), and exits the script.

### Use case:
This function is useful when you want to handle unauthorized access to certain parts of your application. It ensures that unauthorized users receive the appropriate response and error message.

## pageNotFound()
### Syntax:

```php
pageNotFound() // Returns 404 Not Found header
```
### Explanation:
The **pageNotFound()** function is used to handle page not found errors. It sets the HTTP response code to 404 (Page Not Found), includes the corresponding error view file (views/errors/404.php), and exits the script.

### Use case:
This function is useful when you want to handle cases where a requested page or resource does not exist. It ensures that users receive the appropriate response and error message.


## locked()
### Syntax:

```php
locked(['user']) // only users can access this file

locked(['editor', 'admin']) // users with admin, editor roles can access 
``` 

### Parameters:

- **`$role`** (optional): An array of roles allowed to access the locked resource. Defaults to ['user'].
### Explanation:
The locked() function is used to restrict access to certain resources based on user roles. It first includes the Auth controller file by calling controller("Auth"). Then, it checks if the user session is empty. If it is, it redirects the user to the login page by calling redirectIfLocked() and exits the script. If the user is logged in, it checks if the user's role is in the allowed roles specified by the $role parameter. If the user's role is not allowed, it returns an unauthorized response by calling the unauthorized() function.

### Use case:
This function is useful when you want to restrict access to certain resources based on user roles. It ensures that only users with the specified roles are allowed to access the resource. If the user is not logged in or their role is not allowed, it handles the access restriction appropriately.


## assets()
### Syntax
```php
assets($path)
```

Parameters:

**`$path`**: The path of the asset file relative to the assets directory.
Explanation:
The **`assets()`** function is used to generate the URL for an asset file. It takes the **`$path`** parameter, which represents the path of the asset file relative to the assets directory, and echoes the complete URL to the asset.

### Example Usage:
```php
assets("css/style.css");
```
### Output:
```
http://example.com/assets/css/style.css
```

<br>

```php
assets("img/logo.png");
```
### Output:
```
http://example.com/assets/img/logo.png
```


## view()
### Syntax:

```php
view($fileName)
```
### Parameters:

**`$fileName`**: The name of the view file (without the .php extension).
Explanation:
The view() function is used to include a view file. It takes the **`$fileName`** parameter, which represents the name of the view file (without the .php extension), and includes the corresponding view file.

Example usage:

```php
view("partials/head"); // "views/partials/head.php"

view("auth/login"); // "views/auth/login.php"

view("users/edit"); // "views/users/edit.php"
```
### Use case:
This function is useful for including reusable view files in your application. It allows you to easily include different views without having to repeat the include statement in multiple places.

## route()
### Syntax:

```php
route($path)
```
### Parameters:

**`$path`**: The path or URL segment for a route.
Explanation:
The **`route()`** function is used to generate the URL for a route. It takes the **`$path`** parameter, which represents the path or URL segment for a route, and returns the complete URL.

Example usage:

```php
echo route("admin/users"); // https://example.com/admin/users
```

### Use case:
This function is useful when you want to generate URLs for different routes in your application. It ensures consistent and correct URL generation, especially if your routes are dynamic or subject to change.

## queryString()
### Syntax:

```php
queryString()
```
### Explanation:
The **`queryString()`** function is used to retrieve the query string from the current URL. It returns the query string portion of the URL (starting with ?).

### Example usage:

```php
echo $queryString = queryString(); // ?param1=value1&param2=value2
```

### Use case:
This function is useful when you need to access or manipulate the query string parameters in your application. It allows you to extract the query string for further processing.

## getRoute()
### Syntax:

```php
getRoute()
```

### Explanation:
The **`getRoute()`** function is used to retrieve the complete URL of the current route, including the query string. It combines the base URL and the query string to form the complete URL.

### Example usage:

```php
echo $route = getRoute(); // http://example.com/products?param1=value1&param2=value2
```


### Use case:
This function is useful when you need to get the complete URL of the current route, including the query string. It allows you to access the current URL for various purposes, such as logging, redirection, or generating links.

## controller()
Syntax:

```php
controller($className)
```

### Parameters:

**`$className`**: The name of the controller class (without the Controller suffix).
Explanation:
The **`controller()`** function is used to include a controller file. It takes the **`$className`** parameter, which represents the name of the controller class (without the Controller suffix), and includes the corresponding controller file.

### Example usage:

```php
controller("Auth"); // includes controllers/AuthController.php
```

### Use case:
This function is useful for including controller files in your application. It allows you to easily include different controllers without having to repeat the include statement in multiple places.
