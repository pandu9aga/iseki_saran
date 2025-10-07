@extends('layouts.member')

@section('content')
<div class="col-sm-12">
    <div class="card table-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="text-primary">Saran Perbaikan</h4>
        </div>

        <div class="card-body p-3">
            <form action="{{ route('suggestion.insert') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body mb-2">
                    <div class="row mb-2">
                        <div class="col-6">
                            <label for="Name_Member" class="form-label">Member</label>
                            <input type="text" id="Name_Member" name="Name_Member" class="form-control" value="{{ $member->nama }}" readonly/>
                            <input type="hidden" id="Id_Member" name="Id_Member" value="{{ $member->id }}" />
                        </div>
                        <div class="col-6">
                            <label for="Team_Suggestion" class="form-label">Team</label>
                            <input type="text" id="Team_Suggestion" name="Team_Suggestion" class="form-control" value="{{ $member->division->nama }}" readonly/>
                        </div>
                    </div>

                    <div class="row mb-2">
						<div class="col">
							<label for="Theme_Suggestion" class="form-label">Tema Perbaikan <span class="text-danger">*</span></label><br>
							@foreach(['Keselamatan','Kualitas','Cost','Waktu','Lingkungan','Moral'] as $theme)
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" name="Theme_Suggestion" id="{{ $theme }}" value="{{ $theme }}" required>
									<label class="form-check-label" for="{{ $theme }}">{{ $theme }}</label>
								</div>
							@endforeach
						</div>
					</div>

					<div class="row mb-2">
						<div class="col">
							<label for="Content_Suggestion" class="form-label">Permasalahan Yang Dialami <span class="text-danger">*</span></label>
							<textarea id="Content_Suggestion" name="Content_Suggestion" class="form-control" required></textarea>
						</div>
					</div>

                    <div class="row mb-2">
                        <label class="form-label">Foto Permasalahan</label>
                        @for($i = 0; $i < 2; $i++)
                        <div class="col-6 d-flex justify-content-center align-items-center">
                            <div class="photo-upload" data-index="{{ $i }}">
								<button type="button" class="btn btn-light btn-upload p-4">
									<i class="material-icons-two-tone" style="font-size:16px;">add</i>
								</button>
							</div>
							<input type="hidden" name="Content_Photos_Suggestion[]" id="photo-path-{{ $i }}">
                        </div>
                        @endfor
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="tuiEditorModal" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-fullscreen">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Edit Foto</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body p-0 d-flex flex-column" style="min-height:0;">
				<!-- Custom toolbar -->
				<div id="custom-tui-toolbar" class="p-2 border-bottom d-flex flex-wrap justify-content-start align-items-center gap-2 bg-light">
					<!-- Left tools -->
					<div class="d-flex gap-2">
						<button type="button" class="btn btn-outline-primary d-flex align-items-center" data-tool="draw">
							<i class="material-icons-two-tone" style="font-size:16px;">edit</i>
						</button>
						<button type="button" class="btn btn-outline-primary d-flex align-items-center" data-tool="rect">
							<i class="material-icons-two-tone" style="font-size:16px;">crop_square</i>
						</button>
						<button type="button" class="btn btn-outline-primary d-flex align-items-center" data-tool="arrow">
							<i class="material-icons-two-tone" style="font-size:16px;">arrow_right_alt</i>
						</button>
						<button type="button" class="btn btn-outline-primary d-flex align-items-center" data-tool="rotate">
							<i class="material-icons-two-tone" style="font-size:16px;">rotate_right</i>
						</button>
					</div>

					<!-- Middle separator -->
					<div class="vr mx-3 d-none d-md-block"></div>

					<!-- Edit tools -->
					<div class="d-flex gap-2">
						<button type="button" class="btn btn-outline-secondary d-flex align-items-center" data-tool="undo">
							<i class="material-icons-two-tone" style="font-size:16px;">undo</i>
						</button>
						<button type="button" class="btn btn-outline-secondary d-flex align-items-center" data-tool="redo">
							<i class="material-icons-two-tone" style="font-size:16px;">redo</i>
						</button>
						<button type="button" class="btn btn-outline-danger d-flex align-items-center" data-tool="delete">
							<i class="material-icons-two-tone" style="font-size:16px;">delete</i>
						</button>
					</div>

					<!-- Right buttons -->
					<div class="ms-auto d-flex gap-2">
						<button type="button" class="btn btn-success d-flex align-items-center" id="tui-save-btn">
							Save
						</button>
						<button type="button" class="btn btn-secondary d-flex align-items-center" data-bs-dismiss="modal">
							Close
						</button>
					</div>
				</div>

				<!-- Editor container -->
				<div id="tui-editor-container" class="d-flex justify-content-center align-items-center bg-dark-subtle" 
					style="flex:1; min-height:0; overflow:hidden;">
					<div id="tui-image-editor" style="width:96%; height:96%;"></div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('style')
<link rel="stylesheet" href="{{ asset('assets/css/tui-color-picker.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/tui-image-editor.min.css') }}">
<style>
.photo-upload {
	position: relative;
	width: 100%;
	height: 200px;
	border: 2px dashed #ccc;
	border-radius: 8px;
	display: flex;
	justify-content: center;
	align-items: center;
	cursor: pointer;
	transition: 0.3s;
}
.photo-upload:hover {
	border-color: #007bff;
	background: #f9f9f9;
}
.photo-upload img {
  width: 100%;
  height: 100%;
  object-fit: contain; /* jaga rasio, tidak keluar dari kotak */
  border-radius: 8px;
}


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

</style>
@endsection

@section('script')
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/tui-code-snippet.min.js') }}"></script>
<script src="{{ asset('assets/js/fabric.min.js') }}"></script>
<script src="{{ asset('assets/js/tui-color-picker.min.js') }}"></script>
<script src="{{ asset('assets/js/tui-image-editor.min.js') }}"></script>

<script>
let tuiEditor = null;
let currentEditIndex = null;
let loadedDataUrl = null;

const modalEl = document.getElementById('tuiEditorModal');

// Init hanya saat modal dibuka
modalEl.addEventListener('shown.bs.modal', () => {
  if (!tuiEditor) initTuiEditor(loadedDataUrl);
});

// Destroy setiap kali modal ditutup
modalEl.addEventListener('hidden.bs.modal', () => {
  if (tuiEditor) {
    try { tuiEditor.destroy(); } catch (e) {}
    tuiEditor = null;
  }
});

function openTuiEditorWithImage(dataUrl) {
  loadedDataUrl = dataUrl;
  new bootstrap.Modal(modalEl).show();
}

// === Fungsi utama ===
function initTuiEditor(initialDataUrl) {
  const container = document.getElementById('tui-image-editor');
  container.innerHTML = ''; // bersihkan sebelum buat baru

  tuiEditor = new tui.ImageEditor(container, {
    cssMaxWidth: container.clientWidth,
    cssMaxHeight: container.clientHeight,
    usageStatistics: false,
  });

  // ==== Load gambar di tengah ====
  if (initialDataUrl) {
  tuiEditor.loadImageFromURL(initialDataUrl, 'uploaded').then(() => {
    const canvas = tuiEditor._graphics.getCanvas();
    const objs = canvas.getObjects();
    if (objs && objs.length > 0) {
      const img = objs[0];
      img.set({
        originX: 'center',
        originY: 'center',
        left: canvas.getWidth() / 2,
        top: canvas.getHeight() / 2,
      });
      canvas.centerObject(img);
      canvas.renderAll();
    }
  }).catch(console.error);
}

  // ===== Custom Toolbar Actions =====
  const btnDraw = document.querySelector('[data-tool="draw"]');
  const btnRect = document.querySelector('[data-tool="rect"]');
  const btnArrow = document.querySelector('[data-tool="arrow"]');
  const btnRotate = document.querySelector('[data-tool="rotate"]');
  const btnUndo = document.querySelector('[data-tool="undo"]');
  const btnRedo = document.querySelector('[data-tool="redo"]');
  const btnDelete = document.querySelector('[data-tool="delete"]');
  const btnSave = document.getElementById('tui-save-btn');

  // ========== Tool Handlers ==========
  btnDraw.onclick = () => {
    deactivateAll();
    btnDraw.classList.add('active');
    tuiEditor.startDrawingMode('FREE_DRAWING');
    tuiEditor.setBrush({ width: 10, color: '#ff9900' });
  };

  btnRect.onclick = () => {
    deactivateAll();
    // btnRect.classList.add('active');
    tuiEditor.stopDrawingMode();
    const canvas = tuiEditor._graphics.getCanvas();
    const cx = canvas.getWidth() / 2;
    const cy = canvas.getHeight() / 2;
    tuiEditor.addShape('rect', {
      stroke: '#ff9900',
      fill: 'transparent',
      strokeWidth: 20,
      width: 300,
      height: 300,
      left: cx,
      top: cy,
      originX: 'center',
      originY: 'center',
    });
  };

  btnArrow.onclick = () => {
    deactivateAll();
    // btnArrow.classList.add('active');
    tuiEditor.stopDrawingMode();
    const canvas = tuiEditor._graphics.getCanvas();
    const cx = canvas.getWidth() / 2;
    const cy = canvas.getHeight() / 2;
    tuiEditor.addIcon('arrow', {
      fill: '#ff9900',
      left: cx,
      top: cy,
      originX: 'center',
      originY: 'center',
    });
  };

  btnRotate.onclick = () => tuiEditor.rotate(90);

  // Undo / Redo / Delete
btnUndo.onclick = () => {
  if (typeof tuiEditor.undo === 'function') tuiEditor.undo();
};

btnRedo.onclick = () => {
  if (typeof tuiEditor.redo === 'function') tuiEditor.redo();
};

btnDelete.onclick = () => {
  try {
    const canvas = tuiEditor._graphics.getCanvas(); // fabric canvas
    // support multiple active selection (group)
    const activeObjects = canvas.getActiveObjects ? canvas.getActiveObjects() : [];
    if (activeObjects && activeObjects.length > 0) {
      // remove all selected objects
      activeObjects.forEach(obj => canvas.remove(obj));
      // clear selection
      canvas.discardActiveObject();
    } else {
      // single active object fallback
      const active = canvas.getActiveObject();
      if (active) {
        canvas.remove(active);
      }
    }
    // re-render canvas
    if (typeof canvas.requestRenderAll === 'function') {
      canvas.requestRenderAll();
    } else {
      canvas.renderAll();
    }

    // Fire Fabric event so any listeners (including tui history) are notified
    canvas.fire && canvas.fire('object:removed');

    // If TUI keeps its own history register, make sure it's aware:
    if (tuiEditor._history && typeof tuiEditor._history.push === 'function') {
      try { tuiEditor._history.push(true); } catch(e) { /* ignore */ }
    }
  } catch (err) {
    console.error('Delete failed', err);
  }
};

  // Save
  btnSave.onclick = () => {
    const dataURL = tuiEditor.toDataURL();
    const hiddenInput = document.getElementById(`photo-path-${currentEditIndex}`);
    if (hiddenInput) {
      hiddenInput.value = dataURL;
      const slot = document.querySelector(`.photo-upload[data-index="${currentEditIndex}"]`);
      if (slot) {
		slot.innerHTML = `<img src="${dataURL}" alt="foto">`;
        slot.addEventListener('click', () => openFileSelectAndEdit(currentEditIndex), { once: true });
      }
    }
    const bootstrapModal = bootstrap.Modal.getInstance(modalEl);
    bootstrapModal?.hide();
  };

  // Fungsi bantu
  function deactivateAll() {
    document.querySelectorAll('#custom-tui-toolbar button').forEach(btn => btn.classList.remove('active'));
    tuiEditor.stopDrawingMode();
  }

  // ==== Bantu agar kontrol bisa diklik di layar kecil ====
  const canvas = tuiEditor._graphics.getCanvas();
  canvas.selection = true;
  canvas.on('object:added', function(e) {
    const obj = e.target;
    if (obj) {
      obj.cornerStyle = 'circle';
      obj.cornerColor = '#00ff00';
      obj.cornerSize = 14; // perbesar handle agar mudah diklik
      obj.transparentCorners = false;
    }
  });
}

function openFileSelectAndEdit(index) {
  currentEditIndex = index;

  const input = document.createElement('input');
  input.type = 'file';
  input.accept = 'image/*';
  input.capture = 'environment';
  input.onchange = async e => {
    const file = e.target.files[0];
    if (!file) return;

    try {
      // ✅ Resize gambar sebelum dimuat
      const resizedBlob = await resizeImage(file, 1280, 1280, 0.8);
      const resizedDataUrl = await blobToDataURL(resizedBlob);

      openTuiEditorWithImage(resizedDataUrl);
    } catch (err) {
      console.error('Resize error:', err);
    }
  };
  input.click();
}

// attach ke tiap slot
document.querySelectorAll('.photo-upload').forEach(el => {
  el.addEventListener('click', function() {
    openFileSelectAndEdit(this.dataset.index);
  });
});

// ✅ Fungsi resize/kompres gambar
function resizeImage(file, maxWidth = 1280, maxHeight = 1280, quality = 0.8) {
  return new Promise((resolve, reject) => {
    const img = new Image();
    const reader = new FileReader();

    reader.onload = e => { img.src = e.target.result; };
    reader.onerror = err => reject(err);

    img.onload = () => {
      let width = img.width;
      let height = img.height;

      if (width > maxWidth || height > maxHeight) {
        const scale = Math.min(maxWidth / width, maxHeight / height);
        width *= scale;
        height *= scale;
      }

      const canvas = document.createElement('canvas');
      canvas.width = width;
      canvas.height = height;
      const ctx = canvas.getContext('2d');
      ctx.drawImage(img, 0, 0, width, height);

      canvas.toBlob(
        blob => resolve(blob),
        'image/jpeg',
        quality
      );
    };

    img.onerror = err => reject(err);
    reader.readAsDataURL(file);
  });
}

// ✅ Konversi Blob ke DataURL agar bisa dimuat ke TUI Editor
function blobToDataURL(blob) {
  return new Promise((resolve, reject) => {
    const reader = new FileReader();
    reader.onload = e => resolve(e.target.result);
    reader.onerror = reject;
    reader.readAsDataURL(blob);
  });
}

</script>

@endsection
