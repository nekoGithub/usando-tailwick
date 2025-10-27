@extends('layouts.vertical', ['title' => 'Editar perfil'])

@section('css')
    <!-- Puedes agregar CSS extra si es necesario -->
@endsection

@section('content')
    @include('layouts.partials/page-title', ['subtitle' => 'Usuario', 'title' => 'Perfil'])

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Actualizar Información del Perfil -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Actualizar Información</h3>
                </div>
                <div class="card-body">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Cambiar Contraseña -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Actualizar Contraseña</h3>
                </div>
                <div class="card-body">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Eliminar Usuario -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title text-danger">Eliminar Cuenta</h3>
                </div>
                <div class="card-body">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>
@endsection

@section('scripts')
    @vite(['resources/js/pages/dashboard-ecommerce.js'])
    @vite(['resources/js/components/timepicker.js'])
@endsection
