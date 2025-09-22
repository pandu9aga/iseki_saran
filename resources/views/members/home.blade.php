@extends('layouts.member')

@section('content')
<div class="col-sm-12">
    <div class="card table-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>Data Saran</h5>
            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                Tambah Saran
            </button>
        </div>
        <div class="card-body p-3">
            <div class="table-responsive">
                <table id="usersTable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Username</th>
                            <th>Name</th>
                            <th>Password</th>
                            <th>Type User</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection