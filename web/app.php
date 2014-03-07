<?php
/**
 * Main app file
 * @author Thomas Collot
 */

use Light\App\Kernel;
use Light\App\Core\Request;
use Light\App\Core\Response;

/**
 * Needed vars on the entierly app
 */
define('_ROOT', dirname(dirname(__FILE__)), true);          // root directory
define('_APP', _root . '/app/', true);                      // app directory
define('_CORE', _app . '/core/', true);                     // core directory
define('_SRC', _root . '/src/', true);                      // src directory
define('_VENDOR', _root . '/vendor/', true);
define('_WEB', _root . '/web/', true);                      // web directory

define('_BASE', '/' . basename(dirname(__DIR__)), true);          // base url
define('_ASSETS', _base . '/web', true);                          // assets url

/**
 * Kernel loading
 */
require_once _app . 'kernel.php';

/**
 * Init the app
 */
$kernel = new Kernel(true);
$kernel->init();

$request = new Request();
$response = new Response($request, $kernel);
$response->send();
