<?php

namespace App\Domain\Users\Controllers;

use App\Domain\Users\Requests\StoreRequest;
use App\Domain\Users\User;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpFoundation\Response;

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
     * A validation request should be created to ensure thar the user exists
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id): \Illuminate\Http\Response
    {
        User::destroy($id);

        return Response('', Response::HTTP_NO_CONTENT);
    }
}
