<?php

namespace App\Http\Actions;

use App\Models\User;

class GetUsersAction
{
    public function execute($request)
    {
        $users = User::query()->with('role');

        if ($request->query('role')) {
            $users = $users->where('role_id', $request->query('role'));
        }

        return $users->get();
    }
}