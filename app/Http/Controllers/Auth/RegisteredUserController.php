<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'user_phone_number' => 'required|string|min:10|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'user_type' => 'required'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'user_phone_number' => $request->user_phone_number,
            'password' => Hash::make($request->password),
            'user_type' => $request->user_type
        ]);

        event(new Registered($user));

        Auth::login($user);

        if(Auth::user()->user_type == 'Restaurant' || Auth::user()->user_type == 'Booking_Manager' || Auth::user()->user_type == 'Manager')
        {
            return redirect(RouteServiceProvider::BACKEND);

        }elseif(Auth::user()->user_type == 'Administrator')
        {
            return redirect(RouteServiceProvider::ADMIN);
        }
        else{
            return redirect(RouteServiceProvider::HOME);
        }

    }

}
