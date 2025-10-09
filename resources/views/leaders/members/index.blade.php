@extends('layouts.leader')

@section('content')
    <div class="col-sm-12">

        <div class="card table-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="text-primary">Data Member</h4>
            </div>
            <div class="card-body p-3">
                <div class="table-responsive">
                    <table class="table table-bordered" id="usersTable">
                        <thead>
                            <tr>
                                <th class="text-primary text-center col-1">No</th>
                                <th class="text-primary text-center">Name</th>
                                <th class="text-primary text-center">NIK</th>
                                <th class="text-primary text-center">Team</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($members as $member)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $member->nama }}</td>
                                    <td class="text-center">{{ $member->nik }}</td>
                                    <td class="text-center">{{ $member->division->nama ?? '' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('modal')
    <!-- Modal Tambah User -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('leader.users.store') }}" method="POST">
                    @csrf
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title text-white" id="addModalLabel">Tambah User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="Username_User" class="form-label">Username</label>
                            <input type="text" class="form-control" id="Username_User" name="Username_User"
                                placeholder="Username">
                        </div>
                        <div class="mb-3">
                            <label for="Name_User" class="form-label">Name</label>
                            <input type="text" class="form-control" id="Name_User" name="Name_User" placeholder="Name">
                        </div>
                        <div class="mb-3">
                            <label for="Password_User" class="form-label">Password</label>
                            <input type="password" class="form-control" id="Password_User" name="Password_User"
                                placeholder="Password">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit User -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="editUserForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header bg-info">
                        <h5 class="modal-title text-white" id="editUserModalLabel">Edit User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="edit_user_id" name="id">
                        <div class="mb-3">
                            <label for="edit_username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="edit_username" name="Username_User" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_name_user" class="form-label">Name</label>
                            <input type="text" class="form-control" id="edit_name_user" name="Name_User" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="edit_password" name="Password_User">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('style')
    <link href="{{ asset('assets/css/datatables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/fixedColumns.dataTables.min.css') }}" rel="stylesheet">
@endsection

@section('script')
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.fixedColumns.min.js') }}"></script>

    <script>
        // $(document).ready(function() {
        //     $('#usersTable').DataTable({
        //         autoWidth: true, // Biarkan DataTables otomatis menyesuaikan lebar kolom
        //         scrollX: false, // Hilangkan horizontal scroll kalau tidak terlalu lebar
        //         columnDefs: [{
        //                 className: "text-center",
        //                 targets: 0
        //             } // No tetap di tengah
        //         ],
        //     });
        // });
        $(document).ready(function () {
            var table;

            if ($.fn.DataTable.isDataTable('#usersTable')) {
                table = $('#usersTable').DataTable();
                table.page.len(-1).draw(); // ✅ default tampil semua
            } else {
                table = $('#usersTable').DataTable({
                    pageLength: -1,
                    lengthMenu: [[10,25,50,100,-1],[10,25,50,100,"All"]],
                    scrollY: '60vh',        // ✅ tinggi maksimal area scroll
                    scrollCollapse: true,   // jika datanya sedikit, tinggi ikut menyesuaikan
                    scrollX: true,          // scroll horizontal tetap aktif
                });
            }

            // biar tabel langsung fit kalau jendela di-resize
            $(window).on('resize', function() {
                table.columns.adjust().draw(false);
            });
        });
    </script>
@endsection
