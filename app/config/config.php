<?php
/**
 * App configuration file
 * @author Thomas Collot
 */

use Light\App\Core\Lightemplate;

/**
 * Define some vars used for database connection
 */
define('_HOST', '127.0.0.1', true);
define('_DATABASE', 'light', true);
define('_LOGIN', 'root', true);
define('_PWD', '', true);

/**
 * Lightemplate configuration
 */
Lightemplate::base('base');                     // Base url var name
Lightemplate::assets('assets');                 // Assets var name
Lightemplate::dir(_src . 'views/');             // Template file directory
/**
 * Cache file directory / time in cache (in seconds) - 
 * @param string  cache directory path (@nullable -> Light wont use cache system)
 * @param int     time to cache (in seconds)
 */
Lightemplate::cache(null, 0);
