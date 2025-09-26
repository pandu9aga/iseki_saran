@extends('layouts.member')

@section('content')
<div class="col-sm-12">
    <div class="card table-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="text-primary">Data Saran</h4>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSuggestionModal">
                Tambah Saran
            </button>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="addSuggestionModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form action="{{ route('suggestion.store') }}" method="POST" style="display:inline;" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header bg-primary">
                            <h5 class="modal-title text-white" id="addSuggestionModalLabel">Saran Perbaikan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col mb-1">
                                    <label for="Name_Member" class="form-label">Member</label>
                                    <input type="text" id="Name_Member" name="Name_Member" class="form-control" value="{{ $member->nama }}" readonly/>
                                    <input type="hidden" id="Id_Member" name="Id_Member" class="form-control" value="{{ $member->id }}" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mb-1">
                                    <label for="Team_Suggestion" class="form-label">Team</label>
                                    <select id="Team_Suggestion" name="Team_Suggestion" class="form-control">
                                        <option value="Assembling">Assembling</option>
                                        <option value="Painting">Painting</option>
                                        <option value="DST">DST</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mb-1">
                                    <label for="Theme_Suggestion" class="form-label">Tema Perbaikan</label>
                                    <select id="Theme_Suggestion" name="Theme_Suggestion" class="form-control">
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
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mb-1">
                                    <label for="Content_Suggestion" class="form-label">Permasalahan Yang Dialami</label>
                                    <textarea id="Content_Suggestion" name="Content_Suggestion" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                Cancel
                            </button>
                            <button type="submit" class="btn btn-primary">Submit</button>
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
                            <th class="text-primary">No</th>
                            <th class="text-primary">Member</th>
                            <th class="text-primary">Team</th>
                            <th class="text-primary">Tema</th>
                            <th class="text-primary">Tanggal Penyerahan Awal</th>
                            <th class="text-primary">Tanggal Penyerahan Akhir</th>
                            <th class="text-primary">Status</th>
                            <th class="text-primary">Permasalahan</th>
                            <th class="text-primary">Foto Permasalahan</th>
                            <th class="text-primary">Perbaikan</th>
                            <th class="text-primary">Foto Perbaikan</th>
                            <th class="text-primary">Skor A</th>
                            <th class="text-primary">Skor B</th>
                            <th class="text-primary">Komentar</th>
                            <th class="text-primary">Leader</th>
                            <th class="text-primary">No Penerimaan Awal</th>
                            <th class="text-primary">No Penerimaan Akhir</th>
                            <th class="text-primary">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('style')
<link href="{{asset('assets/css/datatables.min.css')}}" rel="stylesheet">
<link href="{{asset('assets/css/fixedColumns.dataTables.min.css')}}" rel="stylesheet">
@endsection

@section('script')
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{asset('assets/js/datatables.min.js')}}"></script>
<script src="{{asset('assets/js/dataTables.fixedColumns.min.js')}}"></script>

<script>
$(document).ready(function () {
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
        order: [[1, 'asc']],
        ajax: {
            url: '{{ route("suggestions.data") }}',
            data: function (d) {
                d.Id_Member = '{{ session('Id_Member') }}'; // inject dari Blade
            },
            type: 'GET',
            error: function (xhr, error, code) {
                console.warn("DataTables AJAX Error:", error, code);
            }
        },
        scrollX: true,
        scrollY: "500px",
        scrollCollapse: true,
        fixedColumns: {
            leftColumns: 1,
            rightColumns: 1
        },
        orderCellsTop: true,
        fixedHeader: false,
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'member_nama', name: 'employees.nama' },
            { data: 'Team_Suggestion', name: 'Team_Suggestion' },
            { data: 'Theme_Suggestion', name: 'Theme_Suggestion' },
            { data: 'Date_First_Suggestion', name: 'Date_First_Suggestion' },
            { data: 'Date_Last_Suggestion', name: 'Date_Last_Suggestion' },
            { data: 'Status_Suggestion', name: 'Status_Suggestion' },
            { 
                data: 'Content_Suggestion', 
                name: 'Content_Suggestion',
                render: function(data, type, row) {
                    if (!data) return '';
                    return data.length > 20 ? data.substr(0, 20) + '...' : data;
                }
            },
            {
                data: 'Content_Photos_Suggestion',
                name: 'Content_Photos_Suggestion',
                render: function (data, type, row) {
                    if (data) {
                        try {
                            // Decode dari HTML entities ke string biasa
                            let decoded = data.replace(/&quot;/g, '"');
                            let files = JSON.parse(decoded);

                            return files.map(file =>
                                '<img src="/iseki_saran/public/uploads/contents/' + file + '" ' +
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
                render: function (data, type, row) {
                    if (data) {
                        try {
                            // Decode dari HTML entities ke string biasa
                            let decoded = data.replace(/&quot;/g, '"');
                            let files = JSON.parse(decoded);

                            return files.map(file =>
                                '<img src="/iseki_saran/public/uploads/improvements/' + file + '" ' +
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
            { data: 'Score_A_Suggestion', name: 'Score_A_Suggestion' },
            { data: 'Score_B_Suggestion', name: 'Score_B_Suggestion' },
            { 
                data: 'Comment_Suggestion', 
                name: 'Comment_Suggestion',
                render: function(data, type, row) {
                    if (!data) return '';
                    return data.length > 20 ? data.substr(0, 20) + '...' : data;
                }
            },
            { data: 'user_name', name: 'users.Name_User' },
            { data: 'Acceptance_First_Suggestion', name: 'Acceptance_First_Suggestion' },
            { data: 'Acceptance_Last_Suggestion', name: 'Acceptance_Last_Suggestion' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        initComplete: function () {
            var api = this.api();

            api.columns().eq(0).each(function (colIdx) {
                var cell = $('.filters th').eq($(api.column(colIdx).header()).index());
                var title = $(cell).text();

                if (title !== "No" && title !== "Action" && title !== "Foto Permasalahan" && title !== "Foto Perbaikan") {
                    if (title === "Tanggal Penyerahan Awal" || title === "Tanggal Penyerahan Akhir") {
                        // Input khusus date
                        $(cell).html(
                            '<input type="date" class="form-control form-control-sm" ' +
                            'style="width:100%; padding:2px 4px; font-size:12px;"/>'
                        );
                    } else if (title === "Status") {
                        // Select khusus status
                        $(cell).html(
                            '<select class="form-control form-control-sm" style="width:100%; font-size:12px;">' +
                            '<option value="">Semua</option>' +
                            '<option value="1">Sudah Selesai</option>' +
                            '<option value="0">Belum Selesai</option>' +
                            '</select>'
                        );
                    } else if (title === "Team") {
                        // Select khusus status
                        $(cell).html(
                            '<select class="form-control form-control-sm" style="width:100%; font-size:12px;">' +
                            '<option value="">Semua</option>' +
                            '<option value="Assembling">Assembling</option>' +
                            '<option value="Painting">Painting</option>' +
                            '<option value="DST">DST</option>' +
                            '</select>'
                        );
                    } else if (title === "Tema") {
                        // Select khusus status
                        $(cell).html(
                            '<select class="form-control form-control-sm" style="width:100%; font-size:12px;">' +
                            '<option value="">Semua</option>' +
                            '<option value="Keselamatan">Keselamatan</option>' +
                            '<option value="Kualitas">Kualitas</option>' +
                            '<option value="Cost">Cost</option>' +
                            '<option value="Waktu">Waktu</option>' +
                            '<option value="Lingkungan">Lingkungan</option>' +
                            '<option value="Moral">Moral</option>' +
                            '<option value="Fasilitas">Fasilitas</option>' +
                            '<option value="Mould Jig">Mould Jig</option>' +
                            '<option value="Set Up">Set Up</option>' +
                            '<option value="Material">Material</option>' +
                            '<option value="Metode">Metode</option>' +
                            '<option value="Informasi">Informasi</option>' +
                            '</select>'
                        );
                    } else if (title === "Skor A") {
                        // Select khusus status
                        $(cell).html(
                            '<select class="form-control form-control-sm" style="width:100%; font-size:12px;">' +
                            '<option value="">Semua</option>' +
                            '<option value="1">1 = Rp 600 rb/tahun</option>' +
                            '<option value="2">2 = Rp 1200 rb/tahun</option>' +
                            '<option value="3">3 = Rp 3600 rb/tahun</option>' +
                            '<option value="4">4 = Rp 9000 rb/tahun</option>' +
                            '<option value="5">5 = Rp 15000 rb/tahun</option>' +
                            '<option value="6">6 = Rp 21000 rb/tahun</option>' +
                            '<option value="7">7 = Rp 30000 rb/tahun</option>' +
                            '<option value="8">8 = Rp 39000 rb/tahun</option>' +
                            '<option value="9">9 = Rp 48000 rb/tahun</option>' +
                            '<option value="10">10 = Rp 60000 rb/tahun</option>' +
                            '<option value="11">11 = Rp 72000 rb/tahun</option>' +
                            '<option value="12">12 = Rp 84000 rb/tahun</option>' +
                            '<option value="13">13 = Rp 96000 rb/tahun</option>' +
                            '<option value="14">14 = Rp 105000 rb/tahun</option>' +
                            '<option value="15">15 = Rp 129000 rb/tahun</option>' +
                            '</select>'
                        );
                    } else {
                        // Input text biasa
                        $(cell).html(
                            '<input type="text" placeholder="Search ' + title + '" ' +
                            'class="form-control form-control-sm" style="width:100%; padding:2px 4px; font-size:12px;"/>'
                        );
                    }
                } else {
                    $(cell).html('');
                }

                // Event search
                $('input, select', cell).off().on('keyup change clear', function () {
                    if (api.column(colIdx).search() !== this.value) {
                        api.column(colIdx).search(this.value).draw();
                    }
                });
            });
        }
    });

    // Delete event
    $('#suggestionsTable').on('click', '.delete-btn', function () {
        var id = $(this).data('id');
        var content = $(this).data('name');

        if (confirm("Hapus saran: " + content + "?")) {
            $.ajax({
                url: '{{ url("suggestion/delete") }}/' + id,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(res) {
                    if(res.success){
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
