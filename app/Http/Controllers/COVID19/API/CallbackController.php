<?php
declare(strict_types=1);

namespace App\Http\Controllers\COVID19\API;

use App\Http\Controllers\Controller;
use App\Models\COVID19\Callback;

class CallbackController extends Controller
{
    public function callbacks()
    {
        return Callback::where('called_back', '=', false)->paginate();
    }
}
