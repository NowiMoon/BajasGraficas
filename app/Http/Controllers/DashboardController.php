<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use App\Models\Alumno;

class DashboardController extends Controller
{
    public function index()
    {
        $alumnos = Alumno::all();
        return view('dashboard', compact('alumnos'));
    }
}

