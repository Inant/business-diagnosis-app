<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class QuestionController extends Controller
{
    public function index()
    {
        $questions = Question::orderBy('order')->get();
        return view('backoffice.questions.index', compact('questions'));
    }

    public function create()
    {
        return view('backoffice.questions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'question' => 'required|string',
            'order' => 'required|integer',
            'is_active' => 'boolean'
        ]);

        Question::create([
            'title' => $request->title,
            'question' => $request->question,
            'slug' => Str::slug($request->title) . '-' . uniqid(),
            'order' => $request->order,
            'is_active' => $request->is_active ? 1 : 0,
        ]);
        return redirect()->route('questions.index')->with('success', 'Pertanyaan berhasil ditambahkan.');
    }

    public function edit(Question $question)
    {
        return view('backoffice.questions.edit', compact('question'));
    }

    public function update(Request $request, Question $question)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'question' => 'required|string',
            'order' => 'required|integer',
            'is_active' => 'boolean'
        ]);
        $question->update([
            'title' => $request->title,
            'question' => $request->question,
            'slug' => Str::slug($request->title) . '-' . uniqid(),
            'order' => $request->order,
            'is_active' => $request->is_active ? 1 : 0,
        ]);
        return redirect()->route('questions.index')->with('success', 'Pertanyaan berhasil diupdate.');
    }

    public function destroy(Question $question)
    {
        $question->delete();
        return redirect()->route('questions.index')->with('success', 'Pertanyaan berhasil dihapus.');
    }
}
