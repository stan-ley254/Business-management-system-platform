<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;


class UserManagementController extends Controller
{
    //


public function index()
{
    $users = User::where('business_id', auth()->user()->business_id)
                 ->where('id', '!=', auth()->id())
                 ->get();

    return view('admin.users.index', compact('users'));
}

public function create()
{
    return view('admin.users.create');
}

public function store(Request $request)
{
    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6|confirmed',
    ]);
    $userRole = Role::where('name', 'user')->first();

    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'business_id' => auth()->user()->business_id,
        'role_id' => $userRole->id,// Example: cashier or normal user
    ]);

    return redirect()->back()->with('success', 'User added.');
}

public function destroy(User $user)
{
    if ($user->business_id === auth()->user()->business_id) {
        $user->delete();
    }

    return back()->with('success', 'User deleted.');
}

}
