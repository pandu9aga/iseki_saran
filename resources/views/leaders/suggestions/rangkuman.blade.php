@extends('layouts.leader')
@section('content')
    <div class="col-sm-12">
        <div class="card table-card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="text-primary">Rangkuman Tahunan</h4>
            </div>

            <div class="col-xl-3 col-sm-6 m-3">
                <div class="card shadow">
                    <div class="card-body m-2">
                        <div class="text-primary mb-1"><b>Pilih Tahun</b></div>
                        <form class="user" method="GET">
                            @csrf
                            <div class="row d-flex align-items-center g-1">
                                <div class="col-auto">
                                    <button type="button" class="btn btn-outline-primary btn-sm" id="prevYear">&lt;</button>
                                </div>
                                <div class="col-5">
                                    <select name="Year" id="yearFilter" class="form-control form-control-sm" required>
                                        @php
                                            $startYear = 2020;
                                            $endYear = date('Y') + 1;
                                        @endphp
                                        @for ($y = $startYear; $y <= $endYear; $y++)
                                            <option value="{{ $y }}" {{ $y == $yearInput ? 'selected' : '' }}>{{ $y }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-auto">
                                    <button type="button" class="btn btn-outline-primary btn-sm" id="nextYear">&gt;</button>
                                </div>
                                <div class="col-auto">
                                    <button class="btn btn-md btn-primary" type="submit">Apply</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts are now above the table -->
    <div class="row mb-4">
        <div class="col-md-6 mb-4">
            <div class="card table-card">
                <div class="card-header">
                    <h5 class="text-primary">Grafik Saran</h5>
                </div>
                <div class="card-body">
                    <canvas id="chartSaran"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card table-card">
                <div class="card-header">
                    <h5 class="text-primary">Grafik Selesai</h5>
                </div>
                <div class="card-body">
                    <canvas id="chartSelesai"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card table-card">
                <div class="card-header">
                    <h5 class="text-primary">Grafik Nilai &gt;5</h5>
                </div>
                <div class="card-body">
                    <canvas id="chartNilaiLebih5"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card table-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="text-primary mb-0">Grafik Total Jam</h5>
                    @php
                        $totalJamChart = 0;
                        foreach($data as $d) {
                            $totalJamChart += $d['total_jam'] ?? 0;
                        }
                    @endphp
                    <span class="badge bg-success fs-6">Total Keuntungan: Rp. {{ number_format($totalJamChart * 60000, 0, ',', '.') }}</span>
                </div>
                <div class="card-body">
                    <canvas id="chartTotalJam"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="card table-card">
            <div class="card-body p-3">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th class="text-primary text-center">Bulan</th>
                                <th class="text-primary text-center">Saran</th>
                                <th class="text-primary text-center">Selesai</th>
                                <th class="text-primary text-center">Nilai &gt;5</th>
                                <th class="text-primary text-center">Total Jam</th>
                                <th class="text-primary text-center">Keuntungan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                                $totalSaran = 0; $totalSelesai = 0; $totalLebih5 = 0; $totalJam = 0; $totalKeuntungan = 0;
                            @endphp
                            @for ($m = 1; $m <= 12; $m++)
                                @php
                                    $totalSaran += $data[$m]['saran'];
                                    $totalSelesai += $data[$m]['selesai'];
                                    $totalLebih5 += $data[$m]['nilai_lebih5'];
                                    $totalJam += $data[$m]['total_jam'] ?? 0;
                                    $keuntungan = ($data[$m]['total_jam'] ?? 0) * 60000;
                                    $totalKeuntungan += $keuntungan;
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $months[$m-1] }}</td>
                                    <td class="text-center">{{ $data[$m]['saran'] }}</td>
                                    <td class="text-center">{{ $data[$m]['selesai'] }}</td>
                                    <td class="text-center">{{ $data[$m]['nilai_lebih5'] }}</td>
                                    <td class="text-center">{{ $data[$m]['total_jam'] ?? 0 }}</td>
                                    <td class="text-center">Rp. {{ number_format($keuntungan, 0, ',', '.') }}</td>
                                </tr>
                            @endfor
                        </tbody>
                        <tfoot class="table-light fw-bold">
                            <tr>
                                <td class="text-center">Total</td>
                                <td class="text-center">{{ $totalSaran }}</td>
                                <td class="text-center">{{ $totalSelesai }}</td>
                                <td class="text-center">{{ $totalLebih5 }}</td>
                                <td class="text-center">{{ $totalJam }}</td>
                                <td class="text-center">Rp. {{ number_format($totalKeuntungan, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
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
    <script src="{{ asset('assets/js/chart.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#prevYear').on('click', function() {
                var val = $('#yearFilter').val();
                if (!val) return;
                var y = +val - 1;
                $('#yearFilter').val(y).closest('form').submit();
            });
            $('#nextYear').on('click', function() {
                var val = $('#yearFilter').val();
                if (!val) return;
                var y = +val + 1;
                $('#yearFilter').val(y).closest('form').submit();
            });
            $('#yearFilter').on('change', function() {
                $(this).closest('form').submit();
            });

            var months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            var saranData = [@for($m=1;$m<=12;$m++){{ $data[$m]['saran'] }}{{ $m<12?',':'' }}@endfor];
            var selesaiData = [@for($m=1;$m<=12;$m++){{ $data[$m]['selesai'] }}{{ $m<12?',':'' }}@endfor];
            var nilaiLebih5Data = [@for($m=1;$m<=12;$m++){{ $data[$m]['nilai_lebih5'] }}{{ $m<12?',':'' }}@endfor];
            var totalJamData = [@for($m=1;$m<=12;$m++){{ $data[$m]['total_jam'] ?? 0 }}{{ $m<12?',':'' }}@endfor];

            function createLineChart(canvasId, label, data, color) {
                var ctx = document.getElementById(canvasId).getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: months,
                        datasets: [{
                            label: label,
                            data: data,
                            borderColor: color,
                            backgroundColor: color.replace('1)', '0.2)'),
                            pointBackgroundColor: color,
                            pointBorderColor: '#fff',
                            pointRadius: 5,
                            pointHoverRadius: 7,
                            fill: true,
                            tension: 0.3
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    // stepSize dihapus agar bisa desimal
                                }
                            }
                        }
                    }
                });
            }

            createLineChart('chartSaran', 'Jumlah Saran', saranData, 'rgba(54, 162, 235, 1)');
            createLineChart('chartSelesai', 'Jumlah Selesai', selesaiData, 'rgba(255, 159, 64, 1)');
            createLineChart('chartNilaiLebih5', 'Nilai >5', nilaiLebih5Data, 'rgba(75, 192, 192, 1)');
            createLineChart('chartTotalJam', 'Total Jam', totalJamData, 'rgba(153, 102, 255, 1)');
        });
    </script>
@endsection