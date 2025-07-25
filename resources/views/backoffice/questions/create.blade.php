@extends('layouts.app')
@section('content')
    <div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-lg p-6 sm:p-10 mt-10">
        <h1 class="text-2xl sm:text-3xl font-bold mb-6 text-gray-800">
            {{ isset($question) ? 'Edit' : 'Tambah' }} Pertanyaan
        </h1>

        <form method="POST" action="{{ isset($question) ? route('questions.update', $question) : route('questions.store') }}" class="space-y-6">
            @csrf
            @if(isset($question)) @method('PUT') @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Judul <span class="text-red-500">*</span></label>
                    <input type="text" name="title" required value="{{ old('title', $question->title ?? '') }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 transition" />
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Urutan <span class="text-red-500">*</span></label>
                    <input type="number" name="order" required min="0" value="{{ old('order', $question->order ?? 0) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 transition" />
                </div>
            </div>
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700">Pertanyaan <span class="text-red-500">*</span></label>
                <textarea name="question" required rows="4"
                          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 transition resize-none">{{ old('question', $question->question ?? '') }}</textarea>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Kategori</label>
                    <select name="category"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                        <option value="1" {{ old('category', $question->category ?? '1') == '1' ? 'selected' : '' }}>Kategori 1</option>
                        <option value="2" {{ old('category', $question->category ?? '1') == '2' ? 'selected' : '' }}>Kategori 2</option>
                    </select>
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Status</label>
                    <select name="is_active"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                        <option value="1" {{ old('is_active', $question->is_active ?? '1') == '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ old('is_active', $question->is_active ?? '1') == '0' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
            </div>

            <div>
                <button type="submit"
                        class="w-full sm:w-auto flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-8 rounded-lg shadow transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ isset($question) ? 'Update' : 'Simpan' }}
                </button>
            </div>
        </form>
    </div>
@endsection
