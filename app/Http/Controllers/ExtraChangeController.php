<?php

namespace App\Http\Controllers;

use App\Models\ExtraChange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ExtraChangeController extends Controller
{
    public function index()
    {
        $title = 'Data Extra Change';
        $breadcrumbs = ['Master', 'Data Extra Change'];

        return view('extra-change.index', compact('title', 'breadcrumbs'));
    }

    public function get(Request $request)
    {
        if ($request->ajax()) {
            $data = ExtraChange::orderBy('name', 'asc')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<a href="#modal-dialog" id="' . $row->id . '" class="btn btn-sm btn-success btn-edit" data-route="' . route('extra-change.update', $row->id) . '" data-bs-toggle="modal">Edit</a> <button type="button" data-route="' . route('extra-change.destroy', $row->id) . '" class="delete btn btn-danger btn-delete btn-sm">Delete</button>';
                    return $actionBtn;
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
            'name' => 'required|string',
            'price' => 'required|numeric',
        ]);

        try {
            DB::beginTransaction();

            $extraChange = ExtraChange::create([
                'name' => $request->name,
                'price' => $request->price,
            ]);

            DB::commit();

            return back()->with('success', "Extra change berhasil ditambahkan");
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

    public function show(ExtraChange $extraChange)
    {
        return response()->json([
            'extrachange' => $extraChange
        ]);
    }

    public function update(Request $request, ExtraChange $extraChange)
    {
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
        ]);

        try {
            DB::beginTransaction();

            $extraChange->update([
                'name' => $request->name,
                'price' => $request->price,
            ]);

            DB::commit();

            return back()->with('success', "Extra change berhasil diupdate");
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

    public function destroy(ExtraChange $extraChange)
    {
        try {
            DB::beginTransaction();

            $extraChange->delete();

            DB::commit();

            return back()->with('success', "Extra change berhasil dihapus");
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }
}
