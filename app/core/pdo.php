<?php
/**
 * Handle PDO instance
 * @author Thomas Collot
 */

namespace Light\App\Core;

class PDO extends \PDO
{
    /**
     * PDO instance
     */
    private static $pdo;
    /**
     * Singleton
     *
     * @return \Light\App\Core\PDO
     */
    public static function singleton()
    {
        // If an instance is not already exist, just create it
        if (!isset(self::$pdo)) {
            $pdo = new self();
        }

        return self::$pdo;
    }

    /**
     * Init the PDO instance
     *
     * @see app/config/config.php
     */
    public function __construct()
    {
        try {
            self::$pdo = new \PDO(
                'mysql:host='._host.';dbname='._database.';',
                _login,
                _pwd,
                array(self::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8')
            );
            self::$pdo->setAttribute(self::ATTR_ERRMODE, self::ERRMODE_WARNING);
        } catch (\PDOException $e) {
            throw $e;
        }
    }
}
