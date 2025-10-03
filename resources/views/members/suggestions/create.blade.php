@extends('layouts.member')

@section('content')
<div class="col-sm-12">
    <div class="card table-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="text-primary">Saran Perbaikan</h4>
        </div>

		<div class="card-body p-3">
			<form action="{{ route('suggestion.store') }}" method="POST" style="display:inline;" enctype="multipart/form-data">
				@csrf
				<div class="modal-body mb-2">
					<div class="row mb-2">
						<div class="col-6">
							<label for="Name_Member" class="form-label">Member</label>
							<input type="text" id="Name_Member" name="Name_Member" class="form-control" value="{{ $member->nama }}" readonly/>
							<input type="hidden" id="Id_Member" name="Id_Member" class="form-control" value="{{ $member->id }}" />
						</div>
						<div class="col-6">
							<label for="Team_Suggestion" class="form-label">Team</label>
							<input type="text" id="Team_Suggestion" name="Team_Suggestion" class="form-control" value="{{ $member->division->nama }}" readonly/>
						</div>
					</div>
					<div class="row mb-2">
						<div class="col">
							<label for="Theme_Suggestion" class="form-label">Tema Perbaikan</label><br>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="Theme_Suggestion" id="Keselamatan" value="Keselamatan">
								<label class="form-check-label" for="Keselamatan">Keselamatan</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="Theme_Suggestion" id="Kualitas" value="Kualitas">
								<label class="form-check-label" for="Kualitas">Kualitas</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="Theme_Suggestion" id="Cost" value="Cost">
								<label class="form-check-label" for="Cost">Cost</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="Theme_Suggestion" id="Waktu" value="Waktu">
								<label class="form-check-label" for="Waktu">Waktu</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="Theme_Suggestion" id="Lingkungan" value="Lingkungan">
								<label class="form-check-label" for="Lingkungan">Lingkungan</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="Theme_Suggestion" id="Moral" value="Moral">
								<label class="form-check-label" for="Moral">Moral</label>
							</div>
						</div>
					</div>
					<div class="row mb-2">
						<div class="col">
							<label for="Content_Suggestion" class="form-label">Permasalahan Yang Dialami</label>
							<textarea id="Content_Suggestion" name="Content_Suggestion" class="form-control"></textarea>
						</div>
					</div>
					<div class="row mb-2">
						<label for="Content_Photos_Suggestion" class="form-label">Foto Permasalahan</label>

						@for($i = 0; $i < 2; $i++)
						<div class="col-6 d-flex justify-content-center align-items-center">
							<div class="photo-upload" data-index="{{ $i }}">
								<button type="button" class="btn btn-light btn-upload p-4">
									<i class="material-icons-two-tone" style="font-size:16px;">add</i>
								</button>
								<input type="hidden" name="Content_Photos_Suggestion[]" id="photo-path-{{ $i }}">
							</div>
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
<div id="tui-editor-popup" style="display:none;">
    <div id="tui-editor"></div>
    <div class="text-center mt-2">
        <button id="save-edit" class="btn btn-primary">Simpan</button>
        <button id="cancel-edit" class="btn btn-secondary">Batal</button>
    </div>
</div>
@endsection

@section('style')
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
	}
	.photo-upload img {
		max-width: 100%;
		max-height: 100%;
		object-fit: cover;
	}

</style>
@endsection


@section('script')
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<link rel="stylesheet" href="{{ asset('assets/css/tui-color-picker.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/tui-image-editor.min.css') }}">

<script src="{{ asset('assets/js/tui-code-snippet.min.js') }}"></script>
<script src="{{ asset('assets/js/fabric.min.js') }}"></script>
<script src="{{ asset('assets/js/tui-color-picker.min.js') }}"></script>
<script src="{{ asset('assets/js/tui-image-editor.min.js') }}"></script>

<script>
let editorInstance;
let currentIndex = null;

document.querySelectorAll('.btn-upload').forEach(btn => {
    btn.addEventListener('click', () => {
        currentIndex = btn.closest('.photo-upload').dataset.index;

        // pilihan ambil kamera / galeri
        let input = document.createElement("input");
        input.type = "file";
        input.accept = "image/*";
        input.capture = "environment"; // dahulukan kamera
        input.onchange = e => {
            let file = e.target.files[0];
            if (!file) return;

            let reader = new FileReader();
            reader.onload = function(ev) {
                openImageEditor(ev.target.result);
            }
            reader.readAsDataURL(file);
        }
        input.click();
    });
});

function openImageEditor(imgUrl) {
    document.getElementById('tui-editor-popup').style.display = 'block';

    if (editorInstance) {
        editorInstance.destroy();
    }

    editorInstance = new tui.ImageEditor('#tui-editor', {
        includeUI: {
            loadImage: {
                path: imgUrl,
                name: 'edit-image'
            },
            theme: {},
            initMenu: 'filter',
            menuBarPosition: 'bottom'
        },
        // cssMaxWidth: 700,
        // cssMaxHeight: 500
    });
}

// tombol simpan hasil edit
document.getElementById('save-edit').addEventListener('click', function() {
    const dataUrl = editorInstance.toDataURL();

    // kirim ke server
    let formData = new FormData();
    
});

document.getElementById('cancel-edit').addEventListener('click', function() {
    document.getElementById('tui-editor-popup').style.display = 'none';
});
</script>
@endsection
