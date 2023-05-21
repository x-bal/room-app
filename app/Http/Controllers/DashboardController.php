<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Dashboard';
        $breadcrumbs = ['Dashboard'];
        $type = 0;
        $roomprice = 0;
        $extraprice = 0;
        $final = 0;

        if (auth()->user()->level == 'admin') {

            if ($request->from || $request->to) {
                $to = Carbon::parse($request->to)->addDay(1)->format('Y-m-d');

                $type = Transaction::whereBetween('transdate', [$request->from, $to])->where('is_finish', 1)->count();
                $roomprice = Transaction::whereBetween('transdate', [$request->from, $to])->sum('total_room_price');
                $extraprice = Transaction::whereBetween('transdate', [$request->from, $to])->sum('total_extra_change');
                $final = Transaction::whereBetween('transdate', [$request->from, $to])->sum('final_total');
            } else {
                $type = Transaction::where('is_finish', 1)->count();
                $roomprice = Transaction::sum('total_room_price');
                $extraprice = Transaction::sum('total_extra_change');
                $final = Transaction::sum('final_total');
            }
        }

        return view('dashboard.index', compact('title', 'breadcrumbs', 'type', 'roomprice', 'extraprice', 'final'));
    }
}
