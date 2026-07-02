@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center mb-6">
        <a href="{{ route('enseignant.dashboard') }}" class="text-gray-600 hover:text-gray-900 mr-4">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="text-2xl font-bold">Modifier le procès-verbal</h1>
    </div>

    <div class="bg-white rounded-xl shadow p-6 max-w-3xl">
        <div class="mb-6 p-4 bg-gray-50 rounded-lg">
            <h3 class="font-semibold text-gray-700 mb-2">Informations de la soutenance</h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div><span class="text-gray-500">Étudiant :</span> <span class="font-medium">{{ $pv->soutenance->etudiant->name ?? 'Non défini' }}</span></div>
                <div><span class="text-gray-500">Titre :</span> <span class="font-medium">{{ $pv->soutenance->titre }}</span></div>
                <div><span class="text-gray-500">Date :</span> <span class="font-medium">{{ \Carbon\Carbon::parse($pv->soutenance->date)->format('d/m/Y H:i') }}</span></div>
                <div><span class="text-gray-500">Filière :</span> <span class="font-medium">{{ $pv->soutenance->filiere }}</span></div>
            </div>
        </div>

        <form action="{{ route('enseignant.pv.update', $pv->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="note" class="block text-sm font-medium text-gray-700 mb-2">
                    Note <span class="text-red-500">*</span>
                </label>
                <input type="number" name="note" id="note" value="{{ old('note', $pv->note) }}"
                       step="0.5" min="0" max="20"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('note') border-red-500 @enderror"
                       required>
                @error('note')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-400 mt-1">Note sur 20 (ex: 14.5)</p>
            </div>

            <div class="mb-4">
                <label for="observations" class="block text-sm font-medium text-gray-700 mb-2">
                    Observations
                </label>
                <textarea name="observations" id="observations" rows="4"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('observations') border-red-500 @enderror"
                          placeholder="Observations, commentaires sur la soutenance...">{{ old('observations', $pv->observations) }}</textarea>
                @error('observations')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('enseignant.dashboard') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-bold py-2 px-6 rounded-lg transition">
                    Annuler
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition">
                    <i class="fas fa-save"></i> Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>
@endsection