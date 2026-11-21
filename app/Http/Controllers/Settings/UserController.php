<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Concerns\HandlesRestore;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdatePasswordUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function __construct(protected UserService $service) {}

    public function index(Request $request)
    {
        if (! $request->expectsJson() && ! $request->ajax()) {
            return view('pages.settings.user.index');
        }
        return $this->service->getData($request);
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        return $this->service->getStore($request->validated());
    }

    public function updatePassword(UpdatePasswordUserRequest $request, User $user): JsonResponse
    {
        return $this->service->getUpdatePassword(
            $user,
            $request->validated()
        );
    }


    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        return $this->service->getUpdate($user, $request->validated());
    }

    public function destroy(Request $request, User $user): JsonResponse
    {
        return $this->service->getDestroy($request, $user);
    }

    public function restore(int $id)
    {
        return HandlesRestore::restore(User::onlyTrashed()->findOrFail($id));
    }
}
