<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\UserSession;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    // List semua sesi dari semua user
    public function index()
    {
        $sessions = UserSession::with(['user', 'aiResponses'])->latest()->paginate(10);
        return view('backoffice.sessions.index', compact('sessions'));
    }

    // Detail satu sesi (jawaban + hasil AI)
    public function show($session_id)
    {
        $session = UserSession::with(['user', 'userAnswers.question', 'aiResponses'])->findOrFail($session_id);
        return view('backoffice.sessions.show', compact('session'));
    }
}
