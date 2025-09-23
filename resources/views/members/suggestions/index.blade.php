@extends('layouts.member')

@section('content')
<div class="container mt-3">
    <h4 class="mb-3">Detail Saran</h4>

    <table class="table table-bordered table-sm align-middle">
        <tbody>
            {{-- Member --}}
            <tr>
                <th class="col-2 small text-nowrap">Member</th>
                <td>{{ $suggestion->member->nama }}</td>
                <td class="col-1 text-center"></td>
            </tr>

            {{-- Team --}}
            <tr>
                <th class="col-2 small text-nowrap">Team</th>
                <td id="value-Team_Suggestion">{{ $suggestion->Team_Suggestion }}</td>
                <td class="col-1 text-center">
                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalTeam">
                        <i class="material-icons-two-tone" style="font-size:16px;">edit</i>
                    </button>
                </td>
            </tr>

            {{-- Tema --}}
            <tr>
                <th class="col-2 small text-nowrap">Tema</th>
                <td id="value-Theme_Suggestion">{{ $suggestion->Theme_Suggestion }}</td>
                <td class="col-1 text-center">
                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalTheme">
                        <i class="material-icons-two-tone" style="font-size:16px;">edit</i>
                    </button>
                </td>
            </tr>

            {{-- Tanggal Awal --}}
            <tr>
                <th class="col-2 small text-nowrap">Tanggal Penyerahan Awal</th>
                <td id="value-Date_First_Suggestion">{{ $suggestion->Date_First_Suggestion }}</td>
                <td class="col-1 text-center">
                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalDateFirst">
                        <i class="material-icons-two-tone" style="font-size:16px;">edit</i>
                    </button>
                </td>
            </tr>

            {{-- Permasalahan --}}
            <tr>
                <th class="col-2 small text-nowrap">Permasalahan</th>
                <td id="value-Content_Suggestion">{{ $suggestion->Content_Suggestion }}</td>
                <td class="col-1 text-center">
                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalContent">
                        <i class="material-icons-two-tone" style="font-size:16px;">edit</i>
                    </button>
                </td>
            </tr>

            {{-- Foto Permasalahan --}}
            <tr>
                <th class="col-2 small text-nowrap">Foto Permasalahan 1</th>
                <td>
                    @if(isset($photos[0]))
                        <img src="{{ asset('uploads/contents/' . $photos[0]) }}" alt="Foto 1" class="img-thumbnail mb-1" style="max-height: 150px;">
                    @else
                        <p class="text-muted">Belum ada foto 1</p>
                    @endif
                </td>
                <td class="col-1 text-center">
                    <button class="btn btn-sm btn-outline-primary edit-photo-btn" data-slot="0" data-photo="{{ isset($photos[0]) ? asset('uploads/contents/'.$photos[0]) : '' }}">
                        <i class="material-icons-two-tone" style="font-size:16px;">{{ isset($photos[0]) ? 'edit' : 'edit' }}</i>
                    </button>
                </td>
            </tr>
            <tr>
                <th class="col-2 small text-nowrap">Foto Permasalahan 2</th>
                <td>
                    @if(isset($photos[1]))
                        <img src="{{ asset('uploads/contents/' . $photos[1]) }}" alt="Foto 2" class="img-thumbnail mb-1" style="max-height: 150px;">
                    @else
                        <p class="text-muted">Belum ada foto 2</p>
                    @endif
                </td>
                <td class="col-1 text-center">
                    <button class="btn btn-sm btn-outline-primary edit-photo-btn" data-slot="1" data-photo="{{ isset($photos[1]) ? asset('uploads/contents/'.$photos[1]) : '' }}">
                        <i class="material-icons-two-tone" style="font-size:16px;">{{ isset($photos[1]) ? 'edit' : 'edit' }}</i>
                    </button>
                </td>
            </tr>

        </tbody>
    </table>
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
                <option value="Assembling" {{ $suggestion->Team_Suggestion == 'Assembling' ? 'selected' : '' }}>Assembling</option>
                <option value="Painting" {{ $suggestion->Team_Suggestion == 'Painting' ? 'selected' : '' }}>Painting</option>
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
            </select>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
          </div>
        </div>
    </form>
  </div>
</div>

{{-- Modal Date First --}}
<div class="modal fade" id="modalDateFirst" tabindex="-1">
  <div class="modal-dialog">
    <form class="ajaxUpdateForm" data-field="Date_First_Suggestion">
        @csrf
        <div class="modal-content">
          <div class="modal-header bg-primary">
            <h5 class="modal-title text-white">Edit Tanggal Penyerahan Awal</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <input type="date" class="form-control" name="value" value="{{ $suggestion->Date_First_Suggestion }}">
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

{{-- Modal Edit Foto (Toast UI) --}}
<div class="modal fade" id="photoEditorModal" tabindex="-1">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title text-white">Edit Foto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-0">
        <div id="tui-image-editor" style="height:600px;"></div>
      </div>
      <div class="modal-footer">
        <button id="saveEditedPhoto" class="btn btn-primary">Simpan</button>
      </div>
    </div>
  </div>
</div>

@endsection

@section('style')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection


@section('script')
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<link rel="stylesheet" href="https://uicdn.toast.com/tui-color-picker/v2.2.7/tui-color-picker.min.css">
<link rel="stylesheet" href="https://uicdn.toast.com/tui-image-editor/v3.15.3/tui-image-editor.min.css">

<script src="https://uicdn.toast.com/tui-code-snippet/v1.5.2/tui-code-snippet.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/3.6.3/fabric.min.js"></script>
<script src="https://uicdn.toast.com/tui-color-picker/v2.2.7/tui-color-picker.min.js"></script>
<script src="https://uicdn.toast.com/tui-image-editor/v3.15.3/tui-image-editor.min.js"></script>


<script>
$(function(){
    $('.ajaxUpdateForm').on('submit', function(e){
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
            success: function(res){
                if(res.success){
                    $('#value-' + field).text(value);
                    form.closest('.modal').modal('hide');
                } else {
                    alert(res.message);
                }
            },
            error: function(){
                alert("Gagal update data.");
            }
        });
    });
});

let imageEditor;
let currentSlot = null;

$(document).on('click', '.edit-photo-btn', function () {
    const slot = $(this).data('slot');
    const photoUrl = $(this).data('photo');
    currentSlot = slot;

    // Reset editor container
    $('#tui-image-editor').html('');

    if (imageEditor) {
        imageEditor.destroy();
        imageEditor = null;
    }

    // Init Toast UI
    imageEditor = new tui.ImageEditor(document.querySelector('#tui-image-editor'), {
        includeUI: {
            loadImage: {
                path: photoUrl,
                name: 'CurrentPhoto',
            },
            menu: ['shape', 'draw', 'icon', 'text', 'filter'],
            uiSize: { width: '100%', height: '720px' },
            menuBarPosition: 'bottom',
        },
        cssMaxWidth: 720,
        cssMaxHeight: 720,
    });

    $('#photoEditorModal').modal('show');
});

$('#saveEditedPhoto').on('click', function () {
    if (!imageEditor) return;

    // ambil data base64 dari editor
    const dataURL = imageEditor.toDataURL({
        format: 'png',
        quality: 1.0
    });

    if (!dataURL) {
        alert("Gagal mengambil data dari editor.");
        return;
    }

    const blob = dataURLtoBlob(dataURL);

    let formData = new FormData();
    formData.append('field', 'Content_Photos_Suggestion');
    formData.append('slot', currentSlot);
    formData.append('photo', blob, 'edited.png');
    formData.append('_token', "{{ csrf_token() }}");

    $.ajax({
        url: "{{ route('suggestion.updateField', $suggestion->Id_Suggestion) }}",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
            if (res.success) {
                location.reload();
            } else {
                alert(res.message);
            }
        },
        error: function () {
            alert("Gagal menyimpan foto.");
        }
    });
});

// Helper convert base64 ke Blob
function dataURLtoBlob(dataURL) {
    const byteString = atob(dataURL.split(',')[1]);
    const mimeString = dataURL.split(',')[0].split(':')[1].split(';')[0];
    const ab = new ArrayBuffer(byteString.length);
    const ia = new Uint8Array(ab);
    for (let i = 0; i < byteString.length; i++) ia[i] = byteString.charCodeAt(i);
    return new Blob([ab], { type: mimeString });
}

</script>
@endsection
