@extends('layouts.leader')
@section('content')
    <div class="col-sm-12">
        <div class="card table-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="text-primary">Record Nilai </h4>
                <a href="{{ route('leader.suggestion.detailPerSaran.export', ['month' => request('Month', $monthInput)]) }}"
                    class="btn btn-success btn-sm">
                    <i class="fas fa-file-excel"></i> Export Excel
                </a>
            </div>

            <div class="col-xl-3 col-sm-6 m-3">
                <div class="card shadow">
                    <div class="card-body m-2">
                        <div class="text-primary mb-1"><b>Pilih Bulan</b></div>
                        <form class="user" method="GET">
                            @csrf
                            <div class="row d-flex align-items-center">
                                <div class="col-8">
                                    <input name="Month" id="monthFilter" type="month" class="form-control"
                                        value="{{ request('Month', $monthInput) }}" required>
                                </div>
                                <div class="col-4">
                                    <button class="d-sm-inline btn btn-md btn-primary" type="submit">Apply</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="card-body p-3">
                <div class="table-responsive">
                    <table id="tableDetail" class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Nama Member</th>
                                <th class="text-center">Total Skor </th>
                                <th>NIK</th>
                                <th>Team</th>
                                <th>Permasalahan</th>
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
@endsection

@section('script')
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatables.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#tableDetail').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 100,
                ajax: {
                    url: '{{ route('leader.suggestion.detailPerSaran.data') }}',
                    data: function(d) {
                        d.month = $('#monthFilter').val() || '{{ request('Month', $monthInput) }}';
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'member_nama',
                        name: 'employees.nama'
                    },
                    {
                        data: 'total_score',
                        name: 'total_score',
                        className: 'text-center'
                    },
                    {
                        data: 'member_nik',
                        name: 'employees.nik'
                    },
                    {
                        data: 'Team_Suggestion',
                        name: 'Team_Suggestion'
                    },
                    {
                        data: 'Content_Suggestion',
                        name: 'Content_Suggestion',
                        render: function(d) {
                            return d && d.length > 50 ? d.substr(0, 50) + '...' : (d || '');
                        }
                    }
                ]
            });
        });
    </script>
@endsection
