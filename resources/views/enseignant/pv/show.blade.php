@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center mb-6">
        <a href="{{ route('enseignant.dashboard') }}" class="text-gray-600 hover:text-gray-900 mr-4">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="text-2xl font-bold">Détails du procès-verbal</h1>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Étudiant</h3>
                    <p class="text-lg font-semibold">{{ $pv->soutenance->etudiant->name ?? 'Non défini' }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Titre du mémoire</h3>
                    <p class="text-lg font-semibold">{{ $pv->soutenance->titre }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Date de soutenance</h3>
                    <p class="text-lg font-semibold">{{ \Carbon\Carbon::parse($pv->soutenance->date)->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Filière</h3>
                    <p class="text-lg font-semibold">{{ $pv->soutenance->filiere }}</p>
                </div>
            </div>

            <hr class="my-6">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="p-4 bg-gray-50 rounded-lg text-center">
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Note</h3>
                    <p class="text-3xl font-bold text-blue-600">{{ $pv->note }}/20</p>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg text-center">
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Mention</h3>
                    <p class="text-xl font-semibold text-green-600">{{ $pv->mention ?? 'Non attribuée' }}</p>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg text-center">
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Statut</h3>
                    <span class="px-3 py-1 text-sm rounded-full
                        @if($pv->status == 'brouillon') bg-yellow-100 text-yellow-800
                        @elseif($pv->status == 'valide') bg-green-100 text-green-800
                        @elseif($pv->status == 'signe') bg-blue-100 text-blue-800
                        @elseif($pv->status == 'archive') bg-gray-100 text-gray-800
                        @endif">
                        {{ $pv->status }}
                    </span>
                </div>
            </div>

            @if($pv->observations)
            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Observations</h3>
                <p class="text-gray-700">{{ $pv->observations }}</p>
            </div>
            @endif

            <div class="flex justify-end space-x-3 mt-6">
                <a href="{{ route('enseignant.pv.edit', $pv->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition">
                    <i class="fas fa-edit"></i> Modifier
                </a>
                <a href="#" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg transition">
                    <i class="fas fa-file-pdf"></i> Exporter PDF
                </a>
            </div>
        </div>
    </div>
</div>
@endsection