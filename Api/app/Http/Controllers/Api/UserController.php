<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\UserException;
use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\UserRecoverPasswordRequest;
use App\Http\Requests\UserRequest;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(private UserService $userService) {}

    public function login(AuthRequest $request)
    {
        return $this->userService->login($request->validated());
    }
    public function logout()
    {
        return $this->userService->logout();
    }

    public function index()
    {
        return $this->userService->index();
    }
    public function show()
    {
        return $this->userService->show();
    }
    public function showLeader()
    {
        return $this->userService->showLeader();
    }
    public function showMember()
    {
        return $this->userService->showMember();
    }

    public function store(UserRequest $request)
    {
        return $this->userService->store($request->validated());
    }
    public function update(UserRequest $request)
    {
        return $this->userService->update($request->validated());
    }
    public function updateRole(Request $request, string $id)
    {
        return $this->userService->updateRole($request->validate([
            'role' => "required|in:member,leader",
        ]), $id);
    }
    public function destroy()
    {
        return $this->userService->destroy();
    }
    public function sendTokenRecover(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);
        return $this->userService->sendTokenRecover($request->email);
    }
    public function resetPassword(UserRecoverPasswordRequest $request)
    {
        return $this->userService->resetPassword($request->validated());
    }

    public function verifyEmail(string $id, string $token)
    {
        return $this->userService->verifyEmail($id, $token);
    }

    public function reSendEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);
        return $this->userService->reSendEmail($request->email);
    }
}
