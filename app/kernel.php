<?php
/**
 * App kernel (autoload and more)
 * @author Thomas Collot
 */

namespace Light\App;

use Light\App\Core\PDO;

class Kernel
{
    /**
     * Define the dev mode
     */
    private $debug;
    /**
     * Retrieve the dev mode
     */
    public function debug()
    {
        return $this->debug;
    }

    /**
     * Current version number (will be used in the future, for stability)
     */
    const VERSION = '0.0.1';

    /**
     * Kernel constructor, define dev mode here
     * @param bool
     */
    public function __construct($debug = false)
    {
        $this->debug = $debug;
    }

    /**
     * Init the kernel
     * @see app/config/config.php
     */
    public function init()
    {
        // Include composer autoloader
        require_once _vendor . 'autoload.php';
    }
}
