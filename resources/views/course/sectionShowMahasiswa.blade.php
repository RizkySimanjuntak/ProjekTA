<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="container">

        <!-- Course Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h1>{{ $course->full_name }}</h1>
            </div>
            <div class="card-body">
                <p class="card-text">{{ $course->summary }}</p>
                <p class="card-text">{{ $course->cpmk }}</p>
            </div>
        </div>

        <!-- Section Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>{{ $section->title }}</h5>
            </div>
            <div class="card-body">
                <p class="card-text">{{ $section->description }}</p>
                <p class="card-text">{{ $section->sub_cpmk }}</p>
            </div>
        </div>

        <!-- Subtopic Progress Tracker -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Subtopic Progress</span>
                <div class="progress" style="width: 200px; height: 20px;">
                    <div class="progress-bar" role="progressbar" style="width: 0%;"
                         aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                </div>
            </div>
        </div>

        <!-- Subtopic Content (will be loaded sequentially) -->
        <div id="subtopic-container">
            @if($section->sub_topic->count() > 0)
                <!-- First subtopic is always visible -->
                <div class="subtopic-item" data-subtopic-id="{{ $section->sub_topic[0]->id }}" data-sequence="1">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6>Sub Materi 1: {{ $section->sub_topic[0]->title }}</h6>
                            <div class="form-check">
                                <input class="form-check-input subtopic-checkbox" type="checkbox"
                                       id="subtopic-check-{{ $section->sub_topic[0]->id }}"
                                       data-next-subtopic="{{ $section->sub_topic[1]->id ?? '' }}">
                                <label class="form-check-label" for="subtopic-check-{{ $section->sub_topic[0]->id }}">
                                    Mark as Complete
                                </label>
                            </div>
                        </div>
                        <div class="card-body">
                            {{ $section->sub_topic[0]->content }}
                            @if($section->sub_topic[0]->labels->count() > 0)
                                <div class="card-body">
                                    <ul class="list-group">
                                        @foreach($section->sub_topic[0]->labels as $label)
                                            <li class="list-group-item">{!! $label->konten !!}</li>
                                        @endforeach
                                        @foreach($section->sub_topic[0]->files as $file)
                                            <li class="list-group-item">
                                                <a href="{{ Storage::url('files/'.basename($file->file_path)) }}" target="_blank">
                                                    <i class="fas
                                                            @if(Str::endsWith($file->file_path, '.pdf')) fa-file-pdf
                                                            @elseif(Str::endsWith($file->file_path, ['.doc', '.docx'])) fa-file-word
                                                            @elseif(Str::endsWith($file->file_path, ['.ppt', '.pptx'])) fa-file-powerpoint
                                                            @elseif(Str::endsWith($file->file_path, ['.jpg', '.jpeg', '.png'])) fa-file-image
                                                            @else fa-file-alt @endif
                                                            me-2"></i>
                                                    {{ $file->name }}
                                                </a>
                                            </li>
                                        @endforeach
                                        @foreach($section->sub_topic[0]->infografis as $infografis)
                                            <a href="{{ asset('storage/' . $infografis->file_path) }}" target="_blank">
                                                <i class="bi bi-file-earmark-text me-2"></i> {{ $infografis->name }}
                                            </a>
                                        @endforeach
                                            @foreach($section->sub_topic[0]->assignments as $assignment)
                                                <li class="list-group-item">
                                                    <strong>{!! $assignment->name !!}</strong><br>
                                                    {!! $assignment->description !!}

                                                    @if($assignment->due_date)
                                                        <div class="mt-2">
                                                                <span class="badge bg-warning text-dark p-2" style="font-size: 0.9rem;">
                                                                    <i class="fas fa-calendar-alt me-1"></i>
                                                                    Due: {{ \Carbon\Carbon::parse($assignment->due_date)->format('d M Y, H:i') }}
                                                                </span>
                                                        </div>
                                                    @endif
                                                </li>
                                            @endforeach
                                            @foreach($section->sub_topic[0]->forums as $forum)
                                                <li class="list-group-item">
                                                    <strong>{!! $forum->name !!}</strong><br>
                                                    {!! $forum->description !!}
                                                </li>
                                            @endforeach
                                            @foreach($section->sub_topic[0]->lessons as $lesson)
                                                <li class="list-group-item">
                                                    <strong>{!! $lesson->name !!}</strong><br>
                                                    {!! $lesson->description !!}
                                                </li>
                                            @endforeach
                                            @foreach($section->sub_topic[0]->urls as $url)
                                                <strong>{!! $url->name !!}</strong><br>
                                                <a href="{{ asset($url->url_link) }}" target="_blank">
                                                    {!! $url->url_link !!}
                                                </a>
                                            @endforeach
                                            @forelse($section->sub_topic[0]->pages as $page)
                                                <li class="list-group-item">
                                                    <a href="{{ route('pages.show', $page->id) }}"><i class="bi bi-file-text me-2"></i> {!! $page->name !!}</a>
                                                </li>
                                            @empty
                                                <li class="list-group-item">No pages available.</li>
                                            @endforelse
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Hidden subtopics (will be shown sequentially) -->
                @for($i = 1; $i < $section->sub_topic->count(); $i++)
                    <div class="subtopic-item d-none" data-subtopic-id="{{ $section->sub_topic[$i]->id }}" data-sequence="{{ $i+1 }}">
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6>Sub Materi {{ $i+1 }}: {{ $section->sub_topic[$i]->title }}</h6>
                                <div class="form-check">
                                    <input class="form-check-input subtopic-checkbox" type="checkbox"
                                           id="subtopic-check-{{ $section->sub_topic[$i]->id }}"
                                           data-next-subtopic="{{ $section->sub_topic[$i+1]->id ?? '' }}">
                                    <label class="form-check-label" for="subtopic-check-{{ $section->sub_topic[$i]->id }}">
                                        Mark as Complete
                                    </label>
                                </div>
                            </div>
                            <div class="card-body">
                                {{ $section->sub_topic[$i]->content }}
                                @if($section->sub_topic[$i]->labels->count() > 0)
                                    <div class="card-body">
                                        <ul class="list-group">
                                            @foreach($section->sub_topic[$i]->labels as $label)
                                                <li class="list-group-item">{!! $label->konten !!}</li>
                                            @endforeach
                                            @foreach($section->sub_topic[$i]->files as $file)
                                                <li class="list-group-item">
                                                    <a href="{{ Storage::url('files/'.basename($file->file_path)) }}" target="_blank">
                                                        <i class="fas
                                                                @if(Str::endsWith($file->file_path, '.pdf')) fa-file-pdf
                                                                @elseif(Str::endsWith($file->file_path, ['.doc', '.docx'])) fa-file-word
                                                                @elseif(Str::endsWith($file->file_path, ['.ppt', '.pptx'])) fa-file-powerpoint
                                                                @elseif(Str::endsWith($file->file_path, ['.jpg', '.jpeg', '.png'])) fa-file-image
                                                                @else fa-file-alt @endif
                                                                me-2"></i>
                                                        {{ $file->name }}
                                                    </a>
                                                </li>
                                            @endforeach
                                            @foreach($section->sub_topic[$i]->infografis as $infografis)
                                                <a href="{{ asset('storage/' . $infografis->file_path) }}" target="_blank">
                                                    <i class="bi bi-file-earmark-text me-2"></i> {{ $infografis->name }}
                                                </a>
                                            @endforeach
                                            @forelse($section->sub_topic[$i]->pages as $page)
                                                <li class="list-group-item">

                                                    <a href="{{ route('pages.show', $page->id) }}"><i class="bi bi-file-text me-2"></i> {!! $page->name !!}</a>

                                                </li>
                                            @empty
                                                <li class="list-group-item">No pages available.</li>
                                            @endforelse
                                            @forelse($section->sub_topic[$i]->assisgnments as $assignment)
                                                <li class="list-group-item">

                                                    <a href="{{ route('assisgnments.show', $assignment->id) }}"><i class="bi bi-file-text me-2"></i> {!! $assignment->name !!}</a>

                                                </li>
                                            @empty
                                                <li class="list-group-item">No pages available.</li>
                                            @endforelse
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endfor
            @endif
        </div>

        <!-- References -->
        @if($section->referensi->count() > 0)
            <div class="card mb-4">
                <div class="card-header">
                    References
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach($section->referensi as $referensi)
                            <li class="list-group-item">
                                <p class="mt-2">{{ $referensi->content }}</p>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <!-- Navigation Buttons -->
        <div class="mt-4">
            <a href="{{ route('courses.topics', $course->id) }}" class="btn btn-secondary">Back to Course</a>
            @can('update', $section)
                <a href="{{ route('sections.edit', [$course->id, $section->id]) }}" class="btn btn-primary">Edit Section</a>
            @endcan
        </div>
    </div>
</div>

<!-- JavaScript for Sequential Subtopic Display with Uncheck Feature -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const subtopicItems = document.querySelectorAll('.subtopic-item');
        const progressBar = document.querySelector('.progress-bar');
        const totalSubtopics = subtopicItems.length;
        let completedSubtopics = 0;
        let visibleSubtopics = 1; // Start with first subtopic visible

        // Initialize progress bar
        updateProgressBar();

        // Add event listeners to all checkboxes
        document.querySelectorAll('.subtopic-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const currentSubtopicId = this.closest('.subtopic-item').dataset.subtopicId;
                const currentSequence = parseInt(this.closest('.subtopic-item').dataset.sequence);
                const nextSubtopicId = this.dataset.nextSubtopic;

                if (this.checked) {
                    // Marking as complete
                    completedSubtopics++;

                    // Show next subtopic if exists
                    if (nextSubtopicId) {
                        const nextSubtopic = document.querySelector(.subtopic-item[data-subtopic-id="${nextSubtopicId}"]);
                        if (nextSubtopic) {
                            nextSubtopic.classList.add('animate_animated', 'animate_fadeIn');
                            setTimeout(() => {
                                nextSubtopic.classList.remove('d-none');
                                visibleSubtopics++;
                            }, 100);

                            // Scroll to next subtopic
                            setTimeout(() => {
                                nextSubtopic.scrollIntoView({ behavior: 'smooth', block: 'start' });
                            }, 500);
                        }
                    }
                } else {
                    // Unchecking
                    completedSubtopics--;

                    // Hide all subsequent subtopics if this was the last completed one
                    if (currentSequence === visibleSubtopics - 1) {
                        hideSubsequentSubtopics(currentSequence);
                    }
                }

                updateProgressBar();
            });
        });

        function updateProgressBar() {
            const progressPercentage = Math.round((completedSubtopics / totalSubtopics) * 100);
            progressBar.style.width = ${progressPercentage}%;
            progressBar.setAttribute('aria-valuenow', progressPercentage);
            progressBar.textContent = ${progressPercentage}%;

            // Change color based on progress
            if (progressPercentage < 30) {
                progressBar.classList.remove('bg-success', 'bg-warning');
                progressBar.classList.add('bg-danger');
            } else if (progressPercentage < 70) {
                progressBar.classList.remove('bg-danger', 'bg-success');
                progressBar.classList.add('bg-warning');
            } else {
                progressBar.classList.remove('bg-danger', 'bg-warning');
                progressBar.classList.add('bg-success');
            }
        }

        function hideSubsequentSubtopics(currentSequence) {
            const allSubtopics = document.querySelectorAll('.subtopic-item');
            allSubtopics.forEach(subtopic => {
                const sequence = parseInt(subtopic.dataset.sequence);
                if (sequence > currentSequence) {
                    subtopic.classList.add('d-none');
                    // Also uncheck any checked boxes in hidden subtopics
                    const checkbox = subtopic.querySelector('.subtopic-checkbox');
                    if (checkbox) checkbox.checked = false;
                }
            });
            visibleSubtopics = currentSequence;
        }
    });
</script>

<!-- Add Animate.css for animations -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<!-- Add some custom styles -->
<style>
    .subtopic-item {
        transition: all 0.3s ease;
    }

    .progress {
        background-color: #e9ecef;
        border-radius: 10px;
        overflow: hidden;
    }

    .progress-bar {
        transition: width 0.6s ease;
    }

    .card-header {
        background-color: #f8f9fa;
    }

    .form-check-input:checked {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }

    .subtopic-checkbox {
        transform: scale(1.3);
        cursor: pointer;
    }

    .animate__fadeIn {
        animation-duration: 0.5s;
    }
</style>