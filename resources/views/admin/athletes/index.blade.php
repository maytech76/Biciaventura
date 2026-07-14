@extends('admin.layouts.master')

@section('title', 'Atletas')

@section('content')

    <div class="compact-wrapper">
        <div class="page-body-wrapper">
            <div class="page-body">

                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12">

                         {{-- Start Table--}} 
                        <div class="card card-table">
                            <div class="card-body">
                                
                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-0 mb-2">
                                    <h3 class="fw-bold">Listado de atletas</h3>
                                    <form class="d-inline-flex">
                                        <a href="javascript:void(0)" 
                                            class="align-items-center btn btn-theme d-flex" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#createAthleteModal">
                                            <i data-feather="plus-square"></i>+ Nuevo
                                        </a>
                                    </form>
                                </div>


                                @if(session('error'))
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        {{ session('error') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                @endif

                                <div class="table-responsive category-table">
                                    <div>
                                        <table class="table theme-table" id="table_id">
                                            <thead>
                                                <tr>
                                                    <th>Foto</th>
                                                    <th>Nombre</th>
                                                    <th>Género</th>
                                                    <th>Email</th>
                                                    <th>Estado</th>
                                                    <th>Status</th>
                                                    <th>Opciones</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @forelse ($athletes as $athlete)
                                                <tr>
                                                    <td>
                                                        <img src="{{ asset('storage/athletes/' . $athlete->photo) }}" 
                                                             alt="{{ $athlete->name }}"
                                                             class="img-fluid rounded-circle shadow-lg" 
                                                             width="40" 
                                                             style="box-shadow: 0 2px 2px rgba(40, 40, 40, 0.1);">
                                                    </td>
                                                    <td class="text-start">{{ $athlete->name }} {{ $athlete->last_name }}</td>
                                                    <td>
                                                        @if ($athlete->gender == 'masculino')
                                                            <span class="text-primary"><i class="ri-men-line me-1"></i>MASCULINO</span>
                                                        @elseif ($athlete->gender == 'femenino')
                                                            <span class="text-danger"><i class="ri-women-line me-1"></i>FEMENINO</span>
                                                        @else
                                                            <span class="text-secondary">N/A</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-start">{{ $athlete->email }}</td>
                                                    <td class="text-start">{{ $athlete->state->name ?? 'N/A' }}</td>
                                                   
                                                    <td>
                                                        @if($athlete->status == 'activo')
                                                            <span class="p-1 px-2" style="background:#099553 ; color: white; border-bottom: none; border-radius:5px;">Activo</span>
                                                        @elseif($athlete->status == 'inactivo')
                                                            <span class="py-1 px-2" style="background:#ba1717 ; color: white; border-bottom: none; border-radius:5px;">Inactivo</span>
                                                        @else
                                                            <span class="py-1 px-2" style="background:#f0ad4e ; color: white; border-bottom: none; border-radius:5px;">Suspendido</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <ul>
                                                            <li>
                                                                <a href="javascript:void(0)" class="view-athlete" data-athlete-id="{{ $athlete->id }}">
                                                                    <i class="ri-eye-line"></i>
                                                                </a>
                                                            </li>

                                                            <li>
                                                                <a href="javascript:void(0)" 
                                                                    class="text-warning edit-athlete" 
                                                                    data-id="{{ $athlete->id }}"
                                                                    data-name="{{ $athlete->name }}"
                                                                    data-last_name="{{ $athlete->last_name }}"
                                                                    data-document="{{ $athlete->document }}"
                                                                    data-birth_date="{{ $athlete->birth_date }}"
                                                                    data-gender="{{ $athlete->gender }}"
                                                                    data-phone="{{ $athlete->phone }}"
                                                                    data-state_id="{{ $athlete->state_id }}"
                                                                    data-city_id="{{ $athlete->city_id }}"
                                                                    data-email="{{ $athlete->email }}"
                                                                    data-team_name="{{ $athlete->team_name }}"
                                                                    data-medical_conditions="{{ $athlete->medical_conditions }}"
                                                                    data-allergies="{{ $athlete->allergies }}"
                                                                    data-blood_type="{{ $athlete->blood_type }}"
                                                                    data-emergency_contact_name="{{ $athlete->emergency_contact_name }}"
                                                                    data-emergency_contact_phone="{{ $athlete->emergency_contact_phone }}"
                                                                    data-notes="{{ $athlete->notes }}"
                                                                    data-status="{{ $athlete->status }}">
                                                                        <i class="ri-pencil-line"></i>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="javascript:void(0)" class="text-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $athlete->id }}">
                                                                    <i class="ri-delete-bin-line"></i>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </td>
                                                </tr>   

                                                <!-- Modal de Eliminación -->
                                                <div class="modal fade" id="deleteModal{{ $athlete->id }}" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header" style="background: linear-gradient(135deg, #ec99a2 0%, #a72b38 100%); color: white; border-bottom: none;">
                                                                <h5 class="modal-title" style="color: white;" id="deleteModalLabel">Confirmar Eliminación</h5>
                                                            </div>
                                                            <div class="modal-body">
                                                                ¿Estás seguro de que quieres eliminar el atleta <strong>"{{ $athlete->name }} {{ $athlete->last_name }}"</strong>?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn" style="background-color: #6c757d; color: white; border: none; border-radius: 0.375rem;" data-bs-dismiss="modal">
                                                                    Cancelar
                                                                </button>
                                                                <form action="{{ route('athletes.destroy', $athlete->id) }}" method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn" style="background-color: #bc1a1a; color: white; border: none; border-radius: 0.375rem;">
                                                                      Eliminar
                                                                   </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                @empty
                                                <tr>
                                                    <td colspan="7" class="text-center py-4">
                                                        <div class="text-muted">
                                                            <i class="ri-inbox-line" style="font-size: 48px;"></i>
                                                            <p class="mt-2">No hay atletas registrados</p>
                                                            <a href="javascript:void(0)" class="btn btn-theme btn-sm" data-bs-toggle="modal" data-bs-target="#createAthleteModal">
                                                                <i class="ri-add-line"></i> Registrar al primer Atleta
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>

                                        {{-- Paginacion --}}
                                        <div class="d-flex justify-content-center align-items-center mt-3">
                                            {{ $athletes->links() }}
                                        </div>
                                    </div>
                                </div>           
                            </div>
                        </div> {{-- end table --}}

                        <!-- Modal para Crear Nuevo Atleta -->
                        <div class="modal fade" id="createAthleteModal" tabindex="-1" aria-labelledby="createAthleteModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-xl modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header" style="background: linear-gradient(135deg, #3c8749 0%, #23892a 100%); color: white; border-bottom: none;">
                                        
                                        <h5 class="modal-title" style="color: white;" id="createAthleteModalLabel">
                                            Registro de Nuevo Atleta
                                        </h5>
                                        
                                    </div>
                                    
                                    <form action="{{ route('athletes.store') }}" method="POST" id="createAthleteForm" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                                            <div class="row">
                                                
                                                
                                                {{-- Foto --}}
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label fw-bold">Foto de Perfil</label>
                                                    <input type="file" 
                                                        class="form-control border" 
                                                        id="photo" 
                                                        name="photo" 
                                                        accept="image/*">
                                                    <small class="text-muted">Formatos permitidos: JPG, PNG, GIF, WEBP (Máx. 2MB)</small>
                                                    
                                                    {{-- Vista previa --}}
                                                    <div class="mt-2" id="photo-preview-container" style="display: none;">
                                                        <img id="photo-preview" src="#" alt="Vista previa" 
                                                            class="img-fluid rounded-circle shadow" 
                                                            style="width: 100px; height: 100px; object-fit: cover;">
                                                    </div>
                                                    
                                                    <div class="invalid-feedback" id="photo-error"></div>
                                                </div>

                                                {{-- Documento --}}
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-bold">Cedula Identidad <span class="text-danger">*</span></label>
                                                    <input type="text" 
                                                           class="form-control border" 
                                                           id="document" 
                                                           name="document" 
                                                           placeholder="Ej: 1234567" 
                                                           required>
                                                    <div class="invalid-feedback" id="document-error"></div>
                                                </div>

                                                {{-- Email --}}
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                                                    <input type="email" 
                                                           class="form-control border" 
                                                           id="email" 
                                                           name="email" 
                                                           placeholder="Ej: atleta@email.com" 
                                                           required>
                                                    <div class="invalid-feedback" id="email-error"></div>
                                                </div>

                                                {{-- Nombre --}}
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-bold">Nombre Completo<span class="text-danger">*</span></label>
                                                    <input type="text" 
                                                           class="form-control border" 
                                                           id="name" 
                                                           name="name" 
                                                           placeholder="Ej: Juan Carlos" 
                                                           required>
                                                    <div class="invalid-feedback" id="name-error"></div>
                                                </div>

                                                {{-- Apellido --}}
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-bold">Apellidos<span class="text-danger">*</span></label>
                                                    <input type="text" 
                                                           class="form-control border" 
                                                           id="last_name" 
                                                           name="last_name" 
                                                           placeholder="Ej: Pérez Gómez" 
                                                           required>
                                                    <div class="invalid-feedback" id="last_name-error"></div>
                                                </div>

                                                {{-- Fecha de Nacimiento --}}
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label fw-bold">Fecha de Nacimiento <span class="text-danger">*</span></label>
                                                    <input type="date" 
                                                           class="form-control border" 
                                                           id="birth_date" 
                                                           name="birth_date" 
                                                           required>
                                                    <div class="invalid-feedback" id="birth_date-error"></div>
                                                </div>

                                                {{-- Género --}}
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label fw-bold">Género <span class="text-danger">*</span></label>
                                                    <select class="form-select border" id="gender" name="gender" required>
                                                        <option value="">Seleccione un Género</option>
                                                        <option value="masculino">Masculino</option>
                                                        <option value="femenino">Femenino</option>
                                                    </select>
                                                    <div class="invalid-feedback" id="gender-error"></div>
                                                </div>

                                                {{-- Teléfono --}}
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label fw-bold">Teléfono</label>
                                                    <input type="text" 
                                                           class="form-control border" 
                                                           id="phone" 
                                                           name="phone" 
                                                           placeholder="Ej: 0412-1234-5678">
                                                    <div class="invalid-feedback" id="phone-error"></div>
                                                </div>

                                                {{-- Estado (Ubicación) --}}
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-bold">Estado</label>
                                                    <select class="form-select border" id="state_id" name="state_id">
                                                        <option value="">Seleccione un Estado</option>
                                                        @foreach($states as $state)
                                                            <option value="{{ $state->id }}">{{ $state->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="invalid-feedback" id="state_id-error"></div>
                                                </div>

                                                {{-- Ciudad --}}
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-bold">Ciudad</label>
                                                    <select class="form-select border" id="city_id" name="city_id">
                                                        <option value="">Seleccione una Ciudad</option>
                                                        @foreach($cities as $city)
                                                            <option value="{{ $city->id }}">{{ $city->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="invalid-feedback" id="city_id-error"></div>
                                                </div>

                                                {{-- Equipo --}}
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-bold"> Nombre del Equipo</label>
                                                    <input type="text" 
                                                           class="form-control border" 
                                                           id="team_name" 
                                                           name="team_name" 
                                                           placeholder="Ej: Equipo Alpha">
                                                    <div class="invalid-feedback" id="team_name-error"></div>
                                                </div>

                                                {{-- Tipo de Sangre --}}
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-bold">Tipo de Sangre</label>
                                                    <select class="form-select border" id="blood_type" name="blood_type">
                                                        <option value="">Seleccione un Tipo</option>
                                                        <option value="A+">A+</option>
                                                        <option value="A-">A-</option>
                                                        <option value="B+">B+</option>
                                                        <option value="B-">B-</option>
                                                        <option value="AB+">AB+</option>
                                                        <option value="AB-">AB-</option>
                                                        <option value="O+">O+</option>
                                                        <option value="O-">O-</option>
                                                    </select>
                                                    <div class="invalid-feedback" id="blood_type-error"></div>
                                                </div>

                                                {{-- Condiciones Médicas --}}
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label fw-bold">Condiciones Médicas</label>
                                                    <textarea class="form-control border" 
                                                              id="medical_conditions" 
                                                              name="medical_conditions" 
                                                              rows="2" 
                                                              placeholder="Ej: Asma controlada, Diabetes tipo 2"></textarea>
                                                    <div class="invalid-feedback" id="medical_conditions-error"></div>
                                                </div>

                                                {{-- Alergias --}}
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label fw-bold">Alergias</label>
                                                    <textarea class="form-control border" 
                                                              id="allergies" 
                                                              name="allergies" 
                                                              rows="2" 
                                                              placeholder="Ej: Polen, Mariscos, Penicilina"></textarea>
                                                    <div class="invalid-feedback" id="allergies-error"></div>
                                                </div>

                                                {{-- Contacto de Emergencia --}}
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-bold">Contacto de Emergencia</label>
                                                    <input type="text" 
                                                           class="form-control border" 
                                                           id="emergency_contact_name" 
                                                           name="emergency_contact_name" 
                                                           placeholder="Ej: María Gómez">
                                                    <div class="invalid-feedback" id="emergency_contact_name-error"></div>
                                                </div>

                                                {{-- Teléfono de Emergencia --}}
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-bold">Teléfono de Emergencia</label>
                                                    <input type="text" 
                                                           class="form-control border" 
                                                           id="emergency_contact_phone" 
                                                           name="emergency_contact_phone" 
                                                           placeholder="Ej: 0414-9876-5432">
                                                    <div class="invalid-feedback" id="emergency_contact_phone-error"></div>
                                                </div>

                                                {{-- Notas --}}
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label fw-bold">Observación</label>
                                                    <textarea class="form-control border" 
                                                              id="notes" 
                                                              name="notes" 
                                                              rows="2" 
                                                              placeholder="Observaciones adicionales sobre el atleta"></textarea>
                                                    <div class="invalid-feedback" id="notes-error"></div>
                                                </div>

                                                {{-- Status --}}
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label fw-bold">Status <span class="text-danger">*</span></label>
                                                    <select class="form-select border" id="status" name="status" required>
                                                        <option value="activo">Activo</option>
                                                        <option value="inactivo">Inactivo</option>
                                                        <option value="suspendido">Suspendido</option>
                                                    </select>
                                                    <div class="invalid-feedback" id="status-error"></div>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="modal-footer" style="border-top: 1px solid #dee2e6;">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                               Cancelar
                                            </button>
                                            <button type="submit" class="btn btn-theme" id="btnSubmit">
                                               Registrar 
                                            </button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div> {{-- Final Modal nuevo Atleta --}}

                        <!-- Modal para Ver Atleta -->
                        <div class="modal fade" id="viewAthleteModal" tabindex="-1" aria-labelledby="viewAthleteModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-bottom: none;">
                                        <h5 class="modal-title" style="color: white;" id="viewAthleteModalLabel">
                                            Detalles del Atleta
                                        </h5>
                                        
                                    </div>
                                    
                                    <div class="modal-body" style="padding: 2rem;">
                                        <!-- Contenido cargado vía AJAX -->
                                        <div id="athlete-detail-content">
                                            <div class="text-center py-4">
                                                <div class="spinner-border text-primary" role="status">
                                                    <span class="visually-hidden">Cargando...</span>
                                                </div>
                                                <p class="mt-2 text-muted">Cargando información del atleta...</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-footer" style="border-top: 1px solid #dee2e6;">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                            Cerrar
                                        </button>
                                        <button type="button" class="btn btn-primary" id="btnEditFromView">
                                            Editar 
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div><!--Final Modal Ver Detalles del Atleta -->

                        {{-- Modal para Editar Atleta --}}
                        <div class="modal fade" id="editAthleteModal" tabindex="-1" aria-labelledby="editAthleteModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-xl modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header" style="background: linear-gradient(135deg, #f7971e 0%, #ffd200 100%); color: black; border-bottom: none;">
                                        <h5 class="modal-title" style="color: black;" id="editAthleteModalLabel">
                                            Editar Ficha del Atleta
                                        </h5>
                                       
                                    </div>
                                    
                                    <form id="editAthleteForm" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                                            <div class="row">
                                                
                                                {{-- Foto y Vista Previa --}}
                                                <div class="col-md-12 mb-3">
                                                    <div class="row align-items-center">
                                                        <div class="col-md-2 text-center">
                                                            <label class="form-label fw-bold">Foto Actual</label>
                                                            <img id="edit-photo-preview" 
                                                                src="{{ asset('images/default-avatar.png') }}" 
                                                                alt="Foto del atleta" 
                                                                class="img-fluid rounded-circle shadow-lg" 
                                                                style="width: 100px; height: 100px; object-fit: cover; border: 3px solid #f7971e;">
                                                        </div>
                                                        <div class="col-md-10">
                                                            <label class="form-label fw-bold">Cambiar Foto</label>
                                                            <input type="file" 
                                                                class="form-control border" 
                                                                id="edit_photo" 
                                                                name="photo" 
                                                                accept="image/*">
                                                            <small class="text-muted">Formatos permitidos: JPG, PNG, GIF, WEBP (Máx. 2MB)</small>
                                                            <div class="invalid-feedback" id="edit-photo-error"></div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Documento --}}
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-bold">Documento <span class="text-danger">*</span></label>
                                                    <input type="text" 
                                                        class="form-control border" 
                                                        id="edit_document" 
                                                        name="document" 
                                                        placeholder="Ej: DOC1234567" 
                                                        required>
                                                    <div class="invalid-feedback" id="edit-document-error"></div>
                                                </div>

                                                {{-- Email --}}
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                                                    <input type="email" 
                                                        class="form-control border" 
                                                        id="edit_email" 
                                                        name="email" 
                                                        placeholder="Ej: atleta@email.com" 
                                                        required>
                                                    <div class="invalid-feedback" id="edit-email-error"></div>
                                                </div>

                                                {{-- Nombre --}}
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-bold">Nombre <span class="text-danger">*</span></label>
                                                    <input type="text" 
                                                        class="form-control border" 
                                                        id="edit_name" 
                                                        name="name" 
                                                        placeholder="Ej: Juan Carlos" 
                                                        required>
                                                    <div class="invalid-feedback" id="edit-name-error"></div>
                                                </div>

                                                {{-- Apellido --}}
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-bold">Apellido <span class="text-danger">*</span></label>
                                                    <input type="text" 
                                                        class="form-control border" 
                                                        id="edit_last_name" 
                                                        name="last_name" 
                                                        placeholder="Ej: Pérez Gómez" 
                                                        required>
                                                    <div class="invalid-feedback" id="edit-last_name-error"></div>
                                                </div>

                                                {{-- Fecha de Nacimiento --}}
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label fw-bold">Fecha de Nacimiento <span class="text-danger">*</span></label>
                                                    <input type="date" 
                                                        class="form-control border" 
                                                        id="edit_birth_date" 
                                                        name="birth_date" 
                                                        required>
                                                    <div class="invalid-feedback" id="edit-birth_date-error"></div>
                                                </div>

                                                {{-- Género --}}
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label fw-bold">Género <span class="text-danger">*</span></label>
                                                    <select class="form-select border" id="edit_gender" name="gender" required>
                                                        <option value="">Seleccione un Género</option>
                                                        <option value="masculino">Masculino</option>
                                                        <option value="femenino">Femenino</option>
                                                    </select>
                                                    <div class="invalid-feedback" id="edit-gender-error"></div>
                                                </div>

                                                {{-- Teléfono --}}
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label fw-bold">Teléfono</label>
                                                    <input type="text" 
                                                        class="form-control border" 
                                                        id="edit_phone" 
                                                        name="phone" 
                                                        placeholder="Ej: 555-1234-5678">
                                                    <div class="invalid-feedback" id="edit-phone-error"></div>
                                                </div>

                                                {{-- Estado (Ubicación) --}}
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-bold">Estado</label>
                                                    <select class="form-select border" id="edit_state_id" name="state_id">
                                                        <option value="">Seleccione un Estado</option>
                                                        @foreach($states as $state)
                                                            <option value="{{ $state->id }}">{{ $state->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="invalid-feedback" id="edit-state_id-error"></div>
                                                </div>

                                                {{-- Ciudad --}}
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-bold">Ciudad</label>
                                                    <select class="form-select border" id="edit_city_id" name="city_id">
                                                        <option value="">Seleccione una Ciudad</option>
                                                        @foreach($cities as $city)
                                                            <option value="{{ $city->id }}">{{ $city->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="invalid-feedback" id="edit-city_id-error"></div>
                                                </div>

                                                {{-- Equipo --}}
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-bold">Equipo</label>
                                                    <input type="text" 
                                                        class="form-control border" 
                                                        id="edit_team_name" 
                                                        name="team_name" 
                                                        placeholder="Ej: Equipo Alpha">
                                                    <div class="invalid-feedback" id="edit-team_name-error"></div>
                                                </div>

                                                {{-- Tipo de Sangre --}}
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-bold">Tipo de Sangre</label>
                                                    <select class="form-select border" id="edit_blood_type" name="blood_type">
                                                        <option value="">Seleccione un Tipo</option>
                                                        <option value="A+">A+</option>
                                                        <option value="A-">A-</option>
                                                        <option value="B+">B+</option>
                                                        <option value="B-">B-</option>
                                                        <option value="AB+">AB+</option>
                                                        <option value="AB-">AB-</option>
                                                        <option value="O+">O+</option>
                                                        <option value="O-">O-</option>
                                                    </select>
                                                    <div class="invalid-feedback" id="edit-blood_type-error"></div>
                                                </div>

                                                {{-- Condiciones Médicas --}}
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label fw-bold">Condiciones Médicas</label>
                                                    <textarea class="form-control border" 
                                                            id="edit_medical_conditions" 
                                                            name="medical_conditions" 
                                                            rows="2" 
                                                            placeholder="Ej: Asma controlada, Diabetes tipo 2"></textarea>
                                                    <div class="invalid-feedback" id="edit-medical_conditions-error"></div>
                                                </div>

                                                {{-- Alergias --}}
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label fw-bold">Alergias</label>
                                                    <textarea class="form-control border" 
                                                            id="edit_allergies" 
                                                            name="allergies" 
                                                            rows="2" 
                                                            placeholder="Ej: Polen, Mariscos, Penicilina"></textarea>
                                                    <div class="invalid-feedback" id="edit-allergies-error"></div>
                                                </div>

                                                {{-- Contacto de Emergencia --}}
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-bold">Contacto de Emergencia</label>
                                                    <input type="text" 
                                                        class="form-control border" 
                                                        id="edit_emergency_contact_name" 
                                                        name="emergency_contact_name" 
                                                        placeholder="Ej: María Gómez">
                                                    <div class="invalid-feedback" id="edit-emergency_contact_name-error"></div>
                                                </div>

                                                {{-- Teléfono de Emergencia --}}
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-bold">Teléfono de Emergencia</label>
                                                    <input type="text" 
                                                        class="form-control border" 
                                                        id="edit_emergency_contact_phone" 
                                                        name="emergency_contact_phone" 
                                                        placeholder="Ej: 555-9876-5432">
                                                    <div class="invalid-feedback" id="edit-emergency_contact_phone-error"></div>
                                                </div>

                                                {{-- Notas --}}
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label fw-bold">Notas</label>
                                                    <textarea class="form-control border" 
                                                            id="edit_notes" 
                                                            name="notes" 
                                                            rows="2" 
                                                            placeholder="Observaciones adicionales sobre el atleta"></textarea>
                                                    <div class="invalid-feedback" id="edit-notes-error"></div>
                                                </div>

                                                {{-- Status --}}
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label fw-bold">Status <span class="text-danger">*</span></label>
                                                    <select class="form-select border" id="edit_status" name="status" required>
                                                        <option value="activo">Activo</option>
                                                        <option value="inactivo">Inactivo</option>
                                                        <option value="suspendido">Suspendido</option>
                                                    </select>
                                                    <div class="invalid-feedback" id="edit-status-error"></div>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="modal-footer" style="border-top: 1px solid #dee2e6;">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                Cancelar
                                            </button>
                                            <button type="submit" class="btn" id="btnEditSubmit" style="background: linear-gradient(135deg, #f7971e 0%, #ffd200 100%); color: black; font-weight: bold;">
                                                Actualizar 
                                            </button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div><!--Final Modal Ver Detalles del Atleta -->

                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>

@endsection


@push('scripts')

{{-- Script Insertar nuevo registro --}}
<script>
    $(document).ready(function() {
        // Inicializar DataTable
        $('#table_id').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
            },
            responsive: true,
            pageLength: 10
        });

        // Resetear formulario al cerrar el modal
        $('#createAthleteModal').on('hidden.bs.modal', function () {
            $('#createAthleteForm')[0].reset();
            $('.invalid-feedback').html('');
            $('.form-control, .form-select').removeClass('is-invalid is-valid');
            $('#photo-preview').hide();
        });

        // Vista previa de la foto
        $('#photo').on('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#photo-preview').show().attr('src', e.target.result);
                }
                reader.readAsDataURL(file);
            }
        });

        // Validación en tiempo real al escribir
        $('.form-control, .form-select').on('input change', function() {
            const field = $(this);
            const errorDiv = $(`#${field.attr('name')}-error`);
            
            // Limpiar estado anterior
            field.removeClass('is-invalid is-valid');
            errorDiv.html('');
            
            // Validar campo específico
            validateField(field);
        });

        // Función para validar campos individuales
        function validateField(field) {
            const name = field.attr('name');
            const value = field.val();
            let isValid = true;
            let errorMsg = '';

            switch(name) {
                case 'document':
                    if (!value || value.trim() === '') {
                        isValid = false;
                        errorMsg = 'El documento es obligatorio.';
                    } else if (value.length < 5) {
                        isValid = false;
                        errorMsg = 'El documento debe tener al menos 5 caracteres.';
                    }
                    break;

                case 'name':
                case 'last_name':
                    if (!value || value.trim() === '') {
                        isValid = false;
                        errorMsg = 'Este campo es obligatorio.';
                    } else if (value.length < 2) {
                        isValid = false;
                        errorMsg = 'Debe tener al menos 2 caracteres.';
                    } else if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/.test(value)) {
                        isValid = false;
                        errorMsg = 'Solo se permiten letras.';
                    }
                    break;

                case 'email':
                    if (!value || value.trim() === '') {
                        isValid = false;
                        errorMsg = 'El email es obligatorio.';
                    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                        isValid = false;
                        errorMsg = 'Ingrese un email válido (ej: usuario@dominio.com).';
                    }
                    break;

                case 'birth_date':
                    if (!value) {
                        isValid = false;
                        errorMsg = 'La fecha de nacimiento es obligatoria.';
                    } else {
                        const age = calculateAge(value);
                        if (age < 18) {
                            isValid = false;
                            errorMsg = 'El atleta debe ser mayor de 18 años.';
                        } else if (age > 80) {
                            isValid = false;
                            errorMsg = 'La edad máxima permitida es 80 años.';
                        }
                    }
                    break;

                case 'phone':
                    if (value && !/^[0-9+\-\s()]{7,20}$/.test(value)) {
                        isValid = false;
                        errorMsg = 'Ingrese un número de teléfono válido.';
                    }
                    break;

                case 'gender':
                    if (!value) {
                        isValid = false;
                        errorMsg = 'Seleccione un género.';
                    }
                    break;

                case 'status':
                    if (!value) {
                        isValid = false;
                        errorMsg = 'Seleccione un status.';
                    }
                    break;

                case 'blood_type':
                    if (value && !['A+','A-','B+','B-','AB+','AB-','O+','O-'].includes(value)) {
                        isValid = false;
                        errorMsg = 'Seleccione un tipo de sangre válido.';
                    }
                    break;
            }

            // Mostrar feedback visual
            if (!isValid) {
                field.addClass('is-invalid');
                field.removeClass('is-valid');
                $(`#${name}-error`).html(errorMsg);
            } else if (field.val() && field.val().trim() !== '') {
                field.removeClass('is-invalid');
                field.addClass('is-valid');
                $(`#${name}-error`).html('');
            }

            return isValid;
        }

        // Validar todo el formulario antes de enviar
        $('#createAthleteForm').on('submit', function(e) {
            let isValid = true;
            
            // Validar todos los campos requeridos y con reglas
            const fieldsToValidate = ['document', 'name', 'last_name', 'birth_date', 'gender', 'email', 'status'];
            
            fieldsToValidate.forEach(fieldName => {
                const field = $(`#${fieldName}`);
                if (!validateField(field)) {
                    isValid = false;
                }
            });

            // Validar campos opcionales que tienen reglas
            const optionalFields = ['phone', 'blood_type'];
            optionalFields.forEach(fieldName => {
                const field = $(`#${fieldName}`);
                if (field.val() && field.val().trim() !== '') {
                    if (!validateField(field)) {
                        isValid = false;
                    }
                }
            });

            // Validar foto (tamaño y tipo)
            const photoFile = $('#photo')[0].files[0];
            if (photoFile) {
                const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
                if (!validTypes.includes(photoFile.type)) {
                    $('#photo').addClass('is-invalid');
                    $('#photo-error').html('Formato no permitido. Use JPG, PNG, GIF o WEBP.');
                    isValid = false;
                } else if (photoFile.size > 2048 * 1024) { // 2MB
                    $('#photo').addClass('is-invalid');
                    $('#photo-error').html('La imagen no debe pesar más de 2MB.');
                    isValid = false;
                } else {
                    $('#photo').removeClass('is-invalid');
                    $('#photo').addClass('is-valid');
                    $('#photo-error').html('');
                }
            }

            if (!isValid) {
                e.preventDefault();
                // Scroll al primer error
                $('.is-invalid:first').focus();
                
                // Mostrar mensaje de error general
                Swal.fire({
                    icon: 'warning',
                    title: 'Formulario incompleto',
                    text: 'Por favor, corrija los campos marcados en rojo.',
                    confirmButtonColor: '#764ba2'
                });
            } else {
                // Mostrar indicador de carga
                $('#btnSubmit').html('<span class="spinner-border spinner-border-sm me-2"></span> Registrando...');
                $('#btnSubmit').prop('disabled', true);
            }
        });

        // Función para calcular edad
        function calculateAge(birthDate) {
            const today = new Date();
            const birth = new Date(birthDate);
            let age = today.getFullYear() - birth.getFullYear();
            const monthDiff = today.getMonth() - birth.getMonth();
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birth.getDate())) {
                age--;
            }
            return age;
        }

        // Restaurar botón si el formulario falla
        @if($errors->any())
            $('#btnSubmit').html('<i class="ri-save-line me-1"></i>Registrar Atleta');
            $('#btnSubmit').prop('disabled', false);
        @endif
    });
</script>

{{--  Script Modal detalles del Atleta --}}
<script>
    $(document).ready(function() {
        // ============================================
        // SCRIPT PARA VER ATLETA (Modal Show)
        // ============================================
        
        // Evento click en el botón ver
        $(document).on('click', '.view-athlete', function(e) {
            e.preventDefault();
            
            const athleteId = $(this).data('athlete-id');
            const modal = $('#viewAthleteModal');
            
            // Mostrar loading en el modal
            $('#athlete-detail-content').html(`
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="mt-2 text-muted">Cargando información del atleta...</p>
                </div>
            `);
            
            // Abrir modal
            modal.modal('show');
            
            // Petición AJAX para obtener los datos
            $.ajax({
                url: `/admin/athletes/${athleteId}`,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    renderAthleteDetails(response);
                },
                error: function(xhr) {
                    console.error('Error al cargar atleta:', xhr);
                    $('#athlete-detail-content').html(`
                        <div class="text-center py-4">
                            <i class="ri-error-warning-line" style="font-size: 48px; color: #dc3545;"></i>
                            <p class="mt-2 text-danger">Error al cargar los datos del atleta.</p>
                            <p class="text-muted small">${xhr.responseJSON?.message || 'Intente nuevamente'}</p>
                        </div>
                    `);
                }
            });
        });

        // Función para renderizar los detalles del atleta
        function renderAthleteDetails(athlete) {
            // Determinar género con ícono
            let genderIcon = '';
            let genderClass = '';
            if (athlete.gender === 'masculino') {
                genderIcon = '<i class="ri-men-line me-1"></i>';
                genderClass = 'text-primary';
            } else if (athlete.gender === 'femenino') {
                genderIcon = '<i class="ri-women-line me-1"></i>';
                genderClass = 'text-danger';
            } else {
                genderIcon = '<i class="ri-genderless-line me-1"></i>';
                genderClass = 'text-secondary';
            }

            // Determinar status y color
            let statusBadge = '';
            let statusColor = '';
            let statusText = '';
            if (athlete.status === 'activo') {
                statusBadge = 'Activo';
                statusColor = '#099553';
                statusText = 'Activo';
            } else if (athlete.status === 'inactivo') {
                statusBadge = 'Inactivo';
                statusColor = '#ba1717';
                statusText = 'Inactivo';
            } else if (athlete.status === 'suspendido') {
                statusBadge = 'Suspendido';
                statusColor = '#f0ad4e';
                statusText = 'Suspendido';
            }

            // Determinar URL de la foto
            const photoUrl = (athlete.photo && athlete.has_photo) 
                ? `/storage/athletes/${athlete.photo}` 
                : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(athlete.name + '+' + athlete.last_name) + '&background=667eea&color=fff&size=200';

            // Construir HTML del detalle
            const html = `
                <div class="row">
                    <!-- Columna izquierda: Foto -->
                    <div class="col-md-4 text-center mb-4 mb-md-0">
                        <div class="position-relative d-inline-block">
                            <img src="${photoUrl}" 
                                 alt="${athlete.name} ${athlete.last_name}" 
                                 class="img-fluid rounded-circle shadow-lg" 
                                 style="width: 200px; height: 200px; object-fit: cover; border: 4px solid #667eea;">
                            
                            <span class="position-absolute bottom-0 start-50 translate-middle-x badge" 
                                  style="background: ${statusColor}; color: white; padding: 5px 15px; border-radius: 20px; font-size: 12px;">
                                ${statusText}
                            </span>
                        </div>
                        
                        <h4 class="mt-3 mb-1 fw-bold">${athlete.name} ${athlete.last_name}</h4>
                        <p class="text-muted small mb-0">
                            <i class="ri-team-line me-1"></i>${athlete.team_name || 'Sin equipo'}
                        </p>
                        
                        <div class="mt-3">
                            <button class="btn btn-sm btn-outline-primary me-1" onclick="editAthleteFromView(${athlete.id})">
                                <i class="ri-pencil-line"></i> Editar
                            </button>
                            <button class="btn btn-sm btn-outline-danger" onclick="deleteAthleteFromView(${athlete.id})">
                                <i class="ri-delete-bin-line"></i> Eliminar
                            </button>
                        </div>
                    </div>

                    <!-- Columna derecha: Información -->
                    <div class="col-md-8">
                        <!-- Información Personal -->
                        <div class="card mb-1 shadow-sm">
                            <div class="card-header" style="background: #f8f9fa; border-bottom: 2px solid #667eea;">
                                <h6 class="mb-0 fw-bold">
                                    <i class="ri-user-2-line me-2 text-primary"></i>IINFORMACIÓN PERSONAL
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <strong class="d-block">Documento</strong>
                                        <small>${athlete.document}</small>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <strong class="d-block">Email</strong>
                                        <small><a href="mailto:${athlete.email}">${athlete.email}</a></small>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <strong class="d-block">Teléfono</strong>
                                        <small>${athlete.phone || 'No registrado'}</small>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <strong class="d-block">Fecha de Nacimiento</strong>
                                        <small>${athlete.birth_date_formatted} <span class="text-muted">(${athlete.age} años)</span></small>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <strong class="d-block">Género</strong>
                                        <small class="${genderClass}">${genderIcon} ${athlete.gender.toUpperCase()}</small>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <strong class="d-block">Tipo de Sangre</strong>
                                        <small>${athlete.blood_type || 'No registrado'}</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Ubicación y Equipo -->
                        <div class="card mb-1 shadow-sm">
                            <div class="card-header" style="background: #f8f9fa; border-bottom: 2px solid #667eea;">
                                <h6 class="mb-0 fw-bold">
                                    <i class="ri-map-pin-2-line me-2 text-primary"></i>UBICACIÓN Y EQUIPO
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <strong class="d-block">Estado</strong>
                                        <small>${athlete.state?.name || 'No registrado'}</small>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <strong class="d-block">Ciudad</strong>
                                        <small>${athlete.city?.name || 'No registrado'}</small>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <strong class="d-block">Equipo</strong>
                                        <small>${athlete.team_name || 'Sin equipo'}</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Información Médica -->
                        <div class="card mb-1 shadow-sm">
                            <div class="card-header" style="background: #f8f9fa; border-bottom: 2px solid #667eea;">
                                <h6 class="mb-0 fw-bold">
                                    <i class="ri-heart-pulse-line me-2 text-primary"></i>INFORMACIÓN MÉDICA
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <strong class="d-block">Condiciones Médicas</strong>
                                        <small>${athlete.medical_conditions || 'Ninguna'}</small>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <strong class="d-block">Alergias</strong>
                                        <small>${athlete.allergies || 'Ninguna'}</small>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <strong class="d-block">Contacto de Emergencia</strong>
                                        <small>${athlete.emergency_contact_name || 'No registrado'}</small>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <strong class="d-block">Teléfono de Emergencia</strong>
                                        <small>${athlete.emergency_contact_phone || 'No registrado'}</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        ${athlete.notes ? `
                        <!-- Notas -->
                        <div class="card shadow-sm">
                            <div class="card-header" style="background: #f8f9fa; border-bottom: 2px solid #667eea;">
                                <h6 class="mb-0 fw-bold">
                                    <i class="ri-sticky-note-line me-2 text-primary"></i>Notas
                                </h6>
                            </div>
                            <div class="card-body">
                                <p class="mb-0">${athlete.notes}</p>
                            </div>
                        </div>
                        ` : ''}

                        <!-- Metadatos -->
                        <div class="mt-2 text-muted small">
                            <i class="ri-time-line"></i> Creado: ${new Date(athlete.created_at).toLocaleString('es-ES')}
                            ${athlete.updated_at !== athlete.created_at ? `<br><i class="ri-edit-line"></i> Actualizado: ${new Date(athlete.updated_at).toLocaleString('es-ES')}` : ''}
                        </div>
                    </div>
                </div>
            `;

            // Insertar HTML en el modal
            $('#athlete-detail-content').html(html);
            
            // Guardar ID del atleta en el botón de editar del footer
            $('#btnEditFromView').data('athlete-id', athlete.id);
        }

        // ============================================
        // FUNCIONES GLOBALES PARA ACCIONES
        // ============================================
        
        // Editar desde el modal de vista
        window.editAthleteFromView = function(id) {
            $('#viewAthleteModal').modal('hide');
            // Redirigir a editar
            window.location.href = `/athletes/${id}/edit`;
        };

        // Eliminar desde el modal de vista
        window.deleteAthleteFromView = function(id) {
            if (confirm('¿Estás seguro de que quieres eliminar este atleta?')) {
                $.ajax({
                    url: `/athletes/${id}`,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#viewAthleteModal').modal('hide');
                        // Recargar la página o actualizar la tabla
                        location.reload();
                    },
                    error: function(xhr) {
                        alert('Error al eliminar el atleta: ' + (xhr.responseJSON?.message || 'Intente nuevamente'));
                    }
                });
            }
        };

        // ============================================
        // EVENTOS DEL MODAL
        // ============================================
        
        // Botón Editar del footer del modal
        $(document).on('click', '#btnEditFromView', function() {
            const athleteId = $(this).data('athlete-id');
            if (athleteId) {
                $('#viewAthleteModal').modal('hide');
                window.location.href = `/athletes/${athleteId}/edit`;
            }
        });

        // Limpiar contenido al cerrar el modal
        $('#viewAthleteModal').on('hidden.bs.modal', function() {
            $('#athlete-detail-content').html(`
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="mt-2 text-muted">Cargando información del atleta...</p>
                </div>
            `);
        });
    });
</script>

{{-- Script Modal Editar registro de atleta --}}
<script>
    $(document).ready(function() {
        // ============================================
        // SCRIPT PARA EDITAR ATLETA (Modal Edit)
        // ============================================
        
        // Variable para almacenar el ID del atleta a editar
        let editingAthleteId = null;

        // Evento click en el botón editar
        $(document).on('click', '.edit-athlete', function(e) {
            e.preventDefault();
            
            // Obtener el ID del atleta desde data-id
            editingAthleteId = $(this).data('id');
            
            // Mostrar loading en el modal
            $('#editAthleteModal').modal('show');
            
            // Mostrar spinner en el modal body
            $('#editAthleteModal .modal-body').html(`
                <div class="text-center py-4">
                    <div class="spinner-border text-warning" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="mt-2 text-muted">Cargando datos del atleta...</p>
                </div>
            `);

            // Petición AJAX para obtener los datos
            $.ajax({
                url: `/admin/athletes/${editingAthleteId}/edit`,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    // Restaurar el formulario en el modal
                    const modalBody = `
                        <div class="row">
                            <!-- Foto y Vista Previa -->
                            <div class="col-md-12 mb-3">
                                <div class="row align-items-center">
                                    <div class="col-md-2 text-center">
                                        <label class="form-label fw-bold">Foto Actual</label>
                                        <img id="edit-photo-preview" 
                                             src="${response.photo_url || '{{ asset('images/default-avatar.png') }}'}" 
                                             alt="Foto del atleta" 
                                             class="img-fluid rounded-circle shadow-lg" 
                                             style="width: 100px; height: 100px; object-fit: cover; border: 3px solid #f7971e;">
                                    </div>
                                    <div class="col-md-10">
                                        <label class="form-label fw-bold">Cambiar Foto</label>
                                        <input type="file" 
                                               class="form-control border" 
                                               id="edit_photo" 
                                               name="photo" 
                                               accept="image/*">
                                        <small class="text-muted">Formatos permitidos: JPG, PNG, GIF, WEBP (Máx. 2MB)</small>
                                        <div class="invalid-feedback" id="edit-photo-error"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Documento -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Documento <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control border" 
                                       id="edit_document" 
                                       name="document" 
                                       value="${response.document}" 
                                       required>
                                <div class="invalid-feedback" id="edit-document-error"></div>
                            </div>

                            <!-- Email -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                                <input type="email" 
                                       class="form-control border" 
                                       id="edit_email" 
                                       name="email" 
                                       value="${response.email}" 
                                       required>
                                <div class="invalid-feedback" id="edit-email-error"></div>
                            </div>

                            <!-- Nombre -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Nombre <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control border" 
                                       id="edit_name" 
                                       name="name" 
                                       value="${response.name}" 
                                       required>
                                <div class="invalid-feedback" id="edit-name-error"></div>
                            </div>

                            <!-- Apellido -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Apellido <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control border" 
                                       id="edit_last_name" 
                                       name="last_name" 
                                       value="${response.last_name}" 
                                       required>
                                <div class="invalid-feedback" id="edit-last_name-error"></div>
                            </div>

                            <!-- Fecha de Nacimiento -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Fecha de Nacimiento <span class="text-danger">*</span></label>
                                <input type="date" 
                                       class="form-control border" 
                                       id="edit_birth_date" 
                                       name="birth_date" 
                                       value="${response.birth_date_formatted}" 
                                       required>
                                <div class="invalid-feedback" id="edit-birth_date-error"></div>
                            </div>

                            <!-- Género -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Género <span class="text-danger">*</span></label>
                                <select class="form-select border" id="edit_gender" name="gender" required>
                                    <option value="">Seleccione un Género</option>
                                    <option value="masculino" ${response.gender === 'masculino' ? 'selected' : ''}>Masculino</option>
                                    <option value="femenino" ${response.gender === 'femenino' ? 'selected' : ''}>Femenino</option>
                                </select>
                                <div class="invalid-feedback" id="edit-gender-error"></div>
                            </div>

                            <!-- Teléfono -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Teléfono</label>
                                <input type="text" 
                                       class="form-control border" 
                                       id="edit_phone" 
                                       name="phone" 
                                       value="${response.phone || ''}" 
                                       placeholder="Ej: 555-1234-5678">
                                <div class="invalid-feedback" id="edit-phone-error"></div>
                            </div>

                            <!-- Estado -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Estado</label>
                                <select class="form-select border" id="edit_state_id" name="state_id">
                                    <option value="">Seleccione un Estado</option>
                                    @foreach($states as $state)
                                        <option value="{{ $state->id }}" ${response.state_id == {{ $state->id }} ? 'selected' : ''}>
                                            {{ $state->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id="edit-state_id-error"></div>
                            </div>

                            <!-- Ciudad -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Ciudad</label>
                                <select class="form-select border" id="edit_city_id" name="city_id">
                                    <option value="">Seleccione una Ciudad</option>
                                    @foreach($cities as $city)
                                        <option value="{{ $city->id }}" ${response.city_id == {{ $city->id }} ? 'selected' : ''}>
                                            {{ $city->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id="edit-city_id-error"></div>
                            </div>

                            <!-- Equipo -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Equipo</label>
                                <input type="text" 
                                       class="form-control border" 
                                       id="edit_team_name" 
                                       name="team_name" 
                                       value="${response.team_name || ''}" 
                                       placeholder="Ej: Equipo Alpha">
                                <div class="invalid-feedback" id="edit-team_name-error"></div>
                            </div>

                            <!-- Tipo de Sangre -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Tipo de Sangre</label>
                                <select class="form-select border" id="edit_blood_type" name="blood_type">
                                    <option value="">Seleccione un Tipo</option>
                                    <option value="A+" ${response.blood_type === 'A+' ? 'selected' : ''}>A+</option>
                                    <option value="A-" ${response.blood_type === 'A-' ? 'selected' : ''}>A-</option>
                                    <option value="B+" ${response.blood_type === 'B+' ? 'selected' : ''}>B+</option>
                                    <option value="B-" ${response.blood_type === 'B-' ? 'selected' : ''}>B-</option>
                                    <option value="AB+" ${response.blood_type === 'AB+' ? 'selected' : ''}>AB+</option>
                                    <option value="AB-" ${response.blood_type === 'AB-' ? 'selected' : ''}>AB-</option>
                                    <option value="O+" ${response.blood_type === 'O+' ? 'selected' : ''}>O+</option>
                                    <option value="O-" ${response.blood_type === 'O-' ? 'selected' : ''}>O-</option>
                                </select>
                                <div class="invalid-feedback" id="edit-blood_type-error"></div>
                            </div>

                            <!-- Condiciones Médicas -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Condiciones Médicas</label>
                                <textarea class="form-control border" 
                                          id="edit_medical_conditions" 
                                          name="medical_conditions" 
                                          rows="2" 
                                          placeholder="Ej: Asma controlada, Diabetes tipo 2">${response.medical_conditions || ''}</textarea>
                                <div class="invalid-feedback" id="edit-medical_conditions-error"></div>
                            </div>

                            <!-- Alergias -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Alergias</label>
                                <textarea class="form-control border" 
                                          id="edit_allergies" 
                                          name="allergies" 
                                          rows="2" 
                                          placeholder="Ej: Polen, Mariscos, Penicilina">${response.allergies || ''}</textarea>
                                <div class="invalid-feedback" id="edit-allergies-error"></div>
                            </div>

                            <!-- Contacto de Emergencia -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Contacto de Emergencia</label>
                                <input type="text" 
                                       class="form-control border" 
                                       id="edit_emergency_contact_name" 
                                       name="emergency_contact_name" 
                                       value="${response.emergency_contact_name || ''}" 
                                       placeholder="Ej: María Gómez">
                                <div class="invalid-feedback" id="edit-emergency_contact_name-error"></div>
                            </div>

                            <!-- Teléfono de Emergencia -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Teléfono de Emergencia</label>
                                <input type="text" 
                                       class="form-control border" 
                                       id="edit_emergency_contact_phone" 
                                       name="emergency_contact_phone" 
                                       value="${response.emergency_contact_phone || ''}" 
                                       placeholder="Ej: 555-9876-5432">
                                <div class="invalid-feedback" id="edit-emergency_contact_phone-error"></div>
                            </div>

                            <!-- Notas -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Notas</label>
                                <textarea class="form-control border" 
                                          id="edit_notes" 
                                          name="notes" 
                                          rows="2" 
                                          placeholder="Observaciones adicionales sobre el atleta">${response.notes || ''}</textarea>
                                <div class="invalid-feedback" id="edit-notes-error"></div>
                            </div>

                            <!-- Status -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Status <span class="text-danger">*</span></label>
                                <select class="form-select border" id="edit_status" name="status" required>
                                    <option value="activo" ${response.status === 'activo' ? 'selected' : ''}>Activo</option>
                                    <option value="inactivo" ${response.status === 'inactivo' ? 'selected' : ''}>Inactivo</option>
                                    <option value="suspendido" ${response.status === 'suspendido' ? 'selected' : ''}>Suspendido</option>
                                </select>
                                <div class="invalid-feedback" id="edit-status-error"></div>
                            </div>
                        </div>
                    `;
                    
                    // Reemplazar el contenido del modal body
                    $('#editAthleteModal .modal-body').html(modalBody);
                    
                    // Mostrar la foto actual
                    $('#edit-photo-preview').attr('src', response.photo_url || '{{ asset('images/default-avatar.png') }}');
                    
                    // Resetear errores
                    $('.invalid-feedback').html('');
                    $('.form-control, .form-select').removeClass('is-invalid is-valid');
                    
                    // Vista previa de la nueva foto
                    $('#edit_photo').on('change', function() {
                        const file = this.files[0];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                $('#edit-photo-preview').attr('src', e.target.result);
                            }
                            reader.readAsDataURL(file);
                        }
                    });
                },
                error: function(xhr) {
                    console.error('Error al cargar atleta:', xhr);
                    $('#editAthleteModal .modal-body').html(`
                        <div class="text-center py-4">
                            <i class="ri-error-warning-line" style="font-size: 48px; color: #dc3545;"></i>
                            <p class="mt-2 text-danger">Error al cargar los datos del atleta.</p>
                            <p class="text-muted small">${xhr.responseJSON?.message || 'Intente nuevamente'}</p>
                        </div>
                    `);
                }
            });
        });

        // ============================================
        // ENVÍO DEL FORMULARIO DE EDICIÓN
        // ============================================
        
        $(document).on('submit', '#editAthleteForm', function(e) {
            e.preventDefault();
            
            // Obtener el ID del atleta
            const athleteId = editingAthleteId;
            
            // Crear FormData para enviar archivos
            const formData = new FormData(this);
            formData.append('_method', 'PUT');
            
            // Mostrar estado de carga
            $('#btnEditSubmit').html('<span class="spinner-border spinner-border-sm me-2"></span> Actualizando...');
            $('#btnEditSubmit').prop('disabled', true);
            
            // Limpiar errores anteriores
            $('.invalid-feedback').html('');
            $('.form-control, .form-select').removeClass('is-invalid is-valid');
            
            // Enviar petición AJAX
            $.ajax({
                url: `/admin/athletes/${athleteId}`,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // Mostrar mensaje de éxito
                        Swal.fire({
                            icon: 'success',
                            title: '¡Actualizado!',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        
                        // Cerrar modal y recargar
                        setTimeout(function() {
                            $('#editAthleteModal').modal('hide');
                            location.reload();
                        }, 2000);
                    }
                },
                error: function(xhr) {
                    // Restaurar botón
                    $('#btnEditSubmit').html('<i class="ri-save-line me-1"></i>Actualizar Atleta');
                    $('#btnEditSubmit').prop('disabled', false);
                    
                    if (xhr.status === 422) {
                        // Errores de validación
                        const errors = xhr.responseJSON.errors;
                        $.each(errors, function(field, messages) {
                            const input = $(`#edit_${field}`);
                            const errorDiv = $(`#edit-${field}-error`);
                            
                            input.addClass('is-invalid');
                            errorDiv.html(messages[0]);
                        });
                        
                        // Scroll al primer error
                        $('.is-invalid:first').focus();
                        
                        Swal.fire({
                            icon: 'warning',
                            title: 'Error de validación',
                            text: 'Por favor, corrija los campos marcados en rojo.',
                            confirmButtonColor: '#f7971e'
                        });
                    } else {
                        // Otros errores
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON?.message || 'Error al actualizar el atleta.',
                            confirmButtonColor: '#f7971e'
                        });
                    }
                }
            });
        });

        // ============================================
        // LIMPIAR MODAL AL CERRAR
        // ============================================
        
        $('#editAthleteModal').on('hidden.bs.modal', function() {
            // Resetear formulario
            $('#editAthleteForm')[0].reset();
            $('.invalid-feedback').html('');
            $('.form-control, .form-select').removeClass('is-invalid is-valid');
            $('#btnEditSubmit').html('<i class="ri-save-line me-1"></i>Actualizar Atleta');
            $('#btnEditSubmit').prop('disabled', false);
            
            // Restaurar contenido del body
            $(this).find('.modal-body').html(`
                <div class="text-center py-4">
                    <div class="spinner-border text-warning" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="mt-2 text-muted">Cargando datos del atleta...</p>
                </div>
            `);
            
            editingAthleteId = null;
        });

        // ============================================
        // VALIDACIÓN EN TIEMPO REAL (Opcional)
        // ============================================
        
        $(document).on('input change', '#editAthleteForm .form-control, #editAthleteForm .form-select', function() {
            const field = $(this);
            const name = field.attr('name');
            
            // Limpiar estado anterior
            field.removeClass('is-invalid is-valid');
            $(`#edit-${name}-error`).html('');
            
            // Validar campos requeridos
            if (field.prop('required')) {
                if (!field.val() || field.val().trim() === '') {
                    field.addClass('is-invalid');
                    $(`#edit-${name}-error`).html('Este campo es obligatorio.');
                } else {
                    field.addClass('is-valid');
                }
            }
            
            // Validar email
            if (name === 'email' && field.val()) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(field.val())) {
                    field.addClass('is-invalid');
                    $(`#edit-${name}-error`).html('Ingrese un email válido.');
                }
            }
        });
    });
</script>


@endpush