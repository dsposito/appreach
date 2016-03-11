<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

/**
 * Serves as the base API controller.
 */
class Controller extends BaseController
{
    /**
     * The dependency-injected request object.
     *
     * @var Request
     */
    protected $request;

    /**
     * Initializes the class.
     *
     * @param Request $request The dependency-injected request object.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
}
