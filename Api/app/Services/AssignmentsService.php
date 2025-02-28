<?php

namespace App\Services;

use App\Exceptions\GeneralExceptionCatch;
use App\Http\Resources\AssignmentsResource;
use App\Http\Resources\GeneralResource;
use App\Models\Assignments;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AssignmentsService
{
    public function index()
    {
        try {
            return new AssignmentsResource(Assignments::with(['userMember', 'userLeader'])->get());
        } catch (\Exception $e) {
            throw new GeneralExceptionCatch('Error: index');
        }
    }

    public function show(string $id)
    {
        try {
            $assignments = Assignments::with(['userMember', 'userLeader'])->where('idAssignment', $id)->first();
            if (!$assignments) {
                return response()->json(["message" => "Assignment not found"], 404);
            }
            return new AssignmentsResource($assignments);
        } catch (\Exception $e) {
            throw new GeneralExceptionCatch('Error: show');
        }
    }
    public function showUser()
    {
        try {
            $assignments = Assignments::with(['userMember', 'userLeader'])->where('idMember', Auth::user()->idUser)
                ->orWhere('idLeader', Auth::user()->idUser)
                ->get();

            if (!$assignments) {
                return response()->json(["message" => "Assignment not found"], 404);
            }
            return new AssignmentsResource($assignments);
        } catch (\Exception $e) {
            throw new GeneralExceptionCatch('Error: show user');
        }
    }
    public function store(array $data)
    {
        try {
            $user = User::where('idUser', $data['idLeader'])->where('role', 'leader')->first();
            if (!$user) {
                return new GeneralResource(['message' => 'user not found']);
            }
            
            Assignments::create($data);
            return response()->json(["message" => "success"], 204);
        } catch (\Exception $e) {
            throw new GeneralExceptionCatch('Error: store');
        }
    }

    public function update(array $data, string $id)
    {
        try {
            $assignments = Assignments::where('idAssignment', $id)->first();
            if (!$assignments) {
                return response()->json(["message" => "Assignment not found"], 404);
            }
            $assignments->update($data);
            return response()->json(["message" => "success"], 204);
        } catch (\Exception $e) {
            throw new GeneralExceptionCatch('Error: update');
        }
    }

    public function destroy(string $id)
    {
        try {
            $assignments = Assignments::where('idAssignment', $id);
            if (!$assignments) {
                return response()->json(["message" => "Assignment not found"], 404);
            }
            $assignments->delete();
            return response()->json(["message" => "success"], 204);
        } catch (\Exception $e) {
            throw new GeneralExceptionCatch('Error: destroy');
        }
    }
}
