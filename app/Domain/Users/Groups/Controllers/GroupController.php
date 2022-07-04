<?php

namespace App\Domain\Users\Groups\Controllers;

use App\Domain\Users\Groups\Group;
use App\Domain\Users\Groups\Requests\AddUserRequest;
use App\Domain\Users\Groups\Requests\GroupStoreRequest;
use App\Domain\Users\User;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
     *
     * @param Group $group
     * @return JsonResponse
     */
    public function destroy(Group $group): JsonResponse
    {
        if (!$group->users->isEmpty()) {
            $usersCount = $group->users->count();
            return \response()->json(['error' => "The group {$group->id} cannot be deleted because it contains $usersCount users"], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        Group::destroy($group->id);

        return \response()->json('', Response::HTTP_NO_CONTENT);
    }

    /**
     * @param AddUserRequest $request
     * @param Group $group
     * @return JsonResponse
     * @throws \JsonException
     */
    public function addUser(AddUserRequest $request, Group $group): JsonResponse
    {
        $user = User::findOrFail($request->user_id);

        if ($group->users->contains($user)) {
            return \response()->json(['error' => "User {$user->id} is already part of the group {$group->id}"], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $group->users()->attach($user);

        return \response()->json(['success' => "User {$user->id} added to group {$group->id}"]);
    }

    /**
     * @param Request $request
     * @param Group $group
     * @param User $user
     * @return JsonResponse
     */
    public function removeUser(Request $request, Group $group, User $user): JsonResponse
    {
        if (!$group->users->contains($user)) {
            return \response()->json(['error' => "User {$user->id} is not part of the group {$group->id}"], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $group->users()->detach($user);

        return \response()->json(['success' => "User {$user->id} has been removed from the group {$group->id}"]);
    }
}
