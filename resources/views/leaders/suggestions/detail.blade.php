@extends('layouts.leader')

@section('content')
<div class="col-sm-12">
    <div class="card table-card">
        <div class="card-header d-flex justify-content-between align-items-center">
			<h4 class="text-primary">Detail Saran</h4>
			<a href="{{ route('leader.suggestions.export', $suggestion->Id_Suggestion) }}" 
			class="btn btn-success btn-sm">
				<i class="material-icons-two-tone text-white" style="font-size:16px;">download</i> Export Excel
			</a>
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
							<td class="col-1 text-center"></td>
						</tr>

						{{-- Tema --}}
						<tr>
							<th class="col-2">Tema</th>
							<td id="value-Theme_Suggestion">{{ $suggestion->Theme_Suggestion }}</td>
							<td class="col-1 text-center"></td>
						</tr>

						{{-- Status --}}
						<tr>
							<th class="col-2">Status</th>
							<td id="value-Status_Suggestion">
								@if ($suggestion->Status_Suggestion == 1)
									<span class="badge bg-success">Sudah Selesai</span>
								@else
									<span class="badge bg-warning text-dark">Belum Selesai</span>
								@endif
							</td>
							<td class="col-1 text-center">
								<button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalStatus">
									<i class="material-icons-two-tone" style="font-size:16px;">edit</i>
								</button>
							</td>
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
							<td class="col-1 text-center">
								@if(!$suggestion->Acceptance_First_Suggestion) 
									<button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#modalAcceptanceFirst">
										Terima
									</button>
								@endif
							</td>
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
							<td class="col-1 text-center"></td>
						</tr>

						{{-- Foto Permasalahan --}}
						<tr>
							<th class="col-2">Foto Permasalahan 1</th>
							<td>
								@if(isset($contentPhotos[0]))
								<img src="{{ asset('uploads/contents/' . $contentPhotos[0]) }}"
									alt="Foto 1"
									class="img-thumbnail mb-1 preview-img"
									style="max-height: 150px; cursor:pointer;"
									data-bs-toggle="modal"
									data-bs-target="#imageModal"
									data-src="{{ asset('uploads/contents/' . $contentPhotos[0]) }}">
								@endif
							</td>
							<td class="col-1 text-center"></td>
						</tr>
						<tr>
							<th class="col-2">Foto Permasalahan 2</th>
							<td>
								@if(isset($contentPhotos[1]))
								<img src="{{ asset('uploads/contents/' . $contentPhotos[1]) }}"
									alt="Foto 2"
									class="img-thumbnail mb-1 preview-img"
									style="max-height: 150px; cursor:pointer;"
									data-bs-toggle="modal"
									data-bs-target="#imageModal"
									data-src="{{ asset('uploads/contents/' . $contentPhotos[1]) }}">
								@endif
							</td>
							<td class="col-1 text-center"></td>
						</tr>

						{{-- Perbaikan --}}
						<tr>
							<th class="col-2">Perbaikan</th>
							<td id="value-Improvement_Suggestion">{{ $suggestion->Improvement_Suggestion }}</td>
							<td class="col-1 text-center"></td>
						</tr>

						{{-- Foto Perbaikan --}}
						<tr>
							<th class="col-2">Foto Perbaikan 1</th>
							<td>
								@if(isset($improvementPhotos[0]))
								<img src="{{ asset('uploads/improvements/' . $improvementPhotos[0]) }}"
									alt="Foto 1"
									class="img-thumbnail mb-1 preview-img"
									style="max-height: 150px; cursor:pointer;"
									data-bs-toggle="modal"
									data-bs-target="#imageModal"
									data-src="{{ asset('uploads/improvements/' . $improvementPhotos[0]) }}">
								@endif
							</td>
							<td class="col-1 text-center"></td>
						</tr>
						<tr>
							<th class="col-2">Foto Perbaikan 2</th>
							<td>
								@if(isset($improvementPhotos[1]))
								<img src="{{ asset('uploads/improvements/' . $improvementPhotos[1]) }}"
									alt="Foto 2"
									class="img-thumbnail mb-1 preview-img"
									style="max-height: 150px; cursor:pointer;"
									data-bs-toggle="modal"
									data-bs-target="#imageModal"
									data-src="{{ asset('uploads/improvements/' . $improvementPhotos[1]) }}">
								@endif
							</td>
							<td class="col-1 text-center"></td>
						</tr>
						
						{{-- Skor A --}}
						<tr>
							<th class="col-2">Skor A</th>
							<td id="value-Score_A_Suggestion">{{ $suggestion->score_a_formatted }}</td>
							<td class="col-1 text-center">
								<button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalScoreA">
									<i class="material-icons-two-tone" style="font-size:16px;">edit</i>
								</button>
							</td>
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
							<td class="col-1 text-center">
								<button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalScoreB">
									<i class="material-icons-two-tone" style="font-size:16px;">edit</i>
								</button>
							</td>
						</tr>

						{{-- Leader --}}
						<tr>
							<th class="col-2">Leader</th>
							<td id="value-Id_User">{{ $suggestion->user->Name_User ?? '' }}</td>
							<td class="col-1 text-center">
								<button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalLeader">
									<i class="material-icons-two-tone" style="font-size:16px;">edit</i>
								</button>
							</td>
						</tr>

						{{-- Komentar --}}
						<tr>
							<th class="col-2">Komentar</th>
							<td id="value-Comment_Suggestion">{{ $suggestion->Comment_Suggestion }}</td>
							<td class="col-1 text-center">
								<button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalComment">
									<i class="material-icons-two-tone" style="font-size:16px;">edit</i>
								</button>
							</td>
						</tr>

					</tbody>
				</table>
			</div>
		</div>
    </div>
</div>

<!-- Modal Preview -->
<div class="modal fade" id="imageModal" tabindex="-1">
	<div class="modal-dialog modal-lg modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-body d-flex justify-content-center align-items-center" style="overflow: hidden;">
				<div id="image-container" style="overflow: hidden; max-width: 100%; max-height: 80vh; position: relative; cursor: grab;">
					<img id="zoomImage" src="" 
					    style="max-width: 100%; max-height: 100%; transform-origin: center center; transition: transform 0.1s ease;">
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-sm btn-primary" id="zoomIn">+</button>
				<button class="btn btn-sm btn-secondary" id="zoomOut">-</button>
				<button class="btn btn-sm btn-danger" data-bs-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

{{-- Modal Skor A --}}
<div class="modal fade" id="modalScoreA" tabindex="-1">
    <div class="modal-dialog">
        <form class="ajaxUpdateForm" data-field="Score_A_Suggestion">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white">Edit Skor A</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <select name="value" class="form-control">
						<option value="1" {{ $suggestion->Score_A_Suggestion == 1 ? 'selected' : '' }}>1 = Rp 600 rb/tahun</option>
						<option value="2" {{ $suggestion->Score_A_Suggestion == 2 ? 'selected' : '' }}>2 = Rp 1200 rb/tahun</option>
						<option value="3" {{ $suggestion->Score_A_Suggestion == 3 ? 'selected' : '' }}>3 = Rp 3600 rb/tahun</option>
						<option value="4" {{ $suggestion->Score_A_Suggestion == 4 ? 'selected' : '' }}>4 = Rp 9000 rb/tahun</option>
						<option value="5" {{ $suggestion->Score_A_Suggestion == 5 ? 'selected' : '' }}>5 = Rp 15000 rb/tahun</option>
						<option value="6" {{ $suggestion->Score_A_Suggestion == 6 ? 'selected' : '' }}>6 = Rp 21000 rb/tahun</option>
						<option value="7" {{ $suggestion->Score_A_Suggestion == 7 ? 'selected' : '' }}>7 = Rp 30000 rb/tahun</option>
						<option value="8" {{ $suggestion->Score_A_Suggestion == 8 ? 'selected' : '' }}>8 = Rp 39000 rb/tahun</option>
						<option value="9" {{ $suggestion->Score_A_Suggestion == 9 ? 'selected' : '' }}>9 = Rp 48000 rb/tahun</option>
						<option value="10" {{ $suggestion->Score_A_Suggestion == 10 ? 'selected' : '' }}>10 = Rp 60000 rb/tahun</option>
						<option value="11" {{ $suggestion->Score_A_Suggestion == 11 ? 'selected' : '' }}>11 = Rp 72000 rb/tahun</option>
						<option value="12" {{ $suggestion->Score_A_Suggestion == 12 ? 'selected' : '' }}>12 = Rp 84000 rb/tahun</option>
						<option value="13" {{ $suggestion->Score_A_Suggestion == 13 ? 'selected' : '' }}>13 = Rp 96000 rb/tahun</option>
						<option value="14" {{ $suggestion->Score_A_Suggestion == 14 ? 'selected' : '' }}>14 = Rp 105000 rb/tahun</option>
						<option value="15" {{ $suggestion->Score_A_Suggestion == 15 ? 'selected' : '' }}>15 = Rp 129000 rb/tahun</option>
					</select>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Modal Skor B --}}
<div class="modal fade" id="modalScoreB" tabindex="-1">
    <div class="modal-dialog">
        <form class="ajaxUpdateForm" data-field="Score_B_Suggestion">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white">Edit Skor B</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row">
                    @php
                        $scoreB = $suggestion->score_b_formatted ?? [];
                    @endphp
                    <div class="col-4">
                        <label>Kreatifitas</label>
                        <select name="value[kreatifitas]" class="form-control">
                            @for($i=0;$i<=5;$i++)
                                <option value="{{ $i }}" {{ ($scoreB['Kreatifitas'] ?? 0) == $i ? 'selected':'' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-4">
                        <label>Ide</label>
                        <select name="value[ide]" class="form-control">
                            @for($i=0;$i<=5;$i++)
                                <option value="{{ $i }}" {{ ($scoreB['Ide'] ?? 0) == $i ? 'selected':'' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-4">
                        <label>Usaha</label>
                        <select name="value[usaha]" class="form-control">
                            @for($i=0;$i<=5;$i++)
                                <option value="{{ $i }}" {{ ($scoreB['Usaha'] ?? 0) == $i ? 'selected':'' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Modal Leader --}}
<div class="modal fade" id="modalLeader" tabindex="-1">
    <div class="modal-dialog">
        <form class="ajaxUpdateForm" data-field="Id_User">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white">Pilih Leader</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    {{-- Text readonly tampilkan nama user session --}}
                    <input type="text" class="form-control mb-2" 
                           value="{{ $user->Name_User }}" readonly>

                    {{-- Hidden Id_User sesuai session --}}
                    <input type="hidden" name="value" value="{{ $user->Id_User }}">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Modal Komentar --}}
<div class="modal fade" id="modalComment" tabindex="-1">
    <div class="modal-dialog">
        <form class="ajaxUpdateForm" data-field="Comment_Suggestion">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white">Edit Komentar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <textarea name="value" class="form-control" rows="4">{{ $suggestion->Comment_Suggestion }}</textarea>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Modal Status --}}
<div class="modal fade" id="modalStatus" tabindex="-1">
    <div class="modal-dialog">
        <form class="ajaxUpdateForm" data-field="Status_Suggestion">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white">Update Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <select name="value" class="form-control">
                        <option value="0" {{ $suggestion->Status_Suggestion == 0 ? 'selected' : '' }}>Belum Selesai</option>
                        <option value="1" {{ $suggestion->Status_Suggestion == 1 ? 'selected' : '' }}>Sudah Selesai</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Modal No Penerimaan Awal --}}
<div class="modal fade" id="modalAcceptanceFirst" tabindex="-1">
    <div class="modal-dialog">
        <form class="ajaxUpdateForm" data-field="Acceptance_First_Suggestion">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title text-white">Konfirmasi Penerimaan Awal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menerima usulan ini?</p>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Terima</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@section('style')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection


@section('script')
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>

<script>
$(document).ready(function () {
    $(".ajaxUpdateForm").on("submit", function (e) {
        e.preventDefault();

        let form = $(this);
        let field = form.data("field");
        let value = form.find("[name='value']").val();
        let url = "{{ route('leader.suggestion.updateField', $suggestion->Id_Suggestion) }}";

        // khusus Skor B: ambil object
        if (field === "Score_B_Suggestion") {
            value = {
                kreatifitas: form.find("[name='value[kreatifitas]']").val(),
                ide: form.find("[name='value[ide]']").val(),
                usaha: form.find("[name='value[usaha]']").val(),
            };
        }

        $.ajax({
            url: url,
            type: "POST",
            data: {
                _token: $('meta[name="csrf-token"]').attr("content"),
                field: field,
                value: value
            },
            success: function (res) {
                if (res.success) {
                    // update tampilan langsung di tabel
					if (field === "Score_A_Suggestion") {
						$("#value-Score_A_Suggestion").text(res.value); 
					}
					if (field === "Score_B_Suggestion") {
						let html = `<table class="table table-bordered table-sm"><tbody>`;
						$.each(res.value, function (label, val) {
							html += `<tr>
										<td class="col-2">${label === "Total" ? "<strong>"+label+"</strong>" : label}</td>
										<td>${label === "Total" ? "<strong>"+val+"</strong>" : val}</td>
									</tr>`;
						});
						html += `</tbody></table>`;
						$("#value-Score_B_Suggestion").html(html);
					}
                    if (field === "Id_User") {
						$("#value-Id_User").text(res.value);
					}
                    if (field === "Comment_Suggestion") {
                        $("#value-Comment_Suggestion").text(value);
                    }
                    if (field === "Status_Suggestion") {
						let badge = value == 1 
							? '<span class="badge bg-success">Sudah Selesai</span>'
							: '<span class="badge bg-warning text-dark">Belum Selesai</span>';
						$("#value-Status_Suggestion").html(badge);
					}
					if (field === "Acceptance_First_Suggestion") {
						$("#value-Acceptance_First_Suggestion").text(res.value);
						form.closest(".modal").modal("hide");
						// hapus tombol "Terima"
						$("#value-Acceptance_First_Suggestion").closest("tr").find("td:last").html("");
					}
                    alert("Data berhasil diperbarui!");
                    form.closest(".modal").modal("hide");
                } else {
                    alert(res.message);
                }
            },
            error: function (xhr) {
                alert("Gagal update data");
            }
        });
    });
});
</script>

<script>
let scale = 1;
let posX = 0, posY = 0;
let isDragging = false;
let startX, startY;

const img = document.getElementById("zoomImage");
const container = document.getElementById("image-container");

// Saat klik thumbnail
document.querySelectorAll(".preview-img").forEach(imgThumb => {
    imgThumb.addEventListener("click", function () {
        const modalImg = document.getElementById("zoomImage");
        modalImg.src = this.getAttribute("data-src"); // ambil dari data-src

        // reset posisi & scale setiap kali buka modal
        scale = 1;
        posX = 0;
        posY = 0;
        updateTransform();
    });
});

function updateTransform() {
  img.style.transform = `translate(${posX}px, ${posY}px) scale(${scale})`;
}

// Zoom In
document.getElementById("zoomIn").addEventListener("click", () => {
  scale += 0.2;
  updateTransform();
});

// Zoom Out
document.getElementById("zoomOut").addEventListener("click", () => {
  if (scale > 0.4) scale -= 0.2;
  updateTransform();
});

// Scroll zoom
container.addEventListener("wheel", (e) => {
  e.preventDefault();
  if (e.deltaY < 0) scale += 0.1; // zoom in
  else if (scale > 0.4) scale -= 0.1; // zoom out
  updateTransform();
});

// Drag (geser gambar kalau di zoom)
container.addEventListener("mousedown", (e) => {
  if (scale > 1) {
    isDragging = true;
    startX = e.clientX - posX;
    startY = e.clientY - posY;
    container.style.cursor = "grabbing";
  }
});
container.addEventListener("mouseup", () => {
  isDragging = false;
  container.style.cursor = "grab";
});
container.addEventListener("mouseleave", () => {
  isDragging = false;
  container.style.cursor = "grab";
});
container.addEventListener("mousemove", (e) => {
  if (isDragging) {
    posX = e.clientX - startX;
    posY = e.clientY - startY;

    // batasi biar tidak overflow dari modal
    const rect = container.getBoundingClientRect();
    const imgRect = img.getBoundingClientRect();
    const maxX = Math.max(0, (imgRect.width - rect.width) / 2);
    const maxY = Math.max(0, (imgRect.height - rect.height) / 2);

    posX = Math.min(maxX, Math.max(-maxX, posX));
    posY = Math.min(maxY, Math.max(-maxY, posY));

    updateTransform();
  }
});
</script>
@endsection
