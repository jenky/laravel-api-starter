<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\ApiController;
use App\Http\Requests\API\v1\UserRequest;
use App\Models\User;
use App\Contracts\Repositories\UserRepository;

/**
 * @Resource("Users", uri="/users")
 */
class UsersController extends ApiController
{
    /**
     * The resource name.
     * 
     * @var string
     */
    protected $resource = 'user';

    /**
     * Display a listing of the resource.
     *
     * @param \App\Models\User $user
     * 
     * @return \Illuminate\Http\Response
     * 
     * @Get("/")
     * @Versions({"v1"})
     */
    public function index(User $user)
    {
        return response()->json(apihelper($user)->collection());
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
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\API\v1\UserRequest $request
     * @param \App\Contracts\Repositories\UserRepository $userRepo
     *
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request, UserRepository $userRepo)
    {
        $userRepo->create($request);

        return $this->response->created();
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\User $user
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show(User $user, $id)
    {
        return $this->findResource($user, $id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\API\v1\UserRequest $request
     * @param \App\Models\User $user
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, User $user, $id)
    {
        $data = $request->except('password');
        if ($request->input('password')) {
            $data['password'] = bcrypt($request->input('password'));
        }

        return $this->updateResource($user, $id, $data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\User $user
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user, $id)
    {
        return $this->deleteResource($user, $id);
    }
}
