<?php

namespace App\Domain\Users\Groups\Controllers;

use App\Domain\Users\Groups\Group;
use App\Domain\Users\Groups\Requests\AddUserRequest;
use App\Domain\Users\Groups\Requests\GroupStoreRequest;
use App\Domain\Users\User;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpFoundation\Response;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Collection
     */
    public function index(): Collection
    {
        return Group::all();
    }

    /**
     * @param GroupStoreRequest $request
     * @return Group
     */
    public function store(GroupStoreRequest $request): Group
    {
        return Group::create($request->validated());
    }

    /**
     * A validation request should be created to ensure thar the group exists
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id): \Illuminate\Http\Response
    {
        Group::destroy($id);

        return Response('', Response::HTTP_NO_CONTENT);
    }

    /**
     * @param AddUserRequest $request
     * @param Group $group
     * @return \Illuminate\Http\Response
     */
    public function addUser(AddUserRequest $request, Group $group): \Illuminate\Http\Response
    {
        $user = User::findOrFail($request->user_id);

        $group->users()->attach($user);

        return \response("User {$user->id} added to group {$group->id}", Response::HTTP_OK);
    }
}
