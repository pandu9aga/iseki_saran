@extends('layouts.leader')
@section('content')
    <div class="col-sm-12">
        <div class="card table-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="text-primary">Record Nilai</h4>
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
                                            $currentMonth = request('Month', $monthInput);
                                        @endphp
                                        @for ($y = $startYear; $y <= $endYear; $y++)
                                            @for ($m = 1; $m <= 12; $m++)
                                                @php $val = sprintf('%04d-%02d', $y, $m); @endphp
                                                <option value="{{ $val }}" {{ $val === $currentMonth ? 'selected' : '' }}>{{ $months[$m-1] }} {{ $y }}</option>
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

            {{-- Ringkasan --}}
            <div class="row g-2 mx-2 mb-3">
                <div class="col-md-3">
                    <div class="p-3 border rounded bg-white d-flex align-items-center gap-3">
                        <div class="text-secondary" style="font-size:0.75rem;letter-spacing:0.5px;text-transform:uppercase;">Jam Perbaikan</div>
                        <div class="ms-auto fw-semibold">{{ $totalJam ? (float)$totalJam : '-' }}<span class="text-secondary fw-normal" style="font-size:0.8rem;">{{ $totalJam ? ' jam' : '' }}</span></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-3 border rounded bg-white d-flex align-items-center gap-3">
                        <div class="text-secondary" style="font-size:0.75rem;letter-spacing:0.5px;text-transform:uppercase;">No Penerimaan</div>
                        <div class="ms-auto fw-semibold" style="font-size:0.85rem;">{{ $firstAcc ? str_pad($firstAcc, 5, '0', STR_PAD_LEFT) : '-' }} – {{ $lastAcc ? str_pad($lastAcc, 5, '0', STR_PAD_LEFT) : '-' }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 border rounded bg-white d-flex align-items-center gap-3 flex-wrap">
                        <div class="text-secondary" style="font-size:0.75rem;letter-spacing:0.5px;text-transform:uppercase;">Per Nilai</div>
                        <div class="d-flex gap-2 ms-auto flex-wrap">
                            @foreach([0, 1, 2, 3, 4] as $i)
                                <span class="badge bg-light text-dark fw-normal px-2 py-1" style="font-size:0.8rem;">Score {{ $i }}: {{ $perNilai[$i] ?? 0 }}</span>
                            @endforeach
                            <span class="badge bg-light text-dark fw-normal px-2 py-1" style="font-size:0.8rem;">Score >5: {{ $perNilai['lebih5'] ?? 0 }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="p-3 border rounded bg-white d-flex align-items-center gap-3 flex-wrap">
                        <div class="text-secondary" style="font-size:0.75rem;letter-spacing:0.5px;text-transform:uppercase;">Per Tema</div>
                        <div class="d-flex gap-2 flex-wrap">
                            @foreach($perTema as $tema => $count)
                                <span class="badge bg-light text-dark fw-normal px-2 py-1" style="font-size:0.8rem;">{{ $tema }}: {{ $count }}</span>
                            @endforeach
                        </div>
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
                                <th class="text-center">No Penerimaan Awal</th>
                                <th class="text-center">Total Jam</th>
                                <th class="text-center">Total Skor</th>
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
    <link href="{{ asset('assets/css/fixedColumns.dataTables.min.css') }}" rel="stylesheet">

@endsection

@section('script')
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.fixedColumns.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#tableDetail').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 100,
                order: [[1, 'asc']],
                ajax: {
                    url: '{{ route('leader.suggestion.detailPerSaran.data') }}',
                    data: function(d) {
                        d.month = $('#monthFilter').val() || '{{ request('Month', $monthInput) }}';
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'member_nama', name: 'employees.nama', defaultContent: '' },
                    {
                        data: 'Acceptance_First_Suggestion',
                        name: 'Acceptance_First_Suggestion',
                        className: 'text-center',
                        defaultContent: ''
                    },
                    {
                        data: 'Hour_Suggestion',
                        name: 'Hour_Suggestion',
                        className: 'text-center',
                        render: function(data, type, row) {
                            if (data === null || data === '') return '';
                            return data + ' jam';
                        }
                    },
                    {
                        data: 'total_score',
                        name: 'total_score',
                        className: 'text-center',
                        defaultContent: 0,
                        searchable: false
                    },
                    { data: 'Team_Suggestion', name: 'Team_Suggestion', defaultContent: '' },
                    {
                        data: 'Content_Suggestion',
                        name: 'Content_Suggestion',
                        defaultContent: '',
                        render: function(d) {
                            return d && d.length > 50 ? d.substr(0, 50) + '...' : (d || '');
                        }
                    }
                ]
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