<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    public function register(RegisterRequest $request){
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']); ;
        $data['username'] = strstr($data['email'],'@', true);
 
        $user = User::create($data);
        $token = $user->createToken(User::USER_TOKEN);
        return $this->success([
            'user' => $user,
            'token' => $token->plainTextToken,
        ], 'user berhasil register');
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $isValid = $this->isValidCredential($request);

        if (!$isValid['success']) {
            return $this->error($isValid['message'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = $isValid['user'];
        $token = $user->createToken(User::USER_TOKEN);

        return $this->success([
            'user' => $user,
            'token' => $token->plainTextToken,
        ], 'Login Successfully');
    }

    private function isValidCredential(LoginRequest $request): array {
        $data = $request->validated();

        $user = User::where('email', $data['email'])->first();
        if ($user == null) {
            return [
                'success' => false,
                'message' => 'invalid credential',
            ];
        }

        if (Hash::check($data['password'], $user->password)) {
            return [
                'success' => true,
                'user' => $user,
            ];
        }

        return [
            'success' => false,
            'message' => 'not match password',
        ];
    }

    public function loginWithToken(): JsonResponse {
        return $this->success(auth()->user(), 'login with token success');
    }

    public function logout(Request $request): JsonResponse {
        $request->user()->currentAccessToken()->delete();
        return $this->success(null, 'logout success');
    }

    public function success($data, $message = '', $status = 200): JsonResponse {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    private function error($message, $status): JsonResponse {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $status);
    }
}

