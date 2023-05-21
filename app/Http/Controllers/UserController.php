<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    public function index()
    {
        $title = 'Data User';
        $breadcrumbs = ['Master', 'Data User'];

        return view('user.index', compact('title', 'breadcrumbs'));
    }

    public function get(Request $request)
    {
        if ($request->ajax()) {
            $data = User::orderBy('name', 'asc')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<a href="#modal-dialog" id="' . $row->id . '" class="btn btn-sm btn-success btn-edit" data-route="' . route('users.update', $row->id) . '" data-bs-toggle="modal">Edit</a> <button type="button" data-route="' . route('users.destroy', $row->id) . '" class="delete btn btn-danger btn-delete btn-sm">Delete</button>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|unique:users',
            'name' => 'required|string',
            'password' => 'required|string',
            'level' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            $user = User::create([
                'username' => $request->username,
                'name' => $request->name,
                'level' => $request->level,
                'password' => bcrypt($request->password),
            ]);

            DB::commit();

            return back()->with('success', "User berhasil ditambahkan");
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

    public function show(User $user)
    {
        return response()->json([
            'user' => $user
        ]);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'username' => 'required|string|unique:users,username,' . $user->id,
            'name' => 'required|string',
            'level' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            $user->update([
                'username' => $request->username,
                'name' => $request->name,
                'level' => $request->level,
                'password' => bcrypt($request->password),
            ]);

            DB::commit();

            return back()->with('success', "User berhasil diupdate");
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

    public function destroy(User $user)
    {
        try {
            DB::beginTransaction();

            $user->delete();

            DB::commit();

            return back()->with('success', "User berhasil dihapus");
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }
}
