@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h4><i class="fas fa-chalkboard-teacher"></i> Dashboard Enseignant</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> Bienvenue {{ Auth::user()->name }} !
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card text-white bg-primary">
                                <div class="card-body text-center">
                                    <h2>{{ \App\Models\Soutenance::where('directeur_id', Auth::id())->count() }}</h2>
                                    <p>Soutenances dirigées</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card text-white bg-warning">
                                <div class="card-body text-center">
                                    <h2>{{ \App\Models\JuryMembre::where('utilisateur_id', Auth::id())->where('statut_confirmation', 'en_attente')->count() }}</h2>
                                    <p>Invitations jury en attente</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection