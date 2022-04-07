<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Social;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    /**
     * Redirect to google oauth2
     *
     * @return void
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
            $social = Social::where('provider_id', $googleUser->id)->first();
            $user = User::where('email', $googleUser->email)->first();
            // Nếu tài khoản tạo thông thường chưa có id khi đăng nhập bằng google
            if ($user && !$social) {
                if ($user->active == User::ACTIVE) {
                    Social::create([
                        'provider_id' => $googleUser->id,
                        'name' => 'Google',
                        'user_id' => $user->id,
                    ]);
                    Auth::login(User::where('email', $googleUser->email)->first());
                    // toastr()->success('Tài khoản đã bị vô hiệu hóa.');

                    return redirect()->route('home');
                }
                // toastr()->error('Tài khoản đã bị vô hiệu hóa.');

                return redirect()->route('login');
            }
            // Nếu đã có id đăng nhập bằng google
            elseif ($social) {
                if ($user->active == User::ACTIVE) {
                    Auth::login(User::where('id', $social->user_id)->first());
                    toastr()->error('Tài khoản đã bị vô hiệu hóa.');

                    return redirect()->route('home');
                }
                // toastr()->error('Tài khoản đã bị vô hiệu hóa.');

                return redirect()->route('login');
            }
            // Nếu chưa có
            else {
                $newUser = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'role' => User::ROLE_USER,
                    'active' => User::ACTIVE,
                    'password' => Hash::make('123456'),
                ]);
                Social::create([
                    'provider_id' => $googleUser->id,
                    'name' => 'Google',
                    'user_id' => $newUser->id,
                ]);
                Auth::login($newUser);

                return redirect()->route('home');
            }
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}
