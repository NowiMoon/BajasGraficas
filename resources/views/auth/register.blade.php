@extends('layouts.app')

@section('content')
<div class="container pt-5" style="margin-top: 100px">
    <div class="row justify-content-center">

        <!-- Módulo: Nuevo usuario (mantener formulario existente) -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header" style="background-color: #004A98; color:white">{{ __('Nuevo usuario') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Nombre') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="clave_usuario" class="col-md-4 col-form-label text-md-end">{{ __('Clave única') }}</label>

                            <div class="col-md-6">
                                <input id="clave_usuario" type="text" class="form-control @error('clave_usuario') is-invalid @enderror" name="clave_usuario" value="{{ old('clave_usuario') }}" required autocomplete="clave_usuario">

                                @error('clave_usuario')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="user_type" class="col-md-4 col-form-label text-md-end">{{ __('Tipo de usuario') }}</label>

                            <div class="col-md-6">
                                <select id="user_type" class="form-control @error('user_type') is-invalid @enderror" name="user_type" required autocomplete="user_type">
                                    <option value="" disabled selected>Seleccione un tipo de usuario</option>
                                    <option value="2" {{ old('user_type') == 2 ? 'selected' : '' }}>Coordinador</option>
                                    <option value="3" {{ old('user_type') == 3 ? 'selected' : '' }}>Trabajador</option>
                                </select>

                                @error('user_type')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Contraseña') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirmar contraseña') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <a href="{{ route('dashboard') }}" class="btn btn-danger ms-2">
                                    {{ __('Cancelar') }}
                                </a>
                                <button type="submit" class="btn btn-primary" style="background-color: #004A98">
                                    {{ __('Registrar') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Módulo: Usuarios existentes -->
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header" style="background-color: #004A98; color:white">Usuarios existentes</div>
                <div class="card-body">
                    @php $roleNames = [1 => 'Administrador', 2 => 'Coordinador', 3 => 'Trabajador']; @endphp

                    @if(isset($users) && $users->count())
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Clave única</th>
                                        <th>Tipo</th>
                                        <th>Estado</th>
                                        <th>Acción</th>
                                        <th>Restablecer contraseña</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->clave_usuario ?? '—' }}</td>
                                            <td>{{ $roleNames[$user->user_type] ?? $user->user_type }}</td>
                                            <td>{{ isset($user->status) ? ($user->status ? 'Activo' : 'Inactivo') : '—' }}</td>
                                            <td>
                                                <form method="POST" action="{{ route('users.toggle', $user->id) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm {{ ($user->status ?? false) ? 'btn-danger' : 'btn-success' }}">
                                                        {{ ($user->status ?? false) ? 'Desactivar' : 'Activar' }}
                                                    </button>
                                                </form>
                                            </td>
                                            <td>
                                                <!-- botón que abre el modal; data-url se usa para asignar action del formulario -->
                                                <button type="button"
                                                    class="btn btn-sm btn-warning btn-open-reset"
                                                    data-url="{{ url('/users/'.$user->id.'/reset-password') }}"
                                                    data-name="{{ $user->name }}">
                                                    Restablecer
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-2 bg-light rounded text-center">No hay usuarios registrados.</div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
@section('scripts')
    @if(session('success'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: '{{ session('success') }}',
            confirmButtonText: 'Aceptar'
        });
    </script>
@endif

@if(session('error'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ session('error') }}',
            confirmButtonText: 'Aceptar'
        });
    </script>
@endif
<script>
document.addEventListener('DOMContentLoaded', function () {
    const resetModalEl = document.getElementById('resetPasswordModal');
    const resetForm = document.getElementById('resetPasswordForm');
    const resetUserName = document.getElementById('resetUserName');
    const newPass = document.getElementById('new_password');
    const newPassConf = document.getElementById('new_password_confirmation');
    const errorBox = document.getElementById('reset-pass-error');

    let bsResetModal = null;
    if (resetModalEl && typeof bootstrap !== 'undefined' && bootstrap.Modal) {
        bsResetModal = new bootstrap.Modal(resetModalEl);
    }

    document.querySelectorAll('.btn-open-reset').forEach(btn => {
        btn.addEventListener('click', function () {
            const url = this.dataset.url;
            const name = this.dataset.name || '';
            if (!resetForm) return;
            resetForm.action = url;
            if (resetUserName) resetUserName.value = name;
            if (newPass) newPass.value = '';
            if (newPassConf) newPassConf.value = '';
            if (errorBox) errorBox.style.display = 'none';

            if (bsResetModal) {
                bsResetModal.show();
            } else if (typeof $ !== 'undefined' && $(resetModalEl).modal) {
                $(resetModalEl).modal('show');
            }
        });
    });

    if (resetForm) {
        resetForm.addEventListener('submit', function (e) {
            if (newPass && newPassConf && newPass.value !== newPassConf.value) {
                e.preventDefault();
                if (errorBox) {
                    errorBox.textContent = 'Las contraseñas no coinciden.';
                    errorBox.style.display = 'block';
                }
                return false;
            }
            const btn = resetForm.querySelector('button[type="submit"]');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = 'Procesando...';
            }
        });
    }
});
</script>
@endsection

<!-- Modal para restablecer contraseña -->
<div class="modal fade" id="resetPasswordModal" tabindex="-1" aria-labelledby="resetPasswordModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="resetPasswordForm" method="POST" action="">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="resetPasswordModalLabel">Restablecer contraseña</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Usuario</label>
            <input type="text" id="resetUserName" class="form-control" readonly>
          </div>
          <div class="mb-3">
            <label for="new_password" class="form-label">Nueva contraseña</label>
            <input type="password" name="new_password" id="new_password" class="form-control" minlength="6" required>
          </div>
          <div class="mb-3">
            <label for="new_password_confirmation" class="form-label">Confirmar contraseña</label>
            <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" minlength="6" required>
          </div>
          <div id="reset-pass-error" class="text-danger small" style="display:none;"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-warning">Restablecer</button>
        </div>
      </form>
    </div>
  </div>
</div>