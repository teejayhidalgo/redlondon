<?php

namespace App\Http\Controllers;

use App\User;
use Auth;
use Socialite;
use Illuminate\Http\Request;

class SocialController extends Controller
{
    public function redirectToProvider(){
        return Socialite::driver('facebook')->redirect();
    }

    public function handleProviderCallBack(){
        $user = Socialite::driver('facebook')->user();

        // OAuth Two Providers
        $token = $user->token;
        $refreshToken = $user->refreshToken; // not always provided
        $expiresIn = $user->expiresIn;

        // OAuth One Providers
        $token = $user->token;

        $fbUser = User::where('email',$user->getEmail())->first();
        //dd($fbUser);
        if(!$fbUser){
		User::create([
			'avatar' => $user->getAvatar(),
			'name' => $user->getName(),
			'email' => $user->getEmail()
		]);
        $fbUser = User::where('email',$user->getEmail())->first();
		}
        if($fbUser){
		Auth::login($fbUser, true);
        }
        return redirect()->to('/');
    }

    public function logout(){
    	Auth::logout();
		return redirect()->to('/');
    }

    public function terms(){
        return view('terms');
    }

    public function privacy(){
        return view('privacy');
    }
}