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
                    <form action="#" method="POST" style="display:inline;" enctype="multipart/form-data">
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
                                    <input type="text" id="Team_Suggestion" name="Team_Suggestion" class="form-control" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mb-1">
                                    <label for="Theme_Suggestion" class="form-label">Tema Perbaikan</label>
                                    <select id="Theme_Suggestion" name="Theme_Suggestion" class="form-control">
                                        <option value="5S">Keselamatan</option>
                                        <option value="Kaizen">Kualitas</option>
                                        <option value="Safety">Cost</option>
                                        <option value="Quality">Waktu</option>
                                        <option value="Cost">Lingkungan</option>
                                        <option value="Delivery">Moral</option>
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
            { data: 'Id_Member', name: 'Id_Member' },
            { data: 'Team_Suggestion', name: 'Team_Suggestion' },
            { data: 'Theme_Suggestion', name: 'Theme_Suggestion' },
            { data: 'Date_First_Suggestion', name: 'Date_First_Suggestion' },
            { data: 'Date_Last_Suggestion', name: 'Date_Last_Suggestion' },
            { data: 'Status_Suggestion', name: 'Status_Suggestion' },
            { data: 'Content_Suggestion', name: 'Content_Suggestion' },
            { data: 'Content_Photos_Suggestion', name: 'Content_Photos_Suggestion' },
            { data: 'Improvement_Suggestion', name: 'Improvement_Suggestion' },
            { data: 'Improvement_Photos_Suggestion', name: 'Improvement_Photos_Suggestion' },
            { data: 'Score_A_Suggestion', name: 'Score_A_Suggestion' },
            { data: 'Score_B_Suggestion', name: 'Score_B_Suggestion' },
            { data: 'Comment_Suggestion', name: 'Comment_Suggestion' },
            { data: 'Id_User', name: 'Id_User' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        initComplete: function () {
            var api = this.api();

            api.columns().eq(0).each(function (colIdx) {
                var cell = $('.filters th').eq($(api.column(colIdx).header()).index());
                var title = $(cell).text();

                if (title !== "No" && title !== "Action") {
                    $(cell).html(
                        '<input type="text" placeholder="Search ' + title + '" ' +
                        'class="form-control form-control-sm" style="width:100%; padding:2px 4px; font-size:12px;"/>'
                    );
                } else {
                    $(cell).html('');
                }

                $('input', cell).off().on('keyup change clear', function () {
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
                url: '/iseki_saran/public/suggestions/' + id,
                type: 'POST',
                data: {
                    _method: 'DELETE',
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
