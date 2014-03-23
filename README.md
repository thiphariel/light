Light
=====
A lightweight PHP framework MVC based for less than 1Mb ! (PSR2 compliant)

##ALPHA VERSION

#Installation

**Light is based with composer autoload, make sure your ready to use it**

* Download the zip tarball or clone git repo.
* Extract it where you want
* Run <code>composer install --no-dev</code>
* Create your Database (default name is light)
* Here you go !

#Configuration

Light is very simple to use, you will need to edit only one single file.
* Go on <code>app/config/config.php</code> and modify all the vars as your wishes !
```php
/**
 * App configuration file
 * @author Thomas Collot
 */

use Light\App\Core\Lightemplate;

/**
 * Define some vars used for database connection
 */
define('_HOST', 'localhost', true);
define('_DATABASE', 'light', true);
define('_LOGIN', 'root', true);
define('_PWD', 'root', true);

/**
 * Lightemplate configuration
 */
Lightemplate::base('base');					// Base url var name
Lightemplate::assets('assets');				// Assets var name
Lightemplate::dir(_src . 'views/');			// Template file directory
/**
 * Cache file directory / time in cache (in seconds) - 
 * @param string  cache directory path (@nullable -> Light wont use cache system)
 * @param int 	  time to cache (in seconds)
 */
Lightemplate::cache(null, 0);
```

Lightemplate configuration uses 4 static functions.
* <code>Lightemplate::base(string)</code> : Use it to rename the var used in the templates files. This is usefull in links href attributes to avoid some manual writing : 
  ```
  <a href="@base/index">Index</a>
  ```
* <code>Lightemplate::assets(string)</code> : Same as the previous one, except this one will be mainly used for assets (css, img, js ...) : 
  ```
  <img src="@assets/img/logo.png">Index</a>
  ```
* <code>Lightemplate::dir(string)</code> : Define your template directory here
* <code>Lightemplate::cache(string, int)</code> : Define (or not, it can be null) a cache directory (Let it null in dev) and define how many longer your cached files have to be refreshed

#How to use Light ?
Just build all your logic in the "src" dir, you will find the controllers, models & views folders and some demo stuff to play with

#Lightemplate

Light is built with his own PHP template engine. For now, it only include some basic functions listed below :
* Comments

  <code>//@ A comment that will not be visible in the compiled php file :)</code>
* Dump var

  <code>@dump(var)</code>
* Template inheritance

  <code>@use "mytemplate.html"</code>
* Child blocks

  Add this block in the parent
  
  ```php
  @block achild
  @endblock
  ```
  
  Then, the child must look like
  ```php
  @block achild
      Blabla
  @endblock
  ```
* Conditions (if, elseif, else) - (Only one condition for now)

  ```php
  @if (test)
    <p>test is defined !</p>
  @elseif (!other)
    <p>other is not defined ! </p>
  @endif
  
  --------------------------------
  
  @if (test == 10)
    <p>Test is 10 !</p>
  @elseif (test >= 11)
    <p>Test is bigger or equal to 11</p>
  @else
    <p>Test is less than 10</p>
  @endif
  ```
* Loop (foreach style)

  ```php
  @foreach (users as user)
    <p>@user->name</p>
  @endforeach
  ```
* Basic / Object var

  ```php
  <p>@user->name</p>
  <p>@count</p>
  ```
