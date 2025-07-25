@extends('layouts.app')

@section('content')
    <div class="container mx-auto max-w-6xl bg-white p-4 sm:p-8 rounded-2xl shadow-lg mt-10">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-gray-800">Daftar Pertanyaan</h1>
            <a href="{{ route('questions.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 sm:px-5 py-2 rounded-lg shadow transition-all duration-150 text-sm flex items-center gap-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Pertanyaan
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 flex items-center bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg shadow animate-fade-in" role="alert">
                <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 flex items-center bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-lg shadow animate-fade-in" role="alert">
                <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                <span class="font-medium">
                @foreach($errors->all() as $error)
                        {{ $error }}<br>
                    @endforeach
            </span>
            </div>
        @endif

        <div class="overflow-x-auto rounded-lg shadow">
            <table class="min-w-[700px] w-full text-sm sm:text-base text-left text-gray-700">
                <thead class="bg-gray-50">
                <tr>
                    <th class="py-3 px-4 sm:px-6 font-semibold">Order</th>
                    <th class="py-3 px-4 sm:px-6 font-semibold">Title</th>
                    <th class="py-3 px-4 sm:px-6 font-semibold">Pertanyaan</th>
                    <th class="py-3 px-4 sm:px-6 font-semibold">Kategori</th>
                    <th class="py-3 px-4 sm:px-6 font-semibold">Status</th>
                    <th class="py-3 px-4 sm:px-6 font-semibold text-center">Aksi</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                @forelse($questions as $q)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-3 px-4 sm:px-6">{{ $q->order }}</td>
                        <td class="py-3 px-4 sm:px-6">{{ $q->title }}</td>
                        <td class="py-3 px-4 sm:px-6">{{ $q->question }}</td>
                        <td class="py-3 px-4 sm:px-6">{{ $q->category == '1' ? 'Kategori 1' : 'Kategori 2' }}</td>
                        <td class="py-3 px-4 sm:px-6">
                        <span class="inline-block px-2 py-1 rounded-full text-xs font-medium
                            {{ $q->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $q->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                        </td>
                        <td class="py-3 px-4 sm:px-6">
                            <div class="flex flex-row items-center justify-center gap-2">
                                <a href="{{ route('questions.edit', $q) }}"
                                   class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded-lg shadow transition text-xs font-semibold flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M15.232 5.232l3.536 3.536M9 13l6-6m2 2l-6 6m-2 2v-2a2 2 0 012-2h2" />
                                    </svg>
                                    Edit
                                </a>
                                <form action="{{ route('questions.destroy', $q) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-lg shadow transition text-xs font-semibold flex items-center gap-1"
                                            onclick="return confirm('Yakin hapus?')">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-8 text-gray-400">Belum ada data pertanyaan.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
