# Helium

Simple MVC concept with PHP language.

## License

MIT License

## Requirements

 - PHP >= 5.3
 - [Composer](https://getcomposer.org/download/)

## How to Install

 - ``` $ git clone https://github.com/mgilangjanuar/helium.git ```
 - ``` $ php composer.phar update ```
 - Setup database (and others) configuration in app/config/config.php

## Folder Structure

```
/app
    | /config                   -- all configuration site and database              
    | /environments             -- for init configuration
    | /controllers              -- controller files
    | /models                   -- model files
    | /views                    -- view files
/public
    | /assets                   -- all scripts, styles, and images you made yourself
    | /vendor                   -- assets from other resources (eg. jQuery, Bootstrap, etc)
/system                         -- all built-in system classes
```


## Guide Started

### Create Controller

``` 
namespace app\controllers;

class SiteController extends \system\BaseController
{
    ...
}
````

### Create Model

```
namespace app\models;

class Example extends \system\BaseModel
{
    ...
}
```

### Create Model Record

```
namespace app\models;

class Example extends \system\BaseRecord
{
    public function tableName()
    {
        return 'table_name';
    }
    ...
}
```

### Route

```http://yourproject/controller/action```

For example if you have controller like this

```
class SiteController extends \system\BaseController
{
    public function actionIndex()
    {
        echo "Hello, World!";
    }    
}
```

You can access it through this link

```http://yourproject/site/index```

Or you can custom it in config.php (Eg. http://yourproject/home)

```
'route' => [
    ...
    'routes' => [
        '/home' => '\app\controllers\SiteController:actionIndex'
    ]
],
```

### GET and POST Request

```
use system\App;

App::$request->post();      //---same like--- $_POST
App::$request->get();       //---same like--- $_GET
App::$request->get('id');   //---same like--- $_GET['id']
```

### Working with URL

```
use system\App;

App::$url->activeUrl();
App::$url->baseUrl();
App::$url->urlTo('/site/index');
App::$url->redirect(['/site/index']);
```

### Render View

```
// in a method action in a controller class
return $this->render('site/index', [
    'data' => 'data1',
    ...
]);
```

### Redirect from Controller

```
return $this->redirect(['/site/index']);
```

### Working with Password

```
use system\App;

echo App::$user->setPassword('yourpassword');    // print hash of 'yourpassword'
App::$user->validatePassword('yourpassword', 'yourhashpassword');   // return boolean
```

### User Authentication

```
use system\App;

App::$user->login($model);  // receive User model subclass of \system\BaseRecord and set session
App::$user->logout();   // clear user session
App::$user->isLoggedIn();   // return boolean
App::$user->identity; // get all attributes of a user has been logged in
```

### Setup Assets

```
// in config.php
...
'assets' => [
    'css' => [
        // all css styles you want
        // 'public/assets/css/style.css',
    ],
    'js' => [
        // all js scripts you want
        // 'public/assets/js/script.js',
    ],
],
...
```

### Generate Random String

Print random string with 10 chars

```
use system\App;

echo App::$helper->generateRandomString(10);
```

### Truncate HTML

Print 20 word from html code

```
use system\App;

$html = ...
echo App::$helper->truncateHtml($html, 20);
```

### Working with Query

```
use system\App;

App::$db->query('SELECT * FROM ...')->execute();
```


