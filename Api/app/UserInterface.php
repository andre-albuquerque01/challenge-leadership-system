<?php

namespace App;

interface UserInterface
{
    public function login(array $data);
    public function logout();
    public function index();
    public function show();
    public function showMember();
    public function showLeader();
    public function store(array $data);
    public function update(array $data);
    public function updateRole(array $data, string $id);
    public function destroy();
    public function sendTokenRecover(string $email);
    public function resetPassword(array $data);
    public function verifyEmail(string $id, string $token);
    public function reSendEmail(string $email);
}
