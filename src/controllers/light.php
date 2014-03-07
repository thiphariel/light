<?php
/**
 * Demo controller. You can delete this file or use it as base
 * @author Thomas Collot
 */

namespace Light\Src\Controllers;

use Light\App\Core\Controller;

class Light extends Controller
{
    /**
     * Add here all specific routes that your controller have to handle
     */

    public function index()
    {
        $this->template->assign('title', 'Index');

        $this->template->display('light/index.html');
    }
}
