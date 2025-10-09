@extends('layouts.leader')

@section('content')
    <div class="col-sm-12">
        <div class="card table-card">
            <div class="card-header d-flex justify-content-between align-items-centerr">
                <h4 class="text-primary">Data Member Belum Menyerahkan Saran</h4>
            </div>
            <div class="col-xl-3 col-sm-6 m-3">
                <div class="card shadow">
                    <div class="card-body m-2">
                        <div class="text-primary mb-1">
                            <b>Pilih Bulan</b>
                        </div>
                        <form class="user" action="{{ route('leader.suggestion.notSubmit.filter') }}" method="GET">
                            @csrf
                            <div class="row d-flex align-items-center">
                                <div class="col-8">
                                    <input name="Month" id="monthFilter" type="month" class="form-control" value="{{ $monthInput }}" required>
                                </div>
                                <div class="col-4">
                                    <button class="d-sm-inline btn btn-md btn-primary" type="submit">
                                        Apply
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="card-body p-3">
                <div class="table-responsive text-nowrap">
                    <table id="suggestionsTable" class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-primary text-center">No</th>
                                <th class="text-primary text-center">Member</th>
                                <th class="text-primary text-center">NIK</th>
                                <th class="text-primary text-center">Team</th>
                            </tr>
                            <tr id="filter-row">
                                <th></th>
                                <th><input type="text" id="filterName" class="form-control form-control-sm" placeholder="Cari Nama"></th>
                                <th><input type="text" id="filterNik" class="form-control form-control-sm" placeholder="Cari NIK"></th>
                                <th>
                                    <select id="filterTeam" class="form-control form-control-sm">
                                        <option value="">Semua</option>
                                        <option value="Assembling">Assembling</option>
                                        <option value="Painting">Painting</option>
                                        <option value="DST">DST</option>
                                    </select>
                                </th>
                            </tr>
                        </thead>
                    </table>
                </div>
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
    $(document).ready(function () {
        const table = $('#suggestionsTable').DataTable({
            processing: true,
            serverSide: true,
            deferRender: true,
            pageLength: 50,
            order: [[2, 'asc']], // tetap bisa sorting manual di header
            ajax: {
                url: "{{ route('leader.suggestion.notSubmit.data') }}",
                data: function (d) {
                    d.Month = $('#monthFilter').val(); // kirim bulan dari input
                    d.name = $('#filterName').val();
                    d.nik = $('#filterNik').val();
                    d.team = $('#filterTeam').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', className: 'text-center', orderable: false, searchable: false },
                { data: 'name', name: 'name', className: 'text-left' },
                { data: 'nik', name: 'nik', className: 'text-center' },
                { data: 'team', name: 'team', className: 'text-left' },
            ],
            scrollX: true,
            scrollY: "500px",
            scrollCollapse: true,
            fixedColumns: { left: 1 },
        });

        // saat form apply ditekan
        $('form.user').on('submit', function (e) {
            e.preventDefault();
            table.draw(false); // reload tanpa reset sort/page
        });

        // event filter nama dan nik (delay biar ga spam)
        let typingTimer;
        const doneTypingInterval = 400;
        $('#filterName, #filterNik').on('keyup', function () {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(() => table.draw(false), doneTypingInterval);
        });

        // filter team
        $('#filterTeam').on('change', function () {
            table.draw(false);
        });
    });
    </script>
@endsection
