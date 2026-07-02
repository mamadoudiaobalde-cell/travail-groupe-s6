@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center mb-6">
        <a href="{{ route('secretaire.soutenances.index') }}" class="text-gray-600 hover:text-gray-900 mr-4 transition">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="text-2xl font-bold">Détails de la soutenance</h1>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Étudiant</h3>
                    <p class="text-lg font-semibold">{{ $soutenance->etudiant->name ?? 'Non défini' }}</p>
                    <p class="text-sm text-gray-500">{{ $soutenance->etudiant->email ?? '' }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Directeur</h3>
                    <p class="text-lg font-semibold">{{ $soutenance->directeur->name ?? 'Non défini' }}</p>
                    <p class="text-sm text-gray-500">{{ $soutenance->directeur->email ?? '' }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Titre</h3>
                    <p class="text-lg font-semibold">{{ $soutenance->titre }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Filière</h3>
                    <p class="text-lg font-semibold">{{ $soutenance->filiere }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Type</h3>
                    <p class="text-lg font-semibold">{{ $soutenance->type }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Statut</h3>
                    <span class="px-3 py-1 text-sm rounded-full 
                        @if($soutenance->statut == 'confirmee') bg-green-100 text-green-800
                        @elseif($soutenance->statut == 'planifiee') bg-yellow-100 text-yellow-800
                        @elseif($soutenance->statut == 'realisee') bg-blue-100 text-blue-800
                        @elseif($soutenance->statut == 'annulee') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ $soutenance->statut }}
                    </span>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Date</h3>
                    <p class="text-lg font-semibold">{{ \Carbon\Carbon::parse($soutenance->date)->format('d/m/Y') }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Heure</h3>
                    <p class="text-lg font-semibold">{{ \Carbon\Carbon::parse($soutenance->heure)->format('H:i') }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Salle</h3>
                    <p class="text-lg font-semibold">{{ $soutenance->salle->nom ?? 'Non affectée' }}</p>
                    <p class="text-sm text-gray-500">{{ $soutenance->salle->localisation ?? '' }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Capacité</h3>
                    <p class="text-lg font-semibold">{{ $soutenance->salle->capacite ?? '-' }} places</p>
                </div>
            </div>

            @if($soutenance->salle->equipements ?? false)
            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Équipements</h3>
                <p class="text-gray-700">{{ $soutenance->salle->equipements }}</p>
            </div>
            @endif

            <div class="flex justify-end space-x-3 mt-6">
                <a href="{{ route('secretaire.soutenances.edit', $soutenance) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition">
                    <i class="fas fa-edit"></i> Modifier
                </a>
                <button onclick="window.print()" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg transition">
                    <i class="fas fa-print"></i> Imprimer
                </button>
            </div>
        </div>
    </div>
</div>
@endsection