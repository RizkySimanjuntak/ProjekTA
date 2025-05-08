@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow-sm border-0 rounded-lg">
                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
                            <h2 class="mb-0 font-weight-bold" style="font-family: 'Poppins', sans-serif;">Tambah Infografis {{ $subTopic->title }} (Khusus Visual)</h2>
                        </div>
                        <div class="card-body p-4">
                            @if(session('success'))
                                <div class="alert alert-success mb-4 rounded" style="font-family: 'Poppins', sans-serif;">{{ session('success') }}</div>
                            @endif

                            @if($errors->any())
                                <div class="alert alert-danger mb-4 rounded" style="font-family: 'Poppins', sans-serif;">
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('infografis.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                                @csrf

                                <input type="hidden" name="sub_topic_id" value="{{ $subTopic->id }}">

                                <div class="row g-4">
                                    <!-- File Upload Area -->
                                    <div class="col-12">
                                        <label for="file_upload" class="form-label font-weight-medium" style="font-family: 'Poppins', sans-serif; color: #3F4254;">Upload Infografis</label>

                                        <div id="upload-area" class="file-upload-area p-4 rounded border text-center"
                                             style="border-color: #E4E6EF; border-style: dashed; min-height: 150px; display: flex; flex-direction: column; justify-content: center; align-items: center; cursor: pointer;"
                                             onclick="document.getElementById('file_upload').click()">
                                            <i class="fas fa-cloud-upload-alt mb-2" style="font-size: 2rem; color: #3699FF;"></i>
                                            <h5 class="mb-1" style="font-family: 'Poppins', sans-serif;">Klik atau tarik file ke sini</h5>
                                            <p class="mb-0 text-muted" style="font-family: 'Poppins', sans-serif;">Format: MP4, JPG, PNG, JPEG (Maks. 5MB)</p>
                                            <input type="file" name="file_upload[]" id="file_upload" class="d-none" onchange="validateAndPreviewFile(this)" accept=".mp4,.jpg,.jpeg,.png" multiple>
                                        </div>

                                        <!-- Preview for new files -->
                                        <div id="file-preview-container" class="mt-3"></div>

                                        <!-- Display for existing uploaded files -->
                                        @if(isset($uploadedInfografis) && count($uploadedInfografis) > 0)
                                            <div class="uploaded-files mt-3">
                                                <h6 style="font-family: 'Poppins', sans-serif;">Infografis Terupload:</h6>
                                                @foreach($uploadedInfografis as $infografis)
                                                    <div class="d-flex align-items-center justify-content-between bg-light rounded p-3 mb-2">
                                                        <div class="d-flex align-items-center">
                                                            <i class="fas {{ Str::contains($infografis->file_path, ['.mp4']) ? 'fa-file-video' : 'fa-file-image' }} mr-3" style="font-size: 1.5rem; color: #3699FF;"></i>
                                                            <div>
                                                                <div style="font-family: 'Poppins', sans-serif; font-weight: 500;">
                                                                    {{ basename($infografis->file_path) }}
                                                                </div>
                                                                <small class="text-muted" style="font-family: 'Poppins', sans-serif;">
                                                                    {{ $infografis->created_at->format('d M Y H:i') }}
                                                                </small>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <a href="{{ Storage::url($infografis->file_path) }}" target="_blank" class="btn btn-sm btn-primary mr-2">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            <button type="button" class="btn btn-sm btn-danger" onclick="deleteInfografis({{ $infografis->id }})">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif

                                        @error('file_upload')
                                        <div class="text-danger mt-2" style="font-family: 'Poppins', sans-serif; font-size: 0.875rem;">{{ $message }}</div>
                                        @enderror
                                        <div id="file-error-message" class="text-danger mt-2" style="font-family: 'Poppins', sans-serif; font-size: 0.875rem; display: none;"></div>
                                    </div>

                                    <!-- Learning Style Selection -->
                                    <div class="col-12 col-md-6">
                                        <label for="learning_style_id" class="form-label font-weight-medium" style="font-family: 'Poppins', sans-serif; color: #3F4254;">Learning Style</label>
                                        <select name="learning_style_id" id="learning_style_id" class="form-control rounded" style="border-color: #E4E6EF;" required>
                                            @foreach($learningStyles as $style)
                                                @if($style->id == 1) {{-- Hanya tampilkan visual (id=1) --}}
                                                <option value="{{ $style->id }}" selected>{{ $style->nama_opsi_dimensi }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback" style="font-family: 'Poppins', sans-serif;">Learning Style wajib dipilih.</div>
                                    </div>

                                    <div class="col-12 mt-4">
                                        <button type="submit" class="btn btn-primary px-4 py-2 font-weight-medium" style="font-family: 'Poppins', sans-serif; background-color: #3699FF; border-color: #3699FF;">
                                            <i class="fas fa-save mr-2"></i> Simpan Infografis
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Form validation
            (function () {
                'use strict';
                var forms = document.querySelectorAll('.needs-validation');
                Array.prototype.slice.call(forms).forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        if (!form.checkValidity()) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            })();
        });

        // Fungsi validasi tipe file
        function isValidFileType(file) {
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'video/mp4'];
            return validTypes.includes(file.type);
        }

        // Fungsi untuk validasi dan preview file baru
        function validateAndPreviewFile(input) {
            const previewContainer = document.getElementById('file-preview-container');
            const errorMessage = document.getElementById('file-error-message');
            previewContainer.innerHTML = '';
            errorMessage.style.display = 'none';

            if (input.files.length > 0) {
                const validFiles = [];
                const invalidFiles = [];

                // Filter file yang valid dan tidak valid
                Array.from(input.files).forEach(file => {
                    if (isValidFileType(file)) {
                        validFiles.push(file);
                    } else {
                        invalidFiles.push(file.name);
                    }
                });

                // Tampilkan pesan error jika ada file tidak valid
                if (invalidFiles.length > 0) {
                    errorMessage.textContent = 'File berikut tidak valid: ' + invalidFiles.join(', ') + '. Hanya file gambar (JPG, JPEG, PNG) dan video (MP4) yang diperbolehkan.';
                    errorMessage.style.display = 'block';

                    // Hanya pertahankan file yang valid
                    const dataTransfer = new DataTransfer();
                    validFiles.forEach(file => dataTransfer.items.add(file));
                    input.files = dataTransfer.files;

                    if (validFiles.length === 0) return;
                }

                // Tampilkan preview untuk file yang valid
                previewNewFiles(input);
            }
        }

        // Fungsi untuk menampilkan preview file baru
        function previewNewFiles(input) {
            const previewContainer = document.getElementById('file-preview-container');
            previewContainer.innerHTML = '';

            Array.from(input.files).forEach((file, index) => {
                const fileElement = document.createElement('div');
                fileElement.className = 'd-flex align-items-center justify-content-between bg-light rounded p-3 mb-2';

                fileElement.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="fas ${file.type.startsWith('image/') ? 'fa-file-image' : 'fa-file-video'} mr-3"
                       style="font-size: 1.5rem; color: #3699FF;"></i>
                    <div>
                        <div style="font-family: 'Poppins', sans-serif; font-weight: 500;">${file.name}</div>
                        <small class="text-muted" style="font-family: 'Poppins', sans-serif;">
                            ${(file.size/1024).toFixed(2)} KB | ${file.type.split('/')[1].toUpperCase()}
                        </small>
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-link text-danger" onclick="removeNewFile(this, ${index})">
                    <i class="fas fa-times"></i>
                </button>
            `;

                previewContainer.appendChild(fileElement);
            });
        }

        // Fungsi untuk menghapus file baru dari preview
        function removeNewFile(button, index) {
            const input = document.getElementById('file_upload');
            const dataTransfer = new DataTransfer();

            Array.from(input.files).forEach((file, i) => {
                if (i !== index) dataTransfer.items.add(file);
            });

            input.files = dataTransfer.files;
            previewNewFiles(input);
        }

        // Fungsi untuk menghapus infografis yang sudah diupload
        function deleteInfografis(infografisId) {
            if (confirm('Apakah Anda yakin ingin menghapus infografis ini?')) {
                fetch(`/infografis/${infografisId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload(); // Refresh halaman setelah penghapusan
                        } else {
                            alert('Gagal menghapus infografis: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat menghapus infografis');
                    });
            }
        }

        // Drag and drop functionality
        const uploadArea = document.getElementById('upload-area');

        ['dragenter', 'dragover'].forEach(event => {
            uploadArea.addEventListener(event, (e) => {
                e.preventDefault();
                e.stopPropagation();
                uploadArea.style.borderColor = '#3699FF';
                uploadArea.style.backgroundColor = 'rgba(54, 153, 255, 0.05)';
            });
        });

        ['dragleave', 'drop'].forEach(event => {
            uploadArea.addEventListener(event, (e) => {
                e.preventDefault();
                e.stopPropagation();
                uploadArea.style.borderColor = '#E4E6EF';
                uploadArea.style.backgroundColor = '';
            });
        });

        uploadArea.addEventListener('drop', (e) => {
            const input = document.getElementById('file_upload');
            const files = e.dataTransfer.files;

            // Validasi file yang di-drop
            const validFiles = Array.from(files).filter(file => isValidFileType(file));

            if (validFiles.length !== files.length) {
                const errorMessage = document.getElementById('file-error-message');
                errorMessage.textContent = 'Beberapa file tidak valid. Hanya file gambar (JPG, JPEG, PNG) dan video (MP4) yang diperbolehkan.';
                errorMessage.style.display = 'block';
            }

            // Buat DataTransfer baru hanya dengan file yang valid
            const dataTransfer = new DataTransfer();
            validFiles.forEach(file => dataTransfer.items.add(file));

            // Assign file yang valid ke input
            input.files = dataTransfer.files;

            // Tampilkan preview
            validateAndPreviewFile(input);
        });
    </script>
@endsection
