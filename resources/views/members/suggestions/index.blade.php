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
							<th class="col-2">Member</th>
							<td>{{ $suggestion->member->nama }} ({{ $suggestion->member->nik }})</td>
							<td class="col-1 text-center"></td>
						</tr>

						{{-- Team --}}
						<tr>
							<th class="col-2">Team</th>
							<td id="value-Team_Suggestion">{{ $suggestion->Team_Suggestion }}</td>
							<td class="col-1 text-center">
								<button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
									data-bs-target="#modalTeam">
									<i class="material-icons-two-tone" style="font-size:16px;">edit</i>
								</button>
							</td>
						</tr>

						{{-- Tema --}}
						<tr>
							<th class="col-2">Tema</th>
							<td id="value-Theme_Suggestion">{{ $suggestion->Theme_Suggestion }}</td>
							<td class="col-1 text-center">
								<button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
									data-bs-target="#modalTheme">
									<i class="material-icons-two-tone" style="font-size:16px;">edit</i>
								</button>
							</td>
						</tr>

						{{-- Status --}}
						<tr>
							<th class="col-2">Status</th>
							@if ($suggestion->Status_Suggestion == 1)
								<td><span class="badge bg-success">Sudah Selesai</span></td>
							@endif
							@if ($suggestion->Status_Suggestion == 0)
								<td><span class="badge bg-warning text-dark">Belum Selesai</span></td>
							@endif
							<td class="col-1 text-center"></td>
						</tr>

						{{-- Tanggal Awal --}}
						<tr>
							<th class="col-2">Tanggal Penyerahan Awal</th>
							<td id="value-Date_First_Suggestion">{{ $suggestion->Date_First_Suggestion }}</td>
							<td class="col-1 text-center"></td>
						</tr>

						{{-- No Penerimaan Awal --}}
						<tr>
							<th class="col-2">No Penerimaan Awal</th>
							<td id="value-Acceptance_First_Suggestion">{{ $suggestion->acceptance_first_suggestion_formatted }}</td>
							<td class="col-1 text-center"></td>
						</tr>

						{{-- Tanggal Akhir --}}
						<tr>
							<th class="col-2">Tanggal Penyerahan Akhir</th>
							<td id="value-Date_Last_Suggestion">{{ $suggestion->Date_Last_Suggestion }}</td>
							<td class="col-1 text-center"></td>
						</tr>

						{{-- No Penerimaan Akhir --}}
						<tr>
							<th class="col-2">No Penerimaan Akhir</th>
							<td id="value-Acceptance_Last_Suggestion">{{ $suggestion->acceptance_last_suggestion_formatted }}</td>
							<td class="col-1 text-center"></td>
						</tr>

						{{-- Permasalahan --}}
						<tr>
							<th class="col-2">Permasalahan</th>
							<td id="value-Content_Suggestion">{{ $suggestion->Content_Suggestion }}</td>
							<td class="col-1 text-center">
								<button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
									data-bs-target="#modalContent">
									<i class="material-icons-two-tone" style="font-size:16px;">edit</i>
								</button>
							</td>
						</tr>

						{{-- Foto Permasalahan --}}
						<tr>
							<th class="col-2">Foto Permasalahan 1</th>
							<td>
								@if(isset($contentPhotos[0]))
								<img src="{{ asset('uploads/contents/' . $contentPhotos[0]) }}" alt="Foto 1" class="img-thumbnail mb-1"
									style="max-height: 150px;">
								@else
								{{-- <p class="text-muted">Belum ada foto 1</p> --}}
								@endif
							</td>
							<td class="col-1 text-center">
								<button class="btn btn-sm btn-outline-primary edit-photo-btn"
									data-slot="0"
									data-field="Content_Photos_Suggestion"
									data-photo="{{ isset($contentPhotos[0]) ? asset('uploads/contents/'.$contentPhotos[0]) : '' }}">
									<i class="material-icons-two-tone" style="font-size:16px;">edit</i>
								</button>
							</td>
						</tr>
						<tr>
							<th class="col-2">Foto Permasalahan 2</th>
							<td>
								@if(isset($contentPhotos[1]))
								<img src="{{ asset('uploads/contents/' . $contentPhotos[1]) }}" alt="Foto 2" class="img-thumbnail mb-1"
									style="max-height: 150px;">
								@else
								{{-- <p class="text-muted">Belum ada foto 2</p> --}}
								@endif
							</td>
							<td class="col-1 text-center">
								<button class="btn btn-sm btn-outline-primary edit-photo-btn"
									data-slot="1"
									data-field="Content_Photos_Suggestion"
									data-photo="{{ isset($contentPhotos[1]) ? asset('uploads/contents/'.$contentPhotos[1]) : '' }}">
									<i class="material-icons-two-tone" style="font-size:16px;">edit</i>
								</button>
							</td>
						</tr>

						{{-- Perbaikan --}}
						<tr>
							<th class="col-2">Perbaikan</th>
							<td id="value-Improvement_Suggestion">{{ $suggestion->Improvement_Suggestion }}</td>
							<td class="col-1 text-center">
								<button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
									data-bs-target="#modalImprovement">
									<i class="material-icons-two-tone" style="font-size:16px;">edit</i>
								</button>
							</td>
						</tr>

						{{-- Foto Perbaikan --}}
						<tr>
							<th class="col-2">Foto Perbaikan 1</th>
							<td>
								@if(isset($improvementPhotos[0]))
								<img src="{{ asset('uploads/improvements/' . $improvementPhotos[0]) }}" alt="Foto 1" class="img-thumbnail mb-1"
									style="max-height: 150px;">
								@else
								{{-- <p class="text-muted">Belum ada foto 1</p> --}}
								@endif
							</td>
							<td class="col-1 text-center">
								<button class="btn btn-sm btn-outline-primary edit-photo-btn"
									data-slot="0"
									data-field="Improvement_Photos_Suggestion"
									data-photo="{{ isset($improvementPhotos[0]) ? asset('uploads/improvements/'.$improvementPhotos[0]) : '' }}">
									<i class="material-icons-two-tone" style="font-size:16px;">edit</i>
								</button>
							</td>
						</tr>
						<tr>
							<th class="col-2">Foto Perbaikan 2</th>
							<td>
								@if(isset($improvementPhotos[1]))
								<img src="{{ asset('uploads/improvements/' . $improvementPhotos[1]) }}" alt="Foto 2" class="img-thumbnail mb-1"
									style="max-height: 150px;">
								@else
								{{-- <p class="text-muted">Belum ada foto 2</p> --}}
								@endif
							</td>
							<td class="col-1 text-center">
								<button class="btn btn-sm btn-outline-primary edit-photo-btn"
									data-slot="1"
									data-field="Improvement_Photos_Suggestion"
									data-photo="{{ isset($improvementPhotos[1]) ? asset('uploads/improvements/'.$improvementPhotos[1]) : '' }}">
									<i class="material-icons-two-tone" style="font-size:16px;">edit</i>
								</button>
							</td>
						</tr>
						
						{{-- Skor A --}}
						<tr>
							<th class="col-2">Skor A</th>
							<td id="value-Score_A_Suggestion">{{ $suggestion->score_a_formatted }}</td>
							<td class="col-1 text-center"></td>
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
							<td class="col-1 text-center"></td>
						</tr>

						{{-- Leader --}}
						<tr>
							<th class="col-2">Leader</th>
							<td id="value-Id_User">{{ $suggestion->user->Name_User ?? '' }}</td>
							<td class="col-1 text-center"></td>
						</tr>

						{{-- Komentar --}}
						<tr>
							<th class="col-2">Komentar</th>
							<td id="value-Comment_Suggestion">{{ $suggestion->Comment_Suggestion }}</td>
							<td class="col-1 text-center"></td>
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
						<option value="Fasilitas" {{ $suggestion->Theme_Suggestion == 'Fasilitas' ? 'selected' : '' }}>Fasilitas</option>
						<option value="Mould Jig" {{ $suggestion->Theme_Suggestion == 'Mould Jig' ? 'selected' : '' }}>Mould Jig</option>
						<option value="Set Up" {{ $suggestion->Theme_Suggestion == 'Set Up' ? 'selected' : '' }}>Set Up</option>
						<option value="Material" {{ $suggestion->Theme_Suggestion == 'Material' ? 'selected' : '' }}>Material</option>
						<option value="Metode" {{ $suggestion->Theme_Suggestion == 'Metode' ? 'selected' : '' }}>Metode</option>
						<option value="Informasi" {{ $suggestion->Theme_Suggestion == 'Informasi' ? 'selected' : '' }}>Informasi</option>
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
                    <textarea class="form-control" name="value"
                        rows="4">{{ $suggestion->Content_Suggestion }}</textarea>
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
<link rel="stylesheet" href="{{ asset('assets/css/tui-color-picker.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/tui-image-editor.min.css') }}">

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

    let imageEditor = null;
    let currentSlot = null;
    let currentField = null;

    // Klik tombol edit foto
    $(document).on('click', '.edit-photo-btn', function () {
        currentSlot = $(this).data('slot');
        currentField = $(this).data('field');
        const photoUrl = $(this).data('photo') || '';

        // Reset container
        $('#tui-image-editor').html('');

        // Hancurkan instance lama
        if (imageEditor) {
            imageEditor.destroy();
            imageEditor = null;
        }

        // Inisialisasi editor
        imageEditor = new tui.ImageEditor(document.querySelector('#tui-image-editor'), {
            includeUI: {
                loadImage: {
                    path: photoUrl || '',
                    name: 'CurrentPhoto',
                },
                menu: ['shape', 'draw', 'icon', 'text', 'filter'],
                uiSize: {
                    width: '100%',
                    height: '720px'
                },
                menuBarPosition: 'bottom',
            },
            cssMaxWidth: 720,
            cssMaxHeight: 720,
        });

        $('#photoEditorModal').modal('show');
    });

    // Simpan hasil edit
    $('#saveEditedPhoto').on('click', function () {
        if (!imageEditor) return;

        const dataURL = imageEditor.toDataURL({
            format: 'jpg',
            quality: 1.0
        });

        if (!dataURL) {
            alert("Gagal mengambil data dari editor.");
            return;
        }

        const blob = dataURLtoBlob(dataURL);

        let formData = new FormData();
        formData.append('field', currentField);
        formData.append('slot', currentSlot);
        formData.append('photo', blob, 'edited.jpg');
        formData.append('_token', "{{ csrf_token() }}");

        $.ajax({
            url: "{{ route('suggestion.updateField', $suggestion->Id_Suggestion) }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
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

    // Helper: convert base64 → Blob
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
