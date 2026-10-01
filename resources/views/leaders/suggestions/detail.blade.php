@extends('layouts.leader')

@section('content')
    <div class="col-sm-12">

        {{-- Panel Navigasi Prev/Next (hanya muncul jika datang dari halaman Belum Dinilai) --}}
        @if($source === 'not-sign')
        @php $navSuffix = '?source=not-sign' . ($month ? '&month=' . urlencode($month) : ''); @endphp
        <div class="d-flex justify-content-between align-items-center mb-2 px-1">
            <div>
                @if($prevId)
                    <a href="{{ route('leader.suggestion.show', $prevId) . $navSuffix }}" class="btn btn-outline-secondary btn-sm btn-nav-prev">
                        <i class="material-icons-two-tone" style="font-size:16px;vertical-align:middle;">arrow_back</i> Saran Sebelumnya
                    </a>
                @else
                    <button class="btn btn-outline-secondary btn-sm" disabled>
                        <i class="material-icons-two-tone" style="font-size:16px;vertical-align:middle;">arrow_back</i> Saran Sebelumnya
                    </button>
                @endif
            </div>
            <a href="{{ route('leader.suggestion.notSign') . ($month ? '?month=' . urlencode($month) : '') }}" class="btn btn-sm btn-light border btn-nav-list">
                <i class="material-icons-two-tone" style="font-size:16px;vertical-align:middle;">list</i> Kembali ke Daftar
            </a>
            <div>
                @if($nextId)
                    <a href="{{ route('leader.suggestion.show', $nextId) . $navSuffix }}" class="btn btn-outline-secondary btn-sm btn-nav-next">
                        Saran Selanjutnya <i class="material-icons-two-tone" style="font-size:16px;vertical-align:middle;">arrow_forward</i>
                    </a>
                @else
                    <button class="btn btn-outline-secondary btn-sm" disabled>
                        Saran Selanjutnya <i class="material-icons-two-tone" style="font-size:16px;vertical-align:middle;">arrow_forward</i>
                    </button>
                @endif
            </div>
        </div>
        @endif

        <div class="card table-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="text-primary mb-0">Detail Saran</h4>
                <div class="d-flex gap-2">
                    @php
                        $bulanPdf = $suggestion->Date_First_Suggestion ? date('Y-m', strtotime($suggestion->Date_First_Suggestion)) : null;
                        $accPdf = $suggestion->Acceptance_First_Suggestion ? str_pad($suggestion->Acceptance_First_Suggestion, 5, '0', STR_PAD_LEFT) : null;
                        $pdfRelPath = ($bulanPdf && $accPdf) ? "uploads/pdf/{$bulanPdf}/Saran_Perbaikan_{$bulanPdf}_{$accPdf}.pdf" : null;
                        $hasPdf = $pdfRelPath && file_exists(public_path($pdfRelPath));
                    @endphp
                    @if($hasPdf)
                        <a href="{{ asset($pdfRelPath) }}" target="_blank" class="btn btn-danger btn-sm">
                            <i class="material-icons-two-tone text-white" style="font-size:16px;">picture_as_pdf</i> Cetak PDF
                        </a>
                    @endif
                    <a href="{{ route('leader.suggestion.export', $suggestion->Id_Suggestion) }}" class="btn btn-success btn-sm">
                        <i class="material-icons-two-tone text-white" style="font-size:16px;">download</i> Export Excel
                    </a>
                </div>
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
                                        {{ optional($suggestion->member)->nama ?? '(Data tidak ditemukan)' }}
                                        ({{ optional($suggestion->member)->nik ?? '-' }})
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

                            {{-- Jam Perbaikan --}}
                            <tr>
                                <th>Jam Perbaikan</th>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="number" name="Hour_Suggestion" 
                                            class="form-control form-control-sm" 
                                            value="{{ $suggestion->Hour_Suggestion !== null && $suggestion->Hour_Suggestion !== '' ? (float)$suggestion->Hour_Suggestion : '' }}" 
                                            placeholder="Masukkan jam" 
                                            min="0" step="any"
                                            style="max-width: 120px;">
                                        <span class="text-muted">jam</span>
                                    </div>
                                </td>
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
                                                        'saran anda lumayan',
                                                        'Saran anda bagus. -> Dear Leader tolong di jadwalkan pengerjaanya',
                                                        'Terkait Hal ini, anda punya saran kek apa?',
                                                        'Akan baik untuk keselamatan',
                                                        'Akan baik untuk kualitas',
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
                                                        style="font-size: 20px;"
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

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <a href="{{ $source === 'not-sign' ? route('leader.suggestion.notSign') : route('leader.suggestion') }}" class="btn btn-secondary">
                        <i class="material-icons-two-tone text-white" style="font-size:16px;vertical-align:middle;">arrow_back</i> Kembali
                    </a>
                    <div class="d-flex gap-2">
                        <button id="btnSaveAll" class="btn btn-primary" data-action="save">
                            <i class="material-icons-two-tone text-white" style="font-size:16px;vertical-align:middle;">save</i> Simpan
                        </button>
                        @if($source === 'not-sign' && $nextId)
                        <button id="btnSaveAndNext" class="btn btn-success" data-action="save_and_next" data-next-id="{{ $nextId }}">
                            Simpan & Lanjut <i class="material-icons-two-tone text-white" style="font-size:16px;vertical-align:middle;">arrow_forward</i>
                        </button>
                        @elseif($source === 'not-sign' && !$nextId)
                        <button id="btnSaveAndFinish" class="btn btn-success" data-action="save_and_finish">
                            Simpan & Selesai <i class="material-icons-two-tone text-white" style="font-size:16px;vertical-align:middle;">check_circle</i>
                        </button>
                        @endif
                    </div>
                </div>
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
            const csrf = $('meta[name="csrf-token"]').attr('content');

            // ===== FUNGSI COLLECT DATA FORM =====
            function collectFields() {
                const fields = {
                    Status_Suggestion: $('[name="Status_Suggestion"]:checked').val(),
                    Score_A_Suggestion: $('[name="Score_A_Suggestion"]:checked').val(),
                    Score_B_Suggestion: {
                        kreatifitas: $('[name="kreatifitas"]:checked').val(),
                        ide: $('[name="ide"]:checked').val(),
                        usaha: $('[name="usaha"]:checked').val()
                    },
                    Hour_Suggestion: $('[name="Hour_Suggestion"]').val(),
                    Comment_Suggestion: $('[name="comment_option"]:checked').val() === 'custom'
                        ? $('[name="comment_custom"]').val()
                        : $('[name="comment_option"]:checked').val(),
                    Acceptance_First_Suggestion: true,
                    Id_User: '{{ $user->Id_User }}'
                };

                const totalSkorB = (parseInt(fields.Score_B_Suggestion.kreatifitas) || 0) +
                                   (parseInt(fields.Score_B_Suggestion.ide) || 0) +
                                   (parseInt(fields.Score_B_Suggestion.usaha) || 0);

                if ((fields.Score_A_Suggestion && parseInt(fields.Score_A_Suggestion) > 0) || totalSkorB > 0) {
                    fields.Status_Suggestion = '1';
                }

                return fields;
            }

            const saveUrl = "{{ route('leader.suggestion.saveAll', $suggestion->Id_Suggestion) }}";

            // ===== AUTO-SAVE RINGAN (TANPA PDF, TANPA LOADING) =====
            // generatePdf: hanya dikirim saat tombol Simpan / Simpan & Lanjut / Simpan & Selesai
            // ditekan, supaya PDF dibuat ulang secara sinkron (render Mpdf di dalam request).
            function triggerAutoSave(callback, generatePdf) {
                const payload = { _token: csrf, ...collectFields(), generate_pdf: generatePdf === true };
                $.ajax({
                    url: saveUrl,
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(payload),
                    success: function(res) {
                        if (typeof callback === 'function') callback(res);
                    },
                    error: function(err) {
                        console.warn('Auto-save error:', err);
                        if (typeof callback === 'function') callback(null);
                    }
                });
            }

            // ===== SIMPAN + GENERATE PDF (loading indicator) =====
            function saveWithPdf(callback) {
                Swal.fire({
                    title: 'Menyimpan & Generate PDF...',
                    html: 'Mohon tunggu sebentar, proses pembuatan PDF sedang berjalan.',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => Swal.showLoading()
                });
                triggerAutoSave(function(res) {
                    Swal.close();
                    if (typeof callback === 'function') callback(res);
                }, true);
            }

            // Bind auto-save ke semua input form ketika berubah
            $(document).on('change', '[name="Status_Suggestion"], [name="Score_A_Suggestion"], [name="kreatifitas"], [name="ide"], [name="usaha"], [name="comment_option"], [name="Hour_Suggestion"]', function() {
                triggerAutoSave();
            });
            $(document).on('blur', '[name="comment_custom"], [name="Hour_Suggestion"]', function() {
                triggerAutoSave();
            });

            // Bisa uncheck radio button Score_A_Suggestion dengan klik dua kali
            let lastCheckedRadio = $('input[name="Score_A_Suggestion"]:checked')[0] || null;
            $(document).on('click', 'input[name="Score_A_Suggestion"]', function() {
                if (this === lastCheckedRadio) {
                    this.checked = false;
                    lastCheckedRadio = null;
                } else {
                    lastCheckedRadio = this;
                }
                triggerAutoSave();
            });

            // ===== NAVIGASI ATAS (PREV/NEXT/LIST) — langsung pindah tanpa auto-save =====
            // Tidak auto-save agar Id_User tidak terisi otomatis (saran tidak tertanda "sudah dinilai")
            // Biarkan link berjalan normal (tidak perlu preventDefault)

            // ===== TOMBOL BAWAH: SIMPAN (Reload halaman yang sama) =====
            $('#btnSaveAll').on('click', function() {
                saveWithPdf(function(res) {
                    const isPdfReady = res && res.pdf_ready;
                    Swal.fire({
                        icon: 'success',
                        title: isPdfReady ? 'Data berhasil disimpan & PDF siap dicetak!' : 'Data berhasil disimpan!',
                        timer: 1200,
                        showConfirmButton: false
                    }).then(() => location.reload());
                });
            });

            // ===== TOMBOL BAWAH: SIMPAN & LANJUT =====
            $('#btnSaveAndNext').on('click', function() {
                const nextId = $(this).data('next-id');
                const month  = '{{ $month }}';
                let nextUrl  = '/iseki_saran/public/leader/suggestion/' + nextId + '?source=not-sign';
                if (month) nextUrl += '&month=' + encodeURIComponent(month);
                saveWithPdf(function() {
                    window.location.href = nextUrl;
                });
            });

            // ===== TOMBOL BAWAH: SIMPAN & SELESAI =====
            $('#btnSaveAndFinish').on('click', function() {
                const month = '{{ $month }}';
                let redirectUrl = '{{ route("leader.suggestion.notSign") }}';
                if (month) redirectUrl += '?month=' + encodeURIComponent(month);
                saveWithPdf(function() {
                    window.location.href = redirectUrl;
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

    // Tambahkan event listener untuk textarea komentar
    document.querySelector('[name="comment_custom"]').addEventListener('input', function() {
        if (this.value.trim() !== '') {
            // Centang radio button "lainnya"
            document.querySelector('[name="comment_option"][value="custom"]').checked = true;
        }
    });

    // Jika radio "lainnya" dicentang, fokus ke textarea
    document.querySelector('[name="comment_option"][value="custom"]').addEventListener('change', function() {
        if (this.checked) {
            document.querySelector('[name="comment_custom"]').focus();
        }
    });

    $(function() {
        // Fungsi untuk cek dan ubah status otomatis
        function checkAndSetStatus() {
            const scoreA = $('[name="Score_A_Suggestion"]:checked').val();
            const skorB = {
                kreatifitas: $('[name="kreatifitas"]:checked').val(),
                ide: $('[name="ide"]:checked').val(),
                usaha: $('[name="usaha"]:checked').val()
            };

            const totalSkorB = (parseInt(skorB.kreatifitas) || 0) +
                            (parseInt(skorB.ide) || 0) +
                            (parseInt(skorB.usaha) || 0);

            // Jika skor A atau total B > 0, set status ke 'Sudah Selesai'
            if ((scoreA && parseInt(scoreA) > 0) || totalSkorB > 0) {
                $('[name="Status_Suggestion"][value="1"]').prop('checked', true);
            }
        }

        // Event listener untuk skor A
        $('[name="Score_A_Suggestion"]').on('change', checkAndSetStatus);

        // Event listener untuk skor B
        $('[name="kreatifitas"], [name="ide"], [name="usaha"]').on('change', checkAndSetStatus);
    });

    // (Auto-save logic moved inside jQuery ready block)
    </script>
@endsection
