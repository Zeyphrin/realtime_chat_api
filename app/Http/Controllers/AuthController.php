<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
 
    public function register(RegisterRequest $request){
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']); ;

        $user = User::create($data);
        $token = $user->createToken(User::USER_TOKEN);

        return $this->success([
            'user'=>$user,
            'token' => $token->plainTextToken,

        ],'user berhasil register');
    }

    
    public function login(LoginRequest $request): JsonResponse{

        $isValid = $this->isValidCredential($request);

        if(!$isValid['success']){
            return $this->error($isValid['message'], Response::HTTP_UNPROCESSABLE_ENTITY);  
        }

        $user = $isValid['user'];
        $token = $user->createdToken(User::USER_TOKEN);
        return $this->success([
            'user'=>$user,
            'token' => $token->plainTextToken,
        ], 'Login Successfully');

    }

    private function isValidCredential (LoginRequest $request):array{
        $data = $request->validated();
     
        $user = User::where('email', $data['email'])->first();
        if ($user == null){
           return[
               'success'=> false,
               'message'=> 'invalid credential',
           ];
        }

        if (Hash::check($data['password'], $user->password)) {
            return[
                'success'=> true,
                'user'=> $user,
            ];
        }

        return [
            'success'=> false,
            'message'=> 'not match password',
        ];
    }

    public function loginWithToken() : JsonResponse{
        return $this->success(auth()->user(), 'login with token success');
    }

    public function logout(Request $request): JsonResponse{
        $request->user()->currentAccessToken()->delete();
        return $this->success(null, 'logout success');
        
    }
}
