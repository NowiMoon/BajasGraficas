@extends('layouts.guest')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reestablecer Contraseña') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="mb-3">
                        {{ __('¿Olvidaste tu contraseña? Ponte en contacto con el administrador.') }}                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
