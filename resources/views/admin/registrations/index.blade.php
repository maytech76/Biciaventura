@extends('admin.layouts.master')

@section('title', 'Inscripciones')

@section('content')

    <div class="compact-wrapper">
        <div class="page-body-wrapper">
            <div class="page-body">

                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12">

                        {{-- Start Table --}} 
                        <div class="card card-table">
                            <div class="card-body">
                                
                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-0 mb-2">
                                    <h3 class="fw-bold">Listado de Inscripciones</h3>
                                    <div>
                                        <a href="javascript:void(0)" 
                                            class="align-items-center btn btn-theme d-flex" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#createRegistrationModal">
                                            <i data-feather="plus-square"></i>+ Nueva Inscripción
                                        </a>
                                    </div>
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
                                                    <th>Código</th>
                                                    <th>Foto</th>
                                                    <th>Atleta</th>
                                                    <th>Categoría</th>
                                                    <th>Evento</th>
                                                    <th>Estado</th>
                                                    <th>Opciones</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @forelse ($registrations as $registration)
                                                <tr>
                                                    <td class="text-start">
                                                        <span class="text-black fw-bold">{{ $registration->code }}</span>
                                                    </td>
                                                    <td>
                                                        @if($registration->athlete && $registration->athlete->photo)
                                                            <img src="{{ $registration->athlete->photo_url }}" 
                                                                 alt="{{ $registration->athlete->full_name }}"
                                                                 class="img-fluid rounded-circle shadow-lg" 
                                                                 width="40" 
                                                                 height="40"
                                                                 style="object-fit: cover; box-shadow: 0 2px 2px rgba(40, 40, 40, 0.1);">
                                                        @else
                                                            <img src="{{ asset('images/default-avatar.png') }}" 
                                                                 alt="Sin foto"
                                                                 class="img-fluid rounded-circle shadow-lg" 
                                                                 width="40" 
                                                                 height="40"
                                                                 style="object-fit: cover; box-shadow: 0 2px 2px rgba(40, 40, 40, 0.1);">
                                                        @endif
                                                    </td>
                                                    <td class="text-start">
                                                        <strong>{{ $registration->athlete->full_name ?? 'N/A' }}</strong>
                                                        <br>
                                                        <small class="text-muted">{{ $registration->athlete->document ?? 'Sin documento' }}</small>
                                                        <br>
                                                        <small class="text-muted">
                                                            <i class="ri-calendar-line"></i> 
                                                            Edad: {{ $registration->athlete->age ?? 'N/A' }} años
                                                        </small>
                                                    </td>
                                                    <td class="text-start">
                                                        <span class="text-info fw-bold">{{ $registration->category->name ?? 'N/A' }}</span>
                                                        <br>
                                                        @if($registration->category)
                                                            <small class="text-muted">
                                                                @if($registration->category->min_age)
                                                                    <i class="ri-user-line"></i> {{ $registration->category->min_age }}-{{ $registration->category->max_age ?? '∞' }} años
                                                                @endif
                                                                @if($registration->category->gender_restriction)
                                                                    <br><i class="ri-genderless-line"></i> {{ $registration->category->gender_restriction }}
                                                                @endif
                                                            </small>
                                                        @endif
                                                    </td>
                                                    <td class="text-start">
                                                        <span class="text-primary">{{ $registration->event->name ?? 'N/A' }}</span>
                                                        <br>
                                                        <small class="text-muted">
                                                            <i class="ri-calendar-line"></i> 
                                                            {{ $registration->event->event_date ? \Carbon\Carbon::parse($registration->event->event_date)->format('d/m/Y') : 'N/A' }}
                                                        </small>
                                                    </td>
                                                    
                                                    <td class="fw-bold">
                                                        @php
                                                            $statusColors = [
                                                                'inscrito' => 'primary',
                                                                'pendiente' => 'warning',
                                                                'confirmado' => 'success',
                                                                'retirado' => 'danger'
                                                            ];
                                                            $statusLabels = [
                                                                'inscrito' => 'Inscrito',
                                                                'pendiente' => 'Pendiente',
                                                                'confirmado' => 'Confirmado',
                                                                'retirado' => 'Retirado'
                                                            ];
                                                        @endphp
                                                        <span class="badge bg-{{ $statusColors[$registration->status] ?? 'secondary' }}">
                                                            {{ $statusLabels[$registration->status] ?? $registration->status }}
                                                        </span>
                                                        <br>
                                                        @if($registration->payment_method)
                                                            <small class="text-muted">
                                                                <i class="ri-wallet-line"></i> 
                                                                {{ $registration->payment_method_label }}
                                                            </small>
                                                        @endif
                                                        @if($registration->amount)
                                                            <br>
                                                            <small class="text-muted">
                                                                <i class="ri-money-dollar-circle-line"></i> 
                                                                ${{ number_format($registration->amount, 2) }}
                                                            </small>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <ul class="d-flex gap-2" style="list-style: none; padding: 0; margin: 0;">
                                                            <li>
                                                                <a href="javascript:void(0)" class="view-registration text-primary" data-id="{{ $registration->id }}">
                                                                    <i class="ri-eye-line" style="font-size: 18px;"></i>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="javascript:void(0)" class="edit-registration text-warning" data-id="{{ $registration->id }}">
                                                                    <i class="ri-pencil-line" style="font-size: 18px;"></i>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="javascript:void(0)" class="delete-registration text-danger" data-id="{{ $registration->id }}">
                                                                    <i class="ri-delete-bin-line" style="font-size: 18px;"></i>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </td>
                                                </tr>   
                                                @empty
                                                <tr>
                                                    <td colspan="7" class="text-center py-4">
                                                        <div class="text-muted">
                                                            <i class="ri-inbox-line" style="font-size: 48px;"></i>
                                                            <p class="mt-2">No hay inscripciones registradas</p>
                                                            <a href="javascript:void(0)" class="btn btn-theme btn-sm" data-bs-toggle="modal" data-bs-target="#createRegistrationModal">
                                                                <i class="ri-add-line"></i> Crear primera inscripción
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>

                                        {{-- Paginación --}}
                                        <div class="d-flex justify-content-center align-items-center mt-3">
                                            {{ $registrations->links() }}
                                        </div>
                                    </div>
                                </div>           
                            </div>
                        </div> {{-- end table --}}

                        {{-- ======== MODAL PARA CREAR INSCRIPCIÓN ========== --}}
                        <div class="modal fade" id="createRegistrationModal" tabindex="-1" aria-labelledby="createRegistrationModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-xl modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header" style="background: linear-gradient(135deg, #3c8749 0%, #23892a 100%); color: white; border-bottom: none;">
                                        <h5 class="modal-title" style="color: white;" id="createRegistrationModalLabel">
                                            <i class="ri-file-add-line me-2"></i>Nueva Inscripción
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    
                                    <form id="createRegistrationForm" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                                            <div class="row">
                                                
                                                {{-- Atleta --}}
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-bold">Atleta <span class="text-danger">*</span></label>
                                                    <select class="form-select border" id="reg_athlete_id" name="athlete_id" required>
                                                        <option value="">Seleccione un Atleta</option>
                                                        @foreach($athletes as $athlete)
                                                            <option value="{{ $athlete->id }}">
                                                                {{ $athlete->full_name }} - {{ $athlete->document }} (Edad: {{ $athlete->age }} años)
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <div class="invalid-feedback" id="reg-athlete_id-error"></div>
                                                </div>

                                                {{-- Evento --}}
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-bold">Evento <span class="text-danger">*</span></label>
                                                    <select class="form-select border" id="reg_event_id" name="event_id" required>
                                                        <option value="">Seleccione un Evento</option>
                                                        @foreach($events as $event)
                                                            <option value="{{ $event->id }}">
                                                                {{ $event->name }} - {{ \Carbon\Carbon::parse($event->event_date)->format('d/m/Y') }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <div class="invalid-feedback" id="reg-event_id-error"></div>
                                                </div>

                                                {{-- Categoría (se carga dinámicamente) --}}
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-bold">Categoría <span class="text-danger">*</span></label>
                                                    <select class="form-select border" id="reg_category_id" name="event_category_id" required>
                                                        <option value="">Primero seleccione Atleta y Evento</option>
                                                    </select>
                                                    <div class="invalid-feedback" id="reg-event_category_id-error"></div>
                                                    <div id="eligibility-info" class="mt-2" style="display: none;">
                                                        <div class="alert alert-info alert-sm">
                                                            <i class="ri-information-line me-1"></i> 
                                                            <span id="eligibility-text">Cargando elegibilidad...</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Comprobante de Pago --}}
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-bold">Comprobante de Pago</label>
                                                    <input type="file" 
                                                        class="form-control border" 
                                                        id="reg_image" 
                                                        name="image" 
                                                        accept="image/*">
                                                    <small class="text-muted">Formatos: JPG, PNG, GIF, WEBP (Máx. 2MB)</small>
                                                    <div class="invalid-feedback" id="reg-image-error"></div>
                                                    <div class="mt-2" id="reg-image-preview-container" style="display: none;">
                                                        <img id="reg-image-preview" src="#" alt="Vista previa" 
                                                            class="img-fluid rounded shadow" 
                                                            style="max-width: 150px; max-height: 150px; object-fit: cover;">
                                                    </div>
                                                </div>

                                                {{-- Método de Pago --}}
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label fw-bold">Método de Pago</label>
                                                    <select class="form-select border" id="reg_payment_method" name="payment_method">
                                                        <option value="">Seleccione</option>
                                                        <option value="efectivo">Efectivo</option>
                                                        <option value="transferencia">Transferencia</option>
                                                        <option value="tarjeta">Tarjeta</option>
                                                        <option value="otros">Otros</option>
                                                    </select>
                                                    <div class="invalid-feedback" id="reg-payment_method-error"></div>
                                                </div>

                                                {{-- Referencia de Pago --}}
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label fw-bold">Referencia de Pago</label>
                                                    <input type="text" 
                                                        class="form-control border" 
                                                        id="reg_payment_reference" 
                                                        name="payment_reference" 
                                                        placeholder="Ej: #123456">
                                                    <div class="invalid-feedback" id="reg-payment_reference-error"></div>
                                                </div>

                                                {{-- Monto --}}
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label fw-bold">Monto</label>
                                                    <input type="number" 
                                                        class="form-control border" 
                                                        id="reg_amount" 
                                                        name="amount" 
                                                        placeholder="0.00" 
                                                        step="0.01">
                                                    <div class="invalid-feedback" id="reg-amount-error"></div>
                                                </div>

                                                {{-- Estado --}}
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-bold">Estado <span class="text-danger">*</span></label>
                                                    <select class="form-select border" id="reg_status" name="status" required>
                                                        <option value="pendiente">Pendiente</option>
                                                        <option value="inscrito">Inscrito</option>
                                                        <option value="confirmado">Confirmado</option>
                                                        <option value="retirado">Retirado</option>
                                                    </select>
                                                    <div class="invalid-feedback" id="reg-status-error"></div>
                                                </div>

                                                {{-- Notas --}}
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-bold">Notas</label>
                                                    <textarea class="form-control border" 
                                                            id="reg_notes" 
                                                            name="notes" 
                                                            rows="2" 
                                                            placeholder="Observaciones adicionales"></textarea>
                                                    <div class="invalid-feedback" id="reg-notes-error"></div>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="modal-footer" style="border-top: 1px solid #dee2e6;">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                Cancelar
                                            </button>
                                            <button type="submit" class="btn btn-theme" id="btnRegSubmit">
                                                Registrar
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        {{-- ======= MODAL PARA VER INSCRIPCIÓN ============= --}}
                        <div class="modal fade" id="viewRegistrationModal" tabindex="-1" aria-labelledby="viewRegistrationModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; border-bottom: none;">
                                        <h5 class="modal-title" style="color: white;" id="viewRegistrationModalLabel">
                                            <i class="ri-file-info-line me-2"></i>Detalles de Inscripción
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    
                                    <div class="modal-body" style="padding: 2rem;">
                                        <div id="registration-detail-content">
                                            <div class="text-center py-4">
                                                <div class="spinner-border text-primary" role="status">
                                                    <span class="visually-hidden">Cargando...</span>
                                                </div>
                                                <p class="mt-2 text-muted">Cargando información de la inscripción...</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-footer" style="border-top: 1px solid #dee2e6;">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                            <i class="ri-close-line me-1"></i>Cerrar
                                        </button>
                                        <button type="button" class="btn btn-warning" id="btnEditFromView">
                                            <i class="ri-pencil-line me-1"></i>Editar Inscripción
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ======= MODAL PARA EDITAR INSCRIPCIÓN ========== --}}
                        <div class="modal fade" id="editRegistrationModal" tabindex="-1" aria-labelledby="editRegistrationModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header" style="background: linear-gradient(135deg, #f7971e 0%, #ffd200 100%); color: black; border-bottom: none;">
                                        <h5 class="modal-title" style="color: black;" id="editRegistrationModalLabel">
                                            <i class="ri-edit-2-line me-2"></i>Editar Inscripción
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    
                                    <form id="editRegistrationForm" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                                            <div class="row">
                                                
                                                {{-- Código (solo lectura) --}}
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-bold">Código</label>
                                                    <input type="text" class="form-control border" id="edit_code" readonly style="background: #f8f9fa;">
                                                </div>

                                                {{-- Atleta (solo lectura) --}}
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-bold">Atleta</label>
                                                    <input type="text" class="form-control border" id="edit_athlete_name" readonly style="background: #f8f9fa;">
                                                </div>

                                                {{-- Evento (solo lectura) --}}
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-bold">Evento</label>
                                                    <input type="text" class="form-control border" id="edit_event_name" readonly style="background: #f8f9fa;">
                                                </div>

                                                {{-- Categoría (solo lectura) --}}
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-bold">Categoría</label>
                                                    <input type="text" class="form-control border" id="edit_category_name" readonly style="background: #f8f9fa;">
                                                </div>

                                                {{-- Comprobante de Pago --}}
                                                <div class="col-md-12 mb-3">
                                                    <div class="row align-items-center">
                                                        <div class="col-md-3 text-center">
                                                            <label class="form-label fw-bold">Comprobante Actual</label>
                                                            <img id="edit-image-preview" 
                                                                src="{{ asset('images/no-image.png') }}" 
                                                                alt="Comprobante" 
                                                                class="img-fluid rounded shadow-lg" 
                                                                style="max-width: 100px; max-height: 100px; object-fit: cover;">
                                                        </div>
                                                        <div class="col-md-9">
                                                            <label class="form-label fw-bold">Cambiar Comprobante</label>
                                                            <input type="file" 
                                                                class="form-control border" 
                                                                id="edit_image" 
                                                                name="image" 
                                                                accept="image/*">
                                                            <small class="text-muted">Formatos: JPG, PNG, GIF, WEBP (Máx. 2MB)</small>
                                                            <div class="invalid-feedback" id="edit-image-error"></div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Método de Pago --}}
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label fw-bold">Método de Pago</label>
                                                    <select class="form-select border" id="edit_payment_method" name="payment_method">
                                                        <option value="">Seleccione</option>
                                                        <option value="efectivo">Efectivo</option>
                                                        <option value="transferencia">Transferencia</option>
                                                        <option value="tarjeta">Tarjeta</option>
                                                        <option value="otros">Otros</option>
                                                    </select>
                                                    <div class="invalid-feedback" id="edit-payment_method-error"></div>
                                                </div>

                                                {{-- Referencia de Pago --}}
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label fw-bold">Referencia de Pago</label>
                                                    <input type="text" 
                                                        class="form-control border" 
                                                        id="edit_payment_reference" 
                                                        name="payment_reference" 
                                                        placeholder="Ej: #123456">
                                                    <div class="invalid-feedback" id="edit-payment_reference-error"></div>
                                                </div>

                                                {{-- Monto --}}
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label fw-bold">Monto</label>
                                                    <input type="number" 
                                                        class="form-control border" 
                                                        id="edit_amount" 
                                                        name="amount" 
                                                        placeholder="0.00" 
                                                        step="0.01">
                                                    <div class="invalid-feedback" id="edit-amount-error"></div>
                                                </div>

                                                {{-- Estado --}}
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-bold">Estado <span class="text-danger">*</span></label>
                                                    <select class="form-select border" id="edit_status" name="status" required>
                                                        <option value="pendiente">Pendiente</option>
                                                        <option value="inscrito">Inscrito</option>
                                                        <option value="confirmado">Confirmado</option>
                                                        <option value="retirado">Retirado</option>
                                                    </select>
                                                    <div class="invalid-feedback" id="edit-status-error"></div>
                                                </div>

                                                {{-- Notas --}}
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-bold">Notas</label>
                                                    <textarea class="form-control border" 
                                                            id="edit_notes" 
                                                            name="notes" 
                                                            rows="2" 
                                                            placeholder="Observaciones adicionales"></textarea>
                                                    <div class="invalid-feedback" id="edit-notes-error"></div>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="modal-footer" style="border-top: 1px solid #dee2e6;">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                <i class="ri-close-line me-1"></i>Cancelar
                                            </button>
                                            <button type="submit" class="btn" id="btnEditSubmit" style="background: linear-gradient(135deg, #f7971e 0%, #ffd200 100%); color: black; font-weight: bold;">
                                                <i class="ri-save-line me-1"></i>Actualizar Inscripción
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <style>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
        #eligibility-info .alert {
            padding: 10px 15px;
            margin-top: 10px;
            border-radius: 8px;
            border: none;
        }
        #eligibility-info .alert-success {
            background: #d4edda;
            color: #155724;
        }
        #eligibility-info .alert-warning {
            background: #fff3cd;
            color: #856404;
        }
        #eligibility-info .alert-info {
            background: #d1ecf1;
            color: #0c5460;
        }
        .bg-light {
            background-color: #f8f9fa !important;
        }
        .rounded {
            border-radius: 8px !important;
        }
    </style>
@endpush

@push('scripts')

    <script>
        $(document).ready(function() {
            // Variables para almacenar datos
            let selectedAthleteId = null;
            let selectedEventId = null;
            
        // ========== FILTRAR CATEGORÍAS POR EVENTO ==========
            $('#reg_event_id').on('change', function() {
                const eventId = $(this).val();
                selectedEventId = eventId;
                const athleteId = $('#reg_athlete_id').val();
                
                if (eventId && athleteId) {
                    loadCategories(eventId, athleteId);
                } else if (eventId) {
                    loadCategories(eventId);
                } else {
                    $('#reg_category_id').html('<option value="">Primero seleccione un Evento</option>');
                    $('#eligibility-info').hide();
                }
            });

            $('#reg_athlete_id').on('change', function() {
                const athleteId = $(this).val();
                selectedAthleteId = athleteId;
                const eventId = $('#reg_event_id').val();
                
                if (athleteId && eventId) {
                    loadCategories(eventId, athleteId);
                } else if (athleteId) {
                    $('#reg_category_id').html('<option value="">Seleccione un Evento primero</option>');
                    $('#eligibility-info').hide();
                }
            });

            $('#reg_athlete_id').on('change', function() {
                const athleteId = $(this).val();
                selectedAthleteId = athleteId;
                const eventId = $('#reg_event_id').val();
                
                if (athleteId && eventId) {
                    loadCategories(eventId, athleteId);
                } else if (athleteId) {
                    $('#reg_category_id').html('<option value="">Seleccione un Evento primero</option>');
                    $('#eligibility-info').hide();
                }
            });

            $('#reg_athlete_id').on('change', function() {
                const athleteId = $(this).val();
                selectedAthleteId = athleteId;
                
                if (athleteId && selectedEventId) {
                    loadCategories(selectedEventId, athleteId);
                } else if (athleteId) {
                    // Solo seleccionó atleta, esperar evento
                    $('#reg_category_id').html('<option value="">Seleccione un Evento primero</option>');
                    $('#eligibility-info').hide();
                }
            });

            function loadCategories(eventId, athleteId = null) {
                if (!eventId) return;

                // Mostrar estado de carga
                $('#reg_category_id').html('<option value="">Cargando categorías...</option>');
                $('#eligibility-info').hide();

                // Construir URL con parámetros
                let url = `{{ route('events.categories', ['eventId' => ':eventId']) }}`.replace(':eventId', eventId);
                
                if (athleteId) {
                    url += `?athlete_id=${athleteId}`;
                }

                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function(response) {
                        if (response.success) {
                            let options = '<option value="">Seleccione una Categoría</option>';
                            
                            if (response.categories && response.categories.length > 0) {
                                // Mostrar solo categorías elegibles
                                response.categories.forEach(category => {
                                    let label = category.label || category.name;
                                    options += `<option value="${category.id}">${label}</option>`;
                                });
                                
                                $('#reg_category_id').html(options);
                                
                                // Mostrar información del atleta (solo si hay atleta seleccionado)
                                if (response.has_athlete && response.athlete_info) {
                                    const infoMessage = `
                                        /* <div class="mt-2 p-2 rounded">
                                            <small>
                                                <strong class="text-dark">👤 ${response.athlete_info.full_name}</strong>
                                                (${response.athlete_info.age} años, 
                                                ${response.athlete_info.gender === 'masculino' ? 'Masculino' : 'Femenino'})
                                                <br>
                                                <span>✅ ${response.categories.length} categorías disponibles</span>
                                            </small>
                                        </div> */
                                    `;
                                    
                                /*   $('#eligibility-info').show(); */
                                    $('#eligibility-text').html(infoMessage);
                                    /* $('#eligibility-info .alert').removeClass('alert-danger alert-warning').addClass('alert-success alert-info'); */
                                }
                                
                                // Si hay atleta seleccionado y solo una categoría, seleccionarla automáticamente
                                if (athleteId && response.categories.length === 1) {
                                    $('#reg_category_id').val(response.categories[0].id);
                                    $('#reg_category_id').trigger('change');
                                }
                                
                            } else {
                                // No hay categorías elegibles
                                $('#reg_category_id').html('<option value="">No hay categorías disponibles para este atleta</option>');
                                
                                // Mostrar mensaje simple
                                if (response.has_athlete && response.athlete_info) {
                                    const infoMessage = `
                                        <div class="mt-2">
                                            <div class="alert alert-warning">
                                                <strong>⚠️ ${response.athlete_info.full_name}</strong> 
                                                (${response.athlete_info.age} años, 
                                                ${response.athlete_info.gender === 'masculino' ? 'Masculino' : 'Femenino'})
                                                <br>
                                                <small>No cumple con los requisitos de edad o género para las categorías disponibles.</small>
                                            </div>
                                        </div>
                                    `;
                                    
                                    $('#eligibility-info').show();
                                    $('#eligibility-text').html(infoMessage);
                                    $('#eligibility-info .alert').removeClass('alert-success').addClass('alert-warning');
                                } else {
                                    const infoMessage = `
                                        <div class="mt-2">
                                            <div class="alert alert-warning">
                                                ⚠️ Este evento no tiene categorías disponibles.
                                            </div>
                                        </div>
                                    `;
                                    
                                    $('#eligibility-info').show();
                                    $('#eligibility-text').html(infoMessage);
                                    $('#eligibility-info .alert').removeClass('alert-success').addClass('alert-warning');
                                }
                            }
                        } else {
                            // Error en la respuesta
                            $('#reg_category_id').html('<option value="">Error al cargar categorías</option>');
                            showNotification('❌ ' + (response.message || 'Error al cargar categorías'), 'error');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error loading categories:', xhr);
                        let errorMsg = 'Error al cargar categorías';
                        
                        if (xhr.status === 404) {
                            errorMsg = 'Ruta no encontrada. Verifica la configuración.';
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        }
                        
                        $('#reg_category_id').html('<option value="">Error al cargar categorías</option>');
                        showNotification('❌ ' + errorMsg, 'error');
                    }
                });
            }

            // ========== VERIFICAR ELEGIBILIDAD AL SELECCIONAR CATEGORÍA ==========
            $('#reg_category_id').on('change', function() {
                const categoryId = $(this).val();
                const athleteId = $('#reg_athlete_id').val();
                const eventId = $('#reg_event_id').val();

                if (categoryId && athleteId && eventId) {
                    checkEligibility(athleteId, eventId, categoryId);
                } else {
                    $('#eligibility-info').hide();
                }
            });

            function checkEligibility(athleteId, eventId, categoryId) {
                $.ajax({
                    url: '/registrations/check-eligibility',
                    method: 'POST',
                    data: {
                        athlete_id: athleteId,
                        event_id: eventId,
                        category_id: categoryId,
                        _token: '{{ csrf_token() }}'
                    },
            
                });
            }

            // ========== VISTA PREVIA DE IMAGEN ==========
            $('#reg_image').on('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#reg-image-preview').attr('src', e.target.result);
                        $('#reg-image-preview-container').show();
                    };
                    reader.readAsDataURL(file);
                } else {
                    $('#reg-image-preview-container').hide();
                }
            });

            $('#edit_image').on('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#edit-image-preview').attr('src', e.target.result);
                    };
                    reader.readAsDataURL(file);
                }
            });

        // ========== ENVÍO DEL FORMULARIO DE CREACIÓN (Versión simplificada) ==========
        $('#createRegistrationForm').on('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            $('#btnRegSubmit').prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Guardando...');
            
            $.ajax({
                url: '{{ route("registrations.store") }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        // Cerrar modal
                        $('#createRegistrationModal').modal('hide');
                        
                        // SweetAlert de éxito
                        Swal.fire({
                            position: "center",
                            icon: "success",
                            title: "Inscripción Exitosa",
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            location.reload();
                        });
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'Error al guardar la inscripción';
                    
                    if (xhr.status === 422 && xhr.responseJSON) {
                        if (xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                    }
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errorMessage,
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'Intentar de nuevo'
                    });
                },
                complete: function() {
                    $('#btnRegSubmit').prop('disabled', false).html('Registrar');
                }
            });
        });
            // ========== ENVÍO DEL FORMULARIO DE EDICIÓN ==========
            $('#editRegistrationForm').on('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const registrationId = $(this).data('id');
                
                $('#btnEditSubmit').prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Actualizando...');
                
                $.ajax({
                    url: `/registrations/${registrationId}`,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            showNotification('✅ ' + response.message, 'success');
                            setTimeout(() => {
                                location.reload();
                            }, 1500);
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = '❌ Error al actualizar la inscripción';
                        
                        if (xhr.status === 422 && xhr.responseJSON) {
                            if (xhr.responseJSON.message) {
                                errorMessage = '❌ ' + xhr.responseJSON.message;
                            } else if (xhr.responseJSON.errors) {
                                const errors = xhr.responseJSON.errors;
                                let errorList = [];
                                for (let field in errors) {
                                    errorList.push(errors[field].join(', '));
                                }
                                errorMessage = '❌ ' + errorList.join(' | ');
                            }
                        }
                        
                        showNotification(errorMessage, 'error');
                    },
                    complete: function() {
                        $('#btnEditSubmit').prop('disabled', false).html('Actualizar Inscripción');
                    }
                });
            });

            // ========== VER DETALLES DE INSCRIPCIÓN ==========
            $('.view-registration').on('click', function() {
                const registrationId = $(this).data('id');
                
                $('#registration-detail-content').html(`
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="mt-2 text-muted">Cargando información de la inscripción...</p>
                    </div>
                `);
                
                $('#viewRegistrationModal').modal('show');
                
                $.ajax({
                    url: `/registrations/${registrationId}/show-json`,
                    method: 'GET',
                    success: function(response) {
                        if (response.success) {
                            const reg = response.registration;
                            const $btnEdit = $('#btnEditFromView');
                            $btnEdit.data('id', reg.id);
                            
                            const html = `
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="fw-bold text-primary"><i class="ri-information-line me-2"></i>Información General</h6>
                                        <hr>
                                        <p><strong>Código:</strong> <span class="badge bg-primary">${reg.code}</span></p>
                                        <p><strong>Atleta:</strong> ${reg.athlete ? reg.athlete.full_name : 'N/A'}</p>
                                        <p><strong>Documento:</strong> ${reg.athlete ? reg.athlete.document : 'N/A'}</p>
                                        <p><strong>Edad:</strong> ${reg.athlete ? reg.athlete.age + ' años' : 'N/A'}</p>
                                        <p><strong>Género:</strong> ${reg.athlete ? reg.athlete.gender_label : 'N/A'}</p>
                                        <p><strong>Evento:</strong> ${reg.event ? reg.event.name : 'N/A'}</p>
                                        <p><strong>Fecha Evento:</strong> ${reg.event ? new Date(reg.event.event_date).toLocaleDateString() : 'N/A'}</p>
                                        <p><strong>Categoría:</strong> ${reg.category ? reg.category.name : 'N/A'}</p>
                                        <p><strong>Estado:</strong> <span class="badge bg-${reg.status_color}">${reg.status_label}</span></p>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="fw-bold text-info"><i class="ri-wallet-line me-2"></i>Información de Pago</h6>
                                        <hr>
                                        <p><strong>Método:</strong> ${reg.payment_method ? reg.payment_method_label : 'No especificado'}</p>
                                        <p><strong>Referencia:</strong> ${reg.payment_reference || 'No especificada'}</p>
                                        <p><strong>Monto:</strong> ${reg.amount ? '$' + Number(reg.amount).toFixed(2) : 'No especificado'}</p>
                                        ${reg.image ? `
                                            <p><strong>Comprobante:</strong><br>
                                            <img src="{{ asset('storage') }}/${reg.image}" 
                                                alt="Comprobante" 
                                                class="img-fluid rounded shadow" 
                                                style="max-width: 200px; max-height: 200px; object-fit: cover;">
                                            </p>
                                        ` : '<p><strong>Comprobante:</strong> No subido</p>'}
                                        ${reg.notes ? `<p><strong>Notas:</strong><br><small>${reg.notes}</small></p>` : ''}
                                        <p><small class="text-muted">Creado: ${new Date(reg.created_at).toLocaleString()}</small></p>
                                        ${reg.updated_at ? `<p><small class="text-muted">Actualizado: ${new Date(reg.updated_at).toLocaleString()}</small></p>` : ''}
                                    </div>
                                </div>
                            `;
                            
                            $('#registration-detail-content').html(html);
                        }
                    },
                    error: function() {
                        $('#registration-detail-content').html(`
                            <div class="alert alert-danger">
                                <i class="ri-error-warning-line me-2"></i>
                                Error al cargar los detalles de la inscripción
                            </div>
                        `);
                    }
                });
            });

            // ========== EDITAR DESDE VER DETALLES ==========
            $('#btnEditFromView').on('click', function() {
                const registrationId = $(this).data('id');
                if (registrationId) {
                    $('#viewRegistrationModal').modal('hide');
                    setTimeout(() => {
                        openEditModal(registrationId);
                    }, 500);
                }
            });

            // ========== EDITAR INSCRIPCIÓN ==========
            $('.edit-registration').on('click', function() {
                const registrationId = $(this).data('id');
                openEditModal(registrationId);
            });

            function openEditModal(registrationId) {
                $.ajax({
                    url: `/registrations/${registrationId}/show-json`,
                    method: 'GET',
                    success: function(response) {
                        if (response.success) {
                            const reg = response.registration;
                            
                            $('#editRegistrationForm').data('id', reg.id);
                            $('#edit_code').val(reg.code);
                            $('#edit_athlete_name').val(reg.athlete ? reg.athlete.full_name : 'N/A');
                            $('#edit_event_name').val(reg.event ? reg.event.name : 'N/A');
                            $('#edit_category_name').val(reg.category ? reg.category.name : 'N/A');
                            
                            if (reg.image) {
                                $('#edit-image-preview').attr('src', `{{ asset('storage') }}/${reg.image}`);
                            } else {
                                $('#edit-image-preview').attr('src', `{{ asset('images/no-image.png') }}`);
                            }
                            
                            $('#edit_payment_method').val(reg.payment_method || '');
                            $('#edit_payment_reference').val(reg.payment_reference || '');
                            $('#edit_amount').val(reg.amount || '');
                            $('#edit_status').val(reg.status);
                            $('#edit_notes').val(reg.notes || '');
                            
                            $('#editRegistrationModal').modal('show');
                        }
                    },
                    error: function() {
                        showNotification('❌ Error al cargar los datos para editar', 'error');
                    }
                });
            }

            // ========== ELIMINAR INSCRIPCIÓN ==========
            $('.delete-registration').on('click', function() {
                const registrationId = $(this).data('id');
                if (confirm('¿Estás seguro de eliminar esta inscripción?')) {
                    $.ajax({
                        url: `/registrations/${registrationId}`,
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                showNotification('✅ ' + response.message, 'success');
                                setTimeout(() => {
                                    location.reload();
                                }, 1500);
                            }
                        },
                        error: function() {
                            showNotification('❌ Error al eliminar la inscripción', 'error');
                        }
                    });
                }
            });

            // ========== FUNCIÓN PARA MOSTRAR NOTIFICACIONES ==========
            function showNotification(message, type = 'success') {
                const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
                const icon = type === 'success' ? 'check-circle' : 'alert-circle';
                
                const notification = $(`
                    <div class="alert ${alertClass} alert-dismissible fade show position-fixed top-0 end-0 m-3" 
                        role="alert" 
                        style="z-index: 9999; min-width: 300px; max-width: 500px;">
                        <i class="ri-${icon}-line me-2"></i>
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `);
                
                $('body').append(notification);
                
                setTimeout(() => {
                    notification.alert('close');
                }, 5000);
            }
        });
    </script>

   {{-- Script para modal edit --}}
    <script>
        $(document).ready(function() {
            // ========== VARIABLES GLOBALES ==========
            let editSelectedAthleteId = null;
            let editSelectedEventId = null;
            let editRegistrationId = null;
        
            // ========== ABRIR MODAL DE EDICIÓN ==========
            $(document).on('click', '.edit-registration', function(e) {
                e.preventDefault();
                const registrationId = $(this).data('id');
                editRegistrationId = registrationId;
                
                if (!registrationId) {
                    showNotification('❌ ID de inscripción no válido', 'error');
                    return;
                }
                
                // Mostrar loading en el modal
                $('#editRegistrationModal .modal-body').html(`
                    <div class="text-center py-4">
                        <div class="spinner-border text-warning" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="mt-2 text-muted">Cargando datos de la inscripción...</p>
                    </div>
                `);
                
                // Abrir el modal
                $('#editRegistrationModal').modal('show');
                
                // Cargar datos de la inscripción
                $.ajax({
                    url: `/admin/registrations/${registrationId}/show-json`,
                    method: 'GET',
                    success: function(response) {
                        if (response.success) {
                            populateEditForm(response.registration);
                        } else {
                            showErrorInModal('Error al cargar los datos: ' + (response.message || 'Error desconocido'));
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Error al cargar los datos de la inscripción';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        }
                        showErrorInModal(errorMsg);
                        console.error('Error loading registration:', xhr);
                    }
                });
            });
        
            // ========== FUNCIÓN PARA POBLAR EL FORMULARIO DE EDICIÓN ==========
            function populateEditForm(registration) {
                // Guardar valores iniciales
                editSelectedAthleteId = registration.athlete_id;
                editSelectedEventId = registration.event_id;
                
                // Construir el formulario de edición (igual al de creación pero con datos precargados)
                const modalBody = `
                    <form id="editRegistrationForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            
                            {{-- Código (solo lectura) --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Código</label>
                                <input type="text" class="form-control border" id="edit_code" value="${registration.code || ''}" readonly style="background: #f8f9fa;">
                            </div>
        
                            {{-- Atleta (SELECT) --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Atleta <span class="text-danger">*</span></label>
                                <select class="form-select border" id="edit_athlete_id" name="athlete_id" required>
                                    <option value="">Seleccione un Atleta</option>
                                    ${getAthletesOptions(registration.athlete_id)}
                                </select>
                                <div class="invalid-feedback" id="edit-athlete_id-error"></div>
                            </div>
        
                            {{-- Evento (SELECT) --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Evento <span class="text-danger">*</span></label>
                                <select class="form-select border" id="edit_event_id" name="event_id" required>
                                    <option value="">Seleccione un Evento</option>
                                    ${getEventsOptions(registration.event_id)}
                                </select>
                                <div class="invalid-feedback" id="edit-event_id-error"></div>
                            </div>
        
                            {{-- Categoría (SELECT - se carga dinámicamente) --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Categoría <span class="text-danger">*</span></label>
                                <select class="form-select border" id="edit_category_id" name="event_category_id" required>
                                    <option value="">Seleccione una categoría</option>
                                </select>
                                <div class="invalid-feedback" id="edit-event_category_id-error"></div>
                                <div id="edit-eligibility-info" class="mt-2" style="display: none;">
                                    <div class="alert alert-info alert-sm">
                                        <i class="ri-information-line me-1"></i> 
                                        <span id="edit-eligibility-text">Cargando elegibilidad...</span>
                                    </div>
                                </div>
                            </div>
        
                            {{-- Comprobante de Pago --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Comprobante de Pago</label>
                                <input type="file" 
                                    class="form-control border" 
                                    id="edit_image" 
                                    name="image" 
                                    accept="image/*">
                                <small class="text-muted">Formatos: JPG, PNG, GIF, WEBP (Máx. 2MB)</small>
                                <div class="invalid-feedback" id="edit-image-error"></div>
                                ${registration.image ? `
                                    <div class="mt-2">
                                        <p class="mb-1"><small class="text-muted">Comprobante actual:</small></p>
                                        <img src="/storage/${registration.image}" 
                                            alt="Comprobante actual" 
                                            class="img-fluid rounded shadow" 
                                            style="max-width: 150px; max-height: 150px; object-fit: cover;">
                                    </div>
                                ` : ''}
                                <div class="mt-2" id="edit-image-preview-container" style="display: none;">
                                    <img id="edit-image-preview" src="#" alt="Vista previa" 
                                        class="img-fluid rounded shadow" 
                                        style="max-width: 150px; max-height: 150px; object-fit: cover;">
                                </div>
                            </div>
        
                            {{-- Método de Pago --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Método de Pago</label>
                                <select class="form-select border" id="edit_payment_method" name="payment_method">
                                    <option value="">Seleccione</option>
                                    <option value="efectivo" ${registration.payment_method === 'efectivo' ? 'selected' : ''}>Efectivo</option>
                                    <option value="transferencia" ${registration.payment_method === 'transferencia' ? 'selected' : ''}>Transferencia</option>
                                    <option value="tarjeta" ${registration.payment_method === 'tarjeta' ? 'selected' : ''}>Tarjeta</option>
                                    <option value="otros" ${registration.payment_method === 'otros' ? 'selected' : ''}>Otros</option>
                                </select>
                                <div class="invalid-feedback" id="edit-payment_method-error"></div>
                            </div>
        
                            {{-- Referencia de Pago --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Referencia de Pago</label>
                                <input type="text" 
                                    class="form-control border" 
                                    id="edit_payment_reference" 
                                    name="payment_reference" 
                                    value="${registration.payment_reference || ''}"
                                    placeholder="Ej: #123456">
                                <div class="invalid-feedback" id="edit-payment_reference-error"></div>
                            </div>
        
                            {{-- Monto --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Monto</label>
                                <input type="number" 
                                    class="form-control border" 
                                    id="edit_amount" 
                                    name="amount" 
                                    value="${registration.amount || ''}"
                                    placeholder="0.00" 
                                    step="0.01">
                                <div class="invalid-feedback" id="edit-amount-error"></div>
                            </div>
        
                            {{-- Estado --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Estado <span class="text-danger">*</span></label>
                                <select class="form-select border" id="edit_status" name="status" required>
                                    <option value="pendiente" ${registration.status === 'pendiente' ? 'selected' : ''}>Pendiente</option>
                                    <option value="inscrito" ${registration.status === 'inscrito' ? 'selected' : ''}>Inscrito</option>
                                    <option value="confirmado" ${registration.status === 'confirmado' ? 'selected' : ''}>Confirmado</option>
                                    <option value="retirado" ${registration.status === 'retirado' ? 'selected' : ''}>Retirado</option>
                                </select>
                                <div class="invalid-feedback" id="edit-status-error"></div>
                            </div>
        
                            {{-- Notas --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Notas</label>
                                <textarea class="form-control border" 
                                        id="edit_notes" 
                                        name="notes" 
                                        rows="2" 
                                        placeholder="Observaciones adicionales">${registration.notes || ''}</textarea>
                                <div class="invalid-feedback" id="edit-notes-error"></div>
                            </div>
        
                        </div>
                    </form>
                `;
        
                // Actualizar el contenido del modal
                $('#editRegistrationModal .modal-body').html(modalBody);
                
                // Actualizar el título del modal
                $('#editRegistrationModalLabel').html(`
                    <i class="ri-edit-2-line me-2"></i>Editar Inscripción: ${registration.code || 'Sin código'}
                `);
        
                // Guardar el ID de la inscripción en el formulario
                $('#editRegistrationForm').data('id', registration.id);
        
                // Cargar las categorías después de que el DOM esté listo
                setTimeout(() => {
                    loadEditCategories(registration.event_id, registration.athlete_id, registration.event_category_id);
                }, 100);
        
                // Configurar eventos
                setupEditEvents(registration);
            }
        
            // ========== FUNCIÓN PARA OBTENER OPCIONES DE ATLETAS ==========
            function getAthletesOptions(selectedId = null) {
                let options = '';
                @foreach($athletes as $athlete)
                    const selected = {{ $athlete->id }} == selectedId ? 'selected' : '';
                    options += `<option value="{{ $athlete->id }}" ${selected}>
                        {{ $athlete->full_name }} - {{ $athlete->document }} (Edad: {{ $athlete->age }} años)
                    </option>`;
                @endforeach
                return options;
            }
        
            // ========== FUNCIÓN PARA OBTENER OPCIONES DE EVENTOS ==========
            function getEventsOptions(selectedId = null) {
                let options = '';
                @foreach($events as $event)
                    const selected = {{ $event->id }} == selectedId ? 'selected' : '';
                    options += `<option value="{{ $event->id }}" ${selected}>
                        {{ $event->name }} - {{ \Carbon\Carbon::parse($event->event_date)->format('d/m/Y') }}
                    </option>`;
                @endforeach
                return options;
            }
        
            // ========== CONFIGURAR EVENTOS DEL FORMULARIO DE EDICIÓN ==========
            function setupEditEvents(registration) {
                
                // ========== EVENTO: Cambio de Atleta ==========
                $('#edit_athlete_id').on('change', function() {
                    const athleteId = $(this).val();
                    editSelectedAthleteId = athleteId;
                    const eventId = $('#edit_event_id').val();
                    
                    if (athleteId && eventId) {
                        loadEditCategories(eventId, athleteId);
                    } else if (athleteId) {
                        $('#edit_category_id').html('<option value="">Seleccione un Evento primero</option>');
                        $('#edit-eligibility-info').hide();
                    }
                });
        
                // ========== EVENTO: Cambio de Evento ==========
                $('#edit_event_id').on('change', function() {
                    const eventId = $(this).val();
                    editSelectedEventId = eventId;
                    const athleteId = $('#edit_athlete_id').val();
                    
                    if (eventId && athleteId) {
                        loadEditCategories(eventId, athleteId);
                    } else if (eventId) {
                        loadEditCategories(eventId);
                    } else {
                        $('#edit_category_id').html('<option value="">Primero seleccione un Evento</option>');
                        $('#edit-eligibility-info').hide();
                    }
                });
        
                // ========== VISTA PREVIA DE IMAGEN ==========
                $('#edit_image').on('change', function() {
                    const file = this.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            $('#edit-image-preview').attr('src', e.target.result);
                            $('#edit-image-preview-container').show();
                        };
                        reader.readAsDataURL(file);
                    } else {
                        $('#edit-image-preview-container').hide();
                    }
                });
        
                // ========== VERIFICAR ELEGIBILIDAD AL SELECCIONAR CATEGORÍA ==========
                $('#edit_category_id').on('change', function() {
                    const categoryId = $(this).val();
                    const athleteId = $('#edit_athlete_id').val();
                    const eventId = $('#edit_event_id').val();
        
                    if (categoryId && athleteId && eventId) {
                        checkEditEligibility(athleteId, eventId, categoryId);
                    } else {
                        $('#edit-eligibility-info').hide();
                    }
                });
        
                // ========== ENVÍO DEL FORMULARIO ==========
                setupEditFormSubmit();
            }
        
            // ========== FUNCIÓN PARA CARGAR CATEGORÍAS EN EDICIÓN ==========
            function loadEditCategories(eventId, athleteId = null, selectedCategoryId = null) {
                if (!eventId) return;
        
                // Mostrar estado de carga
                $('#edit_category_id').html('<option value="">Cargando categorías...</option>');
                $('#edit-eligibility-info').hide();
        
                // Construir URL con parámetros
                let url = `{{ route('events.categories', ['eventId' => ':eventId']) }}`.replace(':eventId', eventId);
                
                if (athleteId) {
                    url += `?athlete_id=${athleteId}`;
                }
        
                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function(response) {
                        if (response.success) {
                            let options = '<option value="">Seleccione una Categoría</option>';
                            
                            if (response.categories && response.categories.length > 0) {
                                // Mostrar categorías elegibles
                                response.categories.forEach(category => {
                                    const selected = category.id == selectedCategoryId ? 'selected' : '';
                                    let label = category.label || category.name;
                                    options += `<option value="${category.id}" ${selected}>${label}</option>`;
                                });
                                
                                $('#edit_category_id').html(options);
                                
                                // Mostrar información del atleta si está seleccionado
                                if (response.has_athlete && response.athlete_info) {
                                    const infoMessage = `
                                        <strong class="text-dark">👤 ${response.athlete_info.full_name}</strong>
                                        (${response.athlete_info.age} años, 
                                        ${response.athlete_info.gender === 'masculino' ? 'Masculino' : 'Femenino'})
                                        <br>
                                        <span>✅ ${response.categories.length} categorías disponibles</span>
                                    `;
                                    
                                    $('#edit-eligibility-info').show();
                                    $('#edit-eligibility-text').html(infoMessage);
                                    $('#edit-eligibility-info .alert').removeClass('alert-danger alert-warning').addClass('alert-success');
                                }
                            } else {
                                // No hay categorías elegibles
                                $('#edit_category_id').html('<option value="">No hay categorías disponibles para este atleta</option>');
                                
                                if (response.has_athlete && response.athlete_info) {
                                    const infoMessage = `
                                        <strong>⚠️ ${response.athlete_info.full_name}</strong> 
                                        (${response.athlete_info.age} años, 
                                        ${response.athlete_info.gender === 'masculino' ? 'Masculino' : 'Femenino'})
                                        <br>
                                        <small>No cumple con los requisitos de edad o género para las categorías disponibles.</small>
                                    `;
                                    
                                    $('#edit-eligibility-info').show();
                                    $('#edit-eligibility-text').html(infoMessage);
                                    $('#edit-eligibility-info .alert').removeClass('alert-success').addClass('alert-warning');
                                } else {
                                    const infoMessage = `⚠️ Este evento no tiene categorías disponibles.`;
                                    
                                    $('#edit-eligibility-info').show();
                                    $('#edit-eligibility-text').html(infoMessage);
                                    $('#edit-eligibility-info .alert').removeClass('alert-success').addClass('alert-warning');
                                }
                            }
                        } else {
                            $('#edit_category_id').html('<option value="">Error al cargar categorías</option>');
                            showNotification('❌ ' + (response.message || 'Error al cargar categorías'), 'error');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error loading categories:', xhr);
                        let errorMsg = 'Error al cargar categorías';
                        
                        if (xhr.status === 404) {
                            errorMsg = 'Ruta no encontrada. Verifica la configuración.';
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        }
                        
                        $('#edit_category_id').html('<option value="">Error al cargar categorías</option>');
                        showNotification('❌ ' + errorMsg, 'error');
                    }
                });
            }
        
            // ========== VERIFICAR ELEGIBILIDAD EN EDICIÓN ==========
            function checkEditEligibility(athleteId, eventId, categoryId) {
                $.ajax({
                    url: '/admin/registrations/check-eligibility',
                    method: 'POST',
                    data: {
                        athlete_id: athleteId,
                        event_id: eventId,
                        category_id: categoryId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            const eligibility = response.eligibility;
                            
                            if (eligibility.eligible) {
                                $('#edit-eligibility-info').show();
                                $('#edit-eligibility-text').html(
                                    `<i class="ri-check-line text-success"></i> 
                                    ✅ ${eligibility.message} (Edad: ${eligibility.age} años)`
                                );
                                $('#edit-eligibility-info .alert').removeClass('alert-danger').addClass('alert-success');
                            } else {
                                $('#edit-eligibility-info').show();
                                $('#edit-eligibility-text').html(
                                    `<i class="ri-close-line text-danger"></i> 
                                    ❌ ${eligibility.message}`
                                );
                                $('#edit-eligibility-info .alert').removeClass('alert-success').addClass('alert-danger');
                            }
                        }
                    },
                    error: function() {
                        showNotification('Error al verificar elegibilidad', 'error');
                    }
                });
            }
        
            // ========== CONFIGURAR ENVÍO DEL FORMULARIO DE EDICIÓN ==========
            function setupEditFormSubmit() {
                $('#editRegistrationForm').off('submit').on('submit', function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData(this);
                    const registrationId = $(this).data('id');
                    
                    // Deshabilitar botón y mostrar loading
                    $('#btnEditSubmit').prop('disabled', true)
                        .html('<span class="spinner-border spinner-border-sm"></span> Actualizando...');
                    
                    // Limpiar errores previos
                    $('.invalid-feedback').html('');
                    $('.form-control').removeClass('is-invalid');
                    
                    $.ajax({
                        url: `/registrations/${registrationId}`,
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.success) {
                                // Cerrar modal
                                $('#editRegistrationModal').modal('hide');
                                
                                // Mostrar SweetAlert de éxito
                                Swal.fire({
                                    position: "center",
                                    icon: "success",
                                    title: "✅ Inscripción Actualizada",
                                    text: response.message || 'Los cambios se guardaron correctamente',
                                    showConfirmButton: true,
                                    confirmButtonColor: '#28a745',
                                    confirmButtonText: '¡Perfecto!'
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: response.message || 'Error al actualizar la inscripción',
                                    confirmButtonColor: '#d33'
                                });
                            }
                        },
                        error: function(xhr) {
                            let errorMessage = 'Error al actualizar la inscripción';
                            
                            if (xhr.status === 422 && xhr.responseJSON) {
                                // Errores de validación
                                if (xhr.responseJSON.errors) {
                                    const errors = xhr.responseJSON.errors;
                                    
                                    // Mostrar errores en los campos correspondientes
                                    for (let field in errors) {
                                        // Mapear nombres de campos
                                        let fieldMap = {
                                            'athlete_id': 'athlete_id',
                                            'event_id': 'event_id',
                                            'event_category_id': 'event_category_id',
                                            'image': 'image',
                                            'payment_method': 'payment_method',
                                            'payment_reference': 'payment_reference',
                                            'amount': 'amount',
                                            'status': 'status',
                                            'notes': 'notes'
                                        };
                                        
                                        const mappedField = fieldMap[field] || field;
                                        $(`#edit-${mappedField}-error`).html(errors[field].join(', '));
                                        $(`#edit_${mappedField}`).addClass('is-invalid');
                                    }
                                    
                                    errorMessage = 'Por favor, corrige los errores en el formulario';
                                } else if (xhr.responseJSON.message) {
                                    errorMessage = xhr.responseJSON.message;
                                }
                                
                                // Mostrar SweetAlert con los errores
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error de Validación',
                                    text: errorMessage,
                                    confirmButtonColor: '#d33',
                                    confirmButtonText: 'Corregir errores'
                                });
                            } else if (xhr.status === 403) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Acceso Denegado',
                                    text: xhr.responseJSON?.message || 'No tienes permisos para editar esta inscripción',
                                    confirmButtonColor: '#d33'
                                });
                            } else {
                                // Otros errores
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: xhr.responseJSON?.message || errorMessage,
                                    confirmButtonColor: '#d33'
                                });
                            }
                            
                            console.error('Error updating registration:', xhr);
                        },
                        complete: function() {
                            // Restaurar botón
                            $('#btnEditSubmit').prop('disabled', false)
                                .html('<i class="ri-save-line me-1"></i>Actualizar Inscripción');
                        }
                    });
                });
            }
        
            // ========== FUNCIÓN PARA MOSTRAR ERROR EN EL MODAL ==========
            function showErrorInModal(message) {
                $('#editRegistrationModal .modal-body').html(`
                    <div class="alert alert-danger text-center py-4">
                        <i class="ri-error-warning-line" style="font-size: 48px;"></i>
                        <h5 class="mt-3">Error</h5>
                        <p>${message}</p>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="ri-close-line me-1"></i>Cerrar
                        </button>
                    </div>
                `);
            }
        
            // ========== FUNCIÓN PARA MOSTRAR NOTIFICACIONES ==========
            function showNotification(message, type = 'success') {
                const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
                const icon = type === 'success' ? 'check-circle' : 'alert-circle';
                
                const notification = $(`
                    <div class="alert ${alertClass} alert-dismissible fade show position-fixed top-0 end-0 m-3" 
                        role="alert" 
                        style="z-index: 9999; min-width: 300px; max-width: 500px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                        <i class="ri-${icon}-line me-2"></i>
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `);
                
                $('body').append(notification);
                
                setTimeout(() => {
                    notification.fadeOut(500, function() {
                        $(this).remove();
                    });
                }, 5000);
            }
        
            // ========== REINICIAR MODAL AL CERRAR ==========
            $('#editRegistrationModal').on('hidden.bs.modal', function() {
                // Limpiar cualquier estado pendiente
                $('#editRegistrationForm').data('id', null);
                $('.invalid-feedback').html('');
                $('.form-control').removeClass('is-invalid');
                editSelectedAthleteId = null;
                editSelectedEventId = null;
                editRegistrationId = null;
            });
        
            // ========== MANEJO DEL BOTÓN "EDITAR" DESDE EL MODAL DE VER ==========
            $(document).on('click', '#btnEditFromView', function() {
                const registrationId = $(this).data('id');
                if (registrationId) {
                    $('#viewRegistrationModal').modal('hide');
                    setTimeout(() => {
                        // Disparar el click en el botón de editar correspondiente
                        $(`.edit-registration[data-id="${registrationId}"]`).click();
                    }, 500);
                }
            });
        });
    </script>

@endpush