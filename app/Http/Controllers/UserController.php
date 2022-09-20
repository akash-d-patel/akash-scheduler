<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\User\CreateRequest;
use App\Http\Requests\User\EditRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends BaseController
{
    public function __construct()
    {
        $this->authorizeResource(User::class);
        $this->getMiddleware();
    }
    /**
     * List
     * @group User
     */
    public function index(Request $request)
    {
        $message = "All records";
        $users = User::with(['creater'])->pimp()->paginate();
        UserResource::collection($users);
        return $this->sendResponse(compact('users'), $message);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Add
     * @group User
     */
    public function store(CreateRequest $request)
    {
        $user = User::createUpdate(new User, $request);
        $message = "User added successfully";
        $user = new UserResource($user);
        return $this->sendResponse(compact('user'), $message);
    }

    /**
     * Show
     * @group User
     */
    public function show(User $user, Request $request)
    {
        $message = "Users listed succesfully!!";
        $user = new UserResource($user);
        return $this->sendResponse(compact('user'), $message);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update
     * @group User
     */
    public function update(EditRequest $request, User $user)
    {
        $user = User::createUpdate($user, $request);
        $message = "User updated successfully!!";
        $user = new UserResource($user);
        return $this->sendResponse(compact('user'), $message);
    }

    /**
     * Delete
     * @group User
     */
    public function destroy(User $user, Request $request)
    {
        $user->delete();
        $message = "User deleted successfully!!";
        $user = new UserResource($user);
        return $this->sendResponse(compact('user'), $message);
    }
}
