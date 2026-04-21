<?php

namespace App\Http\Controllers;

use App\Models\Server;
use App\Models\Incident;

class DashboardController extends Controller
{
    public function index()
    {
        $servers = Server::all();

        $incidents = Incident::latest()
            ->take(20)
            ->get();

        return view('dashboard', compact(
            'servers',
            'incidents'
        ));
    }
}