<?php

namespace App\Http\Controllers;

use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class RoomTypeController extends Controller
{
    public function index()
    {
        $title = 'Data Room Type';
        $breadcrumbs = ['Master', 'Data Room Type'];

        return view('room-type.index', compact('title', 'breadcrumbs'));
    }

    public function get(Request $request)
    {
        if ($request->ajax()) {
            $data = RoomType::orderBy('room_type', 'asc')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<a href="#modal-dialog" id="' . $row->id . '" class="btn btn-sm btn-success btn-edit" data-route="' . route('type-room.update', $row->id) . '" data-bs-toggle="modal">Edit</a> <button type="button" data-route="' . route('type-room.destroy', $row->id) . '" class="delete btn btn-danger btn-delete btn-sm">Delete</button>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_type' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            $roomtype = RoomType::create([
                'room_type' => $request->room_type,
            ]);

            DB::commit();

            return back()->with('success', "Room type berhasil ditambahkan");
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

    public function show(RoomType $roomType)
    {
        return response()->json([
            'roomtype' => $roomType
        ]);
    }

    public function update(Request $request, RoomType $roomType)
    {
        $request->validate([
            'room_type' => 'required|string'
        ]);

        try {
            DB::beginTransaction();

            $roomType->update([
                'room_type' => $request->room_type,
            ]);

            DB::commit();

            return back()->with('success', "Room type berhasil diupdate");
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

    public function destroy(RoomType $roomType)
    {
        try {
            DB::beginTransaction();

            $roomType->delete();

            DB::commit();

            return back()->with('success', "Room type berhasil dihapus");
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }
}
