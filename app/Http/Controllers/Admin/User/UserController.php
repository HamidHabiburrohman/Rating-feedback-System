<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.users.index');
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        // TODO: Simpan user/admin baru
    }

    public function show(string $id)
    {
        return view('admin.users.show');
    }

    public function edit(string $id)
    {
        return view('admin.users.edit');
    }

    public function update(Request $request, string $id)
    {
        // TODO: Update data user/admin
    }

    public function destroy(string $id)
    {
        // TODO: Hapus user/admin
    }

    public function toggleStatus(string $id)
    {
        // TODO: Toggle status aktif/non-aktif user
    }
}