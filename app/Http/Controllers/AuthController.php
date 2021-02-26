<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Gallery;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $registerData = $request->validated();
        $registerData['password'] = Hash::make($registerData['password']);
        $user = User::create($registerData);
        $token = Auth::login($user);

        return $this->jsonTokenResponse($token);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        $token = Auth::attempt($credentials);

        return $this->jsonTokenResponse($token);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'logout' => true,
        ]);
    }

    public function me()
    {
        return auth('api')->user();
    }

    public function userGallery(Request $request)
    {
        $user = auth('api')->user();

        $galleriesQuery = Gallery::query();
        $galleriesQuery->with('user', 'images');
        $search = $request->header('searchText');
        $galleriesQuery->where(function ($query) use ($search) {
            $query->where('title', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%')
                ->orwhereHas('user', function ($que) use ($search) {
                    $que->where('first_name', 'like', '%' . $search . '%')
                        ->orWhere('last_name', 'like', '%' . $search . '%');
                });
        });

        $galleries = $galleriesQuery->where('user_id', $user->id)->take($request->header('pagination'))
            ->get();

        $count = $galleriesQuery->count();

        return [$user, $galleries, $count];
    }

    private function jsonTokenResponse($token)
    {
        return response()->json([
            'token' => $token,
            'user' => Auth::user(),
        ]);
    }
}
