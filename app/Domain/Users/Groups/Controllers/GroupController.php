<?php

namespace App\Domain\Users\Groups\Controllers;

use App\Domain\Users\Groups\Group;
use App\Domain\Users\Groups\Requests\GroupStoreRequest;
use App\Domain\Users\Requests\StoreRequest;
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
}
