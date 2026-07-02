@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-warning text-white">
                    <h4><i class="fas fa-user-graduate"></i> Dashboard Étudiant</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> Bienvenue {{ Auth::user()->name }} !
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card text-white bg-info">
                                <div class="card-body text-center">
                                    <h2>{{ \App\Models\Soutenance::where('etudiant_id', Auth::id())->count() }}</h2>
                                    <p>Mes soutenances</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card text-white bg-success">
                                <div class="card-body text-center">
                                    <h2>{{ \App\Models\Soutenance::where('etudiant_id', Auth::id())->where('statut', 'confirmee')->count() }}</h2>
                                    <p>Soutenances confirmées</p>
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