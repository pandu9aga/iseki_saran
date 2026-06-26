@extends('layouts.leader')

@section('content')
    <div class="col-sm-12">
        <div class="card table-card">
            <div class="card-header d-flex justify-content-between align-items-centerr">
                <h4 class="text-primary">Data Saran</h4>
            </div>
            <div class="col-xl-3 col-sm-6 m-3">
                <div class="card shadow">
                    <div class="card-body m-2">
                        <div class="text-primary mb-1">
                            <b>Pilih Bulan</b>
                        </div>
                        <form class="user" action="{{ route('leader.suggestion.filter') }}" method="GET">
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
                <button id="btnExportAllPdf" class="btn btn-danger btn-sm">
                    <i class="material-icons-two-tone text-white" style="font-size:16px;">picture_as_pdf</i>
                    Export PDF All
                </button>
                <button id="btnExportExcel" class="btn btn-success btn-sm">
                    <i class="material-icons-two-tone text-white" style="font-size:16px;">table_view</i>
                    Download Excel
                </button>
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            </div>
            <div class="card-body p-3">
                <div class="table-responsive text-nowrap">
                    <table id="suggestionsTable" class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-primary text-center">No</th>
                                <th class="text-primary text-center">Member</th>
                                <th class="text-primary text-center">Permasalahan</th>
                                <th class="text-primary text-center">Tanggal <br> Penyerahan <br> Awal</th>
                                <th class="text-primary text-center">Team</th>
                                <th class="text-primary text-center">Foto <br> Permasalahan</th>
                                <th class="text-primary text-center">Perbaikan</th>
                                <th class="text-primary text-center">Foto <br> Perbaikan</th>
                                <th class="text-primary text-center">Skor A</th>
                                <th class="text-primary text-center">Skor B</th>
                                <th class="text-primary text-center">Komentar<span style="color: #FFFFFF;">__________________________________________________________</span></th>
                                <th class="text-primary text-center">Leader</th>
                                <th class="text-primary text-center">Status</th>
                                <th class="text-primary text-center">Tema</th>
                                <th class="text-primary text-center">No <br> Penerimaan <br> Awal</th>
                                <th class="text-primary text-center">No <br> Penerimaan <br> Akhir</th>
                                <th class="text-primary text-center">Tanggal <br> Penyerahan <br> Akhir</th>
                                <th class="text-primary text-center">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <!-- Modal Export PDF All -->
            <div class="modal fade" id="modalExportPdfAll" tabindex="-1">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-primary">
                                Daftar PDF Saran <span id="modalMonth"></span>
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <div class="mb-3">
                                <span class="badge bg-primary me-2">
                                    Total Dinilai: <b id="totalSuggestion">0</b>
                                </span>
                                <span class="badge bg-info">
                                    PDF Tersedia: <b id="totalPdfReady">0</b>
                                </span>
                            </div>

                            <div id="pdfBadgeContainer" class="d-flex flex-wrap gap-2 mb-2">
                                <span class="text-muted">Loading...</span>
                            </div>

                            <span class="text-secondary text-xs">
                                Badge hijau = PDF tersedia • Abu-abu = belum dikonversi.
                            </span>

                            <span class="text-secondary text-xs">
                                Silakan tunggu atau lakukan simpan ulang pada saran yang belum tersedia PDF-nya.
                            </span>
                        </div>

                        <div class="modal-footer d-flex flex-wrap gap-2 justify-content-between">
                            <button class="btn btn-secondary" data-bs-dismiss="modal">
                                Close
                            </button>
                            <div class="d-flex flex-wrap gap-2" id="divisionDownloadBtns">
                                <!-- Dynamically populated per-division download buttons -->
                            </div>
                        </div>
                    </div>
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
            // clone header row untuk filter
            $('#suggestionsTable thead tr')
                .clone(true)
                .addClass('filters')
                .appendTo('#suggestionsTable thead');

            var table = $('#suggestionsTable').DataTable({
                processing: true,
                serverSide: true,
                deferRender: true,
                pageLength: 50,
                // order: [[3, 'desc']],
                ajax: {
                    url: '{{ route('leader.suggestions.data.month') }}',
                    type: 'GET',
                    data: function(d) {
                        d.month = $('#monthFilter').val() || '{{ $monthInput }}';
                    },
                    error: function(xhr, error, code) {
                        console.warn("DataTables AJAX Error:", error, code);
                    }
                },
                scrollX: true,
                scrollY: "500px",
                scrollCollapse: true,
                fixedColumns: {
                    leftColumns: 2,
                    rightColumns: 1
                },
                orderCellsTop: true,
                fixedHeader: false,
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'member_nama',
                        name: 'employees.nama',
                        render: function(data, type, row) {
                            if (!data) return '';
                            return data.length > 20 ? data.substr(0, 15) + '...' : data;
                        }
                    },
                    {
                        data: 'Content_Suggestion',
                        name: 'Content_Suggestion',
                        render: function(data, type, row) {
                            if (!data) return '';
                            return data.length > 20 ? data.substr(0, 20) + '...' : data;
                        }
                    },

                    {
                        data: 'Date_First_Suggestion',
                        name: 'Date_First_Suggestion'
                    },
                    {
                        data: 'Team_Suggestion',
                        name: 'Team_Suggestion'
                    },

                    {
                        data: 'Content_Photos_Suggestion',
                        name: 'Content_Photos_Suggestion',
                        render: function(data, type, row) {
                            if (data) {
                                try {
                                    // Decode dari HTML entities ke string biasa
                                    let decoded = data.replace(/&quot;/g, '"');
                                    let files = JSON.parse(decoded);

                                    return files.map(file =>
                                        '<img src="/iseki_saran/public/uploads/contents/' +
                                        file + '" ' +
                                        'class="img-thumbnail m-1" style="max-width:60px; max-height:60px;"/>'
                                    ).join('');
                                } catch (e) {
                                    console.error("Invalid JSON after decode:", data, e);
                                    return '';
                                }
                            }
                            return '';
                        }
                    },
                    {
                        data: 'Improvement_Suggestion',
                        name: 'Improvement_Suggestion',
                        render: function(data, type, row) {
                            if (!data) return '';
                            return data.length > 20 ? data.substr(0, 20) + '...' : data;
                        }
                    },
                    {
                        data: 'Improvement_Photos_Suggestion',
                        name: 'Improvement_Photos_Suggestion',
                        render: function(data, type, row) {
                            if (data) {
                                try {
                                    // Decode dari HTML entities ke string biasa
                                    let decoded = data.replace(/&quot;/g, '"');
                                    let files = JSON.parse(decoded);

                                    return files.map(file =>
                                        '<img src="/iseki_saran/public/uploads/improvements/' +
                                        file + '" ' +
                                        'class="img-thumbnail m-1" style="max-width:60px; max-height:60px;"/>'
                                    ).join('');
                                } catch (e) {
                                    console.error("Invalid JSON after decode:", data, e);
                                    return '';
                                }
                            }
                            return '';
                        }
                    },
                    {
                        data: 'Score_A_Suggestion',
                        name: 'Score_A_Suggestion'
                    },
                    {
                        data: 'Score_B_Suggestion',
                        name: 'Score_B_Suggestion'
                    },
                    {
                        data: 'Comment_Suggestion',
                        name: 'Comment_Suggestion',
                        render: function(data, type, row) {
                            if (!data) return '';
                            return data.length > 100 ? data.substr(0, 100) + '...' : data;
                        }
                    },
                    {
                        data: 'user_name',
                        name: 'users.Name_User'
                    },
                    {
                        data: 'Status_Suggestion',
                        name: 'Status_Suggestion'
                    },
                    {
                        data: 'Theme_Suggestion',
                        name: 'Theme_Suggestion'
                    },
                    {
                        data: 'Acceptance_First_Suggestion',
                        name: 'Acceptance_First_Suggestion'
                    },
                    {
                        data: 'Acceptance_Last_Suggestion',
                        name: 'Acceptance_Last_Suggestion'
                    },
                    {
                        data: 'Date_Last_Suggestion',
                        name: 'Date_Last_Suggestion'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                initComplete: function() {
                    var api = this.api();

                    api.columns().eq(0).each(function(colIdx) {
                        var cell = $('.filters th').eq($(api.column(colIdx).header()).index());
                        var title = $(cell).text().replace(/\s+/g, ' ').trim();

                        // Hanya beri filter untuk kolom tertentu
                        if (title !== "No" && title !== "Action" && !title.includes("Foto")) {

                            // === 🔹 FILTER TANGGAL (Date/Month Toggle) ===
                            if (title.includes("Tanggal Penyerahan Awal") || title.includes("Tanggal Penyerahan Akhir")) {
                                const filterId = `filterDate_${colIdx}`;

                                // Ambil bulan & tahun dari input bulan (misalnya id-nya #monthFilter)
                                const selectedMonth = document.querySelector('#monthFilter')?.value || '';
                                let year, month;

                                if (selectedMonth) {
                                    [year, month] = selectedMonth.split('-').map(Number);
                                    month = month - 1; // karena index bulan mulai dari 0
                                } else {
                                    const now = new Date();
                                    year = now.getFullYear();
                                    month = now.getMonth();
                                }

                                // Awal dan akhir bulan berdasarkan input bulan
                                const firstDay = new Date(year, month, 1);
                                const lastDay = new Date(year, month + 1, 0);

                                // Format yyyy-mm-dd
                                const formatDate = d =>
                                    `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`;

                                const minDate = formatDate(firstDay);
                                const maxDate = formatDate(lastDay);

                                $(cell).html(`
                                    <div class="d-flex flex-column align-items-stretch">
                                        <input id="${filterId}" type="date" 
                                            class="form-control form-control-sm mb-1"
                                            style="width:100%; padding:2px 4px; font-size:12px; line-height:1.1;"
                                            min="${minDate}" max="${maxDate}"
                                            placeholder="Pilih tanggal"/>
                                    </div>
                                `);

                                // Event filter tanggal
                                $(document).on('change', `#${filterId}`, function() {
                                    const val = $(this).val();
                                    const inputType = this.type;
                                    api.column(colIdx).search(val ? `${val}|${inputType}` : '', true, false).draw();
                                });

                            // === 🔹 FILTER STATUS ===
                            } else if (title.includes("Status")) {
                                $(cell).html(`
                                    <select class="form-control form-control-sm" style="font-size:12px; padding:2px 4px;">
                                        <option value="">Semua</option>
                                        <option value="1">Sudah Selesai</option>
                                        <option value="0">Belum Selesai</option>
                                    </select>
                                `);

                            // === 🔹 FILTER TEAM ===
                            } else if (title.includes("Team")) {
                                $(cell).html(`
                                    <select class="form-control form-control-sm" style="font-size:12px; padding:2px 4px;">
                                        <option value="">Semua</option>
                                        <option value="Assembling">Assembling</option>
                                        <option value="Painting">Painting</option>
                                        <option value="DST">DST</option>
                                    </select>
                                `);

                            // === 🔹 FILTER TEMA ===
                            } else if (title.includes("Tema")) {
                                $(cell).html(`
                                    <select class="form-control form-control-sm" style="font-size:12px; padding:2px 4px;">
                                        <option value="">Semua</option>
                                        <option value="Keselamatan">Keselamatan</option>
                                        <option value="Kualitas">Kualitas</option>
                                        <option value="Cost">Cost</option>
                                        <option value="Waktu">Waktu</option>
                                        <option value="Lingkungan">Lingkungan</option>
                                        <option value="Moral">Moral</option>
                                        <option value="Fasilitas">Fasilitas</option>
                                        <option value="Mould Jig">Mould Jig</option>
                                        <option value="Set Up">Set Up</option>
                                        <option value="Material">Material</option>
                                        <option value="Metode">Metode</option>
                                        <option value="Informasi">Informasi</option>
                                    </select>
                                `);

                            // === 🔹 FILTER SKOR A ===
                            } else if (title.includes("Skor A")) {
                                $(cell).html(`
                                    <select class="form-control form-control-sm" style="font-size:12px; padding:2px 4px;">
                                        <option value="">Semua</option>
                                        ${Array.from({ length: 15 }, (_, i) => 
                                            `<option value="${i+1}">${i+1} = Rp ${600*i} rb/tahun</option>`
                                        ).join('')}
                                    </select>
                                `);

                            // === 🔹 FILTER TEXT UMUM ===
                            } else {
                                $(cell).html(`
                                    <input type="text" 
                                        placeholder="Search ${title}" 
                                        class="form-control form-control-sm"
                                        style="font-size:12px; padding:2px 4px;"/>
                                `);
                            }
                        } else {
                            $(cell).html(''); // kolom No, Foto, Action kosong
                        }

                        // Event umum input & select
                        $('input[type="text"], select', cell).off().on('keyup change clear', function() {
                            if (api.column(colIdx).search() !== this.value) {
                                api.column(colIdx).search(this.value).draw();
                            }
                        });
                    });

                    // ✅ Setelah semua filter siap, sinkronkan ulang lebar kolom (mencegah header geser)
                    setTimeout(() => {
                        api.columns.adjust().draw(false);
                    }, 300);
                },


            });

            // Delete event
            $('#suggestionsTable').on('click', '.delete-btn', function() {
                var id = $(this).data('id');
                var content = $(this).data('name');

                if (confirm("Hapus saran: " + content + "?")) {
                    $.ajax({
                        url: '{{ url('leader/suggestion/delete') }}/' + id,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(res) {
                            if (res.success) {
                                table.ajax.reload(null, false);
                                alert(res.message);
                            } else {
                                alert('Gagal menghapus data');
                            }
                        },
                        error: function(xhr) {
                            alert('Error: ' + xhr.statusText);
                        }
                    });
                }
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
    <script>
        $('#btnExportAllPdf').on('click', function () {
            const month = $('#monthFilter').val();

            if (!month) {
                alert('Silakan pilih bulan terlebih dahulu');
                return;
            }

            $('#modalMonth').text(month);
            $('#pdfBadgeContainer').html('<span class="text-muted">Loading...</span>');
            $('#totalSuggestion').text('0');
            $('#totalPdfReady').text('0');
            $('#divisionDownloadBtns').html('');

            $('#modalExportPdfAll').modal('show');

            $.ajax({
                url: "{{ route('leader.suggestion.exportAllPdf.list') }}",
                type: 'GET',
                data: { Month: month },
                success: function (res) {

                    $('#totalSuggestion').text(res.total);
                    $('#totalPdfReady').text(res.pdf_ready);

                    if (!res.items.length) {
                        $('#pdfBadgeContainer').html(
                            '<span class="text-muted">Tidak ada data</span>'
                        );
                        return;
                    }

                    // Kelompokkan berdasarkan divisi/team
                    let grouped = {};
                    res.items.forEach(item => {
                        const team = item.team || '-';
                        if (!grouped[team]) grouped[team] = [];
                        grouped[team].push(item);
                    });

                    let html = '';
                    Object.keys(grouped).forEach(team => {
                        html += `<div class="w-100 mb-2">
                            <strong class="text-primary d-block mb-1">
                                <i class="material-icons-two-tone" style="font-size:16px; vertical-align:middle;">group</i>
                                ${team}
                                <span class="badge bg-light text-dark">${grouped[team].length}</span>
                            </strong>
                            <div class="d-flex flex-wrap gap-2">`;

                        grouped[team].forEach(item => {
                            const color = item.exists ? 'success' : 'secondary';
                            const url   = "{{ url('leader/suggestion') }}/" + item.id;

                            html += `
                                <a href="${url}" target="_blank"
                                class="badge bg-${color} fs-6 px-3 py-2 text-decoration-none">
                                    ${item.acc}
                                </a>
                            `;
                        });

                        html += `</div></div>`;
                    });

                    $('#pdfBadgeContainer').html(html);

                    // === Generate tombol download per divisi ===
                    let btnHtml = '';

                    // Tombol per divisi
                    if (res.teams && res.teams.length > 0) {
                        res.teams.forEach(team => {
                            const teamCount = grouped[team] ? grouped[team].length : 0;
                            const readyCount = grouped[team] ? grouped[team].filter(i => i.exists).length : 0;
                            btnHtml += `
                                <button class="btn btn-outline-danger btn-sm btn-download-pdf-divisi"
                                        data-team="${team}"
                                        title="Download PDF divisi ${team} (${readyCount}/${teamCount} tersedia)">
                                    <i class="material-icons-two-tone" style="font-size:14px; vertical-align:middle;">picture_as_pdf</i>
                                    ${team} <span class="badge bg-danger">${readyCount}</span>
                                </button>
                            `;
                        });

                        // Tombol download semua (jika ada lebih dari 1 divisi)
                        if (res.teams.length > 1) {
                            btnHtml += `
                                <button class="btn btn-danger btn-sm btn-download-pdf-divisi"
                                        data-team="all"
                                        title="Download semua divisi">
                                    <i class="material-icons-two-tone text-white" style="font-size:14px; vertical-align:middle;">picture_as_pdf</i>
                                    Download Semua <span class="badge bg-light text-danger">${res.pdf_ready}</span>
                                </button>
                            `;
                        }
                    }

                    $('#divisionDownloadBtns').html(btnHtml);
                },
                error: function () {
                    $('#pdfBadgeContainer').html(
                        '<span class="text-danger">Gagal memuat data</span>'
                    );
                }
            });
        });
    </script>
    <script>
        // Download per divisi via delegated event
        $(document).on('click', '.btn-download-pdf-divisi', function () {
            const month = $('#monthFilter').val();
            const team  = $(this).data('team');

            const form = $('<form>', {
                method: 'POST',
                action: "{{ route('leader.suggestion.exportAllPdf') }}"
            });

            form.append($('<input>', {
                type: 'hidden',
                name: '_token',
                value: '{{ csrf_token() }}'
            }));

            form.append($('<input>', {
                type: 'hidden',
                name: 'Month',
                value: month
            }));

            form.append($('<input>', {
                type: 'hidden',
                name: 'Team',
                value: team
            }));

            $('body').append(form);
            form.submit();
            form.remove();
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#btnExportExcel').on('click', function () {
                var table = $('#suggestionsTable').DataTable();
                var params = table.ajax.params();
                var queryStr = $.param(params);
                window.location.href = "{{ route('leader.suggestion.exportExcelMonth') }}?" + queryStr;
            });
        });
    </script>
@endsection
