<?php
declare(strict_types=1);

namespace App\Http\Controllers\SALOP\API;

use App\Http\Controllers\Controller;
use App\Models\SALOP\Extension;

class ExtensionsController extends Controller
{
    public function numbers()
    {
        $numbers = Extension::get(['id','number']);
        return $numbers;
    }

    public function full()
    {
        $extensions = Extension::get(['id','number','password']);
        return $extensions;
    }
}
