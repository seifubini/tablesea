<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use App\Providers\RouteServiceProvider;
use App\Models\Restaurant;
use App\Models\User;

class AccountController extends Controller
{

	public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     *  Edit User Account Profile
     *
     *  @param  User $user
     *  @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        if(Auth::user()->user_type == 'Restaurant' || Auth::user()->user_type == 'Administrator')
        {
            $restaurants = DB::table('restaurants')
                ->where('user_id', Auth::user()->id)
                ->get();

            return view('Admin.User.user_profile', compact('user', 'restaurants'));
        }
        else{
            return redirect()->back()
                ->with('error', "You don't have enough permission to perform this action. Contact the System Administrator.");
        }

    }

    /**
     * Handle an incoming user update request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, User $user)
    {
        if(Auth::user()->user_type == 'Restaurant' || Auth::user()->user_type == 'Administrator')
        {
            $request->validate([
                'name' => 'required|string|max:255',
                'user_image' => 'required|image|mimes:jpeg,png,jpg|max:2048'
            ]);

            $imageName = $request->name.time().'.'.$request->user_image->extension();
            $request->user_image->move(public_path('images/users'), $imageName);
            $id = Auth::user()->id;

            if ($request->password != "")
            {
                $request->validate([
                    'password' => ['required', 'confirmed', Rules\Password::defaults()],
                ]);

                DB::table('users')
                    ->where('id', $id)
                    ->update([
                        'name' => $request->name,
                        'password' => Hash::make($request->password),
                        'remember_token' => Str::random(60),
                        'user_image' => $imageName
                    ]);

                /**$user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();*/

                event(new PasswordReset($user));

                return redirect()->back()
                    ->with('success', "Profile updated successfully.");
            }
            else{
                DB::table('users')
                    ->where('id', $id)
                    ->update([
                        'name' => $request->name,
                        'user_image' => $imageName
                    ]);

                return redirect()->back()
                    ->with('success', "Profile updated successfully.");
            }
        }
        else{
            return redirect()->back()
                ->with('error', "You don't have enough permission to perform this action. Contact the System Administrator.");
        }
    }
}
