<?php

namespace App\Http\Controllers;

use App\Models\DetailTransaction;
use App\Models\ExtraChange;
use App\Models\Room;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class DetailTransactionController extends Controller
{
    public function index()
    {
        //
    }

    public function get(Request $request, $id)
    {
        if ($request->ajax()) {
            $data = DetailTransaction::where('transaction_id', $id)->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('action', function ($row) {
                    $actionBtn = '<a href="#modal-dialog" id="' . $row->id . '" class="btn btn-sm btn-success btn-edit" data-route="' . route('room.update', $row->id) . '" data-bs-toggle="modal">Edit</a> <button type="button" data-route="' . route('room.destroy', $row->id) . '" class="delete btn btn-danger btn-delete btn-sm">Delete</button>';
                    return $actionBtn;
                })
                ->editColumn('extra', function ($row) {
                    return $row->extra->name;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $transaction = Transaction::find($request->transaction);
            $extra = ExtraChange::find($request->extra);
            $room = Room::find($request->room);

            DetailTransaction::create([
                'transaction_id' => $request->transaction,
                'extra_change_id' => $request->extra,
                'room_id' => $request->room,
            ]);

            $transaction->update([
                'total_room_price' => $room->price,
                'total_extra_change' => $transaction->total_extra_change + ($extra->price * $request->qty),
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => "Berhasil"
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DetailTransaction  $detailTransaction
     * @return \Illuminate\Http\Response
     */
    public function show(DetailTransaction $detailTransaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DetailTransaction  $detailTransaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DetailTransaction $detailTransaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DetailTransaction  $detailTransaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(DetailTransaction $detailTransaction)
    {
        //
    }
}
