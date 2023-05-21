<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function getCode()
    {

        // $this->authorize('isAdmin');

        $tickets = Ticket::select(['id', 'name', 'harga'])->get();

        return $this->sendResponse($tickets, 'Tickets list');
    }

    public function printQR($id)
    {
        $transaction = Transaction::where('id', $id)
            ->first();

        $ticket = Ticket::where('id', $transaction->ticket_id)
            ->first();

        return view('printqr', [
            'ticket' => $transaction,
            'ticket_detail' => $ticket,
        ]);
    }

    public function print_qr($type, $print)
    {
        $tickets = [];
        $transactions = str_replace('[', '', request('transactions'));
        $transactions = str_replace(']', '', $transactions);
        $transactions = explode(',', $transactions);

        foreach ($transactions as $transaction) {
            $tickets[] =   Transaction::where('id', $transaction)->first();
        }

        return view('qrCode', [
            'tickets' => $tickets,
            'type' => $type,
            'print' => $print - 1
        ]);
    }

    public function detailGroup()
    {

        // return response()->json($ticket);
        return view('detailGroup');
    }
    public function detailGroupDua()
    {

        // return response()->json($ticket);
        return view('detailGroupDua');
    }
    public function detailGroupTiga()
    {

        // return response()->json($ticket);
        return view('detailGroupTiga');
    }

    public function detailGroupLast()
    {
        $transaction = Transaction::where('status', 'open')
            ->where('tipe', 'group')
            ->where('gate', 1)
            ->select(['ticket_code', 'amount', 'amount_scanned', 'nama_customer', 'updated_at'])
            ->orderBy('updated_at', 'desc')
            ->first();
        $transaction['time'] = Carbon::parse($transaction->updated_at)->format('d/m/Y H:i:s');

        return response()->json($transaction);
    }

    public function detailGroupLastDua()
    {
        $transaction = Transaction::where('status', 'open')
            ->where('tipe', 'group')
            ->where('gate', 2)
            ->select(['ticket_code', 'amount', 'amount_scanned', 'nama_customer', 'updated_at'])
            ->orderBy('updated_at', 'desc')
            ->first();
        $transaction['time'] = Carbon::parse($transaction->updated_at)->format('d/m/Y H:i:s');

        return response()->json($transaction);
    }
    public function detailGroupLastTiga()
    {
        $transaction = Transaction::where('status', 'open')
            ->where('tipe', 'group')
            ->where('gate', 3)
            ->select(['ticket_code', 'amount', 'amount_scanned', 'nama_customer', 'updated_at'])
            ->orderBy('updated_at', 'desc')
            ->first();
        $transaction['time'] = Carbon::parse($transaction->updated_at)->format('d/m/Y H:i:s');

        return response()->json($transaction);
    }
}
