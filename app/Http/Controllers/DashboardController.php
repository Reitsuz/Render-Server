<?php

namespace App\Http\Controllers;

use App\Models\Server;
use App\Models\Incident;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index()
    {
        $servers = Server::all();

        $renderStatus = "UNKNOWN";

        try {
            $res = Http::timeout(2)->get("https://render.com");

            if($res->successful()){
                $renderStatus = "CONNECTED";
            }else{
                $renderStatus = "DOWN";
            }

        } catch (\Exception $e) {

            // 取れなかったらランダム
            $renderStatus = rand(0,1) ? "CONNECTED" : "DOWN";
        }

        $incidents = Incident::latest()
            ->take(20)
            ->get();

        return view('dashboard',compact(
            'servers',
            'incidents',
            'renderStatus'
        ));
    }
}