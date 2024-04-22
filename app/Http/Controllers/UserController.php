<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
	public function index(Request $request)
	{
		$users = User::with('roles')->get();
		if (!$request->ajax()) return view('users.index', compact('users'));
		return response()->json(['users' => $users], 200);
	}

	public function getAllDT()
	{
		$users = User::with('roles')->query();
		return DataTables::of($users)->toJson();
	}

	public function store(UserRequest $request)
	{
		$user = new User($request->all());
		$user->save();
		$user->assignRole($request->role);
		if (!$request->ajax()) return back()->with('success', 'User created successfully');
		return response()->json(['status' => 'User created successfully', 'user' => $user], 201);
	}

	public function show(Request $request, User $user)
	{
		if (!$request->ajax()) return view();
		return response()->json(['user' => $user], 200);
	}

	public function update(UserRequest $request, User $user)
	{
		$data = $request->all();
		//Remove password from request if not submitted
		if (!$request->filled('password')) {
			unset($data['password'], $data['password_confirmation']);
		}
		$user->update($data);
		$user->syncRoles([$request->role]);
		if (!$request->ajax()) return back()->with('success', 'User updated successfully');
		return response()->json([], 204);
	}

	public function destroy(Request $request, User $user)
	{
		$user->delete();
		if (!$request->ajax()) return back()->with('success', 'User deleted successfully');
		return response()->json([], 204);
	}
}
