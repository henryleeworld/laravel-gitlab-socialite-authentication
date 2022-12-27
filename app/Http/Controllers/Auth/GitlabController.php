<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use Exception;
use Socialite;
  
class GitLabController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function redirectToGitLab()
    {
        return Socialite::driver('gitlab')->redirect();
    }
      
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function handleGitLabCallback()
    {
        try {
            $gitlabUser = Socialite::driver('gitlab')->user();
            $user = User::updateOrCreate([
                'gitlab_id'       => $gitlabUser->id,
            ], [
                'name'            => $gitlabUser->name,
                'email'           => $gitlabUser->email,
                'password'        => encrypt('123456dummy'),
                'gitlab_nickname' => $gitlabUser->nickname,
                'github_avatar'   => $gitlabUser->avatar,
            ]);
            Auth::login($user);
            return redirect('/dashboard');
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}

