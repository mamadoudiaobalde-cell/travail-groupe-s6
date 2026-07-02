@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-chart-line text-blue-600"></i> Dashboard Responsable Pédagogique
        </h1>
        <p class="text-gray-600">Bienvenue, {{ Auth::user()->name }} !</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <i class="fas fa-calendar-check text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Total soutenances</p>
                    <p class="text-2xl font-bold">{{ $stats['total'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Réalisées</p>
                    <p class="text-2xl font-bold">{{ $stats['realisees'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-lg">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">En attente</p>
                    <p class="text-2xl font-bold">{{ $stats['en_attente'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-lg">
                    <i class="fas fa-users text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Taux réussite</p>
                    <p class="text-2xl font-bold">{{ $stats['taux_reussite'] ?? 0 }}%</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">
                <i class="fas fa-graduation-cap text-blue-600"></i> Par filière
            </h3>
            <div class="space-y-3">
                @forelse($stats['par_filiere'] ?? [] as $filiere)
                    <div>
                        <div class="flex justify-between text-sm">
                            <span>{{ $filiere->filiere }}</span>
                            <span>{{ $filiere->count }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" 
                                 style="width: {{ $stats['total'] > 0 ? ($filiere->count / $stats['total']) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500">Aucune donnée disponible</p>
                @endforelse
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">
                <i class="fas fa-exclamation-triangle text-yellow-600"></i> Alertes
            </h3>
            <div class="space-y-3">
                <div class="p-3 bg-red-50 rounded-lg">
                    <p class="text-sm text-red-700">
                        <i class="fas fa-circle text-red-500 text-xs mr-2"></i>
                        {{ $stats['sans_salle'] ?? 0 }} soutenance(s) sans salle
                    </p>
                </div>
                <div class="p-3 bg-yellow-50 rounded-lg">
                    <p class="text-sm text-yellow-700">
                        <i class="fas fa-circle text-yellow-500 text-xs mr-2"></i>
                        {{ $stats['jury_incomplet'] ?? 0 }} jury(x) incomplet(s)
                    </p>
                </div>
                <div class="p-3 bg-blue-50 rounded-lg">
                    <p class="text-sm text-blue-700">
                        <i class="fas fa-circle text-blue-500 text-xs mr-2"></i>
                        {{ $stats['sans_pv'] ?? 0 }} soutenance(s) sans PV
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection