@extends('admin.layouts.master')

@section('title', 'Ordenes de Servicio')

@section('styles')
    <!-- DataTable CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection

@section('content')
    <div class="compact-wrapper">
        <div class="page-body-wrapper">
            <div class="page-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card card-table">
                                <div class="card-body">

                                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-0 mb-3">
                                        <h3 class="fw-bold">Listado de Ordenes</h3>
                                        <div class="d-flex gap-2">

                                            <!-- Filtro de estado -->
                                            <select class="form-select" id="filterStatus" style="width: auto;">
                                                <option value="all">Todos los estados</option>
                                                <option value="1">Activss</option>
                                                <option value="0">Inactivas</option>
                                            </select>

                                            <a href="javascript:void(0)" class="align-items-center btn btn-theme d-flex"
                                                data-bs-toggle="modal" data-bs-target="#createMechancModal">
                                                <i data-feather="plus-square"></i>+ Nueva
                                            </a>
                                        </div>
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

@push('styles')
    
@endpush

@push('scripts')
    
@endpush