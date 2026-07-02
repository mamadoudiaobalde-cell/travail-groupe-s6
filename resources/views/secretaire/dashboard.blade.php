@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4><i class="fas fa-file-alt"></i> Dashboard Secrétaire Pédagogique</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> Bienvenue {{ Auth::user()->name }} !
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card text-white bg-info">
                                <div class="card-body text-center">
                                    <h2>{{ \App\Models\Soutenance::whereDate('date', now()->toDateString())->count() }}</h2>
                                    <p>Soutenances aujourd'hui</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card text-white bg-warning">
                                <div class="card-body text-center">
                                    <h2>{{ \App\Models\Soutenance::where('statut', 'planifiee')->count() }}</h2>
                                    <p>À confirmer</p>
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