<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Brian2694\Toastr\Facades\Toastr;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = Permission::orderBy('id', 'desc')->get();
        return view('role-permission.permission.index', ['permissions' => $permissions]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('role-permission.permission.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:permissions,name',
            'group_name' => 'required',
        ]);
        $permission = Permission::create(["name" => strtolower(trim($request->name)), 'group_name' => strtolower(trim($request->group_name))]);
        if ($permission) {
            Toastr::success('New Permission Added Successfully.', 'Success!!');
            return redirect()->route('permission.index');
        }
        //toast('Error on Saving Permission', 'error');
        return back()->withInput();
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
        $permission = Permission::findOrFail($id);
        return view('role-permission.permission.edit', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permission $permission)
    {
        $this->validate($request, [
            'name' => 'required|unique:permissions,name,' . $permission->id,
            'group_name' => 'required',
        ]);

        $permission = $permission->update(["name" => strtolower(trim($request->name)), 'group_name' => strtolower(trim($request->group_name))]);
        if ($permission) {
            Toastr::success('Permission updated Successfully.', 'Success!!');
            return redirect()->route('permission.index');
        }
        return back()->withInput();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();
        Toastr::success('Permission deleted Successfully.', 'Success!!');

        return redirect()->route('permission.index');
    }
}
