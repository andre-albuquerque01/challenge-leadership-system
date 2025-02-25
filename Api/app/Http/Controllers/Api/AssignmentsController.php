<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssignmentsRequest;
use App\Services\AssignmentsService;
use Illuminate\Http\Request;

class AssignmentsController extends Controller
{
    public function __construct(private AssignmentsService $assignmentsService) {}

    public function index()
    {
        return $this->assignmentsService->index();
    }
    public function show(string $id)
    {
        return $this->assignmentsService->show($id);
    }
    public function showUser()
    {
        return $this->assignmentsService->showUser();
    }

    public function store(AssignmentsRequest $request)
    {
        return $this->assignmentsService->store($request->validated());
    }
    public function update(AssignmentsRequest $request, string $id)
    {
        return $this->assignmentsService->update($request->validated(), $id);
    }
    public function destroy(string $id)
    {
        return $this->assignmentsService->destroy($id);
    }
}
