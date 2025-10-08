@extends('layouts.member')

@section('content')
<div class="col-sm-12">
    <div class="card table-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="text-primary">Detail Saran</h4>
        </div>

		<div class="card-body p-3">
			<div class="table-responsive">
				<table class="table table-bordered table-sm align-middle">
					<tbody>
						{{-- Member --}}
						<tr>
							<td>Member: <span class="text-primary fw-bold">{{ $suggestion->member->nama }} ({{ $suggestion->member->nik }})</span></td>
							<td>Team: <span id="value-Team_Suggestion" class="text-primary fw-bold">{{ $suggestion->Team_Suggestion }}</span> 
								<button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
									data-bs-target="#modalTeam">
									<i class="material-icons-two-tone" style="font-size:16px;">edit</i>
								</button>
							</td>
						</tr>

						{{-- Tema & Status --}}
						<tr>
							<td>Tema: <span id="value-Theme_Suggestion" class="text-primary fw-bold">{{ $suggestion->Theme_Suggestion }}</span>
								<button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
									data-bs-target="#modalTheme">
									<i class="material-icons-two-tone" style="font-size:16px;">edit</i>
								</button>
							</td>
							<td>
								Status:
								@if ($suggestion->Status_Suggestion == 1)
									<span class="badge bg-success">Sudah Selesai</span>
								@endif
								@if ($suggestion->Status_Suggestion == 0)
									<span class="badge bg-warning text-dark">Belum Selesai</span>
								@endif
							</td>
						</tr>

						{{-- Tanggal --}}
						<tr>
							<td>Tanggal Penyerahan Awal: <span class="text-primary fw-bold">{{ $suggestion->Date_First_Suggestion }}</span></td>
							<td id="value-Acceptance_First_Suggestion">
								No Penerimaan Awal:
								<span class="text-primary fw-bold">{{ $suggestion->acceptance_first_suggestion_formatted }}</span>
							</td>
						</tr>
						<tr>
							<td id="value-Date_Last_Suggestion">Tanggal Penyerahan Akhir:
								<span class="text-primary fw-bold">{{ $suggestion->Date_Last_Suggestion }}</span>
							</td>
							<td id="value-Acceptance_Last_Suggestion">No Penerimaan Akhir:
								<span class="text-primary fw-bold">{{ $suggestion->acceptance_last_suggestion_formatted }}</span>
							</td>
						</tr>
					</tbody>
				</table>
			</div>

		</div>

		<div class="card-body p-3">
			<div class="table-responsive">
				<table class="table table-bordered table-sm align-middle">
					<tbody>
						{{-- Permasalahan --}}
						<tr>
							<th class="col-2">Permasalahan</th>
							<td id="value-Content_Suggestion">
								{{ $suggestion->Content_Suggestion }}
								<button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
									data-bs-target="#modalContent">
									<i class="material-icons-two-tone" style="font-size:16px;">edit</i>
								</button>
							</td>
						</tr>

						{{-- Foto Permasalahan --}}
						<tr>
							<th class="col-2">Foto Permasalahan</th>
							<td>
								@if(isset($contentPhotos[0]))
									<img src="{{ asset('uploads/contents/' . $contentPhotos[0]) }}" 
										alt="Foto 1" 
										class="img-thumbnail mb-1 preview-photo" 
										data-index="0"
										style="max-height: 150px; cursor: pointer;">
								@else
									<button class="btn btn-sm btn-outline-primary edit-photo-btn"
										data-slot="0"
										data-field="Content_Photos_Suggestion"
										data-photo="{{ isset($contentPhotos[0]) ? asset('uploads/contents/'.$contentPhotos[0]) : '' }}">
										<i class="material-icons-two-tone" style="font-size:16px;">add</i>
									</button>
								@endif

								@if(isset($contentPhotos[1]))
									<img src="{{ asset('uploads/contents/' . $contentPhotos[1]) }}" 
										alt="Foto 2" 
										class="img-thumbnail mb-1 preview-photo" 
										data-index="1"
										style="max-height: 150px; cursor: pointer;">
								@else
									<button class="btn btn-sm btn-outline-primary edit-photo-btn"
										data-slot="1"
										data-field="Content_Photos_Suggestion"
										data-photo="{{ isset($contentPhotos[1]) ? asset('uploads/contents/'.$contentPhotos[1]) : '' }}">
										<i class="material-icons-two-tone" style="font-size:16px;">add</i>
									</button>
								@endif
							</td>
						</tr>

						{{-- Perbaikan --}}
						<tr>
							<th class="col-2">Perbaikan</th>
							<td id="value-Improvement_Suggestion">
								{{ $suggestion->Improvement_Suggestion }}
								<button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
									data-bs-target="#modalImprovement">
									<i class="material-icons-two-tone" style="font-size:16px;">edit</i>
								</button>
							</td>
						</tr>

						{{-- Foto Perbaikan --}}
						<tr>
							<th class="col-2">Foto Perbaikan</th>
							<td>
								@if(isset($improvementPhotos[0]))
								<img src="{{ asset('uploads/improvements/' . $improvementPhotos[0]) }}" 
									alt="Foto 1" 
									class="img-thumbnail mb-1 preview-photo" 
									data-index="0"
									style="max-height: 150px;">
								@else
								<button class="btn btn-sm btn-outline-primary edit-photo-btn"
									data-slot="0"
									data-field="Improvement_Photos_Suggestion"
									data-photo="{{ isset($improvementPhotos[0]) ? asset('uploads/improvements/'.$improvementPhotos[0]) : '' }}">
									<i class="material-icons-two-tone" style="font-size:16px;">add</i>
								</button>
								@endif
								@if(isset($improvementPhotos[1]))
								<img src="{{ asset('uploads/improvements/' . $improvementPhotos[1]) }}" 
									alt="Foto 2" 
									class="img-thumbnail mb-1 preview-photo" 
									data-index="1"
									style="max-height: 150px;">
								@else
								<button class="btn btn-sm btn-outline-primary edit-photo-btn"
									data-slot="1"
									data-field="Improvement_Photos_Suggestion"
									data-photo="{{ isset($improvementPhotos[1]) ? asset('uploads/improvements/'.$improvementPhotos[1]) : '' }}">
									<i class="material-icons-two-tone" style="font-size:16px;">add</i>
								</button>
								@endif
							</td>
						</tr>
						
						{{-- Skor A --}}
						<tr>
							<th class="col-2">Skor A</th>
							<td id="value-Score_A_Suggestion">{{ $suggestion->score_a_formatted }}</td>
						</tr>

						{{-- Skor B --}}
						<tr>
							<th class="col-2">Skor B</th>
							<td id="value-Score_B_Suggestion">
								@if($suggestion->score_b_formatted)
									<table class="table table-bordered table-sm">
										<tbody>
											@foreach($suggestion->score_b_formatted as $label => $val)
												<tr class="mb-0">
													<td class="col-2">
														@if($label === 'Total')
															<strong>{{ $label }}</strong>
														@else
															{{ $label }}
														@endif
													</td>
													<td>
														@if($label === 'Total')
															<strong>{{ $val }}</strong>
														@else
															{{ $val }}
														@endif
													</td>
												</tr>
											@endforeach
										</tbody>
									</table>
								@endif
							</td>
						</tr>

						{{-- Leader --}}
						<tr>
							<th class="col-2">Leader</th>
							<td id="value-Id_User">{{ $suggestion->user->Name_User ?? '' }}</td>
						</tr>

						{{-- Komentar --}}
						<tr>
							<th class="col-2">Komentar</th>
							<td id="value-Comment_Suggestion">{{ $suggestion->Comment_Suggestion }}</td>
						</tr>

					</tbody>
				</table>
			</div>
		</div>
    </div>
</div>


{{-- Modal Team --}}
<div class="modal fade" id="modalTeam" tabindex="-1">
    <div class="modal-dialog">
        <form class="ajaxUpdateForm" data-field="Team_Suggestion">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white">Edit Team</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <select name="value" class="form-control">
                        <option value="Assembling" {{ $suggestion->Team_Suggestion == 'Assembling' ? 'selected' : '' }}>
                            Assembling</option>
                        <option value="Painting" {{ $suggestion->Team_Suggestion == 'Painting' ? 'selected' : '' }}>
                            Painting</option>
                        <option value="DST" {{ $suggestion->Team_Suggestion == 'DST' ? 'selected' : '' }}>DST</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Modal Theme --}}
<div class="modal fade" id="modalTheme" tabindex="-1">
    <div class="modal-dialog">
        <form class="ajaxUpdateForm" data-field="Theme_Suggestion">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white">Edit Tema</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <select name="value" class="form-control">
                        <option value="Keselamatan" {{ $suggestion->Theme_Suggestion == 'Keselamatan' ? 'selected' : '' }}>Keselamatan</option>
                        <option value="Kualitas" {{ $suggestion->Theme_Suggestion == 'Kualitas' ? 'selected' : '' }}>Kualitas</option>
                        <option value="Cost" {{ $suggestion->Theme_Suggestion == 'Cost' ? 'selected' : '' }}>Cost</option>
                        <option value="Waktu" {{ $suggestion->Theme_Suggestion == 'Waktu' ? 'selected' : '' }}>Waktu</option>
                        <option value="Lingkungan" {{ $suggestion->Theme_Suggestion == 'Lingkungan' ? 'selected' : '' }}>Lingkungan</option>
                        <option value="Moral" {{ $suggestion->Theme_Suggestion == 'Moral' ? 'selected' : '' }}>Moral</option>
						{{-- <option value="Fasilitas" {{ $suggestion->Theme_Suggestion == 'Fasilitas' ? 'selected' : '' }}>Fasilitas</option>
						<option value="Mould Jig" {{ $suggestion->Theme_Suggestion == 'Mould Jig' ? 'selected' : '' }}>Mould Jig</option>
						<option value="Set Up" {{ $suggestion->Theme_Suggestion == 'Set Up' ? 'selected' : '' }}>Set Up</option>
						<option value="Material" {{ $suggestion->Theme_Suggestion == 'Material' ? 'selected' : '' }}>Material</option>
						<option value="Metode" {{ $suggestion->Theme_Suggestion == 'Metode' ? 'selected' : '' }}>Metode</option>
						<option value="Informasi" {{ $suggestion->Theme_Suggestion == 'Informasi' ? 'selected' : '' }}>Informasi</option> --}}
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Modal Content (Textarea) --}}
<div class="modal fade" id="modalContent" tabindex="-1">
    <div class="modal-dialog">
        <form class="ajaxUpdateForm" data-field="Content_Suggestion">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white">Edit Permasalahan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <textarea class="form-control" name="value" rows="4">{{ $suggestion->Content_Suggestion }}</textarea>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Modal Improvement (Textarea) --}}
<div class="modal fade" id="modalImprovement" tabindex="-1">
    <div class="modal-dialog">
        <form class="ajaxUpdateForm" data-field="Improvement_Suggestion">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white">Edit Perbaikan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <textarea class="form-control" name="value"
                        rows="4">{{ $suggestion->Improvement_Suggestion }}</textarea>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Modal Edit Foto (TUI Editor Style) --}}
<div class="modal fade" id="tuiEditorModal" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-fullscreen">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Edit Foto</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body p-0 d-flex flex-column" style="min-height:0;">
				<!-- Toolbar -->
				<div id="custom-tui-toolbar" class="p-2 border-bottom d-flex flex-wrap justify-content-start align-items-center gap-2 bg-light">
					<div class="d-flex gap-2">
						<button type="button" class="btn btn-outline-primary" data-tool="draw"><i class="material-icons-two-tone" style="font-size:16px;">edit</i></button>
						<button type="button" class="btn btn-outline-primary" data-tool="rect"><i class="material-icons-two-tone" style="font-size:16px;">crop_square</i></button>
						<button type="button" class="btn btn-outline-primary" data-tool="arrow"><i class="material-icons-two-tone" style="font-size:16px;">arrow_right_alt</i></button>
						<button type="button" class="btn btn-outline-primary" data-tool="rotate"><i class="material-icons-two-tone" style="font-size:16px;">rotate_right</i></button>
					</div>

					<div class="vr mx-3 d-none d-md-block"></div>

					<div class="d-flex gap-2">
						<button type="button" class="btn btn-outline-secondary" data-tool="undo"><i class="material-icons-two-tone" style="font-size:16px;">undo</i></button>
						<button type="button" class="btn btn-outline-secondary" data-tool="redo"><i class="material-icons-two-tone" style="font-size:16px;">redo</i></button>
						<button type="button" class="btn btn-outline-danger" data-tool="delete"><i class="material-icons-two-tone" style="font-size:16px;">delete</i></button>
					</div>

					<div class="ms-auto d-flex gap-2">
						<button type="button" class="btn btn-success" id="tui-save-btn">Simpan</button>
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
					</div>
				</div>

				<div id="tui-editor-container" class="d-flex justify-content-center align-items-center bg-dark-subtle" style="flex:1; overflow:hidden;">
					<div id="tui-image-editor" style="width:96%; height:96%;"></div>
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

<link rel="stylesheet" href="{{ asset('assets/css/tui-color-picker.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/tui-image-editor.min.css') }}">

<style>
    /* make editor area fill the modal body neatly */
	#tui-editor-container {
	height: calc(100vh - 120px); /* adjust if header/footer different */
	}

	/* toolbar button active state */
	#custom-tui-toolbar .active {
	box-shadow: inset 0 0 0 2px rgba(0,123,255,0.25);
	}

	.tie-btn-history {
		display: none !important;
	}

	.tie-btn-reset {
		display: none !important;
	}

	.tie-btn-deleteAll {
		display: none !important;
	}

	.tie-color-fill {
		display: none !important;
	}

	.triangle, .circle {
		display: none !important;
	}

	.tie-icon-add-button {
		display: none !important;
	}


	.tui-image-editor-partition {
		display: none !important;
	}

	.tui-image-editor-button[data-icontype="icon-arrow"],
	.tui-image-editor-button[data-icontype="icon-arrow-3"],
	.tui-image-editor-button[data-icontype="icon-star"],
	.tui-image-editor-button[data-icontype="icon-star-2"],
	.tui-image-editor-button[data-icontype="icon-polygon"],
	.tui-image-editor-button[data-icontype="icon-location"],
	.tui-image-editor-button[data-icontype="icon-heart"],
	.tui-image-editor-button[data-icontype="icon-bubble"] {
		display: none !important;
	}

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
<script src="{{ asset('assets/js/tui-code-snippet.min.js') }}"></script>
<script src="{{ asset('assets/js/fabric.min.js') }}"></script>
<script src="{{ asset('assets/js/tui-color-picker.min.js') }}"></script>
<script src="{{ asset('assets/js/tui-image-editor.min.js') }}"></script>

<script>
	$(function () {
        // Handle form update (text fields)
        $('.ajaxUpdateForm').on('submit', function (e) {
            e.preventDefault();
            let form = $(this);
            let field = form.data('field');
            let value = form.find('[name="value"]').val();

            $.ajax({
                url: "{{ route('suggestion.updateField', $suggestion->Id_Suggestion) }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    field: field,
                    value: value
                },
                success: function (res) {
                    if (res.success) {
                        $('#value-' + field).text(value);
                        form.closest('.modal').modal('hide');
                    } else {
                        alert(res.message);
                    }
                },
                error: function () {
                    alert("Gagal update data.");
                }
            });
        });
    });

/* ---------- Variabel global ---------- */
let imageEditor = null;
let currentEditIndex = null;
let currentField = null;
const suggestionUpdateUrl = "{{ route('suggestion.updateField', $suggestion->Id_Suggestion) }}";
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
const modalEl = document.getElementById('tuiEditorModal');

/* ---------- Utility: resize + blob->dataUrl ---------- */
async function resizeImage(file, maxWidth = 1280, maxHeight = 1280, quality = 0.8) {
  return new Promise((resolve, reject) => {
    const img = new Image();
    const reader = new FileReader();
    reader.onload = e => {
      img.onload = () => {
        let ratio = Math.min(maxWidth / img.width, maxHeight / img.height, 1);
        const canvas = document.createElement('canvas');
        canvas.width = Math.round(img.width * ratio);
        canvas.height = Math.round(img.height * ratio);
        const ctx = canvas.getContext('2d');
        ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
        canvas.toBlob(blob => resolve(blob), 'image/jpeg', quality);
      };
      img.onerror = reject;
      img.src = e.target.result;
    };
    reader.onerror = reject;
    reader.readAsDataURL(file);
  });
}

function blobToDataURL(blob) {
  return new Promise((resolve, reject) => {
    const r = new FileReader();
    r.onload = e => resolve(e.target.result);
    r.onerror = reject;
    r.readAsDataURL(blob);
  });
}

/* ---------- Open file (camera) and load to editor ---------- */
function openFileSelectAndEdit(index, field) {
  currentEditIndex = index;
  currentField = field;

  const input = document.createElement('input');
  input.type = 'file';
  input.accept = 'image/*';
  input.capture = 'environment'; // buka kamera di HP
  input.onchange = async e => {
    const file = e.target.files && e.target.files[0];
    if (!file) return;
    try {
      const resizedBlob = await resizeImage(file, 1280, 1280, 0.8);
      const dataUrl = await blobToDataURL(resizedBlob);
      openTuiEditorWithImage(dataUrl);
    } catch (err) {
      console.error('Resize/load error', err);
      alert('Gagal memproses gambar.');
    }
  };
  input.click();
}

/* ---------- Inisialisasi editor & toolbar (dipanggil saat show) ---------- */
function openTuiEditorWithImage(dataUrl) {
  // show modal
  const bsModal = new bootstrap.Modal(modalEl);
  bsModal.show();

  // tunggu modal selesai show agar ukuran container stabil
  modalEl.addEventListener('shown.bs.modal', function once() {
    modalEl.removeEventListener('shown.bs.modal', once);
    initTuiEditor(dataUrl);
  });
}

function initTuiEditor(initialDataUrl) {
  // bersihkan container dulu
  const container = document.getElementById('tui-image-editor');
  container.innerHTML = '';

  // destroy instance lama kalau ada
  if (imageEditor) {
    try { imageEditor.destroy(); } catch (e) {}
    imageEditor = null;
  }

  // buat editor TANPA includeUI sama sekali (langsung core editor)
  imageEditor = new tui.ImageEditor(container, {
    usageStatistics: false,
    cssMaxWidth: 2000,
    cssMaxHeight: 2000,
    selectionStyle: {
      cornerSize: 20,
      rotatingPointOffset: 70
    }
  });

  // load gambar ke kanvas
  imageEditor.loadImageFromURL(initialDataUrl, 'LoadedImage').then(() => {
    const canvas = imageEditor._graphics.getCanvas();
    const objs = canvas.getObjects();
    if (objs && objs.length > 0) {
      const img = objs[0];
      img.set({
        originX: 'center',
        originY: 'center',
        left: canvas.getWidth() / 2,
        top: canvas.getHeight() / 2
      });
      canvas.centerObject(img);
      canvas.renderAll();
    }
    enableToolbar();
  }).catch(err => {
    console.error('loadImageFromURL failed', err);
    enableToolbar();
  });

  const canvas = imageEditor._graphics.getCanvas();
  canvas.selection = true;
  canvas.on('object:added', e => {
    const obj = e.target;
    if (obj) {
      obj.cornerStyle = 'circle';
      obj.cornerColor = '#00ff00';
      obj.cornerSize = 14;
      obj.transparentCorners = false;
    }
  });
}

/* ---------- Toolbar handlers ---------- */
function enableToolbar() {
  const tb = document.querySelectorAll('#custom-tui-toolbar [data-tool]');
  tb.forEach(btn => btn.classList.remove('disabled')); // pastikan aktif

  const deactivateAll = () => {
    document.querySelectorAll('#custom-tui-toolbar button').forEach(b => b.classList.remove('active'));
    try { imageEditor.stopDrawingMode(); } catch(e){}
  };

  const getCanvas = () => imageEditor && imageEditor._graphics && imageEditor._graphics.getCanvas ? imageEditor._graphics.getCanvas() : null;

  // draw
  document.querySelector('#custom-tui-toolbar [data-tool="draw"]').onclick = () => {
    deactivateAll();
    document.querySelector('#custom-tui-toolbar [data-tool="draw"]').classList.add('active');
    try {
      imageEditor.startDrawingMode('FREE_DRAWING');
      imageEditor.setBrush({ width: 10, color: '#ff9900' });
    } catch (e) { console.error(e); }
  };

  // rect shape
  document.querySelector('#custom-tui-toolbar [data-tool="rect"]').onclick = () => {
    deactivateAll();
    try {
      imageEditor.stopDrawingMode();
	  const canvas = imageEditor._graphics.getCanvas();
	  const cx = canvas.getWidth() / 2;
	  const cy = canvas.getHeight() / 2;
      imageEditor.addShape('rect', {
        stroke: '#ff9900',
        fill: 'transparent',
        strokeWidth: 20,
        width: 300,
        height: 300,
        left: cx,
        top: cy,
        originX: 'center',
        originY: 'center'
      });
    } catch (e) { console.error(e); }
  };

  // arrow (icon)
  document.querySelector('#custom-tui-toolbar [data-tool="arrow"]').onclick = () => {
    deactivateAll();
    try {
      imageEditor.stopDrawingMode();
	  const canvas = imageEditor._graphics.getCanvas();
	  const cx = canvas.getWidth() / 2;
	  const cy = canvas.getHeight() / 2;
      imageEditor.addIcon('arrow', { fill: '#ff9900', left: cx, top: cy, originX: 'center', originY: 'center' });
    } catch (e) { console.error(e); }
  };

  // rotate
  document.querySelector('#custom-tui-toolbar [data-tool="rotate"]').onclick = () => {
    try { imageEditor.rotate(90); } catch (e) { console.error(e); }
  };

  // undo / redo
  document.querySelector('#custom-tui-toolbar [data-tool="undo"]').onclick = () => {
    try { if (typeof imageEditor.undo === 'function') imageEditor.undo(); } catch(e){ console.error(e); }
  };
  document.querySelector('#custom-tui-toolbar [data-tool="redo"]').onclick = () => {
    try { if (typeof imageEditor.redo === 'function') imageEditor.redo(); } catch(e){ console.error(e); }
  };

  // delete active object(s)
  document.querySelector('#custom-tui-toolbar [data-tool="delete"]').onclick = () => {
    try {
      const canvas = getCanvas();
      if (!canvas) return;
      const activeObjects = canvas.getActiveObjects ? canvas.getActiveObjects() : (canvas.getActiveObject() ? [canvas.getActiveObject()] : []);
      if (activeObjects && activeObjects.length) {
        activeObjects.forEach(o => canvas.remove(o));
        canvas.discardActiveObject && canvas.discardActiveObject();
        if (canvas.requestRenderAll) canvas.requestRenderAll(); else canvas.renderAll();
        // notify TUI history if present
        if (imageEditor._history && typeof imageEditor._history.push === 'function') {
          try { imageEditor._history.push(true); } catch(e) {}
        }
      }
    } catch (err) { console.error(err); }
  };
}

/* ---------- Save: kirim ke server via AJAX/FormData ke route yang benar ---------- */
document.getElementById('tui-save-btn').addEventListener('click', async () => {
  if (!imageEditor) return;

  try {
    const dataURL = imageEditor.toDataURL({ format: 'jpeg', quality: 0.8 });
    const blob = await (await fetch(dataURL)).blob();

    const fd = new FormData();
    fd.append('field', currentField);
    fd.append('slot', currentEditIndex);
    fd.append('photo', blob, 'edited.jpg');

    const res = await fetch(suggestionUpdateUrl, {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': csrfToken },
      body: fd
    });

    const json = await res.json();
    if (json.success) {
      // success -> reload atau update UI sesuai kebutuhan
      const bs = bootstrap.Modal.getInstance(modalEl);
      bs && bs.hide();
      location.reload();
    } else {
      alert(json.message || 'Gagal menyimpan gambar.');
    }
  } catch (err) {
    console.error(err);
    alert('Terjadi kesalahan saat menyimpan gambar.');
  }
});

/* ---------- Hook tombol add/edit foto yang ada di blade ---------- */
document.querySelectorAll('.edit-photo-btn').forEach(btn => {
  btn.addEventListener('click', function (e) {
    const index = this.dataset.slot;
    const field = this.dataset.field;
    openFileSelectAndEdit(index, field);
  });
});

/* ---------- Clean up editor saat modal ditutup ---------- */
modalEl.addEventListener('hidden.bs.modal', () => {
  try {
    if (imageEditor) { imageEditor.destroy(); imageEditor = null; }
  } catch (e) { console.warn(e); }
  // kosongkan container agar tidak ada instance tersisa
  const container = document.getElementById('tui-image-editor');
  if (container) container.innerHTML = '';
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
</script>

@endsection
