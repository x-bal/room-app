<?php

namespace App\Http\Controllers;

use App\Http\Requests\Transaction\CreateTransactionRequest;
use App\Models\Ticket;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class TransactionController extends Controller
{
    public function index()
    {
        $title = 'Data Transaction';
        $breadcrumbs = ['Master', 'Data Transaction'];
        $tickets = Ticket::get();

        return view('transaction.index', compact('title', 'breadcrumbs', 'tickets'));
    }

    public function get(Request $request)
    {
        if ($request->ajax()) {
            $data = Transaction::get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<a href="#modal-dialog" id="' . $row->id . '" class="btn btn-sm btn-success btn-edit" data-route="' . route('tickets.update', $row->id) . '" data-bs-toggle="modal">Edit</a> <button type="button" data-route="' . route('tickets.destroy', $row->id) . '" class="delete btn btn-danger btn-delete btn-sm">Delete</button>';
                    return $actionBtn;
                })
                ->addColumn('ticket', function ($row) {
                    return $row->ticket->name;
                })
                ->addColumn('harga', function ($row) {
                    return 'Rp. ' . number_format($row->ticket->harga, 0, ',', '.');
                })
                ->editColumn('harga_ticket', function ($row) {
                    return 'Rp. ' . number_format($row->harga_ticket, 0, ',', '.');
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function create()
    {
        $title = 'Add Transaction';
        $breadcrumbs = ['Master', 'Add Transaction'];
        $action = route('transactions.store');
        $method = 'POST';
        $transaction = new Transaction();

        return view('transaction.form', compact('title', 'breadcrumbs', 'action', 'method', 'transaction'));
    }

    public function store(CreateTransactionRequest $request)
    {
        try {
            DB::beginTransaction();

            $attr = $request->except('name', 'ticket', 'type_customer', 'print', 'jumlah');
            $attr['ticket_id'] = $request->ticket;
            $attr['tipe'] = $request->type_customer;
            $attr['nama_customer'] = $request->name;
            $attr['user_id'] = auth()->user()->id;

            $attr['ticket_code'] = 'TKT' . Carbon::now('Asia/Jakarta')->format('dmY') . rand(1000, 9999);

            $transaction = Transaction::create($attr);

            DB::commit();

            return redirect()->route('transactions.index')->with('success', "Transaction berhasil ditambahkan");
        } catch (\Throwable $th) {
            return $th->getMessage();
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

    public function show(Transaction $transaction)
    {
        return response()->json([
            'status' => 'success',
            'ticket' => $transaction
        ], 200);
    }

    public function edit(Transaction $transaction)
    {
        $title = 'Edit Transaction';
        $breadcrumbs = ['Master', 'Edit Transaction'];
        $action = route('transactions.update', $transaction->id);
        $method = 'PUT';

        return view('transaction.form', compact('title', 'breadcrumbs', 'action', 'method', 'transaction'));
    }

    public function update(CreateTransactionRequest $request, Transaction $transaction)
    {
        try {
            DB::beginTransaction();

            $transaction->update($request->all());

            DB::commit();

            return redirect()->route('transactions.index')->with('success', "Transaction berhasil diupdate");
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

    public function destroy(Transaction $transaction)
    {
        try {
            DB::beginTransaction();

            $transaction->delete();

            DB::commit();

            return redirect()->route('transaction.index')->with('success', "Transaction berhasil dihapus");
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }
}
