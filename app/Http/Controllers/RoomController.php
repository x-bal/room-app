<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class RoomController extends Controller
{
    public function index()
    {
        $title = 'Data Room';
        $breadcrumbs = ['Master', 'Data Room'];
        $roomtype = RoomType::get();

        return view('room.index', compact('title', 'breadcrumbs', 'roomtype'));
    }

    public function get(Request $request)
    {
        if ($request->ajax()) {
            $data = Room::orderBy('room_name', 'asc')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('action', function ($row) {
                    $actionBtn = '<a href="#modal-dialog" id="' . $row->id . '" class="btn btn-sm btn-success btn-edit" data-route="' . route('room.update', $row->id) . '" data-bs-toggle="modal">Edit</a> <button type="button" data-route="' . route('room.destroy', $row->id) . '" class="delete btn btn-danger btn-delete btn-sm">Delete</button>';
                    return $actionBtn;
                })
                ->editColumn('room_type', function ($row) {
                    return $row->roomtype->room_type;
                })
                ->editColumn('price', function ($row) {
                    return 'Rp. ' . number_format($row->price, 0, ',', '.');
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_type' => 'required|numeric',
            'room_name' => 'required|string',
            'area' => 'required|string',
            'price' => 'required|numeric',
            'facility' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            $room = Room::create([
                'room_type_id' => $request->room_type,
                'room_name' => $request->room_name,
                'area' => $request->area,
                'price' => $request->price,
                'facility' => $request->facility,
            ]);

            DB::commit();

            return back()->with('success', "Room berhasil ditambahkan");
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

    public function show(Room $room)
    {
        return response()->json([
            'room' => $room
        ]);
    }

    public function update(Request $request, Room $room)
    {
        $request->validate([
            'room_type' => 'required|numeric',
            'room_name' => 'required|string',
            'area' => 'required|string',
            'price' => 'required|numeric',
            'facility' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            $room->update([
                'room_type_id' => $request->room_type,
                'room_name' => $request->room_name,
                'area' => $request->area,
                'price' => $request->price,
                'facility' => $request->facility,
            ]);

            DB::commit();

            return back()->with('success', "Room berhasil diupdate");
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

    public function destroy(Room $room)
    {
        try {
            DB::beginTransaction();

            $room->delete();

            DB::commit();

            return back()->with('success', "Room berhasil dihapus");
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }
}
