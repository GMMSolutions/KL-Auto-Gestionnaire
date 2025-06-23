<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VehicleController extends Controller
{
    /**
     * Display a listing of the vehicles.
     */
    public function index(): View
    {
        $vehicles = Vehicle::latest()->get();
        return view('vehicles.index', compact('vehicles'));
    }
}
