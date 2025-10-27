@extends('layouts.vertical', ['title' => 'Listado de Usuarios'])

@section('css')
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- DataTables Responsive CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />


    <style>
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate {
            display: none;
        }

        #usersTable_wrapper {
            width: 100%;
        }

        #usersTable thead th,
        #usersTable tbody td {
            vertical-align: middle;
        }

        #usersTable tbody td {
            padding: 0.5rem 0.75rem !important;
        }

        #usersTable thead th {
            padding: 0.75rem !important;
        }

        #usersTable tbody td:first-child,
        #usersTable thead th:first-child {
            padding-left: 1rem !important;
            width: 40px;
        }

        #usersTable thead th:nth-child(2) {
            width: 110px;
        }

        #usersTable thead th:nth-child(5) {
            width: 130px;
        }

        #usersTable thead th:nth-child(6) {
            width: 120px;
        }

        #usersTable thead th:nth-child(7) {
            width: 100px;
        }

        #usersTable td {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 200px;
        }

        #usersTable td:nth-child(4) {
            max-width: 250px;
        }

        /* DataTables Responsive - Control de expansión */
        table.dataTable.dtr-inline.collapsed>tbody>tr>td.dtr-control:before,
        table.dataTable.dtr-inline.collapsed>tbody>tr>th.dtr-control:before {
            background-color: #3b82f6;
            border: none;
            box-shadow: 0 0 3px rgba(0, 0, 0, 0.2);
        }

        table.dataTable.dtr-inline.collapsed>tbody>tr.parent>td.dtr-control:before,
        table.dataTable.dtr-inline.collapsed>tbody>tr.parent>th.dtr-control:before {
            background-color: #dc2626;
        }

        /* Detalles responsive */
        table.dataTable>tbody>tr.child ul.dtr-details {
            display: block;
            list-style-type: none;
            margin: 0;
            padding: 0;
        }

        table.dataTable>tbody>tr.child ul.dtr-details>li {
            border-bottom: 1px solid #f3f4f6;
            padding: 0.5rem 0;
        }

        table.dataTable>tbody>tr.child span.dtr-title {
            display: inline-block;
            min-width: 120px;
            font-weight: 600;
            color: #6b7280;
        }

        table.dataTable>tbody>tr.child span.dtr-data {
            color: #1f2937;
        }

        /* Switch toggle */
        .status-switch {
            position: relative;
            display: inline-block;
            width: 44px;
            height: 24px;
        }

        .status-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .status-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #e5e7eb;
            transition: .3s;
            border-radius: 24px;
        }

        .status-slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .3s;
            border-radius: 50%;
        }

        input:checked+.status-slider {
            background-color: #10b981;
        }

        input:checked+.status-slider:before {
            transform: translateX(20px);
        }

        /* Botones de acción */
        .action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 0.375rem;
            transition: all 0.2s;
            cursor: pointer;
            border: none;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .btn-edit {
            background-color: #dbeafe;
            color: #2563eb;
        }

        .btn-edit:hover {
            background-color: #bfdbfe;
            color: #1d4ed8;
        }

        .btn-delete {
            background-color: #fee2e2;
            color: #dc2626;
        }

        .btn-delete:hover {
            background-color: #fecaca;
            color: #b91c1c;
        }

        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
        }
    </style>
@endsection

@section('content')
    @include('layouts.partials/page-title', ['subtitle' => 'Users', 'title' => 'Listado de Usuarios'])

    <div class="card">
        <div class="card-header">
            <h6 class="card-title">Listado de usuarios</h6>
            <button class="btn btn-sm bg-primary text-white" id="addUserBtn">
                <i class="size-4 me-1" data-lucide="plus"></i>Agergar Usuario
            </button>
        </div>
        <div class="card-header">
            <div class="md:flex md:items-center md:space-y-0 space-y-4 gap-3 w-full">
                <!-- Buscador grande -->
                <div class="relative grow">
                    <input class="form-input form-input-sm ps-9 w-full" id="searchInput"
                        placeholder="Buscar por nombre o correo" type="text" />
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3">
                        <i class="size-3.5 flex items-center text-default-500 fill-default-100" data-lucide="search"></i>
                    </div>
                </div>

                <!-- Select más pequeño -->
                <div class="shrink-0">
                    <select class="form-input form-input-sm w-40" id="statusFilter">
                        <option value="">Todos</option>
                        <option value="activo">Activo</option>
                        <option value="inactivo">Inactivo</option>
                    </select>
                </div>
            </div>
        </div>


        <div class="flex flex-col relative">
            <div id="loadingOverlay" class="loading-overlay" style="display: none;">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary"></div>
            </div>
            <div class="overflow-x-auto">
                <div class="min-w-full inline-block align-middle">
                    <div class="overflow-hidden">
                        <table class="min-w-full divide-y divide-default-200" id="usersTable">
                            <thead class="bg-default-150">
                                <tr class="text-sm font-normal text-default-700 whitespace-nowrap">
                                    <th class="ps-4 text-start">
                                        <input class="form-checkbox" id="checkbox-all" type="checkbox" />
                                    </th>
                                    <th class="px-3.5 py-3 text-start" scope="col">Nro</th>
                                    <th class="px-3.5 py-3 text-start" scope="col">Nombre</th>
                                    <th class="px-3.5 py-3 text-start" scope="col">Correo Electrónico</th>
                                    <th class="px-3.5 py-3 text-start" scope="col">Rol</th>
                                    <th class="px-3.5 py-3 text-start" scope="col">Fecha de creación</th>
                                    <th class="px-3.5 py-3 text-start" scope="col">Estado</th>
                                    <th class="px-3.5 py-3 text-start" scope="col">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Los datos se cargarán aquí vía AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <p class="text-default-500 text-sm" id="tableInfo">Showing <b>0</b> of <b>0</b> Results</p>
                <nav aria-label="Pagination" class="flex items-center gap-2" id="paginationNav">
                    <button
                        class="btn btn-sm border bg-transparent border-default-200 text-default-600 hover:bg-primary/10 hover:text-primary hover:border-primary/10"
                        type="button" id="prevBtn">
                        <i class="size-4 me-1" data-lucide="chevron-left"></i> Prev
                    </button>
                    <div id="pageButtons"></div>
                    <button
                        class="btn btn-sm border bg-transparent border-default-200 text-default-600 hover:bg-primary/10 hover:text-primary hover:border-primary/10"
                        type="button" id="nextBtn">Next
                        <i class="size-4 ms-1" data-lucide="chevron-right"></i>
                    </button>
                </nav>
            </div>
        </div>
    </div>

    <!-- Modal: Agregar Usuario -->
    <div id="userModal" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center">
        <div class="card relative rounded-2xl shadow-2xl w-full max-w-2xl p-8 transition-all">
            <!-- Botón de cerrar -->
            <button id="closeModalBtn" class="absolute top-4 right-4  transition">
                <i class="fas fa-times text-xl"></i>
            </button>

            <!-- Encabezado -->
            <div class="border-b  pb-4 mb-6">
                <h5 class="text-2xl font-bold ">Agregar Nuevo Usuario</h5>
                <p class="text-sm  mt-1">Complete la información del usuario a registrar</p>
            </div>

            <!-- Formulario -->
            <form id="addUserForm" class="space-y-8">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex flex-col space-y-2">
                        <label for="name" class="text-sm font-semibold ">Nombre</label>
                        <input type="text" id="name" name="name"
                            class="form-input w-full rounded-lg  focus:ring-2 focus:ring-blue-500"
                            placeholder="Escriba su nombre completo">
                    </div>

                    <div class="flex flex-col space-y-2">
                        <label for="email" class="text-sm font-semibold ">Correo
                            Electrónico</label>
                        <input type="email" id="email" name="email"
                            class="form-input w-full rounded-lg  focus:ring-2 focus:ring-blue-500"
                            placeholder="Escriba su correo electrónico">
                    </div>

                    <div class="flex flex-col space-y-2">
                        <label for="password" class="text-sm font-semibold ">Contraseña</label>
                        <input type="password" id="password" name="password"
                            class="form-input w-full rounded-lg  focus:ring-2 focus:ring-blue-500"
                            placeholder="Escriba una contraseña segura">
                    </div>

                    <div class="flex flex-col space-y-2">
                        <label for="status" class="text-sm font-semibold ">Estado</label>
                        <select id="status" name="status"
                            class="form-input w-full rounded-lg  focus:ring-2 focus:ring-blue-500">
                            <option value="activo">Activo</option>
                            <option value="inactivo">Inactivo</option>
                        </select>
                    </div>
                </div>

                <!-- Separador -->
                <div class="border-t  my-4"></div>

                <!-- Botones -->
                <div class="flex justify-end gap-3">
                    <button type="button" id="cancelBtn"
                        class="btn btn-sm bg-danger text-white hover:bg-red-700 transition-colors rounded-lg px-5 py-2">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="btn btn-sm bg-primary text-white hover:bg-blue-700 transition-colors rounded-lg px-5 py-2">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal: Editar Usuario -->
    <div id="editUserModal"
        class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center">
        <div class="card relative rounded-2xl shadow-2xl w-full max-w-2xl p-8 transition-all">
            <!-- Botón de cerrar -->
            <button id="closeEditModalBtn" class="absolute top-4 right-4 transition">
                <i class="fas fa-times text-xl"></i>
            </button>

            <!-- Encabezado -->
            <div class="border-b pb-4 mb-6">
                <h5 class="text-2xl font-bold">Editar Usuario</h5>
                <p class="text-sm mt-1">Modifique la información del usuario</p>
            </div>

            <!-- Formulario -->
            <form id="editUserForm" class="space-y-8">
                @csrf
                @method('PUT') <!-- importante para PUT -->

                <input type="hidden" id="editUserId" name="id">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex flex-col space-y-2">
                        <label for="editName" class="text-sm font-semibold">Nombre</label>
                        <input type="text" id="editName" name="name"
                            class="form-input w-full rounded-lg focus:ring-2 focus:ring-blue-500"
                            placeholder="Nombre completo">
                    </div>

                    <div class="flex flex-col space-y-2">
                        <label for="editEmail" class="text-sm font-semibold">Correo Electrónico</label>
                        <input type="email" id="editEmail" name="email"
                            class="form-input w-full rounded-lg focus:ring-2 focus:ring-blue-500"
                            placeholder="Correo electrónico">
                    </div>

                    <div class="flex flex-col space-y-2">
                        <label for="editPassword" class="text-sm font-semibold">Contraseña</label>
                        <input type="password" id="editPassword" name="password"
                            class="form-input w-full rounded-lg focus:ring-2 focus:ring-blue-500"
                            placeholder="Dejar vacío para no cambiar">
                    </div>

                    <div class="flex flex-col space-y-2">
                        <label for="editStatus" class="text-sm font-semibold">Estado</label>
                        <select id="editStatus" name="status"
                            class="form-input w-full rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="activo">Activo</option>
                            <option value="inactivo">Inactivo</option>
                        </select>
                    </div>
                    <div class="flex flex-col space-y-2">
                        <label for="editRole" class="text-sm font-semibold">Rol</label>
                        <select id="editRole" name="role"
                            class="form-input w-full rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">Seleccione un rol</option>
                            <!-- Opciones se llenan con JS -->
                        </select>
                    </div>

                </div>

                <!-- Separador -->
                <div class="border-t my-4"></div>

                <!-- Botones -->
                <div class="flex justify-end gap-3">
                    <button type="button" id="cancelEditBtn"
                        class="btn btn-sm bg-danger text-white hover:bg-red-700 transition-colors rounded-lg px-5 py-2">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="btn btn-sm bg-primary text-white hover:bg-blue-700 transition-colors rounded-lg px-5 py-2">
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof jQuery === 'undefined') {
                console.error('jQuery no está disponible');
                return;
            }

            let currentPage = 1;
            let perPage = 10;
            let totalRecords = 0;
            let searchTerm = '';
            let statusFilter = '';

            const table = jQuery('#usersTable').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('users.data') }}',
                    type: 'GET',
                    data: function(d) {
                        d.search = searchTerm;
                        d.status = statusFilter;
                        d.page = currentPage;
                        d.per_page = perPage;
                    },
                    beforeSend: function() {
                        jQuery('#loadingOverlay').show();
                    },
                    complete: function() {
                        jQuery('#loadingOverlay').hide();
                        // Reinicializar lucide icons después de cargar datos
                        setTimeout(function() {
                            if (typeof lucide !== 'undefined') {
                                lucide.createIcons();
                            }
                        }, 100);
                    },
                    dataSrc: function(json) {
                        totalRecords = json.recordsFiltered;
                        updatePagination(json);
                        return json.data;
                    }
                },
                columns: [{
                        data: null,
                        orderable: false,
                        searchable: false,
                        responsivePriority: 1,
                        className: 'ps-4',
                        width: '40px',
                        render: function(data, type, row) {
                            return '<input class="form-checkbox" type="checkbox" />';
                        }
                    },
                    {
                        data: 'id',
                        responsivePriority: 2,
                        className: 'text-sm text-primary',
                        width: '110px',
                        render: function(data) {
                            return '#TW' + String(data).padStart(7, '0');
                        }
                    },
                    {
                        data: 'name',
                        responsivePriority: 1,
                        className: 'font-medium'
                    },

                    {
                        data: 'email',
                        responsivePriority: 3
                    },
                    {
                        data: 'role', // <-- NUEVA COLUMNA
                        responsivePriority: 4,
                        className: 'text-center',
                        width: '40px',
                        render: function(data) {
                            return data; // Muestra el nombre del rol
                        }
                    },
                    {
                        data: 'created_at',
                        responsivePriority: 5,
                        width: '130px',
                        render: function(data) {
                            return new Date(data).toLocaleDateString('es-ES', {
                                year: 'numeric',
                                month: 'short',
                                day: 'numeric'
                            });
                        }
                    },
                    {
                        data: 'status',
                        responsivePriority: 4,
                        width: '120px',
                        render: function(data, type, row) {
                            const isActive = data === 'activo';
                            return `
                                <label class="status-switch">
                                    <input type="checkbox" ${isActive ? 'checked' : ''} onchange="toggleStatus(${row.id}, this.checked)">
                                    <span class="status-slider"></span>
                                </label>
                            `;
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        responsivePriority: 1,
                        className: 'text-center',
                        width: '100px',
                        render: function(data, type, row) {
                            return `
                                <div class="flex items-center justify-center gap-2">
                                    <button class="action-btn btn-edit" onclick="editUser(${row.id})" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="action-btn btn-delete" onclick="deleteUser(${row.id})" title="Eliminar">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            `;
                        }
                    }
                ],
                order: [
                    [1, 'desc']
                ],
                pageLength: perPage,
                searching: false,
                lengthChange: false,
                info: false,
                paging: false,
                autoWidth: false,
                dom: 't'
            });

            let searchTimeout;
            jQuery('#searchInput').on('keyup', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    searchTerm = jQuery('#searchInput').val();
                    currentPage = 1;
                    table.ajax.reload();
                }, 500);
            });

            jQuery('#statusFilter').on('change', function() {
                statusFilter = jQuery(this).val();
                currentPage = 1;
                table.ajax.reload();
            });

            function updatePagination(json) {
                const totalPages = Math.ceil(json.recordsFiltered / perPage);
                const start = (currentPage - 1) * perPage + 1;
                const end = Math.min(currentPage * perPage, json.recordsFiltered);

                jQuery('#tableInfo').html(
                    `Showing <b>${start}</b> to <b>${end}</b> of <b>${json.recordsFiltered}</b> Results`);

                let pageButtonsHtml = '';
                const maxButtons = 5;
                let startPage = Math.max(1, currentPage - Math.floor(maxButtons / 2));
                let endPage = Math.min(totalPages, startPage + maxButtons - 1);

                if (endPage - startPage < maxButtons - 1) {
                    startPage = Math.max(1, endPage - maxButtons + 1);
                }

                for (let i = startPage; i <= endPage; i++) {
                    const activeClass = i === currentPage ? 'bg-primary text-white' :
                        'bg-transparent border border-default-200 text-default-600 hover:bg-primary/10 hover:text-primary hover:border-primary/10';
                    pageButtonsHtml += `
                        <button class="btn size-7.5 ${activeClass}" type="button" onclick="goToPage(${i})">
                            ${i}
                        </button>
                    `;
                }

                jQuery('#pageButtons').html(pageButtonsHtml);
                jQuery('#prevBtn').prop('disabled', currentPage === 1);
                jQuery('#nextBtn').prop('disabled', currentPage === totalPages);
            }

            window.goToPage = function(page) {
                currentPage = page;
                table.ajax.reload();
            };

            jQuery('#prevBtn').on('click', function() {
                if (currentPage > 1) {
                    currentPage--;
                    table.ajax.reload();
                }
            });

            jQuery('#nextBtn').on('click', function() {
                const totalPages = Math.ceil(totalRecords / perPage);
                if (currentPage < totalPages) {
                    currentPage++;
                    table.ajax.reload();
                }
            });

            jQuery('#checkbox-all').on('change', function() {
                jQuery('.form-checkbox').not(this).prop('checked', this.checked);
            });

            window.toggleStatus = function(userId, isActive) {
                const newStatus = isActive ? 'activo' : 'inactivo';

                jQuery('#loadingOverlay').show();

                jQuery.ajax({
                    url: `/users/${userId}/status`,
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        status: newStatus
                    },
                    success: function(response) {
                        jQuery('#loadingOverlay').hide();
                        if (response.success) {
                            console.log('Estado actualizado correctamente');
                        }
                    },
                    error: function(xhr) {
                        jQuery('#loadingOverlay').hide();
                        alert('Error al actualizar el estado');
                        table.ajax.reload(null, false);
                    }
                });
            };




            window.deleteUser = function(id) {
                Swal.fire({
                    title: "¿Estás seguro?",
                    text: "¡No podrás revertir esta acción!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Sí, eliminar",
                    cancelButtonText: "Cancelar",
                    reverseButtons: true,
                    customClass: {
                        popup: "animate__animated animate__fadeIn",
                        confirmButton: "bg-danger text-white hover:bg-red-700",
                        cancelButton: "bg-default-100 text-default-800 hover:bg-default-200"
                    },
                    background: document.documentElement.getAttribute('data-theme') === 'dark' ?
                        "#000000" : "#fff",
                    color: document.documentElement.getAttribute('data-theme') === 'dark' ? "#fff" :
                        "#000"
                }).then((result) => {
                    if (result.isConfirmed) {
                        jQuery('#loadingOverlay').show();

                        jQuery.ajax({
                            url: `/users/${id}`,
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr(
                                    'content')
                            },
                            success: function(response) {
                                jQuery('#loadingOverlay').hide();
                                if (response.success) {
                                    table.ajax.reload();
                                    Swal.fire({
                                        title: "¡Eliminado!",
                                        text: "Usuario eliminado correctamente",
                                        icon: "success",
                                        timer: 2000,
                                        showConfirmButton: false,
                                        background: document.documentElement
                                            .getAttribute('data-theme') === 'dark' ?
                                            "#000000" : "#fff",
                                        color: document.documentElement
                                            .getAttribute('data-theme') === 'dark' ?
                                            "#fff" : "#000",
                                        customClass: {
                                            popup: "animate__animated animate__fadeInDown"
                                        }
                                    });
                                }
                            },
                            error: function(xhr) {
                                jQuery('#loadingOverlay').hide();
                                Swal.fire({
                                    title: "Error",
                                    text: "No se pudo eliminar el usuario",
                                    icon: "error",
                                    confirmButtonText: "Entendido",
                                    customClass: {
                                        popup: "animate__animated animate__shakeX"
                                    },
                                    background: document.documentElement
                                        .getAttribute('data-theme') === 'dark' ?
                                        "#1f2937" : "#fff",
                                    color: document.documentElement.getAttribute(
                                            'data-theme') === 'dark' ? "#fff" :
                                        "#000"
                                });
                            }
                        });
                    }
                });
            }

        });
    </script>

    <script>
        // --- Modal Control ---
        const userModal = document.getElementById('userModal');
        const addUserBtn = document.getElementById('addUserBtn');
        const closeModalBtn = document.getElementById('closeModalBtn');
        const cancelBtn = document.getElementById('cancelBtn');


        function openModal() {
            userModal.classList.remove('hidden');
        }

        function closeModal() {
            userModal.classList.add('hidden');
            document.getElementById('addUserForm').reset();
        }

        addUserBtn.addEventListener('click', openModal);
        closeModalBtn.addEventListener('click', closeModal);
        cancelBtn.addEventListener('click', closeModal);

        window.addEventListener('click', (e) => {
            if (e.target === userModal) closeModal();
        });

        // --- Enviar el formulario vía AJAX ---
        jQuery('#addUserForm').on('submit', function(e) {
            e.preventDefault();

            jQuery('#loadingOverlay').show();

            const formData = {
                name: jQuery('#name').val(),
                email: jQuery('#email').val(),
                password: jQuery('#password').val(),
                status: jQuery('#status').val(),
                _token: jQuery('meta[name="csrf-token"]').attr('content')
            };

            console.log(formData);

            // Detecta el modo activo (usa las mismas reglas de tu plantilla)
            const isDarkMode = document.documentElement.getAttribute('data-theme') === 'dark';

            // Obtiene los colores definidos en las variables CSS
            const swalBackground = getComputedStyle(document.documentElement).getPropertyValue('--color-card')
                .trim();
            const swalTextColor = getComputedStyle(document.documentElement).getPropertyValue('--color-default-800')
                .trim();

            jQuery.ajax({
                url: '{{ route('users.store') }}',
                method: 'POST',
                data: formData,
                success: function(response) {
                    jQuery('#loadingOverlay').hide();
                    if (response.success) {
                        closeModal();
                        Swal.fire({
                            title: "Usuario agregado correctamente",
                            text: "El nuevo usuario se registró con éxito.",
                            icon: "success",
                            showConfirmButton: false,
                            timer: 2000,
                            background: isDarkMode ? "#000000" : "#fff", // cambia según el modo
                            color: isDarkMode ? "#fff" : "#000",
                            customClass: {
                                popup: "animate__animated animate__fadeInDown",
                                title: "text-lg font-semibold",
                                icon: "animate__animated animate__bounceIn"
                            }
                        });
                        jQuery('#usersTable').DataTable().ajax.reload();
                    } else {
                        Swal.fire({
                            title: "Error",
                            text: "Por favor complete todos los campos.",
                            icon: "error",
                            confirmButtonText: "Entendido",
                            customClass: {
                                popup: "animate__animated animate__shakeX"
                            }
                        });
                    }
                },
                error: function(xhr) {
                    jQuery('#loadingOverlay').hide();
                    let message = "Ocurrió un error inesperado.";
                    if (xhr.status === 422 && xhr.responseJSON?.errors) {
                        // Muestra el primer mensaje de error
                        const firstError = Object.values(xhr.responseJSON.errors)[0][0];
                        message = firstError;
                    }

                    Swal.fire({
                        title: "Error",
                        text: message,
                        icon: "error",
                        confirmButtonText: "Entendido",
                        background: isDarkMode ? "#000000" : "#fff", // cambia según el modo
                        color: isDarkMode ? "#fff" : "#000",
                        customClass: {
                            popup: "animate__animated animate__shakeX"
                        }
                    });

                }
            });
        });
    </script>

    <script>
        // --- Modal Control Editar ---
        const editUserModal = document.getElementById('editUserModal');
        const closeEditModalBtn = document.getElementById('closeEditModalBtn');
        const cancelEditBtn = document.getElementById('cancelEditBtn');

        // Detecta el modo activo (usa las mismas reglas de tu plantilla)
        const isDarkMode = document.documentElement.getAttribute('data-theme') === 'dark';

        // Obtiene los colores definidos en las variables CSS
        const swalBackground = getComputedStyle(document.documentElement).getPropertyValue('--color-card')
            .trim();
        const swalTextColor = getComputedStyle(document.documentElement).getPropertyValue('--color-default-800')
            .trim();

        function openEditModal(user, roles, userRoleId) {
            document.getElementById('editUserId').value = user.id;
            document.getElementById('editName').value = user.name;
            document.getElementById('editEmail').value = user.email;
            document.getElementById('editPassword').value = '';
            document.getElementById('editStatus').value = user.status;

            // Llenar select de roles
            const roleSelect = document.getElementById('editRole');
            roleSelect.innerHTML = ''; // limpiar opciones

            // Opción por defecto
            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.text = 'Seleccione un rol';
            roleSelect.appendChild(defaultOption);

            // Agregar roles desde el controlador
            for (const [id, name] of Object.entries(roles)) {
                const option = document.createElement('option');
                option.value = id;
                option.text = name;
                if (userRoleId == id) option.selected = true; // seleccionar rol actual
                roleSelect.appendChild(option);
            }

            editUserModal.classList.remove('hidden');
        }

        function closeEditModal() {
            editUserModal.classList.add('hidden');
            document.getElementById('editUserForm').reset();
        }

        closeEditModalBtn.addEventListener('click', closeEditModal);
        cancelEditBtn.addEventListener('click', closeEditModal);

        window.addEventListener('click', (e) => {
            if (e.target === editUserModal) closeEditModal();
        });

        window.editUser = function(id) {
            jQuery('#loadingOverlay').show();
            jQuery.ajax({
                url: `/users/${id}/edit`,
                method: 'GET',
                success: function(response) {
                    jQuery('#loadingOverlay').hide();
                    if (response.success && response.user) {
                        // <-- Asegúrate de pasar response.roles y response.user_role_id
                        openEditModal(response.user, response.roles, response.user_role_id);
                    } else {
                        Swal.fire('Error', 'No se pudo obtener los datos del usuario', 'error');
                    }
                },
                error: function() {
                    jQuery('#loadingOverlay').hide();
                    Swal.fire('Error', 'Ocurrió un error al obtener el usuario', 'error');
                }
            });
        };




        jQuery('#editUserForm').on('submit', function(e) {
            e.preventDefault();
            jQuery('#loadingOverlay').show();

            const userId = jQuery('#editUserId').val();
            const formData = {
                name: jQuery('#editName').val(),
                email: jQuery('#editEmail').val(),
                password: jQuery('#editPassword').val(),
                status: jQuery('#editStatus').val(),
                role_id: jQuery('#editRole').val(),
                _token: jQuery('meta[name="csrf-token"]').attr('content'),
                _method: 'PUT'
            };

            const isDarkMode = document.documentElement.getAttribute('data-theme') === 'dark';

            jQuery.ajax({
                url: `/users/${userId}`,
                method: 'POST', // con _method=PUT
                data: formData,
                success: function(response) {
                    jQuery('#loadingOverlay').hide();
                    if (response.success) {
                        closeEditModal();
                        Swal.fire({
                            title: "Usuario actualizado",
                            text: "Los cambios se guardaron correctamente.",
                            icon: "success",
                            showConfirmButton: false,
                            timer: 2000,
                            background: isDarkMode ? "#000000" : "#fff",
                            color: isDarkMode ? "#fff" : "#000",
                            customClass: {
                                popup: "animate__animated animate__fadeInDown",
                                title: "text-lg font-semibold",
                                icon: "animate__animated animate__bounceIn"
                            }
                        });
                        jQuery('#usersTable').DataTable().ajax.reload();
                    } else {
                        Swal.fire('Error', 'Complete todos los campos correctamente', 'error');
                    }
                },
                error: function(xhr) {
                    jQuery('#loadingOverlay').hide();
                    let message = "Ocurrió un error inesperado.";
                    if (xhr.status === 422 && xhr.responseJSON?.errors) {
                        message = Object.values(xhr.responseJSON.errors)[0][0];
                    }
                    Swal.fire({
                        title: "Error",
                        text: message,
                        icon: "error",
                        confirmButtonText: "Entendido",
                        background: isDarkMode ? "#000000" : "#fff", // cambia según el modo
                        color: isDarkMode ? "#fff" : "#000",
                        customClass: {
                            popup: "animate__animated animate__shakeX"
                        }
                    });
                }
            });
        });
    </script>
@endsection
