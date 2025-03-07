<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MessageRequest;
use App\Services\MessageService;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function __construct(private MessageService $messageService) {}

    public function show(string $id)
    {
        return $this->messageService->show($id);
    }
    public function showUser()
    {
        return $this->messageService->showUser();
    }
    public function store(MessageRequest $request)
    {
        return $this->messageService->store($request->validated());
    }
}
