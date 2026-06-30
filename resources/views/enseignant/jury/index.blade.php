@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-gavel text-green-600"></i> Mes participations jury
        </h1>
        <p class="text-gray-600">Gérez vos participations aux jurys de soutenance.</p>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Étudiant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Titre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rôle</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($jurys ?? [] as $jury)
                    <tr>
                        <td class="px-6 py-4 text-sm">
                            {{ \Carbon\Carbon::parse($jury->soutenance->date)->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 text-sm">
                            {{ $jury->soutenance->etudiant->name ?? 'Non défini' }}
                        </td>
                        <td class="px-6 py-4 text-sm">
                            {{ Str::limit($jury->soutenance->titre ?? 'Sans titre', 30) }}
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                {{ $jury->role }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($jury->statut_confirmation == 'confirme')
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                    ✅ Confirmé
                                </span>
                            @elseif($jury->statut_confirmation == 'refuse')
                                <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">
                                    ❌ Refusé
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">
                                    ⏳ En attente
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm">
                            @if($jury->statut_confirmation == 'en_attente')
                                <form action="{{ route('enseignant.jury.confirm', $jury->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded-lg hover:bg-green-600 transition mr-2">
                                        <i class="fas fa-check"></i> Accepter
                                    </button>
                                </form>
                                <form action="{{ route('enseignant.jury.refuse', $jury->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded-lg hover:bg-red-600 transition">
                                        <i class="fas fa-times"></i> Refuser
                                    </button>
                                </form>
                            @else
                                <span class="text-gray-400 text-sm">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            <i class="fas fa-info-circle mr-2"></i>
                            Aucune invitation en attente ou participations confirmées.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection