<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function index(Request $request)
    {
        if ($request->hasHeader('X-Requested-With')) {
            return User::all();
        } else {
            return view('authors');
        }
    }

    /**
     * @return View
     */
    public function showLoginForm(): View
    {
        return view('login');
    }

    /**
     * @return View
     */
    public function create(): View
    {
        return view('signup');
    }

    /**
     * @param  StoreUserRequest  $request
     * @return RedirectResponse
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $validated = $request->all();
        $validated['password'] = Hash::make($request->password);
        User::create($validated);
        return redirect('/login');
    }

    /**
     * @param  User  $user
     * @return View|User
     */
    public function show(User $user, Request $request): View|User
    {
        if ($request->hasHeader('X-Requested-With')) {
            return $user;
        } else {
            return view('admin.profile_info');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  User  $user
     * @return View
     */
    public function edit(User $user): View
    {
        if (Auth::user()->cannot('update', $user)) {
            abort(403);
        }
        return view('admin.profile_edit')
            ->with(compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateUserRequest  $request
     * @param  User  $user
     * @return RedirectResponse
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->last_name = $request->last_name;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();
        $request->session()->flash('profile_edited', '???????? ???????????? ?????????????? ??????????????????!');
        return redirect()->route('profile');
    }

    /**
     * @param  Request  $request
     * @return RedirectResponse
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if (false !== str_contains(url()->previous(), 'profile')) {
            return redirect()->route('main');
        } else {
            return back();
        }
    }

    /**
     * @param  Request  $request
     * @return View|RedirectResponse
     */
    public function authenticate(Request $request): View|RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if ($request->input('remember-me')) {
            $needUserRemember = true;
        } else {
            $needUserRemember = false;
        }

        if (Auth::attempt($credentials, $needUserRemember)) {
            $request->session()->regenerate();
            return redirect()->route('chats');
        }

        return back()->withErrors([
            'email' => '???????????????? ?????????? ??/?????? ????????????.',
        ])->onlyInput('email');
    }
}
