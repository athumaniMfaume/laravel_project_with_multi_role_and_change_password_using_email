<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\forgotPasswordMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
        public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'You have been logged out.');
    }


    public function login()
    {
        return view('login');
    }

    public function login_post(Request $request)
    {
        // Validate the login request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        // Retrieve the credentials
        $credentials = $request->only('email', 'password');
    
        // Attempt authentication
        if (Auth::attempt($credentials)) {
            // Retrieve the authenticated user's role
            $role = Auth::user()->role;
    
            // Redirect based on role
            if ($role == 2) { // Super Admin
                return redirect()->route('superadmin.dashboard');
            } elseif ($role == 1) { // Admin
                return redirect()->route('admin.dashboard');
            } elseif ($role == 0) { // User
                return redirect()->route('user.dashboard');
            } else { // Unauthorized role
                Auth::logout();
                return redirect()->back()->with('error', 'Unauthorized user role.');
            }
        }
    
        // Authentication failed
        return redirect()->back()->with('error', 'Email or password is incorrect.');
    }
    
    

    public function register()
    {
        return view('register');
    }

    public function register_post(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'role' => 'required',
            'password' => 'required|confirmed',

        ]);
        
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->password = Hash::make($request->password);

        if ($user->save()) {
            return redirect()->back()->with('success','User Registration Successfully!');
        }

        return redirect()->back()->with('error','Error Registrtion Failed!');

    }

    public function forgot_password()
    {
        return view('forgot_password');
    }

    public function forgot_password_post(Request $request)
{
    $request->validate([
        'email' => 'required|email',
    ]);

    $user = User::where('email', $request->email)->first();

    if ($user) {
        // Generate a token and save it
        $user->remember_token = Str::random(50); 
        $user->save(); 

        // Send the email
        Mail::to($user->email)->send(new forgotPasswordMail($user));

        return redirect()->back()->with('success', 'Wait for the link to verify your email.');
    }

    return redirect()->back()->with('error', 'Email not found!');
}

   


    /**
     * Show the form for creating a new resource.
     */
    public function reset_password(Request $request, $token)
    {
        $user = User::where('remember_token', '=', $token);

        if ($user->count() == 0) {
            abort(403);
        }

        $user = $user->first();
        $token = $token;
        return view('reset_password',compact('token','user'));
         
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function reset_password_post(Request $request, $token)
    {
        $request->validate([
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
        ]);

        $user = User::where('remember_token', '=', $token);

        if ($user->count() == 0) {
            abort(403);
        }

        $user = $user->first();
        $user->password = Hash::make($request->password);
        $user->remember_token = Str::random(50);
        
        if ($user->save()) {
            return redirect()->route('login')->with('success', 'Your password has been reseted successfully!');
        }

        return redirect()->route('password_reset_post')->with('error', 'Something went wrong try again later!');

        

    }

    /**
     * Display the specified resource.
     */
    public function change_password()
    {
        if (Auth::check()) {
            return view('change_password');
        }

        return redirect()->route('login');
    }

    public function change_password_post(Request $request)
    {
        if(Auth::check()){
            
             // Validate input
        $request->validate([
            'password' => 'required', // Old password
            'new_password' => 'required|confirmed', // New password with confirmation
        ]);

        // Get the currently authenticated user
        $user = Auth::user();

        // Check if the provided old password is correct
        if (!Hash::check($request->password, $user->password)) {
            return redirect()->back()->with('error', 'The old password is incorrect.');
        }

        // Update user's password
        $user->password = Hash::make($request->new_password);
        $user->save();

        // Return success message
        return redirect()->route('login')->with('success', 'Password updated successfully!');

        }

        return redirect()->route('change_password')->with('error', 'Something wrong!');

       
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
