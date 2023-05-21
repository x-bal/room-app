<?php

namespace App\Http\Controllers;

use App\Models\ExtraChange;
use App\Models\Room;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class TransactionController extends Controller
{
    public function index()
    {
        if (auth()->user()->level == 'admin') {
            $title = 'Data Transaction';
            $breadcrumbs = ['Master', 'Data Transaction'];

            return view('transaction.index', compact('title', 'breadcrumbs'));
        } else {
            $title = 'Order Room';
            $breadcrumbs = ['Master', 'Order Room'];

            return view('transaction.index', compact('title', 'breadcrumbs'));
        }
    }

    public function get(Request $request)
    {
        if ($request->ajax()) {
            if (auth()->user()->level == 'admin') {
                if ($request->from || $request->to) {
                    $to = Carbon::parse($request->to)->addDay(1)->format('Y-m-d');

                    $data = Transaction::where(['is_finish' => 1])->whereBetween('transdate', [$request->from, $to])->get();
                } else {
                    $data = Transaction::where(['is_finish' => 1])->get();
                }
            } else {
                $data = Transaction::where(['user_id' => auth()->user()->id, 'is_finish' => 1])->get();
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<a href="' . route('transaction.show', $row->id) . '" id="' . $row->id . '" class="btn btn-sm btn-info btn-edit">Detail</a>';

                    if (auth()->user()->level == 'admin') {
                        $actionBtn .= ' <button type="button" data-route="' . route('extra-change.destroy', $row->id) . '" class="delete btn btn-danger btn-delete btn-sm">Delete</button>';
                    }
                    return $actionBtn;
                })
                ->editColumn('transdate', function ($row) {
                    return Carbon::parse($row->transdate)->format('d/m/Y');
                })
                ->editColumn('total_room_price', function ($row) {
                    return 'Rp. ' . number_format($row->total_room_price, 0, ',', '.');
                })
                ->editColumn('total_extra_change', function ($row) {
                    return 'Rp. ' . number_format($row->total_extra_change, 0, ',', '.');
                })
                ->editColumn('final_total', function ($row) {
                    return 'Rp. ' . number_format($row->final_total, 0, ',', '.');
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function create()
    {
        $title = 'Add New Order Room';
        $breadcrumbs = ['Master', 'Add New Order Room'];

        $room = Room::get();
        $extraChange = ExtraChange::get();
        $transaction = Transaction::where(['user_id' => auth()->user()->id, 'is_finish' => 0])->first();

        if (!$transaction) {
            $transaction = Transaction::create([
                'user_id' => auth()->user()->id,
                'transcode' => date('YmdHis') . rand(1000, 9999),
                'transdate' => Carbon::now()->format('Y-m-d')
            ]);
        }

        return view('transaction.create', compact('title', 'breadcrumbs', 'transaction', 'room', 'extraChange'));
    }

    public function store(Request $request)
    {
        //
    }


    public function show(Transaction $transaction)
    {
        $title = 'Detail Transaction';
        $breadcrumbs = ['Master', 'Detail Transaction'];

        return view('transaction.show', compact('title', 'breadcrumbs', 'transaction'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $request->validate([
            'custname' => 'required|string'
        ]);

        try {
            DB::beginTransaction();

            $transaction->update([
                'custname' => $request->custname,
                'final_total' => $transaction->total_room_price + $transaction->total_extra_change,
                'is_finish' => 1
            ]);

            DB::commit();

            return redirect()->route('transaction.index')->with('success', "Transaction success");
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

    public function destroy(Transaction $transaction)
    {
        //
    }

    public function getRoom(Request $request, Transaction $transaction)
    {
        if ($request->ajax()) {
            $data = $transaction->rooms()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('action', function ($row) {
                    $actionBtn = '<a href="#modal-dialog" id="' . $row->id . '" class="btn btn-sm btn-primary btn-extra" data-bs-toggle="modal" data-route="' . route('transaction.extra', $row->id) . '"> Add Extra Change</a> <button type="button" data-route="' . route('transaction.delete-room', $row->id) . '" class="delete btn btn-danger btn-delete btn-sm">Delete</button>';
                    return $actionBtn;
                })
                ->editColumn('day', function ($row) {
                    return $row->pivot->day;
                })
                ->editColumn('extra', function ($row) {
                    $extra = DB::table('extra_change_room')->where(['room_id' => $row->pivot->room_id, 'transaction_id' => $row->pivot->transaction_id])->get();
                    $list = '<span>';
                    foreach ($extra as $ex) {
                        $list .= ExtraChange::find($ex->extra_change_id)->name . ' x ' . $ex->qty . ' = ' . 'Rp. ' . number_format(ExtraChange::find($ex->extra_change_id)->price * $ex->qty, 0, ',', '.') . '</br>';
                    }
                    $list .= '</span>';

                    return $list;
                })
                ->rawColumns(['action', 'extra'])
                ->make(true);
        }
    }

    public function room(Request $request, Transaction $transaction)
    {
        try {
            DB::beginTransaction();

            $transaction->rooms()->attach($request->room, ['day' => $request->day]);
            $room = Room::find($request->room);
            $transaction->update([
                'total_room_price' => $transaction->total_room_price + ($room->price * $request->day)
            ]);
            DB::commit();

            return response()->json([
                'status' => 'success'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);
        }
    }

    public function deleteRoom(Room $room)
    {
        try {
            DB::beginTransaction();

            $transaction = Transaction::find(request('transaction_id'));
            $details = DB::table('extra_change_room')->where(['room_id' => $room->id, 'transaction_id' => $transaction->id])->delete();

            $transaction->rooms()->detach($room->id);

            DB::commit();

            return back();
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function extra(Request $request, Room $room)
    {
        try {
            DB::beginTransaction();

            DB::table('extra_change_room')->insert([
                'room_id' => $room->id,
                'extra_change_id' => $request->extra,
                'transaction_id' => $request->transaction_id,
                'qty' => $request->qty
            ]);

            $transaction  = Transaction::find($request->transaction_id);
            $extra = ExtraChange::find($request->extra);

            $transaction->update([
                'total_extra_change' => $transaction->total_extra_change + ($extra->price * $request->qty)
            ]);
            DB::commit();

            return back();
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }
}
