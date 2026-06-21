@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h4><i class="fas fa-chart-line"></i> Dashboard Responsable Pédagogique</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> Bienvenue {{ Auth::user()->name }} !
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-4">
                            <div class="card text-white bg-primary">
                                <div class="card-body text-center">
                                    <h2>{{ \App\Models\Soutenance::count() }}</h2>
                                    <p>Total soutenances</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-white bg-success">
                                <div class="card-body text-center">
                                    <h2>{{ \App\Models\Soutenance::where('statut', 'realisee')->count() }}</h2>
                                    <p>Soutenances réalisées</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-white bg-warning">
                                <div class="card-body text-center">
                                    <h2>{{ \App\Models\Soutenance::where('statut', 'planifiee')->count() }}</h2>
                                    <p>À venir</p>
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