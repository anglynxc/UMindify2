<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\LearningSession;
use App\Models\SessionRegistration;
use App\Models\MentorProfile;
use App\Models\Kategori;
use App\Models\Jurusan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
public function dashboard()
{
    // Ambil featured sessions untuk semua user
    $featured_sessions = LearningSession::with(['mentor', 'kategori'])
                                      ->where('status', 'active')
                                      ->where('tanggal', '>=', now())
                                      ->orderBy('created_at', 'desc')
                                      ->take(6)
                                      ->get();

    // Jika user belum login, tampilkan dashboard public
    if (!Auth::check()) {
        return view('user.dashboard', compact('featured_sessions'));
    }

    $user = Auth::user();
    
    // Redirect berdasarkan role
    if ($user->isAdmin()) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->isMentor()) {
        return redirect()->route('mentor.dashboard');
    }

    // Untuk user biasa, ambil data my_sessions
    $my_sessions = SessionRegistration::with('session.mentor')
                                     ->where('user_id', $user->id)
                                     ->whereHas('session', function($query) {
                                         $query->where('tanggal', '>=', now());
                                     })
                                     ->latest()
                                     ->take(5)
                                     ->get();

    return view('user.dashboard', compact('featured_sessions', 'my_sessions'));
}
    public function browseSessions(Request $request)
    {
        $query = LearningSession::with(['mentor', 'kategori'])
                               ->where('status', 'active')
                               ->where('tanggal', '>=', now());

        // Filters
        if ($request->has('kategori_id') && $request->kategori_id) {
            $query->where('kategori_id', $request->kategori_id);
        }

        if ($request->has('tipe') && $request->tipe) {
            $query->where('tipe', $request->tipe);
        }

        if ($request->has('search') && $request->search) {
            $query->where('judul', 'like', '%' . $request->search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $request->search . '%');
        }

        $sessions = $query->latest()->paginate(12);
        $categories = Kategori::all();

        return view('user.sessions.browse', compact('sessions', 'categories'));
    }

    public function sessionDetail($id)
    {
        $session = LearningSession::with(['mentor', 'kategori', 'mentor.mentorProfile'])
                                 ->findOrFail($id);

        // Check if user already registered
        $is_registered = SessionRegistration::where('session_id', $id)
                                          ->where('user_id', Auth::id())
                                          ->exists();

        return view('user.sessions.detail', compact('session', 'is_registered'));
    }

    public function registerSession($session_id)
    {
        $session = LearningSession::where('status', 'active')
                                 ->where('tanggal', '>=', now())
                                 ->findOrFail($session_id);

        // Check if session is full
        if ($session->isFull()) {
            return redirect()->back()->with('error', 'Maaf, kuota sesi ini sudah penuh.');
        }

        // Check if already registered
        $existing_registration = SessionRegistration::where('session_id', $session_id)
                                                   ->where('user_id', Auth::id())
                                                   ->first();

        if ($existing_registration) {
            return redirect()->back()->with('error', 'Anda sudah terdaftar di sesi ini.');
        }

        // Create registration
        SessionRegistration::create([
            'session_id' => $session_id,
            'user_id' => Auth::id(),
            'status' => 'pending'
        ]);

        return redirect()->back()->with('success', 'Pendaftaran berhasil! Menunggu persetujuan mentor.');
    }

    public function mySessions()
    {
        $registrations = SessionRegistration::with(['session.mentor', 'session.kategori'])
                                          ->where('user_id', Auth::id())
                                          ->latest()
                                          ->get();

        return view('user.sessions.my-sessions', compact('registrations'));
    }

    public function cancelRegistration($registration_id)
    {
        $registration = SessionRegistration::where('user_id', Auth::id())
                                          ->findOrFail($registration_id);

        // Only allow cancellation if status is pending
        if ($registration->status === 'pending') {
            $registration->delete();
            return redirect()->back()->with('success', 'Pendaftaran berhasil dibatalkan.');
        }

        return redirect()->back()->with('error', 'Tidak bisa membatalkan pendaftaran yang sudah disetujui.');
    }

public function becomeMentor()
{
    $user = Auth::user();
    
    // Check if user is already an approved mentor
    if ($user->isApprovedMentor()) {
        return redirect()->route('user.dashboard')
                       ->with('info', 'Anda sudah terdaftar sebagai mentor.');
    }

    // Check if user already has pending application
    if ($user->hasPendingMentorApplication()) {
        return redirect()->route('user.dashboard')
                       ->with('info', 'Anda sudah mengajukan permohonan menjadi mentor. Menunggu persetujuan admin.');
    }

    $jurusans = Jurusan::all();
    return view('user.become-mentor', compact('jurusans'));
}

public function submitMentorApplication(Request $request)
{
    $user = Auth::user();

    $request->validate([
        'nim' => 'required|string|max:20|unique:users,nim,' . $user->id,
        'jurusan_id' => 'required|exists:jurusans,id',
        'pengalaman' => 'required|string|min:50',
        'deskripsi_diri' => 'required|string|min:50',
        'cv' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        'terms' => 'required|accepted'
    ]);

    try {
        // Update user data - TIDAK mengubah role!
        $user->update([
            'nim' => $request->nim,
            // JANGAN update role di sini
        ]);

        // Handle CV upload
        $cv_path = null;
        if ($request->hasFile('cv')) {
            $cv_path = $request->file('cv')->store('cvs', 'public');
        }

        // Create mentor profile dengan status 'pending'
        MentorProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'jurusan_id' => $request->jurusan_id,
                'pengalaman' => $request->pengalaman,
                'deskripsi_diri' => $request->deskripsi_diri,
                'cv_path' => $cv_path,
                'status' => 'pending' // Status pending menunggu approval admin
            ]
        );

        return redirect()->route('user.dashboard')->with('success', 'Permohonan menjadi mentor berhasil dikirim! Menunggu persetujuan admin.');

    } catch (\Exception $e) {
        return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
    }
}

    public function profile()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update($request->only('name', 'email'));

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }
}