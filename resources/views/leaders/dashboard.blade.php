@extends('layouts.leader')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card table-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="text-primary">Dashboard Leader</h4>
            </div>
            <div class="card-body p-3">
                <p>Selamat datang, {{ $user->Name_User ?? 'Leader' }}</p>
            </div>
        </div>
    </div>
</div>
<div class="row">
    {{-- <div class="col-md-4">
        <div class="card table-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="text-primary">Data Member</h5>
            </div>
            <div class="card-body p-2" style="max-width: 300px; margin:auto;">
                <canvas id="memberChart"></canvas>
            </div>
        </div>
    </div> --}}
    <div class="col-md-4">
        <div class="card table-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="text-primary">Data Saran <span class="text-secondary" id="thisMonth"></span></h5>
            </div>
            <b class="mx-4" style="font-size: 18px; color: grey;">Total: <span id="total-saran"></span></b>
            <div class="card-body p-2" style="max-width: 500px;">
                <canvas id="suggestionChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card table-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="text-primary">Data Belum Menyerahkan <span class="text-secondary" id="thisMonth2"></span></h5>
            </div>
            <div class="card-body p-2" style="max-width: 300px; margin:auto;">
                <canvas id="notSubmitChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card table-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="text-primary">Data Belum Dinilai <span class="text-secondary" id="thisMonth3"></span></h5>
            </div>
            <div class="card-body p-2" style="max-width: 300px; margin:auto;">
                <canvas id="notSignChart"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('assets/js/chart.min.js') }}"></script>
<script>
// fetch("{{ route('member.stats') }}")
//     .then(res => res.json())
//     .then(data => {
//         let ctx = document.getElementById('memberChart').getContext('2d');

//         let labels = data.byDivision.map(item => item.nama);
//         let values = data.byDivision.map(item => item.total);

//         // warna random beda2
//         let backgroundColors = [
//             'rgba(255, 99, 132, 0.9)',
//             'rgba(54, 162, 235, 0.9)',
//             'rgba(255, 206, 86, 0.9)',
//             'rgba(75, 192, 192, 0.9)',
//             'rgba(153, 102, 255, 0.9)',
//             'rgba(255, 159, 64, 0.9)'
//         ];

//         // Plugin untuk total di tengah pie chart
//         const centerText = {
//             id: 'centerText',
//             beforeDraw(chart) {
//                 const { ctx, chartArea: { top, bottom, left, right } } = chart;

//                 const xCenter = (left + right) / 2;
//                 const yCenter = (top + bottom) / 2;

//                 ctx.save();
//                 ctx.font = 'bold 18px Arial';
//                 ctx.fillStyle = 'grey';
//                 ctx.textAlign = 'center';
//                 ctx.textBaseline = 'middle';
//                 ctx.fillText('Total: ' + chart.config.data.datasets[0].data.reduce((a,b)=>a+b,0), xCenter, yCenter);
//                 ctx.restore();
//             }
//         };

//         new Chart(ctx, {
//             type: 'doughnut',
//             data: {
//                 labels: labels,
//                 datasets: [{
//                     label: 'Jumlah Member per Divisi',
//                     data: values,
//                     backgroundColor: backgroundColors,
//                     borderColor: 'white',
//                     borderWidth: 2
//                 }]
//             },
//             options: {
//                 responsive: true,
//                 maintainAspectRatio: true,
//                 plugins: {
//                     title: {
//                         display: true,
//                         text: 'Jumlah Member per Divisi'
//                     },
//                     legend: {
//                         position: 'bottom',
//                         labels: {
//                             generateLabels(chart) {
//                                 const data = chart.data;
//                                 if (data.labels.length && data.datasets.length) {
//                                     return data.labels.map((label, i) => {
//                                         let value = data.datasets[0].data[i];
//                                         let backgroundColor = data.datasets[0].backgroundColor[i];

//                                         return {
//                                             text: `${label} (${value})`, // tampil nama + nilai
//                                             fillStyle: backgroundColor,
//                                             strokeStyle: backgroundColor,
//                                             hidden: isNaN(value) || chart.getDatasetMeta(0).data[i].hidden,
//                                             index: i
//                                         };
//                                     });
//                                 }
//                                 return [];
//                             }
//                         }
//                     }
//                 }
//             },
//             plugins: [centerText]
//         });
//     });

fetch("{{ route('suggestion.stats') }}")
    .then(res => res.json())
    .then(data => {
        document.getElementById("thisMonth").innerText = "(" + data.month + ")";

        let ctx = document.getElementById('suggestionChart').getContext('2d');

        let labels = data.byTeam.map(item => item.Team_Suggestion);
        let values = data.byTeam.map(item => item.total);

        let totalAll = values.reduce((a, b) => a + b, 0);
        document.getElementById("total-saran").innerText = totalAll;

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Saran',
                    data: values,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.9)',
                        'rgba(54, 162, 235, 0.9)',
                        'rgba(255, 206, 86, 0.9)',
                        'rgba(75, 192, 192, 0.9)',
                        'rgba(153, 102, 255, 0.9)',
                        'rgba(255, 159, 64, 0.9)'
                    ],
                    borderColor: 'white',
                    borderWidth: 1
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Jumlah Saran per Divisi'
                    },
                    // subtitle: {
                    //     display: true,
                    //     text: 'Total semua saran: ' + totalAll,
                    //     padding: { bottom: 10 },
                    //     color: 'black',
                    //     font: { weight: 'bold', size: 14 }
                    // },
                    legend: {
                        position: 'bottom',
                        labels: {
                            generateLabels(chart) {
                                const data = chart.data;
                                if (data.labels.length && data.datasets.length) {
                                    return data.labels.map((label, i) => {
                                        let value = data.datasets[0].data[i];
                                        let backgroundColor = data.datasets[0].backgroundColor[i];
                                        return {
                                            text: `${label} (${value})`,
                                            fillStyle: backgroundColor,
                                            strokeStyle: backgroundColor,
                                            hidden: isNaN(value) || chart.getDatasetMeta(0).data[i].hidden,
                                            index: i
                                        };
                                    });
                                }
                                return [];
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    });

fetch("{{ route('notSubmit.stats') }}")
    .then(res => res.json())
    .then(data => {
        document.getElementById("thisMonth2").innerText = "(" + data.month + ")";
        let ctx = document.getElementById('notSubmitChart').getContext('2d');

        // Ambil label dan nilai dari hasil API
        let labels = data.byDivision.map(item => item.team);
        let values = data.byDivision.map(item => item.total_not_submit);

        // warna random beda-beda (bisa ditambah kalau divisinya banyak)
        let backgroundColors = [
            'rgba(255, 99, 132, 0.9)',
            'rgba(54, 162, 235, 0.9)',
            'rgba(255, 206, 86, 0.9)',
            'rgba(75, 192, 192, 0.9)',
            'rgba(153, 102, 255, 0.9)',
            'rgba(255, 159, 64, 0.9)',
            'rgba(100, 181, 246, 0.9)',
            'rgba(255, 138, 101, 0.9)',
            'rgba(174, 213, 129, 0.9)',
            'rgba(240, 98, 146, 0.9)'
        ];

        // Plugin untuk menampilkan total di tengah doughnut chart
        const centerText = {
            id: 'centerText',
            beforeDraw(chart) {
                const { ctx, chartArea: { top, bottom, left, right } } = chart;
                const xCenter = (left + right) / 2;
                const yCenter = (top + bottom) / 2;

                ctx.save();
                ctx.font = 'bold 18px Arial';
                ctx.fillStyle = 'grey';
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                ctx.fillText('Total: ' + data.total, xCenter, yCenter);
                ctx.restore();
            }
        };

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Belum Menyerahkan per Divisi',
                    data: values,
                    backgroundColor: backgroundColors.slice(0, labels.length),
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: `Jumlah Belum Menyerahkan per Divisi`
                    },
                    legend: {
                        position: 'bottom',
                        labels: {
                            generateLabels(chart) {
                                const data = chart.data;
                                if (data.labels.length && data.datasets.length) {
                                    return data.labels.map((label, i) => {
                                        const value = data.datasets[0].data[i];
                                        const color = data.datasets[0].backgroundColor[i];
                                        return {
                                            text: `${label} (${value})`,
                                            fillStyle: color,
                                            strokeStyle: color,
                                            index: i
                                        };
                                    });
                                }
                                return [];
                            }
                        }
                    }
                }
            },
            plugins: [centerText]
        });
    })
    .catch(err => console.error('Error fetching data:', err));

fetch("{{ route('notSign.stats') }}")
    .then(res => res.json())
    .then(data => {
        document.getElementById("thisMonth3").innerText = "(" + data.month + ")";
        let ctx = document.getElementById('notSignChart').getContext('2d');

        // Ambil label dan nilai dari hasil API
        let labels = data.byDivision.map(item => item.division);
        let values = data.byDivision.map(item => item.total_not_signed);

        // warna random beda-beda (bisa ditambah kalau divisinya banyak)
        let backgroundColors = [
            'rgba(255, 99, 132, 0.9)',
            'rgba(54, 162, 235, 0.9)',
            'rgba(255, 206, 86, 0.9)',
            'rgba(75, 192, 192, 0.9)',
            'rgba(153, 102, 255, 0.9)',
            'rgba(255, 159, 64, 0.9)',
            'rgba(100, 181, 246, 0.9)',
            'rgba(255, 138, 101, 0.9)',
            'rgba(174, 213, 129, 0.9)',
            'rgba(240, 98, 146, 0.9)'
        ];

        // Plugin untuk menampilkan total di tengah doughnut chart
        const centerText = {
            id: 'centerText',
            beforeDraw(chart) {
                const { ctx, chartArea: { top, bottom, left, right } } = chart;
                const xCenter = (left + right) / 2;
                const yCenter = (top + bottom) / 2;

                ctx.save();
                ctx.font = 'bold 18px Arial';
                ctx.fillStyle = 'grey';
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                ctx.fillText('Total: ' + data.total, xCenter, yCenter);
                ctx.restore();
            }
        };

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Belum Dinilai per Divisi',
                    data: values,
                    backgroundColor: backgroundColors.slice(0, labels.length),
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: `Jumlah Belum Dinilai per Divisi`
                    },
                    legend: {
                        position: 'bottom',
                        labels: {
                            generateLabels(chart) {
                                const data = chart.data;
                                if (data.labels.length && data.datasets.length) {
                                    return data.labels.map((label, i) => {
                                        const value = data.datasets[0].data[i];
                                        const color = data.datasets[0].backgroundColor[i];
                                        return {
                                            text: `${label} (${value})`,
                                            fillStyle: color,
                                            strokeStyle: color,
                                            index: i
                                        };
                                    });
                                }
                                return [];
                            }
                        }
                    }
                }
            },
            plugins: [centerText]
        });
    })
    .catch(err => console.error('Error fetching data:', err));
</script>
@endsection