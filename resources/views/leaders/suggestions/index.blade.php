@extends('layouts.leader')

@section('content')
    <div class="col-sm-12">
        <div class="card table-card">
            <div class="card-header d-flex justify-content-between align-items-centerr">
                <h4 class="text-primary">Data Saran</h4>
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
                                <th class="text-primary text-center">Komentar</th>
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
                order: [[3, 'desc']],
                ajax: {
                    url: '{{ route('leader.suggestions.data') }}',
                    type: 'GET',
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
                            return data.length > 20 ? data.substr(0, 20) + '...' : data;
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
                const toggleId = `toggleType_${colIdx}`;

                $(cell).html(`
                    <div class="d-flex flex-column align-items-stretch">
                        <input id="${filterId}" type="date" 
                            class="form-control form-control-sm mb-1"
                            style="width:100%; padding:2px 4px; font-size:12px; line-height:1.1;"
                            placeholder="Pilih tanggal"/>
                        <button type="button" id="${toggleId}" 
                            class="btn btn-primary btn-xs" 
                            style="font-size:10px; padding:2px 4px;">Month</button>
                    </div>
                `);

                // Event tombol toggle Date <-> Month
                $(document).on('click', `#${toggleId}`, function() {
                    const input = document.getElementById(filterId);
                    if (input.type === "date") {
                        input.type = "month";
                        $(this).text("Date");
                    } else {
                        input.type = "date";
                        $(this).text("Month");
                    }
                    input.dispatchEvent(new Event('change'));
                });

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
        });
    </script>
@endsection
