<?php
declare(strict_types=1);

namespace App\Http\Controllers;

class TheatersOfOperationsPanelController extends Controller
{
    public function index()
    {
        return redirect()->route('theaters_of_operations.map');
    }

    public function map()
    {
        return view('theaters_of_operations.map');
    }

    public function list()
    {
        return view('theaters_of_operations.list');
    }

    public function create()
    {
        return view('theaters_of_operations.edit', ['theater_of_operations' => null]);
    }

    public function timetape()
    {
        return view('theaters_of_operations.timetape');
    }
}
