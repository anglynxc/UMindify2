<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\MentorProfile;
use App\Models\LearningSession;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_mentors' => User::where('role', 'mentor')->count(),
            'total_sessions' => LearningSession::count(),
            'pending_mentors' => MentorProfile::where('status', 'pending')->count(),
            'active_sessions' => LearningSession::where('status', 'active')->count(),
            'completed_sessions' => LearningSession::where('status', 'completed')->count(),
        ];

        $recent_sessions = LearningSession::with('mentor')
            ->latest()
            ->take(5)
            ->get();

        $pending_mentors = MentorProfile::with('user')
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_sessions', 'pending_mentors'));
    }

    public function mentorRequests()
    {
        $mentor_requests = MentorProfile::with(['user', 'jurusan'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('admin.mentor-requests', compact('mentor_requests'));
    }

// app/Http/Controllers/Admin/AdminController.php - pastikan method approveMentor
public function approveMentor($id)
{
    try {
        $mentorProfile = MentorProfile::with('user')->findOrFail($id);
        
        // Pastikan status masih pending
        if ($mentorProfile->status !== 'pending') {
            return redirect()->back()->with('error', 'Permohonan mentor ini sudah diproses sebelumnya.');
        }

        $mentorProfile->update(['status' => 'approved']);
        
        // Update user role to mentor HANYA DI SINI
        $mentorProfile->user->update(['role' => 'mentor']);

        return redirect()->back()->with('success', 'Mentor berhasil disetujui! User sekarang memiliki akses mentor.');

    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}

public function rejectMentor($id)
{
    try {
        $mentorProfile = MentorProfile::findOrFail($id);
        
        // Pastikan status masih pending
        if ($mentorProfile->status !== 'pending') {
            return redirect()->back()->with('error', 'Permohonan mentor ini sudah diproses sebelumnya.');
        }

        $mentorProfile->update(['status' => 'rejected']);
        // User tetap role 'user', tidak diubah

        return redirect()->back()->with('success', 'Mentor berhasil ditolak!');

    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}

    public function allMentors()
    {
        $mentors = User::with('mentorProfile')
            ->where('role', 'mentor')
            ->latest()
            ->get();

        return view('admin.all-mentors', compact('mentors'));
    }

    public function allUsers()
    {
        $users = User::where('role', 'user')->latest()->get();
        return view('admin.all-users', compact('users'));
    }

    public function allSessions()
    {
        $sessions = LearningSession::with(['mentor', 'kategori'])
            ->latest()
            ->get();

        return view('admin.all-sessions', compact('sessions'));
    }

    public function manageCategories()
    {
        $categories = \App\Models\Kategori::latest()->get();
        return view('admin.manage-categories', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        \App\Models\Kategori::create($request->all());

        return redirect()->back()->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function manageJurusans()
    {
        $jurusans = \App\Models\Jurusan::latest()->get();
        return view('admin.manage-jurusans', compact('jurusans'));
    }

    public function storeJurusan(Request $request)
    {
        $request->validate([
            'nama_jurusan' => 'required|string|max:255',
            'kode' => 'required|string|max:10|unique:jurusans',
        ]);

        \App\Models\Jurusan::create($request->all());

        return redirect()->back()->with('success', 'Jurusan berhasil ditambahkan!');
    }
}