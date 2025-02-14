<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\DB; 

class UserController extends Controller
{
    // ...
    public function index()
    {
        // Eloquent
        $users = User::when(request()->has('username'), function ( $query) {
            $query->where('username', 'Like', '%'.request()->input('username').'%');
        })->when(request()->has('email'), function ( $query) {
            $query->where('email', 'Like', '%'.request()->input('email').'%');
        })
        ->paginate(request()->per_page);


        //$users = DB::table('users')->get();
        return UserResource::collection($users);
    }
    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Str::random(8);

        $user = User::create($data);

        return response()->json(UserResource::make($user), 201);
    }
    public function show(User $user)
    {
        return response()->json(UserResource::make($user),0);
    }

    public function update(StoreUserRequest $request, User $user)
    {
        $data = $request->validated();
        $user->update($data);
        return response()->json(UserResource::make($user),0);
    }
}