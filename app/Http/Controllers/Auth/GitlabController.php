<?php

namespace App\Http\Controllers\Auth;

use Auth;
use Exception;
use App\Http\Controllers\Controller;
use App\Models\User;
use Socialite;
  
class GitlabController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function redirectToGitlab()
    {
        return Socialite::driver('gitlab')->redirect();
    }
      
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function handleGitlabCallback()
    {
        try {
            $user = Socialite::driver('gitlab')->user();
            $finduser = User::where('gitlab_id', $user->id)->first();
            if($finduser) {
                Auth::login($finduser);
                return redirect('/dashboard');
            }else{
                $newUser = User::create([
                    'name'            => $user->name,
                    'email'           => $user->email,
                    'gitlab_id'       => $user->id,
                    'gitlab_nickname' => $user->nickname,
                    'github_avatar'   => $user->avatar,
                    'password'        => encrypt('123456dummy')
                ]);
                Auth::login($newUser);
     
                return redirect('/dashboard');
            }
    
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}

