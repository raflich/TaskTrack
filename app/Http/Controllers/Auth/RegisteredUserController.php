<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Board;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama_user' => ['required', 'string', 'max:100'],
            'email'     => ['required', 'string', 'email', 'max:100', 'unique:users,email'],
            'password'  => ['required', 'confirmed', 'min:8'],
        ]);

        $user = User::create([
            'nama_user' => $request->nama_user,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
        ]);

        // Otomatis buat 1 board untuk user baru
        Board::create([
            'id_user'    => $user->id_user,
            'nama_board' => 'My Kanban Board',
        ]);

        event(new Registered($user));
        Auth::login($user);

        return redirect()->route('kanban.index');
    }
}