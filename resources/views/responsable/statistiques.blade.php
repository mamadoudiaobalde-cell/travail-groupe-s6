@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center mb-6">
        <a href="{{ route('responsable.dashboard') }}" class="text-gray-600 hover:text-gray-900 mr-4 transition">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="text-2xl font-bold">Statistiques détaillées</h1>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="p-4 bg-gray-50 rounded-lg text-center">
                <h3 class="text-sm font-medium text-gray-500">Licence</h3>
                <p class="text-3xl font-bold text-blue-600">{{ $stats['licence'] ?? 0 }}</p>
                <p class="text-sm text-gray-500">soutenances</p>
            </div>
            <div class="p-4 bg-gray-50 rounded-lg text-center">
                <h3 class="text-sm font-medium text-gray-500">Master</h3>
                <p class="text-3xl font-bold text-green-600">{{ $stats['master'] ?? 0 }}</p>
                <p class="text-sm text-gray-500">soutenances</p>
            </div>
            <div class="p-4 bg-gray-50 rounded-lg text-center">
                <h3 class="text-sm font-medium text-gray-500">Doctorat</h3>
                <p class="text-3xl font-bold text-purple-600">{{ $stats['doctorat'] ?? 0 }}</p>
                <p class="text-sm text-gray-500">soutenances</p>
            </div>
        </div>

        <hr class="my-6">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-lg font-semibold mb-4">Par filière</h3>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="text-left text-xs font-medium text-gray-500 uppercase">Filière</th>
                            <th class="text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($stats['par_filiere'] ?? [] as $item)
                            <tr>
                                <td class="py-2 text-sm">{{ $item->filiere }}</td>
                                <td class="py-2 text-sm text-right font-semibold">{{ $item->count }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="py-2 text-center text-gray-500">Aucune donnée</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div>
                <h3 class="text-lg font-semibold mb-4">Évolution mensuelle</h3>
                <div class="space-y-3">
                    @forelse($stats['evolution'] ?? [] as $mois)
                        <div>
                            <div class="flex justify-between text-sm">
                                <span>{{ $mois->mois }}</span>
                                <span>{{ $mois->count }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" 
                                     style="width: {{ $stats['total'] > 0 ? ($mois->count / $stats['total']) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500">Aucune donnée disponible</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection