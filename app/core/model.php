<?php
/**
 * Handle database communication
 * @author Thomas Collot
 */

namespace Light\App\Core;

use Light\App\Kernel;

class Model
{
    protected $table = null;
    protected $pdo;

    public function __construct(Kernel $kernel)
    {
        $this->table = basename(strtolower(get_class($this))) . 's';
        $this->pdo = PDO::singleton();
    }

    public function select()
    {
        try {
            $sql = 'select * from ' . $this->table;

            $query = $this->pdo->prepare($sql);
            $query->execute();

            return $query->fetchAll(\PDO::FETCH_OBJ);
        } catch (\Exception $e) {
            die($e);
        }
    }
}
