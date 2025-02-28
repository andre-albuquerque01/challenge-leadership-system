<?php

namespace App\Services;

use App\Events\VerifyEmailEvent;
use App\Exceptions\GeneralExceptionCatch;
use App\Exceptions\UserException;
use App\Http\Resources\AuthLoginResource;
use App\Http\Resources\GeneralResource;
use App\Http\Resources\UserResource;
use App\Jobs\SendRecoverPasswordEmailJob;
use App\Jobs\SendVerifyEmailJob;
use App\Models\Regions;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class UserService
{
    public function __construct(private Request $request) {}

    public function login(array $data)
    {
        try {
            if (!Auth::attempt($data)) {
                throw new UserException('Credentials invalid');
            }

            $user = Auth::user();
            if (User::where('email', $user->email)->whereNull('email_verified_at')->exists()) {
                return new GeneralResource(['message' => 'E-mail não verificado']);
            }

            $scopes = ($user->role == "leader") ? ['leader'] : ['member'];
            $token = $this->request->user()->createToken('JesusIsKingOfKings', $scopes, now()->addHours(2))->plainTextToken;
            $role = $user->role == 'leader' ? 'leader' : 'member';
            return new AuthLoginResource(['token' => $token, 'r' => $role]);
        } catch (\Exception $e) {
            throw new GeneralExceptionCatch('Error: login');
        }
    }
    public function logout()
    {
        try {
            $this->request->user()->currentAccessToken()->delete();
            return new GeneralResource(['message' => 'success']);
        } catch (\Exception $e) {
            throw new GeneralExceptionCatch('Error: login');
        }
    }

    public function index()
    {
        try {
            return UserResource::collection(User::with('region')->get());
        } catch (\Exception $e) {
            throw new GeneralExceptionCatch('Error: user show all');
        }
    }

    public function show(): UserResource
    {
        try {
            return new UserResource(User::with('region')->findOrFail(Auth::user()->idUser)->first());
        } catch (\Exception $e) {
            throw new GeneralExceptionCatch('Error: user show');
        }
    }
    public function showMember()
    {
        try {
            return UserResource::collection(User::where('role', 'member')->with('region')->get());
        } catch (\Exception $e) {
            throw new GeneralExceptionCatch('Error: show member');
        }
    }

    public function showLeader()
    {
        try {
            return UserResource::collection(User::where('role', 'leader')->with('region')->get());
        } catch (\Exception $e) {
            throw new GeneralExceptionCatch('Error: show leader');
        }
    }

    public function store(array $data)
    {
        try {
            $data['password'] = Hash::make($data['password']);
            $data['role'] = 'member';
            $data['remember_token'] = Str::random(60);
            $user = User::create($data);

            $data['userId'] = $user->idUser;
            Regions::create($data);

            dispatch(new SendVerifyEmailJob($user->email, $user->remember_token, $user->idUser));
            return response()->json(['message' => 'success'], 201);
        } catch (\Exception $e) {
            throw new GeneralExceptionCatch('Error: user create');
        }
    }

    public function update(array $data)
    {
        try {
            $user = User::where('idUser', Auth::user()->idUser)->first();
            if (!$user) {
                return response()->json(['message' => 'user not found'], 404);
            }
            if (!Hash::check($data['password'], $user->password)) {
                return response()->json(['message' => 'password incorrect'], 401);
            }
            $data['password'] = $user->password;

            $filteredData = Arr::only($data, ['cep', 'address', 'house', 'neighborhood']);

            Regions::where('userId', $user->idUser)->update($filteredData);
            $user->update($data);
            return new GeneralResource(['message' => 'success']);
        } catch (\Exception $e) {
            throw new GeneralExceptionCatch('Error: user update');
        }
    }

    public function updateRole(array $data, string $id)
    {
        try {
            $user = User::where('idUser', $id)->first();
            if (!$user) {
                return response()->json(['message' => 'user not found'], 404);
            }
            $user->update($data);
            return new GeneralResource(['message' => 'success']);
        } catch (\Exception $e) {
            throw new GeneralExceptionCatch('Error: user update');
        }
    }

    public function destroy(): GeneralResource
    {
        try {
            $user = User::where('idUser', Auth::user()->idUser)->first();
            if (!$user) {
                return response()->json(['message' => 'user not found'], 404);
            }
            $user->touch('deleted_at');
            return new GeneralResource(['message' => 'success']);
        } catch (\Exception $e) {
            throw new GeneralExceptionCatch('Error: user delete');
        }
    }

    public function sendTokenRecover(string $email)
    {
        try {
            $user = User::where('email', $email)->first();
            if (!$user) {
                return response()->json(['message' => 'user not found'], 404);
            }
            $token = strtoupper(Str::random(60));
            $table = DB::table('password_reset_tokens')->where('email', $email)->first();
            if (!$table) {
                DB::table('password_reset_tokens')->insert([
                    'email' => $email,
                    'token' => $token,
                    'created_at' => now(),
                ]);
            } else {
                DB::table('password_reset_tokens')->update([
                    'token' => $token,
                    'created_at' => now(),
                ]);
            }
            dispatch(new SendRecoverPasswordEmailJob($user->email, $token));

            return new GeneralResource(['message' => 'send e-mail']);
        } catch (\Exception $e) {
            throw new GeneralExceptionCatch('Error, send email recover password');
        }
    }
    public function resetPassword(array $data)
    {
        try {
            $passwordResetTokens = DB::table('password_reset_tokens')->where('token', $data['token'])->first();
            if (!isset($passwordResetTokens)) throw new UserException("Token invalid");

            User::where('email', $passwordResetTokens->email)->update([
                'password' => Hash::make($data['password']),
            ]);
            DB::table('password_reset_tokens')->where('token', $data['token'])->delete();
            return new GeneralResource(['message' => 'success']);
        } catch (\Exception $e) {
            throw new GeneralExceptionCatch('Error, reset password');
        }
    }

    public function verifyEmail(string $id, string $token)
    {
        try {
            $user = User::where('idUser', $id)->first();
            if (!$user) {
                return response()->json(['message' => 'user not found'], 404);
            }
            if ($token == $user->remember_token) {
                $user->touch("email_verified_at");
                return new GeneralResource(['message' => 'success']);
            }
            return response()->json(['message' => 'Token invalid'], 401);
        } catch (UserException $e) {
            throw new GeneralExceptionCatch($e->getMessage());
        }
    }

    public function reSendEmail(string $email)
    {
        try {
            $user = User::where('email', $email)->first();
            if (!$user) {
                return response()->json(['message' => 'user not found'], 404);
            }
            dispatch(new SendVerifyEmailJob($user->email, $user->remember_token, $user->idUser));

            return new GeneralResource(['message' => 'send e-mail']);
        } catch (\Exception $e) {
            throw new GeneralExceptionCatch($e->getMessage());
        }
    }
}
