<?php 
/**
 * Handle model loading and init the template engine
 * @author Thomas Collot
 */

namespace Light\App\Core;

use Light\App\Kernel;

class Controller
{
    protected $request;
    protected $kernel;
    protected $template;

    /**
     * Contruct the controller object with the Request and init the template engine
     * @param Request $request
     * @param Kernel $kernel
     **/
    public function __construct(Request $request, Kernel $kernel)
    {
        $this->request = $request;
        $this->kernel = $kernel;
        $this->template = new Lightemplate();
    }

    /**
     * Handle model loading
     **/
    public function model($name)
    {
        $class = '\Light\Src\Models\\' . $name;

        try {
            $this->$name = new $class($this->kernel);
        } catch (\Exception $e) {
            if ($this->kernel->debug()) {
                die($e->getMessage());
            }
        }
    }
}
