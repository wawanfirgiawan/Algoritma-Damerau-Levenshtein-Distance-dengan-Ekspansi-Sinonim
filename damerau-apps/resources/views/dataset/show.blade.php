@extends('layouts.app')
@section('content')
    <main id="main">
        <div class="container p-3" style="margin-top: 8rem">
            <div class="row">
                <div class="col-md-12">
                    <p class="mb-4 fs-2" style="color: #38527E"><a style="color: #38527E" href="{{ route('dataset.index') }}">
                            <i class="bi bi-arrow-left-short fs-2"></i></a>Detail Dataset</p>
                    <div class="card p-5 ">
                        <div class="row align-items-center" style="margin-top:-27px">
                            <div class="col-md-1" id="img-dataset">
                                <i class="fad fa-database fa-4x" style="color: #38527E"></i>
                            </div>
                            <div class="col-md-11 mb-2">
                                <a href="{{ url('detail') }}">
                                    <h2 class="mt-3 text-capitalize" style="color: #38527E">{{ $dataset->name }}</h2>
                                </a>
                                <p>Dibuat oleh : <span class="fw-bold"> {{ $dataset->user->full_name }}</span></p>

                            </div>
                            <div class="col-md-12">
                                <a href="{{ url('download/' . $id) }}" class="btn btn-sm mb-3 text-white p-2 ps-3 pe-3"
                                    style="background-color: #38527E"><i class="fal fa-download me-1"></i> Download</a>
                                <p>{{ $dataset->abstract }}</p>
                            </div>
                            <div class="col-md-3">
                                <h4>Karakteristik</h4>
                                <p>
                                    @if ($characteristics->count() > 0)
                                        @foreach ($characteristics as $characteristic)
                                            {{ $characteristic->name_characteristic }} @if (!$loop->last)
                                                ,
                                            @endif
                                        @endforeach
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-3">
                                <h4>Bidang Studi</h4>
                                <p>
                                    {{ $dataset->subjectArea->name_subject_area ?? '-' }}
                                </p>
                            </div>
                            <div class="col-md-3">
                                <h4>Tugas Terkait</h4>
                                <p>
                                    @if ($associatedTasks->count() > 0)
                                        @foreach ($associatedTasks as $associatedTask)
                                            {{ $associatedTask->name_associated_task }} @if (!$loop->last)
                                                ,
                                            @endif
                                        @endforeach
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-3">
                                <h4>Jenis Fitur</h4>
                                <p>
                                    @if ($featureTypes->count() > 0)
                                        @foreach ($featureTypes as $featureType)
                                            {{ $featureType->name_feature_type }}
                                        @endforeach
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-3">
                                <h4>Jumlah Baris</h4>
                                <p>{{ $dataset->instances ?? '-' }}</p>
                            </div>
                            <div class="col-md-3">
                                <h4>Jumlah Fitur</h4>
                                <p>{{ $dataset->features ?? '-' }}</p>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="card p-4">
                        <div class="card-header">
                            <p class="fs-2 mt-2" style="color: #38527E">Informasi Dataset</p>
                        </div>
                        <div class="card-body">
                            {!! $dataset->information !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="card p-4">
                        <div class="card-header">
                            <p class="fs-2 mt-2" style="color: #38527E">Paper Yang Berhubungan</p>
                        </div>
                        <div class="card-body">
                            @forelse ($papers as $paper)
                                <p class="fs-5"><a target="_blank" href="{{ url('' . $paper->url) }}"
                                        style="color: #38527E">{{ $paper->title }}</a></p>
                                <p style="margin-top: -17px">{{ $paper->description ?? '-' }}</p>
                                @empty
                                <p style="margin-bottom: -15px">Belum ada paper</p>
                            @endforelse
                        </div>
                        <div class="card-body">
                            <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal"
                                class="btn btn-sm p-2 text-white ps-3 pe-3 @guest
                                    disabled @endguest"
                                style="background-color: #38527E"><i class="fas fa-upload"></i> Upload Paper</a>
                        </div>
                    </div>
                </div>
            </div> 
        </div>
    </main>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title fs-3" style="color: #38527E" id="exampleModalLabel">Tambahkan Paper</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="text" placeholder="Judul" class="form-control" name="title" id="title">
                    <div class="invalid-feedback">
                        Harap masukkan judul paper!
                    </div>
                    <input type="url" class="form-control mt-3" placeholder="Link akses" name="url" id="url">
                    <div class="invalid-feedback">
                        Harap masukkan link akses paper!
                    </div>
                    <textarea name="description" id="description" cols="30" class="form-control mt-3" rows="5"
                        placeholder="Deskripsi"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" onclick="donate()" id="donate" class="btn text-white"
                        style="background-color: #38527E"><i class="fas fa-upload"></i> Upload</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        function donate() {
            document.getElementById('title').classList.remove('is-invalid')
            document.getElementById('url').classList.remove('is-invalid')

            let btnDonate = document.getElementById('donate')
            btnDonate.disabled = true
            let title = document.getElementById('title').value
            let description = document.getElementById('description').value
            let url = document.getElementById('url').value
            let formData = new FormData()
            formData.append('id_dataset', "{{ $id }}")
            formData.append('title', title)
            formData.append('description', description)
            formData.append('url', url)
            let csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            fetch('/donation/paper', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: formData,
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.status == 422) {
                        const keys = Object.keys(data.message);
                        keys.forEach(key => {
                            document.getElementById(`${key}`).classList.add('is-invalid')
                        });
                    } else {
                        document.getElementById('exampleModal').style.display = "none"
                        Swal.fire({
                            title: "Terimakasih!",
                            text: "Anda berhasil mengupload paper kedalam dataset ini.",
                            icon: "success",
                            allowOutsideClick: false
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload()
                            }
                        });
                    }
                    btnDonate.disabled = false
                })
                .catch(error => {
                    btnDonate.disabled = false
                });
        }
    </script>
@endsection
