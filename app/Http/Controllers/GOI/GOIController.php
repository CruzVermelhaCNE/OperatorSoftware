<?php
declare(strict_types=1);

namespace App\Http\Controllers\GOI;

use App\Http\Controllers\Controller;

class GOIController extends Controller
{
    public function index()
    {
        return redirect()->route('goi.map');
    }

    public function map()
    {
        return view('goi.map');
    }

    public function list()
    {
        return view('goi.list');
    }

    public function create()
    {
        return view('goi.edit', ['theater_of_operations' => null]);
    }

    public function timetape()
    {
        return view('goi.timetape');
    }
}
