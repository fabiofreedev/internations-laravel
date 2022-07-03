<?php

namespace App\Domain\Users\Controllers;

use App\Domain\Users\Requests\StoreRequest;
use App\Domain\Users\User;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Collection
     */
    public function index(): Collection
    {
        return User::with('role')->get();
    }

    /**
     * @param StoreRequest $request
     * @return User
     */
    public function store(StoreRequest $request): User
    {
        return User::create($request->validated());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
