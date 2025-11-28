<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\MentorProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Redirect berdasarkan role
            $user = Auth::user();
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            } elseif ($user->isMentor()) {
                return redirect()->route('mentor.dashboard');
            } else {
                return redirect()->route('user.dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput();
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

        Auth::login($user);

        return redirect()->route('user.dashboard')->with('success', 'Registrasi berhasil! Selamat datang di Umindify.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function submitMentorApplication(Request $request)
    {
        $user = Auth::user();

        // Validasi yang lebih spesifik
        $request->validate([
            'nim' => 'required|string|max:20|unique:users,nim,' . $user->id,
            'jurusan_id' => 'required|exists:jurusans,id',
            'pengalaman' => 'required|string|min:50',
            'deskripsi_diri' => 'required|string|min:50',
            'cv' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'terms' => 'required|accepted'
        ]);

        // Handle CV upload
        $cv_path = null;
        if ($request->hasFile('cv')) {
            $cv_path = $request->file('cv')->store('cvs', 'public');
        }

        try {
            // Update user data
            $user->update([
                'nim' => $request->nim,
            ]);

            // Create mentor profile
            MentorProfile::create([
                'user_id' => $user->id,
                'jurusan_id' => $request->jurusan_id,
                'pengalaman' => $request->pengalaman,
                'deskripsi_diri' => $request->deskripsi_diri,
                'cv_path' => $cv_path,
                'status' => 'pending'
            ]);

            return redirect()->route('user.dashboard')->with('success', 'Permohonan menjadi mentor berhasil dikirim! Menunggu persetujuan admin.');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }
}