<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
	public function getAll()
	{
		$roles = Role::all()->pluck('name');
		return response()->json(['roles' => $roles], 200);
	}
}
