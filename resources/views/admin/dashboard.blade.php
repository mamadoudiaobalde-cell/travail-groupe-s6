@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4><i class="fas fa-shield-alt"></i> Dashboard Administrateur</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> Bienvenue {{ Auth::user()->name }} !
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-4">
                            <div class="card text-white bg-info">
                                <div class="card-body text-center">
                                    <h2>{{ \App\Models\User::count() }}</h2>
                                    <p>Utilisateurs</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-white bg-success">
                                <div class="card-body text-center">
                                    <h2>{{ \App\Models\Salle::count() }}</h2>
                                    <p>Salles</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-white bg-warning">
                                <div class="card-body text-center">
                                    <h2>{{ \App\Models\Soutenance::count() }}</h2>
                                    <p>Soutenances</p>
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