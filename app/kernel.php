<?php
/**
 * App kernel (handle PDO instance, autoload and more)
 * @author Thomas Collot
 */

namespace Light\App;

use Light\App\Psr4Autoload;

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
     * PDO instance
     */
    private $pdo;
    /**
     * Retrieve active PDO instance
     */
    public function pdo()
    {
        return $this->pdo;
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
        $this->pdo = null;
    }

    /**
     * Init the kernel
     * @see app/config/config.php
     */
    public function init()
    {
        // Include composer autoloader
        require_once _vendor . 'autoload.php';
        
        try {
            $this->pdo = new \PDO(
                'mysql:host='._host.';dbname='._database.';',
                _login,
                _pwd,
                array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8')
            );
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_WARNING);
        } catch (\PDOException $e) {
            if ($this->debug) {
                die($e->getMessage());
            } else {
                die('Oups, there is a problem, Light could not be able to reach the database ...');
            }
        }
    }
}
