<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\LearningSession;
use App\Models\Kategori;
use App\Models\Jurusan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SessionRegistration; 
class MentorController extends Controller
{

public function sessionParticipants($session_id)
{
    $session = LearningSession::where('mentor_id', Auth::id())->findOrFail($session_id);
    $participants = SessionRegistration::with('user')
                                      ->where('session_id', $session_id)
                                      ->get();

    return view('mentor.sessions.participants', compact('session', 'participants'));
}

public function approveParticipant($registration_id)
{
    $registration = SessionRegistration::findOrFail($registration_id);
    
    // Pastikan session milik mentor yang login
    $session = LearningSession::where('mentor_id', Auth::id())
                             ->findOrFail($registration->session_id);

    $registration->update(['status' => 'approved']);

    // Update terisi count
    $session->increment('terisi');

    return redirect()->back()->with('success', 'Peserta berhasil disetujui!');
}

public function rejectParticipant($registration_id)
{
    $registration = SessionRegistration::findOrFail($registration_id);
    
    // Pastikan session milik mentor yang login
    $session = LearningSession::where('mentor_id', Auth::id())
                             ->findOrFail($registration->session_id);

    $registration->update(['status' => 'rejected']);

    return redirect()->back()->with('success', 'Peserta berhasil ditolak!');
}

public function activateSession($session_id)
{
    $session = LearningSession::where('mentor_id', Auth::id())->findOrFail($session_id);
    $session->update(['status' => 'active']);

    return redirect()->back()->with('success', 'Sesi berhasil diaktifkan!');
}

public function completeSession($session_id)
{
    $session = LearningSession::where('mentor_id', Auth::id())->findOrFail($session_id);
    $session->update(['status' => 'completed']);

    return redirect()->back()->with('success', 'Sesi ditandai sebagai selesai!');
}
    public function dashboard()
    {
        $mentor = Auth::user();
        $stats = [
            'total_sessions' => LearningSession::where('mentor_id', $mentor->id)->count(),
            'active_sessions' => LearningSession::where('mentor_id', $mentor->id)
                                                ->where('status', 'active')
                                                ->count(),
            'completed_sessions' => LearningSession::where('mentor_id', $mentor->id)
                                                  ->where('status', 'completed')
                                                  ->count(),
            'total_earnings' => LearningSession::where('mentor_id', $mentor->id)
                                              ->where('status', 'completed')
                                              ->sum('harga'),
        ];

        $upcoming_sessions = LearningSession::where('mentor_id', $mentor->id)
                                           ->where('status', 'active')
                                           ->where('tanggal', '>=', now())
                                           ->orderBy('tanggal')
                                           ->take(5)
                                           ->get();

        return view('mentor.dashboard', compact('stats', 'upcoming_sessions'));
    }

    public function sessions()
    {
        $sessions = LearningSession::with('kategori')
                                  ->where('mentor_id', Auth::id())
                                  ->latest()
                                  ->get();

        return view('mentor.sessions.index', compact('sessions'));
    }

    public function createSession()
    {
        $categories = Kategori::all();
        return view('mentor.sessions.create', compact('categories'));
    }

    public function storeSession(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'kategori_id' => 'required|exists:kategories,id',
            'tipe' => 'required|in:online,offline',
            'lokasi_offline' => 'required_if:tipe,offline',
            'link_meeting' => 'required_if:tipe,online',
            'tanggal' => 'required|date|after:today',
            'jam_mulai' => 'required',
            'durasi' => 'required|integer|min:30',
            'harga' => 'required|numeric|min:0',
            'kuota' => 'required|integer|min:1',
        ]);

        LearningSession::create([
            'mentor_id' => Auth::id(),
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'kategori_id' => $request->kategori_id,
            'tipe' => $request->tipe,
            'lokasi_offline' => $request->lokasi_offline,
            'link_meeting' => $request->link_meeting,
            'tanggal' => $request->tanggal,
            'jam_mulai' => $request->jam_mulai,
            'durasi' => $request->durasi,
            'harga' => $request->harga,
            'kuota' => $request->kuota,
            'status' => 'draft',
        ]);

        return redirect()->route('mentor.sessions')->with('success', 'Sesi berhasil dibuat!');
    }

    public function editSession($id)
    {
        $session = LearningSession::where('mentor_id', Auth::id())->findOrFail($id);
        $categories = Kategori::all();

        return view('mentor.sessions.edit', compact('session', 'categories'));
    }

    public function updateSession(Request $request, $id)
    {
        $session = LearningSession::where('mentor_id', Auth::id())->findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'kategori_id' => 'required|exists:kategories,id',
            'tipe' => 'required|in:online,offline',
            'lokasi_offline' => 'required_if:tipe,offline',
            'link_meeting' => 'required_if:tipe,online',
            'tanggal' => 'required|date|after:today',
            'jam_mulai' => 'required',
            'durasi' => 'required|integer|min:30',
            'harga' => 'required|numeric|min:0',
            'kuota' => 'required|integer|min:1',
            'status' => 'required|in:draft,active,cancelled',
        ]);

        $session->update($request->all());

        return redirect()->route('mentor.sessions')->with('success', 'Sesi berhasil diperbarui!');
    }

    public function deleteSession($id)
    {
        $session = LearningSession::where('mentor_id', Auth::id())->findOrFail($id);
        $session->delete();

        return redirect()->route('mentor.sessions')->with('success', 'Sesi berhasil dihapus!');
    }

    public function profile()
    {
        $mentor = Auth::user();
        $jurusans = Jurusan::all();

        return view('mentor.profile', compact('mentor', 'jurusans'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'nim' => 'required|string|max:20|unique:users,nim,' . $user->id,
            'jurusan_id' => 'required|exists:jurusans,id',
            'pengalaman' => 'required|string',
            'deskripsi_diri' => 'required|string',
        ]);

        // Update user data
        $user->update([
            'name' => $request->name,
            'nim' => $request->nim,
        ]);

        // Update mentor profile
        $user->mentorProfile->update([
            'jurusan_id' => $request->jurusan_id,
            'pengalaman' => $request->pengalaman,
            'deskripsi_diri' => $request->deskripsi_diri,
        ]);

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }
}