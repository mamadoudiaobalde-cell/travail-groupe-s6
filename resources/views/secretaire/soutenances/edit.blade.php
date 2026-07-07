@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center mb-6">
        <a href="{{ route('secretaire.soutenances.index') }}" class="text-gray-600 hover:text-gray-900 mr-4 transition">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="text-2xl font-bold">Modifier la soutenance</h1>
    </div>

    <div class="bg-white rounded-lg shadow p-6 max-w-3xl">
        <form action="{{ route('secretaire.soutenances.update', $soutenance) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label for="etudiant_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Étudiant <span class="text-red-500">*</span>
                    </label>
                    <select name="etudiant_id" id="etudiant_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('etudiant_id') border-red-500 @enderror"
                            required>
                        <option value="">Sélectionner un étudiant</option>
                        @foreach($etudiants as $etudiant)
                            <option value="{{ $etudiant->id }}" {{ old('etudiant_id', $soutenance->etudiant_id) == $etudiant->id ? 'selected' : '' }}>
                                {{ $etudiant->name }} - {{ $etudiant->email }}
                            </option>
                        @endforeach
                    </select>
                    @error('etudiant_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="directeur_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Directeur <span class="text-red-500">*</span>
                    </label>
                    <select name="directeur_id" id="directeur_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('directeur_id') border-red-500 @enderror"
                            required>
                        <option value="">Sélectionner un directeur</option>
                        @foreach($enseignants as $enseignant)
                            <option value="{{ $enseignant->id }}" {{ old('directeur_id', $soutenance->directeur_id) == $enseignant->id ? 'selected' : '' }}>
                                {{ $enseignant->name }} - {{ $enseignant->email }}
                            </option>
                        @endforeach
                    </select>
                    @error('directeur_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-4">
                <label for="titre" class="block text-sm font-medium text-gray-700 mb-2">
                    Titre <span class="text-red-500">*</span>
                </label>
                <input type="text" name="titre" id="titre" value="{{ old('titre', $soutenance->titre) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('titre') border-red-500 @enderror"
                       required>
                @error('titre')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="filiere" class="block text-sm font-medium text-gray-700 mb-2">
                    Filière <span class="text-red-500">*</span>
                </label>
                <input type="text" name="filiere" id="filiere" value="{{ old('filiere', $soutenance->filiere) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('filiere') border-red-500 @enderror"
                       required>
                @error('filiere')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                        Type <span class="text-red-500">*</span>
                    </label>
                    <select name="type" id="type"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('type') border-red-500 @enderror"
                            required>
                        <option value="licence" {{ old('type', $soutenance->type) == 'licence' ? 'selected' : '' }}>Licence</option>
                        <option value="master" {{ old('type', $soutenance->type) == 'master' ? 'selected' : '' }}>Master</option>
                        <option value="doctorat" {{ old('type', $soutenance->type) == 'doctorat' ? 'selected' : '' }}>Doctorat</option>
                    </select>
                    @error('type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="salle_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Salle
                    </label>
                    <select name="salle_id" id="salle_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('salle_id') border-red-500 @enderror">
                        <option value="">Sans salle</option>
                        @foreach($salles as $salle)
                            <option value="{{ $salle->id }}" {{ old('salle_id', $soutenance->salle_id) == $salle->id ? 'selected' : '' }}>
                                {{ $salle->nom }} (Capacité: {{ $salle->capacite }})
                            </option>
                        @endforeach
                    </select>
                    @error('salle_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-2">
                        Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="date" id="date" value="{{ old('date', $soutenance->date) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('date') border-red-500 @enderror"
                           required>
                    @error('date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="heure" class="block text-sm font-medium text-gray-700 mb-2">
                        Heure <span class="text-red-500">*</span>
                    </label>
                    <input type="time" name="heure" id="heure" value="{{ old('heure', $soutenance->heure) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('heure') border-red-500 @enderror"
                           required>
                    @error('heure')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-4">
                <label for="statut" class="block text-sm font-medium text-gray-700 mb-2">
                    Statut <span class="text-red-500">*</span>
                </label>
                <select name="statut" id="statut"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('statut') border-red-500 @enderror"
                        required>
                    <option value="brouillon" {{ old('statut', $soutenance->statut) == 'brouillon' ? 'selected' : '' }}>Brouillon</option>
                    <option value="planifiee" {{ old('statut', $soutenance->statut) == 'planifiee' ? 'selected' : '' }}>Planifiée</option>
                    <option value="confirmee" {{ old('statut', $soutenance->statut) == 'confirmee' ? 'selected' : '' }}>Confirmée</option>
                    <option value="realisee" {{ old('statut', $soutenance->statut) == 'realisee' ? 'selected' : '' }}>Réalisée</option>
                    <option value="annulee" {{ old('statut', $soutenance->statut) == 'annulee' ? 'selected' : '' }}>Annulée</option>
                </select>
                @error('statut')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-3 mt-6">
                <a href="{{ route('secretaire.soutenances.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-bold py-2 px-6 rounded-lg transition">
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