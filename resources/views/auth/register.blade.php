@extends('layouts.app')

@section('content')
<div class="container pt-5" style="margin-top: 100px">
    <div class="row justify-content-center">

        <!-- Alertas de Sesión (Éxito / Error) -->
        <div class="col-md-8">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>

        <!-- Módulo: Nuevo usuario -->
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
        <div class="col-md-8 mb-4 mt-4">
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

        {{-- Botón: Eliminar todos los alumnos (solo Administrador) --}}
        @if(Auth::check() && Auth::user()->user_type === 1)
        <div class="col-md-8 mb-4 mt-4">
            <div class="card">
                <div class="card-header" style="background-color: #004A98; color:white">Eliminar todos los alumnos</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('alumnos.destroyAll') }}" class="delete-all-form d-inline">
                        @csrf
                        <input type="hidden" name="admin_password" id="admin_password" value="">
                        <button type="submit" class="btn btn-danger" id="btn-delete-all">
                            Eliminar todos los alumnos
                        </button>
                    </form>
                    <p class="mt-2 text-muted small">Esta acción eliminará todos los registros de alumnos y no se puede deshacer.</p>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection

@section('scripts')
    @parent
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.delete-all-form').forEach(function(form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                if (typeof Swal === 'undefined') {
                    if (confirm('¿Eliminar todos los alumnos? Esta acción no se puede deshacer.')) {
                        form.submit();
                    }
                    return;
                }

                Swal.fire({
                    title: 'Confirmación administrativa',
                    text: 'Para continuar, ingresa tu contraseña de administrador.',
                    input: 'password',
                    inputAttributes: {
                        autocapitalize: 'off',
                        autocorrect: 'off'
                    },
                    inputPlaceholder: 'Contraseña del administrador',
                    showCancelButton: true,
                    confirmButtonText: 'Confirmar y eliminar',
                    cancelButtonText: 'Cancelar',
                    preConfirm: (password) => {
                        if (!password) {
                            Swal.showValidationMessage('La contraseña es obligatoria');
                            return false;
                        }

                        return fetch("{{ route('admin.validate_password') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ password: password })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (!data || data.valid !== true) {
                                const msg = (data && data.message) ? data.message : 'Contraseña incorrecta';
                                Swal.showValidationMessage(msg);
                                return false;
                            }
                            return password;
                        })
                        .catch(() => {
                            Swal.showValidationMessage('Error validando la contraseña');
                            return false;
                        });
                    }
                }).then((result) => {
                    if (result.isConfirmed && result.value) {
                        const hidden = document.getElementById('admin_password');
                        if (hidden) hidden.value = result.value;
                        form.submit();
                    }
                });
            });
        });

        const resetModalEl = document.getElementById('resetPasswordModal');
        const resetForm = document.getElementById('resetPasswordForm');
        const resetUserName = document.getElementById('resetUserName');
        const newPassword = document.getElementById('new_password');
        const newPasswordConfirmation = document.getElementById('new_password_confirmation');
        const resetPassError = document.getElementById('reset-pass-error');

        if (resetModalEl && resetForm && resetUserName) {
            const resetModal = new bootstrap.Modal(resetModalEl);

            document.querySelectorAll('.btn-open-reset').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    resetForm.action = btn.dataset.url;
                    resetUserName.value = btn.dataset.name || '';
                    newPassword.value = '';
                    newPasswordConfirmation.value = '';
                    resetPassError.style.display = 'none';
                    resetPassError.textContent = '';
                    resetModal.show();
                });
            });

            resetForm.addEventListener('submit', function (e) {
                if (newPassword.value !== newPasswordConfirmation.value) {
                    e.preventDefault();
                    resetPassError.textContent = 'Las contraseñas no coinciden.';
                    resetPassError.style.display = 'block';
                    return;
                }
                resetPassError.style.display = 'none';
                resetPassError.textContent = '';
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