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
                            <div class="row d-flex align-items-center g-1">
                                <div class="col-auto">
                                    <button type="button" class="btn btn-outline-primary btn-sm" id="prevMonth">&lt;</button>
                                </div>
                                <div class="col-5">
                                    <select name="Month" id="monthFilter" class="form-control form-control-sm" required>
                                        @php
                                            $startYear = 2020;
                                            $endYear = date('Y') + 1;
                                            $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                                        @endphp
                                        @for ($y = $startYear; $y <= $endYear; $y++)
                                            @for ($m = 1; $m <= 12; $m++)
                                                @php $val = sprintf('%04d-%02d', $y, $m); @endphp
                                                <option value="{{ $val }}" {{ $val === $monthInput ? 'selected' : '' }}>{{ $months[$m-1] }} {{ $y }}</option>
                                            @endfor
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-auto">
                                    <button type="button" class="btn btn-outline-primary btn-sm" id="nextMonth">&gt;</button>
                                </div>
                                <div class="col-auto">
                                    <button class="btn btn-md btn-primary" type="submit">Apply</button>
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

        // Prev/Next month navigation
        $('#prevMonth').on('click', function() {
            var val = $('#monthFilter').val();
            if (!val) return;
            var p = val.split('-');
            var y = +p[0], m = +p[1];
            m--;
            if (m < 1) { m = 12; y--; }
            $('#monthFilter').val(y + '-' + String(m).padStart(2, '0')).closest('form').submit();
        });
        $('#nextMonth').on('click', function() {
            var val = $('#monthFilter').val();
            if (!val) return;
            var p = val.split('-');
            var y = +p[0], m = +p[1];
            m++;
            if (m > 12) { m = 1; y++; }
            $('#monthFilter').val(y + '-' + String(m).padStart(2, '0')).closest('form').submit();
        });
        $('#monthFilter').on('change', function() {
            $(this).closest('form').submit();
        });
    });
    </script>
@endsection
