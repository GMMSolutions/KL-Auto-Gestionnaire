@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content')
<div class="container py-4">
    @guest
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h2 class="mb-0">Bienvenue sur KL Auto Gestionnaire</h2>
                    </div>
                    <div class="card-body">
                        <div class="text-center">
                            <p class="lead">Connectez-vous pour accéder à votre espace de gestion.</p>
                            <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i>Connexion
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h2 class="mb-0">Tableau de bord</h2>
                    </div>
                    <div class="card-body">
                        <!-- Existing dashboard content -->
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <p>Bienvenue, {{ auth()->user()->name }} !</p>
                        <p>Vous êtes connecté avec l'adresse email : {{ auth()->user()->email }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endguest
</div>
@endsection
