@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow-sm border-0 rounded-lg">
                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
                            <h2 class="mb-0 font-weight-bold" style="font-family: 'Poppins', sans-serif;">Tambah URL {{ $subTopic->title }}</h2>
                        </div>
                        <div class="card-body p-4">
                            @if(session('success'))
                                <div class="alert alert-success mb-4 rounded" style="font-family: 'Poppins', sans-serif;">{{ session('success') }}</div>
                            @endif

                            <form action="{{ route('url.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                                @csrf

                                <input type="hidden" name="sub_topic_id" value="{{ $subTopic->id }}">

                                <div class="row g-4">
                                    <div class="col-12 col-md-6">
                                        <label for="name" class="form-label font-weight-medium" style="font-family: 'Poppins', sans-serif; color: #3F4254;">Nama URL</label>
                                        <input type="text" name="name" id="name" class="form-control rounded" style="border-color: #E4E6EF;" required>
                                        <div class="invalid-feedback" style="font-family: 'Poppins', sans-serif;">Nama URL wajib diisi.</div>
                                    </div>
                                    
                                    <div class="col-12 col-md-6">
                                        <label for="url_link" class="form-label font-weight-medium" style="font-family: 'Poppins', sans-serif; color: #3F4254;">Link URL</label>
                                        <input type="url" name="url_link" id="url_link" class="form-control rounded" style="border-color: #E4E6EF;" placeholder="https://example.com" required>
                                        <div class="invalid-feedback" style="font-family: 'Poppins', sans-serif;">Link URL wajib diisi dan harus valid.</div>
                                    </div>
                                    
                                    <div class="col-12">
                                        <label for="description" class="form-label font-weight-medium" style="font-family: 'Poppins', sans-serif; color: #3F4254;">Deskripsi</label>
                                        <textarea name="description" id="description" rows="4" class="form-control rounded" style="border-color: #E4E6EF;" required></textarea>
                                        <div class="invalid-feedback" style="font-family: 'Poppins', sans-serif;">Deskripsi wajib diisi.</div>
                                    </div>
                                    
                                    <div class="col-12 col-md-6">
                                        <label for="learning_style_id" class="form-label font-weight-medium" style="font-family: 'Poppins', sans-serif; color: #3F4254;">Learning Style</label>
                                        <select name="learning_style_id" id="learning_style_id" class="form-control rounded" style="border-color: #E4E6EF;" required>
                                            <option value="">-- Pilih Learning Style --</option>
                                            @foreach($learningStyles as $style)
                                                <option value="{{ $style->id }}">{{ $style->style_name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback" style="font-family: 'Poppins', sans-serif;">Learning Style wajib dipilih.</div>
                                    </div>

                                    <!-- Restrict Access Section -->
                                    <div class="col-12 mt-4">
                                        <div class="card border-0 shadow-sm mb-4">
                                            <div class="card-header bg-light d-flex justify-content-between align-items-center py-3">
                                                <h4 class="mb-0 font-weight-bold" style="font-family: 'Poppins', sans-serif;">Restrict Access</h4>
                                                <button type="button" class="btn btn-sm btn-outline-primary" id="addRestrictionBtn" style="font-family: 'Poppins', sans-serif;">
                                                    <i class="fas fa-plus me-1"></i> Add restriction...
                                                </button>
                                            </div>
                                            <div class="card-body p-0">
                                                <div class="list-group list-group-flush" id="restrictionsList">
                                                    <!-- Restrictions will be added here dynamically -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 mt-4">
                                        <button type="submit" class="btn btn-primary px-4 py-2 font-weight-medium" style="font-family: 'Poppins', sans-serif; background-color: #3699FF; border-color: #3699FF;">
                                            <i class="fas fa-save me-1"></i> Buat URL
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

    <!-- Restriction Options Modal -->
    <div class="modal fade" id="restrictionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="font-family: 'Poppins', sans-serif;">Add restriction...</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item border-0 px-0 py-2 d-flex align-items-center restriction-option" data-type="date">
                            <i class="fas fa-calendar-alt me-3 text-muted"></i>
                            <div>
                                <h6 class="mb-0" style="font-family: 'Poppins', sans-serif;">Date</h6>
                                <p class="mb-0 text-muted small" style="font-family: 'Poppins', sans-serif;">Prevent access until (or from) a specified date and time.</p>
                            </div>
                        </li>
                        <li class="list-group-item border-0 px-0 py-2 d-flex align-items-center restriction-option" data-type="grade">
                            <i class="fas fa-star me-3 text-muted"></i>
                            <div>
                                <h6 class="mb-0" style="font-family: 'Poppins', sans-serif;">Grade</h6>
                                <p class="mb-0 text-muted small" style="font-family: 'Poppins', sans-serif;">Require students to achieve a specified grade.</p>
                            </div>
                        </li>
                        <li class="list-group-item border-0 px-0 py-2 d-flex align-items-center restriction-option" data-type="group">
                            <i class="fas fa-users me-3 text-muted"></i>
                            <div>
                                <h6 class="mb-0" style="font-family: 'Poppins', sans-serif;">Group</h6>
                                <p class="mb-0 text-muted small" style="font-family: 'Poppins', sans-serif;">Allow only students who belong to a specified group, or all groups.</p>
                            </div>
                        </li>
                        <li class="list-group-item border-0 px-0 py-2 d-flex align-items-center restriction-option" data-type="profile">
                            <i class="fas fa-user me-3 text-muted"></i>
                            <div>
                                <h6 class="mb-0" style="font-family: 'Poppins', sans-serif;">User profile</h6>
                                <p class="mb-0 text-muted small" style="font-family: 'Poppins', sans-serif;">Control access based on fields within the student's profile.</p>
                            </div>
                        </li>
                        <li class="list-group-item border-0 px-0 py-2 d-flex align-items-center restriction-option" data-type="set">
                            <i class="fas fa-layer-group me-3 text-muted"></i>
                            <div>
                                <h6 class="mb-0" style="font-family: 'Poppins', sans-serif;">Restriction set</h6>
                                <p class="mb-0 text-muted small" style="font-family: 'Poppins', sans-serif;">Add a set of nested restrictions to apply complex logic.</p>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="font-family: 'Poppins', sans-serif;">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Load required libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
    <script src="https://cdn.tiny.cloud/1/yfqawquyfm7j3if4r87pex17imhoo6xmc04b5yg0j9pafsk0/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize TinyMCE
            if (typeof tinymce === 'undefined') {
                console.error('TinyMCE tidak dimuat!');
            } else {
                tinymce.init({
                    selector: '#description',
                    plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange formatpainter pageembed linkchecker a11ychecker tinymcespellchecker permanentpen powerpaste advtable advcode editimage advtemplate ai mentions tinycomments tableofcontents footnotes mergetags autocorrect typography inlinecss markdown',
                    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
                    tinycomments_mode: 'embedded',
                    tinycomments_author: 'Author name',
                    mergetags_list: [
                        { value: 'First.Name', title: 'First Name' },
                        { value: 'Email', title: 'Email' },
                    ],
                    ai_request: (request, respondWith) => respondWith.string(() => Promise.reject('See docs to implement AI Assistant')),
                    setup: function (editor) {
                        editor.on('init', function () {
                            console.log('TinyMCE berhasil diinisialisasi untuk #description');
                        });
                        editor.on('error', function (e) {
                            console.error('TinyMCE error:', e);
                        });
                    }
                });
            }

            // Initialize restriction modal
            const restrictionModal = new bootstrap.Modal(document.getElementById('restrictionModal'));
            
            // Add restriction button click handler
            document.getElementById('addRestrictionBtn').addEventListener('click', function(e) {
                e.preventDefault();
                restrictionModal.show();
            });

            // Handle restriction option selection using event delegation
            document.addEventListener('click', function(e) {
                // Handle restriction option click
                if (e.target.closest('.restriction-option')) {
                    const option = e.target.closest('.restriction-option');
                    const type = option.getAttribute('data-type');
                    addRestriction(type);
                    restrictionModal.hide();
                }
                
                // Handle remove restriction button click
                if (e.target.closest('.remove-restriction')) {
                    e.preventDefault();
                    e.target.closest('.restriction-item').remove();
                }
            });

            // Function to add a restriction to the form
            function addRestriction(type) {
                let restrictionHtml = '';
                const timestamp = Date.now(); // Unique identifier for each restriction
                
                switch(type) {
                    case 'date':
                        restrictionHtml = `
                            <div class="list-group-item border-0 px-0 py-3 restriction-item" data-type="${type}">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0" style="font-family: 'Poppins', sans-serif;">
                                        <i class="fas fa-calendar-alt me-2 text-primary"></i>Date restriction
                                    </h6>
                                    <button type="button" class="btn btn-sm btn-outline-danger remove-restriction">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label small" style="font-family: 'Poppins', sans-serif;">From</label>
                                        <input type="datetime-local" name="restrictions[${timestamp}][type]" value="${type}" hidden>
                                        <input type="datetime-local" name="restrictions[${timestamp}][from]" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small" style="font-family: 'Poppins', sans-serif;">Until</label>
                                        <input type="datetime-local" name="restrictions[${timestamp}][until]" class="form-control form-control-sm">
                                    </div>
                                </div>
                            </div>
                        `;
                        break;
                        
                    case 'grade':
                        restrictionHtml = `
                            <div class="list-group-item border-0 px-0 py-3 restriction-item" data-type="${type}">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0" style="font-family: 'Poppins', sans-serif;">
                                        <i class="fas fa-star me-2 text-primary"></i>Grade restriction
                                    </h6>
                                    <button type="button" class="btn btn-sm btn-outline-danger remove-restriction">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label small" style="font-family: 'Poppins', sans-serif;">Minimum grade</label>
                                        <input type="hidden" name="restrictions[${timestamp}][type]" value="${type}">
                                        <input type="number" min="0" max="100" name="restrictions[${timestamp}][min_grade]" class="form-control form-control-sm" placeholder="0-100">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small" style="font-family: 'Poppins', sans-serif;">Activity</label>
                                        <input type="text" name="restrictions[${timestamp}][activity]" class="form-control form-control-sm" placeholder="Activity name">
                                    </div>
                                </div>
                            </div>
                        `;
                        break;
                        
                    case 'group':
                        restrictionHtml = `
                            <div class="list-group-item border-0 px-0 py-3 restriction-item" data-type="${type}">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0" style="font-family: 'Poppins', sans-serif;">
                                        <i class="fas fa-users me-2 text-primary"></i>Group restriction
                                    </h6>
                                    <button type="button" class="btn btn-sm btn-outline-danger remove-restriction">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label class="form-label small" style="font-family: 'Poppins', sans-serif;">Select group</label>
                                        <input type="hidden" name="restrictions[${timestamp}][type]" value="${type}">
                                        <select name="restrictions[${timestamp}][group_id]" class="form-control form-control-sm">
                                            <option value="">-- Select group --</option>
                                            @if(isset($groups) && count($groups) > 0)
                                                @foreach($groups as $group)
                                                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                        `;
                        break;
                        
                    case 'profile':
                        restrictionHtml = `
                            <div class="list-group-item border-0 px-0 py-3 restriction-item" data-type="${type}">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0" style="font-family: 'Poppins', sans-serif;">
                                        <i class="fas fa-user me-2 text-primary"></i>Profile restriction
                                    </h6>
                                    <button type="button" class="btn btn-sm btn-outline-danger remove-restriction">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label small" style="font-family: 'Poppins', sans-serif;">Field</label>
                                        <input type="hidden" name="restrictions[${timestamp}][type]" value="${type}">
                                        <select name="restrictions[${timestamp}][field]" class="form-control form-control-sm">
                                            <option value="department">Department</option>
                                            <option value="year">Year</option>
                                            <option value="major">Major</option>
                                            <option value="status">Status</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small" style="font-family: 'Poppins', sans-serif;">Value</label>
                                        <input type="text" name="restrictions[${timestamp}][value]" class="form-control form-control-sm">
                                    </div>
                                </div>
                            </div>
                        `;
                        break;
                        
                    case 'set':
                        restrictionHtml = `
                            <div class="list-group-item border-0 px-0 py-3 restriction-item" data-type="${type}">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0" style="font-family: 'Poppins', sans-serif;">
                                        <i class="fas fa-layer-group me-2 text-primary"></i>Restriction set
                                    </h6>
                                    <button type="button" class="btn btn-sm btn-outline-danger remove-restriction">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <input type="hidden" name="restrictions[${timestamp}][type]" value="${type}">
                                <p class="small text-muted mb-3" style="font-family: 'Poppins', sans-serif;">
                                    Add multiple restrictions below to create complex access rules.
                                </p>
                                <div class="restriction-set-inner" data-set-id="${timestamp}">
                                    <!-- Nested restrictions will be added here -->
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary mt-2 add-nested-restriction" data-set-id="${timestamp}">
                                    <i class="fas fa-plus me-1"></i> Add restriction
                                </button>
                            </div>
                        `;
                        break;
                }
                
                const restrictionElement = document.createElement('div');
                restrictionElement.innerHTML = restrictionHtml;
                document.getElementById('restrictionsList').appendChild(restrictionElement.firstChild);
            }

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
    </script>
@endsection