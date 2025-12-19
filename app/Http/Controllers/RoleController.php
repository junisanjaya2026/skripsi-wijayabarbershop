<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::all();
        return view('admin.role.index', compact('roles')); 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.role.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       
        $request->validate([
        'role_name'   => 'required|string',
        'description'       => 'required|string',
    ]);

    Role::create([
        'role_name'   => $request->role_name,
        'description' => $request->description,
    ]);

    return redirect()->route('admin.role.index')
        ->with('success', 'Role berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
              $role = Role::findOrFail($id);
            // $roles = Role::all();

    return view('admin.role.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $role = Role::findOrFail($id);

    $request->validate([
        'role_name'   => 'required|string',
        'description'       => 'required|string',
    ]);


    // Update data lainnya
    $role->update([
        'role_name'   => $request->role_name,
        'description' => $request->description
    ]);

    return redirect()->route('admin.role.index')
        ->with('success', 'Role berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::findOrFail($id);


    // Hapus data item
    $role->delete();
    return redirect()->route('admin.role.index')
        ->with('success', 'Role berhasil dihapus');
    }
}
