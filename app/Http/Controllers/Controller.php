<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    // Base Controller sekarang mendukung method middleware() & authorizeResource()
}