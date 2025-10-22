@extends('layouts.leader')

@section('content')
    <div class="col-sm-12">
        <div class="card table-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="text-primary">Detail Saran</h4>
                <a href="{{ route('leader.suggestion.export', $suggestion->Id_Suggestion) }}" class="btn btn-success btn-sm">
                    <i class="material-icons-two-tone text-white" style="font-size:16px;">download</i> Export Excel
                </a>
            </div>

            <div class="card-body p-3">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm align-middle">
                        <tbody>
                            {{-- Member --}}
                            <tr>
                                <td>
                                    Member:
                                    <span class="text-primary fw-bold">
                                        {{ $suggestion->member->nama }} ({{ $suggestion->member->nik }})
                                    </span>
                                </td>
                                <td>Team: <span class="text-primary fw-bold"> {{ $suggestion->Team_Suggestion }} </span>
                                </td>
                            </tr>

                            {{-- Tema & Status --}}
                            <tr>
                                <td>Tema: <span class="text-primary fw-bold"> {{ $suggestion->Theme_Suggestion }}</span>
                                </td>
                                {{-- <td>
                                    Status:
                                    <select class="form-select form-select-sm w-auto d-inline-block"
                                        data-field="Status_Suggestion">
                                        <option value="0" {{ $suggestion->Status_Suggestion == 0 ? 'selected' : '' }}>
                                            Belum Selesai</option>
                                        <option value="1" {{ $suggestion->Status_Suggestion == 1 ? 'selected' : '' }}>
                                            Sudah Selesai</option>
                                    </select>
                                </td> --}}
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        Status:
                                        <label class="form-check-label mb-0">
                                            <input type="radio" name="Status_Suggestion" value="0" data-field="Status_Suggestion"
                                                class="form-check-input me-1"
                                                {{ $suggestion->Status_Suggestion == 0 ? 'checked' : '' }}>
                                            Belum Selesai
                                        </label>

                                        <label class="form-check-label mb-0">
                                            <input type="radio" name="Status_Suggestion" value="1" data-field="Status_Suggestion"
                                                class="form-check-input me-1"
                                                {{ $suggestion->Status_Suggestion == 1 ? 'checked' : '' }}>
                                            Sudah Selesai
                                        </label>
                                    </div>
                                </td>
                            </tr>

                            {{-- Tanggal --}}
                            <tr>
                                <td>Tanggal Penyerahan Awal:
                                    <span class="text-primary fw-bold"> {{ $suggestion->Date_First_Suggestion }} </span>
                                </td>
                                <td id="value-Acceptance_First_Suggestion">
                                    No Penerimaan Awal:
                                    @if ($suggestion->Acceptance_First_Suggestion)
                                        <span class="text-primary fw-bold"> {{ $suggestion->Acceptance_First_Suggestion }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td id="value-Date_Last_Suggestion">Tanggal Penyerahan Akhir:
                                    <span class="text-primary fw-bold"> {{ $suggestion->Date_Last_Suggestion }} </span>
                                </td>
                                <td id="value-Acceptance_Last_Suggestion">No Penerimaan Akhir:
                                    <span class="text-primary fw-bold">
                                        {{ $suggestion->acceptance_last_suggestion_formatted }} </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>

            {{-- DETAIL --}}
            <div class="card-body p-3">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm align-middle">
                        <tbody>
                            {{-- Permasalahan --}}
                            <tr>
                                <th class="col-2">Permasalahan</th>
                                <td>{{ $suggestion->Content_Suggestion }}</td>
                            </tr>

                            {{-- Foto Permasalahan --}}
                            <tr>
                                <th>Foto Permasalahan</th>
                                <td>
                                    <div class="d-flex gap-2 flex-wrap">
                                        @foreach ($contentPhotos as $photo)
                                            <img src="{{ asset('uploads/contents/' . $photo) }}"
                                                alt="Foto {{ $loop->index + 1 }}"
                                                class="img-thumbnail mb-1 preview-photo"
                                                data-index="{{ $loop->index + 1}}"
									            style="max-height: 150px;">
                                        @endforeach
                                    </div>
                                </td>
                            </tr>

                            {{-- Perbaikan --}}
                            <tr>
                                <th>Perbaikan</th>
                                <td>{{ $suggestion->Improvement_Suggestion }}</td>
                            </tr>

                            {{-- Foto Perbaikan --}}
                            <tr>
                                <th>Foto Perbaikan</th>
                                <td>
                                    <div class="d-flex gap-2 flex-wrap">
                                        @foreach ($improvementPhotos as $photo)
                                            <img src="{{ asset('uploads/improvements/' . $photo) }}"
                                                alt="Foto {{ $loop->index + 1 }}"
                                                class="img-thumbnail mb-1 preview-photo"
                                                data-index="{{ $loop->index + 1}}"
									            style="max-height: 150px;">
                                        @endforeach
                                    </div>
                                </td>
                            </tr>

                            {{-- Skor A --}}
                            <tr>
                                <th>Skor A</th>
                                <td>
                                    <div class="row">
                                        @php
                                            $labels = [
                                                1 => '600 rb/tahun',
                                                2 => '1200 rb/tahun',
                                                3 => '3600 rb/tahun',
                                                4 => '9000 rb/tahun',
                                                5 => '15000 rb/tahun',
                                                6 => '21000 rb/tahun',
                                                7 => '30000 rb/tahun',
                                                8 => '39000 rb/tahun',
                                                9 => '48000 rb/tahun',
                                                10 => '60000 rb/tahun',
                                                11 => '72000 rb/tahun',
                                                12 => '84000 rb/tahun',
                                                13 => '96000 rb/tahun',
                                                14 => '105000 rb/tahun',
                                                15 => '129000 rb/tahun',
                                            ];

                                            $chunks = array_chunk($labels, 5, true);
                                        @endphp

                                        @foreach ($chunks as $chunk)
                                            <div class="col">
                                                @foreach ($chunk as $i => $label)
                                                    <label class="d-block">
                                                        <input type="radio" name="Score_A_Suggestion" id="score_a_{{ $i }}"
                                                            value="{{ $i }}" data-field="Score_A_Suggestion"
                                                            {{ $suggestion->Score_A_Suggestion == $i ? 'checked' : '' }}>
                                                        {{ $i }} = Rp {{ $label }}
                                                    </label>
                                                @endforeach
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>

                            {{-- Skor B --}}
                            <tr>
                                <th>Skor B</th>
                                <td>
                                    <table class="table table-sm mb-0">
                                        <tr>
                                            <td>Kreatifitas</td>
                                            <td>
                                                @for ($i = 0; $i <= 5; $i++)
                                                    <label class="me-2">
                                                        <input type="radio" name="kreatifitas"
                                                            value="{{ $i }}" data-field="Score_B_Suggestion"
                                                            {{ optional($suggestion->score_b_formatted)['Kreatifitas'] == $i ? 'checked' : '' }}>
                                                        {{ $i }}
                                                    </label>
                                                @endfor
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Ide</td>
                                            <td>
                                                @for ($i = 0; $i <= 5; $i++)
                                                    <label class="me-2">
                                                        <input type="radio" name="ide" value="{{ $i }}"
                                                            data-field="Score_B_Suggestion"
                                                            {{ optional($suggestion->score_b_formatted)['Ide'] == $i ? 'checked' : '' }}>
                                                        {{ $i }}
                                                    </label>
                                                @endfor
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Usaha</td>
                                            <td>
                                                @for ($i = 0; $i <= 5; $i++)
                                                    <label class="me-2">
                                                        <input type="radio" name="usaha" value="{{ $i }}"
                                                            data-field="Score_B_Suggestion"
                                                            {{ optional($suggestion->score_b_formatted)['Usaha'] == $i ? 'checked' : '' }}>
                                                        {{ $i }}
                                                    </label>
                                                @endfor
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Total</td>
                                            <td>
                                                <strong>{{ $suggestion->score_b_formatted['Total'] ?? '-' }}</strong>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <tr>
                                <th class="col-2">Total Skor</th>
                                <td id="totalAkhir"><strong>{{ $suggestion->total_score ?? '-' }}</strong></td>

                            </tr>

                            {{-- Leader --}}
                            <tr>
                                <th class="col-2">Leader</th>
                                <td id="value-Id_User">{{ $suggestion->user->Name_User ?? '-' }}</td>
                            </tr>
                            {{-- Komentar --}}
                            <tr>
                                <th>Komentar</th>
                                <td>
                                    <div class="d-flex flex-column gap-2">
                                        <div class="row">
                                            <div class="col-md-3">
                                                @php
                                                    $options = [
                                                        'akan dijadwalkan perbaikannya',
                                                        'sangat memudahkan pekerjaan',
                                                        'meningkatkan keselamatan',
                                                        'meningkatkan kualitas',
                                                        'lingkungan kerja tambah nyaman',
                                                        'saran anda ditolak',
                                                    ];
                                                @endphp

                                                @foreach ($options as $opt)
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="comment_option"
                                                            value="{{ $opt }}" data-field="Comment_Suggestion"
                                                            {{ $suggestion->Comment_Suggestion == $opt ? 'checked' : '' }}>
                                                        <label class="form-check-label">{{ ucfirst($opt) }}</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div class="col-md-9">
                                                <div class="form-check d-flex align-items-center">
                                                    <input class="form-check-input me-2" type="radio" name="comment_option"
                                                        value="custom"
                                                        {{ $suggestion->Comment_Suggestion && !in_array($suggestion->Comment_Suggestion, $options) ? 'checked' : '' }}
                                                        data-field="Comment_Suggestion">
                                                    <label class="form-check-label me-2">Lainnya:</label>
                                                    <textarea class="form-control form-control-sm mt-1"
                                                        name="comment_custom" placeholder="Tulis komentar..."
                                                        rows="3">{{ $suggestion->Comment_Suggestion && !in_array($suggestion->Comment_Suggestion, $options) ? $suggestion->Comment_Suggestion : '' }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="text-end mt-3">
                    <a href="{{ route('leader.suggestion') }}" class="btn btn-primary">Kembali</a>
                    <button id="btnSaveAll" class="btn btn-primary"> Simpan </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Preview Foto -->
    <div class="modal fade" id="photoPreviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <button type="button" class="btn-close btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="preview-container position-relative d-inline-block">
                        <img id="previewImage" src="" class="preview-img" />
                    </div>
                </div>
                <div class="modal-footer justify-content-center border-0">
                    <button class="btn btn-outline-primary btn-sm" id="zoomInBtn"><i class="material-icons-two-tone">zoom_in</i></button>
                    <button class="btn btn-outline-primary btn-sm" id="zoomOutBtn"><i class="material-icons-two-tone">zoom_out</i></button>
                    <button class="btn btn-outline-primary btn-sm" id="zoomResetBtn"><i class="material-icons-two-tone">refresh</i></button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('style')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        #photoPreviewModal .modal-body {
            background-color: #000;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #photoPreviewModal img {
            cursor: grab;
        }

        .preview-container {
            overflow: auto; /* agar bisa scroll jika di-zoom */
            max-height: 80vh;
            max-width: 100%;
        }

        .preview-img {
            display: block;
            max-width: 100%;
            max-height: 80vh;
            margin: 0 auto;
            transform-origin: center center;
            transition: transform 0.2s ease;
            cursor: grab;
        }
    </style>
@endsection


@section('script')
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>

    <script>
        $(function() {
            const updateUrl = "{{ route('leader.suggestion.updateField', $suggestion->Id_Suggestion) }}";
            const csrf = $('meta[name="csrf-token"]').attr('content');

            // ===== Fungsi AJAX Global =====
            function updateField(field, value) {
                return $.ajax({
                    url: updateUrl,
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        _token: csrf,
                        field: field,
                        value: value
                    })
                });
            }

            // ===== SAVE ALL =====
            $('#btnSaveAll').on('click', function() {
                const fields = {
                    Status_Suggestion: $('[name="Status_Suggestion"]:checked').val(),
                    Score_A_Suggestion: $('[name="Score_A_Suggestion"]:checked').val(),
                    Score_B_Suggestion: {
                        kreatifitas: $('[name="kreatifitas"]:checked').val(),
                        ide: $('[name="ide"]:checked').val(),
                        usaha: $('[name="usaha"]:checked').val()
                    },
                    Comment_Suggestion: $('[name="comment_option"]:checked').val() === 'custom' ?
                        $('[name="comment_custom"]').val() :
                        $('[name="comment_option"]:checked').val(),
                    Acceptance_First_Suggestion: true,
                    Id_User: '{{ $user->Id_User }}' 
                };

                const requests = Object.entries(fields).map(([field, value]) => updateField(field, value));

                Promise.all(requests)
                    .then(responses => {
                        const allSuccess = responses.every(r => r.success);
                        if (allSuccess) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Data berhasil disimpan!',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => location.reload());
                        } else {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Sebagian data gagal diperbarui!',
                                text: 'Periksa koneksi atau data yang tidak valid.'
                            });
                        }
                    })
                    .catch(() => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal menyimpan data!',
                            text: 'Terjadi kesalahan server atau jaringan.'
                        });
                    });
            });

        });
    </script>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const modal = new bootstrap.Modal(document.getElementById('photoPreviewModal'));
        const previewImage = document.getElementById('previewImage');
        const zoomInBtn = document.getElementById('zoomInBtn');
        const zoomOutBtn = document.getElementById('zoomOutBtn');
        const zoomResetBtn = document.getElementById('zoomResetBtn');
        let currentZoom = 1;

        // Klik gambar → buka modal
        document.querySelectorAll('.preview-photo').forEach(img => {
            img.addEventListener('click', e => {
                const src = e.target.src;
                previewImage.src = src;
                currentZoom = 1;
                previewImage.style.transform = `scale(${currentZoom})`;
                modal.show();
            });
        });

        // Zoom in
        zoomInBtn.addEventListener('click', () => {
            currentZoom = Math.min(currentZoom + 0.2, 5);
            previewImage.style.transform = `scale(${currentZoom})`;
        });

        // Zoom out
        zoomOutBtn.addEventListener('click', () => {
            currentZoom = Math.max(currentZoom - 0.2, 0.2);
            previewImage.style.transform = `scale(${currentZoom})`;
        });

        // Reset zoom (fit to modal)
        zoomResetBtn.addEventListener('click', () => {
            currentZoom = 1;
            previewImage.style.transform = `scale(${currentZoom})`;
            // Scroll ke tengah
            const container = document.querySelector('.preview-container');
            container.scrollTo({ top: 0, left: 0, behavior: 'smooth' });
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        let lastCheckedRadio = null;

        const scoreARadios = document.querySelectorAll('input[name="Score_A_Suggestion"]');
        scoreARadios.forEach(radio => {
            radio.addEventListener('click', function(e) {
                if (this === lastCheckedRadio) {
                    this.checked = false;
                    lastCheckedRadio = null;
                    updateField(this.dataset.field, null);
                } else {
                    lastCheckedRadio = this;
                    updateField(this.dataset.field, this.value);
                }
            });
        });
    });
    </script>
@endsection
