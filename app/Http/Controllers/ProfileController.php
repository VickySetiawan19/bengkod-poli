<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Show the profile / change password page.
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Update the user password with server-side validation.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'password_lama' => ['required', 'current_password'],
            'password_baru' => ['required', 'string', 'min:8', 'confirmed', 'different:password_lama'],
        ], [
            'password_lama.required' => 'Password lama wajib diisi.',
            'password_lama.current_password' => 'Password lama tidak cocok dengan password akun saat ini.',
            'password_baru.required' => 'Password baru wajib diisi.',
            'password_baru.min' => 'Password baru minimal harus 8 karakter.',
            'password_baru.confirmed' => 'Konfirmasi password baru tidak cocok.',
            'password_baru.different' => 'Password baru tidak boleh sama dengan password lama.',
        ]);

        Auth::user()->update([
            'password' => Hash::make($request->password_baru),
        ]);

        return redirect()->route('profile.edit')->with('success', 'Password berhasil diperbarui.');
    }
}
