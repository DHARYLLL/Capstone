{{-- filepath: resources/views/admin/businesses/knowledge-base.blade.php --}}
@php $isManager = isset($slug); @endphp
@if($isManager)
    @php
        if ($slug === 'villa-carmelita') { $unitName = 'Villa Carmelita'; $unitType = 'Accommodation / Hotel'; $unitIcon = '🏨'; $sidebarExtras = ['Room Availability', 'Rate Configuration', 'Guest Inquiries']; }
        elseif ($slug === 'monclaire-pool') { $unitName = 'Monclaire Pool'; $unitType = 'Facility / Pool'; $unitIcon = '🏊'; $sidebarExtras = ['Pool Schedule', 'Pass & Rental Rates', 'Guest Inquiries']; }
        else { $unitName = 'Dakong Balay'; $unitType = 'Food & Restaurant'; $unitIcon = '🍽️'; $sidebarExtras = ['Menu Management', 'Dining Availability', 'Guest Inquiries']; }
    @endphp
@endif

@extends($isManager ? 'layouts.manager' : 'layouts.admin')

@section('page_title', 'Knowledge Base Upload')
@section('page_description', 'Upload PDFs and CSVs, review processing results, and approve knowledge entries for chatbot use.')
@section('breadcrumbs', ($isManager ? $unitName . ' / ' : 'Admin / Businesses / ') . 'Knowledge Base')
@section('unit-type', $isManager ? $unitType : '')

@if($isManager)
@section('manager-sidebar')
    @include('partials.manager-sidebar')
@endsection
@endif

@section('content')
<div class="space-y-8">

    {{-- ── Page header ────────────────────────────────────────────────────── --}}
    <section class="rounded-[2rem] border border-[#eadfce] bg-white p-6 shadow-sm lg:p-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <h1 class="text-3xl font-black tracking-tight text-gray-900">Knowledge Base Upload</h1>
                <p class="mt-2 max-w-3xl text-sm leading-6 text-gray-500">
                    Upload PDFs and CSVs, review processing results, and approve knowledge entries for chatbot use.
                </p>
            </div>
            <div class="flex gap-3">
                {{-- Triggers the hidden file input --}}
                <button type="button" id="header-upload-btn"
                        class="btn rounded-full border-0 bg-[#4A3E3D] text-white hover:bg-[#3a2f2e]">
                    Upload files
                </button>
                <button type="button"
                        class="btn rounded-full border border-gray-300 bg-white text-gray-700 hover:bg-[#F4EEDF] hover:text-gray-700">
                    View logs
                </button>
            </div>
        </div>
    </section>

    {{-- ── Main grid ──────────────────────────────────────────────────────── --}}
    <section class="grid gap-6 xl:grid-cols-[1.3fr_1fr]">
        <div class="space-y-6">

            {{-- ── Drop zone ─────────────────────────────────────────────── --}}
            {{--
                HIDDEN FILE INPUT — positioned off-screen so it never affects layout.
                accept=".pdf,.csv" restricts the OS file picker to only PDF and CSV.
                id="kb-file-input" is the binding anchor for all trigger elements.
            --}}
            <input type="file" id="kb-file-input" accept=".pdf,.csv" class="sr-only" aria-label="Upload knowledge base file">

            {{--
                DROP ZONE CONTAINER — clicking anywhere on this element programmatically
                calls click() on #kb-file-input, opening the OS file picker.
                The dashed border signals interactivity; hover state deepens the border.
            --}}
            <div id="kb-drop-zone"
                 class="cursor-pointer rounded-[2rem] border-2 border-dashed border-[#d8c6b0] bg-[#FBF8F2] p-8 transition-colors hover:border-[#4A3E3D] hover:bg-[#F4EEE4]"
                 role="button" tabindex="0" aria-label="Click or drag to upload a file">
                <div class="flex flex-col items-center gap-3 text-center">
                    {{--
                        CENTRAL + ICON BUTTON — also a click trigger for the hidden input.
                        Styled as a deep warm brown rounded square per design system.
                    --}}
                    <div id="kb-plus-btn"
                         class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[#4A3E3D] text-white shadow-sm transition-transform hover:scale-105">
                        <span class="text-2xl font-black leading-none">+</span>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Drop PDF policy papers or CSV tables here</h2>
                        <p class="mt-1 text-sm text-gray-500">Supported: PDF, CSV · Max size: 25 MB per file</p>
                        <p class="mt-2 text-xs text-gray-400">Or click anywhere in this area to browse files</p>
                    </div>
                </div>
            </div>

            {{-- ── Status KPI cards ──────────────────────────────────────── --}}
            <div class="grid gap-4 md:grid-cols-3">
                <div class="card bg-base-100 shadow-sm">
                    <div class="card-body p-5">
                        <div class="text-sm text-gray-500">Queued</div>
                        <div class="mt-2 text-3xl font-black text-gray-900">4</div>
                        <p class="mt-2 text-sm text-gray-500">Waiting for processing</p>
                    </div>
                </div>
                <div class="card bg-base-100 shadow-sm">
                    <div class="card-body p-5">
                        <div class="text-sm text-gray-500">Processing</div>
                        <div class="mt-2 text-3xl font-black text-gray-900">2</div>
                        <p class="mt-2 text-sm text-gray-500">Indexing and validation</p>
                    </div>
                </div>
                <div class="card bg-base-100 shadow-sm">
                    <div class="card-body p-5">
                        <div class="text-sm text-gray-500">Indexed</div>
                        <div class="mt-2 text-3xl font-black text-gray-900">18</div>
                        <p class="mt-2 text-sm text-gray-500">Ready for assistant responses</p>
                    </div>
                </div>
            </div>

            {{-- ── Processing queue ──────────────────────────────────────── --}}
            <div class="card bg-base-100 shadow-sm">
                <div class="card-body p-6">
                    <h2 class="text-lg font-bold text-gray-900">Processing queue</h2>
                    <div class="mt-4 space-y-3">
                        <div class="rounded-2xl bg-[#FAF8F4] p-4">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <div class="font-semibold text-gray-800">Villa Carmelita policies.pdf</div>
                                    <div class="text-sm text-gray-500">PDF · 12 pages · Uploading</div>
                                </div>
                                <span class="inline-flex items-center rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700 ring-1 ring-amber-200">62%</span>
                            </div>
                            <div class="mt-3 h-2 rounded-full bg-gray-200">
                                <div class="h-2 w-[62%] rounded-full bg-[#4A3E3D]"></div>
                            </div>
                        </div>
                        <div class="rounded-2xl bg-[#FAF8F4] p-4">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <div class="font-semibold text-gray-800">Restaurant menu.csv</div>
                                    <div class="text-sm text-gray-500">CSV · 42 rows · Validated</div>
                                </div>
                                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-200">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                    Ready
                                </span>
                            </div>
                            <p class="mt-2 text-xs text-emerald-600">Columns mapped successfully · 2 duplicate rows skipped</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Right column ──────────────────────────────────────────────── --}}
        <div class="space-y-6">
            <div class="card bg-base-100 shadow-sm">
                <div class="card-body p-6">
                    <h2 class="text-lg font-bold text-gray-900">Validation log</h2>
                    <ul class="mt-4 space-y-3 text-sm text-gray-600">
                        <li class="rounded-2xl bg-[#F8F1E7] p-4">Policy file accepted · PDF structure validated</li>
                        <li class="rounded-2xl bg-[#F8F1E7] p-4">CSV preview generated · 12 columns matched</li>
                        <li class="rounded-2xl bg-[#F8F1E7] p-4">Knowledge index updated · Assistant responses refreshed</li>
                    </ul>
                </div>
            </div>

            <div class="card bg-base-100 shadow-sm">
                <div class="card-body p-6">
                    <h2 class="text-lg font-bold text-gray-900">File actions</h2>
                    <div class="mt-4 space-y-3 text-sm">
                        <button type="button" class="w-full rounded-2xl bg-[#FAF8F4] p-3 text-left text-gray-700 transition hover:bg-[#F0E8DE]">Preview extracted text</button>
                        <button type="button" class="w-full rounded-2xl bg-[#FAF8F4] p-3 text-left text-gray-700 transition hover:bg-[#F0E8DE]">Replace upload</button>
                        <button type="button" class="w-full rounded-2xl bg-[#FAF8F4] p-3 text-left text-gray-700 transition hover:bg-[#F0E8DE]">Mark approved</button>
                        <button type="button" class="w-full rounded-2xl bg-[#FAF8F4] p-3 text-left text-gray-700 transition hover:bg-[#F0E8DE]">Archive source file</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

{{-- ════════════════════════════════════════════════════════════════════════
     UPLOAD CONFIRMATION MODAL
     Hidden by default (hidden class). Revealed by JS after file selection.
     ════════════════════════════════════════════════════════════════════════ --}}
<div id="kb-upload-modal"
     class="fixed inset-0 z-50 hidden items-center justify-center p-4"
     role="dialog" aria-modal="true" aria-labelledby="modal-title">

    {{-- ── Translucent backdrop ──────────────────────────────────────────── --}}
    {{--
        Semi-opaque black overlay dims the entire dashboard behind the modal.
        Clicking it triggers "Cancel & Discard" behavior.
    --}}
    <div id="kb-modal-backdrop"
         class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>

    {{-- ── Modal card ───────────────────────────────────────────────────── --}}
    {{--
        Positioned above the backdrop with z-index via relative stacking.
        max-w-xl keeps it compact; rounded-3xl + shadow-2xl match the design system.
        max-h-[90vh] + overflow-y-auto ensures scroll on small screens.
    --}}
    <div class="relative w-full max-w-xl overflow-y-auto rounded-3xl bg-white shadow-2xl max-h-[90vh]">

        {{-- ── 1. FILE METADATA & VALIDATION HEADER ───────────────────── --}}
        {{--
            Top section on a slightly warm off-white background to distinguish it
            from the white modal body, echoing the card layering in the dashboard.
            Contains three elements:
              a) Document icon — visual anchor for file type
              b) File name + size text block — populated by JS from File.name / File.size
              c) Green success badge — validates the file is under 25 MB
        --}}
        <div class="rounded-t-3xl bg-[#FAF8F4] px-6 py-5 border-b border-[#eadfce]">
            <div class="flex items-start gap-4">
                {{-- File type icon --}}
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[#4A3E3D] text-white shadow-sm">
                    <svg viewBox="0 0 24 24" class="h-6 w-6 fill-current" aria-hidden="true">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zm-1 1.5L18.5 9H13V3.5zM6 20V4h5v7h7v9H6z"/>
                    </svg>
                </div>

                <div class="min-w-0 flex-1">
                    {{-- File name — truncated if long --}}
                    <p id="modal-file-name"
                       class="truncate text-base font-bold text-gray-900">
                        room_rates_v2.pdf
                    </p>
                    {{-- File size --}}
                    <p id="modal-file-size"
                       class="mt-0.5 text-sm text-gray-500">
                        2.4 MB
                    </p>
                    {{-- Size validation badge --}}
                    <span id="modal-size-badge"
                          class="mt-2 inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-200">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                        Within 25 MB limit — ready for processing
                    </span>
                </div>

                {{-- Close (X) button --}}
                <button type="button" id="kb-modal-close"
                        class="ml-2 flex h-8 w-8 shrink-0 items-center justify-center rounded-full text-gray-400 hover:bg-gray-100 hover:text-gray-600"
                        aria-label="Close modal">
                    <svg viewBox="0 0 24 24" class="h-5 w-5 fill-current"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
                </button>
            </div>
        </div>

        {{-- ── MODAL BODY ───────────────────────────────────────────────── --}}
        <div class="space-y-5 px-6 py-5">

            {{-- ── 2. DYNAMIC DATA PREVIEW CONTAINER ───────────────────── --}}
            {{--
                Scrollable text window (max-h fixed) showing the first extracted
                content from the file. Gives the admin a sanity check that the
                parser is reading their document cleanly before indexing begins.
                Content is populated by the JS FileReader API.
                Font is monospace to signal raw/parsed data rather than formatted UI.
            --}}
            <div>
                <div class="mb-2 flex items-center justify-between">
                    <h3 id="modal-title" class="text-sm font-semibold text-gray-700">Content preview</h3>
                    <span class="text-xs text-gray-400">First 500 characters extracted</span>
                </div>
                <div id="modal-preview-box"
                     class="h-36 overflow-y-auto rounded-2xl border border-[#eadfce] bg-[#FAF8F4] p-4 font-mono text-xs leading-5 text-gray-600 whitespace-pre-wrap">
                    Parsing file content…
                </div>
            </div>

            {{-- ── 3. BRANCH ROUTING DROP-DOWN ─────────────────────────── --}}
            {{--
                Mandatory field. Requires the admin to tag which operational
                branch this knowledge belongs to. Prevents cross-contamination
                in the vector database where the chatbot retrieves context.
                Options map to the three business units in Project RED AI.
            --}}
            <div>
                <label for="modal-branch" class="mb-1.5 block text-sm font-semibold text-gray-700">
                    Which branch does this knowledge belong to?
                    <span class="text-rose-500" aria-label="required">*</span>
                </label>
                <p class="mb-2 text-xs text-gray-400">
                    This tags the file so the AI assistant only uses it when answering queries for the correct business unit.
                </p>
                <select id="modal-branch"
                        class="select select-bordered w-full rounded-2xl border-[#d8c6b0] bg-white text-sm text-gray-800 focus:border-[#4A3E3D] focus:outline-none">
                    <option value="" disabled selected>Select an operational branch…</option>
                    <option value="accommodation">🏨 Accommodation / Hotel — Villa Carmelita</option>
                    <option value="restaurant">🍽️ Food &amp; Restaurant — Dakong Balay</option>
                    <option value="facility">🏊 Facility / Pool — Monclaire Pool</option>
                </select>
                {{-- Inline validation hint shown only when submit attempted without selection --}}
                <p id="modal-branch-error" class="mt-1.5 hidden text-xs text-rose-500">
                    Please select a branch before processing.
                </p>
            </div>

        </div>

        {{-- ── 4. ACTION FOOTER ─────────────────────────────────────────── --}}
        {{--
            Sticky footer row. Right-aligned per convention.
            Cancel button: light border, transparent fill — low visual weight.
            Confirm button: deep warm brown fill (#4A3E3D), bold white text, pill shape.
        --}}
        <div class="flex items-center justify-end gap-3 border-t border-[#eadfce] px-6 py-4 rounded-b-3xl bg-white">
            <button type="button" id="kb-modal-cancel"
                    class="btn rounded-full border border-gray-300 bg-white px-5 text-sm text-gray-600 hover:bg-gray-50 hover:text-gray-800">
                Cancel &amp; Discard
            </button>
            <button type="button" id="kb-modal-confirm"
                    class="btn rounded-full border-0 bg-[#4A3E3D] px-6 text-sm font-bold text-white shadow-sm hover:bg-[#3a2f2e]">
                Confirm &amp; Process Knowledge
            </button>
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════════════════════════════════
     SUCCESS TOAST — shown after confirming upload
     ════════════════════════════════════════════════════════════════════════ --}}
<div id="kb-success-toast"
     class="fixed bottom-6 right-6 z-[60] hidden max-w-sm rounded-2xl border border-emerald-200 bg-white p-4 shadow-xl">
    <div class="flex items-start gap-3">
        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
            <svg viewBox="0 0 24 24" class="h-5 w-5 fill-current"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
        </div>
        <div>
            <p class="text-sm font-bold text-gray-900">Knowledge file queued</p>
            <p id="toast-file-name" class="mt-0.5 text-xs text-gray-500">Your file has been submitted for indexing.</p>
        </div>
        <button type="button" id="kb-toast-close" class="ml-auto text-gray-400 hover:text-gray-600">
            <svg viewBox="0 0 24 24" class="h-4 w-4 fill-current"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
        </button>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const fileInput     = document.getElementById('kb-file-input');
    const dropZone      = document.getElementById('kb-drop-zone');
    const plusBtn       = document.getElementById('kb-plus-btn');
    const headerBtn     = document.getElementById('header-upload-btn');
    const modal         = document.getElementById('kb-upload-modal');
    const backdrop      = document.getElementById('kb-modal-backdrop');
    const modalClose    = document.getElementById('kb-modal-close');
    const modalCancel   = document.getElementById('kb-modal-cancel');
    const modalConfirm  = document.getElementById('kb-modal-confirm');
    const modalFileName = document.getElementById('modal-file-name');
    const modalFileSize = document.getElementById('modal-file-size');
    const modalSizeBadge = document.getElementById('modal-size-badge');
    const previewBox    = document.getElementById('modal-preview-box');
    const branchSelect  = document.getElementById('modal-branch');
    const branchError   = document.getElementById('modal-branch-error');
    const successToast  = document.getElementById('kb-success-toast');
    const toastFileName = document.getElementById('toast-file-name');
    const toastClose    = document.getElementById('kb-toast-close');

    // ── Trigger helpers ─────────────────────────────────────────────────────
    const openFilePicker = () => fileInput.click();

    dropZone.addEventListener('click',   openFilePicker);
    headerBtn.addEventListener('click',  openFilePicker);

    // Keyboard accessibility for drop zone
    dropZone.addEventListener('keydown', e => {
        if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); openFilePicker(); }
    });

    // ── Drag-and-drop visual feedback ───────────────────────────────────────
    dropZone.addEventListener('dragover',  e => { e.preventDefault(); dropZone.classList.add('border-[#4A3E3D]', 'bg-[#F4EEE4]'); });
    dropZone.addEventListener('dragleave', ()  => { dropZone.classList.remove('border-[#4A3E3D]', 'bg-[#F4EEE4]'); });
    dropZone.addEventListener('drop', e => {
        e.preventDefault();
        dropZone.classList.remove('border-[#4A3E3D]', 'bg-[#F4EEE4]');
        const file = e.dataTransfer.files[0];
        if (file) handleFile(file);
    });

    // ── File input change ───────────────────────────────────────────────────
    fileInput.addEventListener('change', () => {
        if (fileInput.files.length) handleFile(fileInput.files[0]);
    });

    // ── Process selected file ───────────────────────────────────────────────
    function handleFile(file) {
        const maxBytes = 25 * 1024 * 1024; // 25 MB

        // Populate metadata header
        modalFileName.textContent = file.name;
        modalFileSize.textContent = formatBytes(file.size);

        // Size validation badge
        if (file.size > maxBytes) {
            modalSizeBadge.className = 'mt-2 inline-flex items-center gap-1.5 rounded-full bg-rose-50 px-3 py-1 text-xs font-semibold text-rose-700 ring-1 ring-rose-200';
            modalSizeBadge.innerHTML = '<span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span> Exceeds 25 MB limit — please reduce file size';
            modalConfirm.disabled = true;
            modalConfirm.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            modalSizeBadge.className = 'mt-2 inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-200';
            modalSizeBadge.innerHTML = '<span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Within 25 MB limit — ready for processing';
            modalConfirm.disabled = false;
            modalConfirm.classList.remove('opacity-50', 'cursor-not-allowed');
        }

        // Reset branch selection
        branchSelect.value = '';
        branchError.classList.add('hidden');

        // Read and preview file content
        previewBox.textContent = 'Reading file…';
        const reader = new FileReader();
        reader.onload = e => {
            const raw = e.target.result;
            // Show first 500 chars of text content as a parsing confirmation
            previewBox.textContent = typeof raw === 'string'
                ? (raw.substring(0, 500) + (raw.length > 500 ? '\n\n… (truncated for preview)' : ''))
                : '[Binary file — text extraction will occur during processing]';
        };
        reader.onerror = () => { previewBox.textContent = 'Could not read file for preview.'; };

        if (file.type === 'text/csv' || file.name.endsWith('.csv')) {
            reader.readAsText(file);
        } else {
            // PDFs are binary — show a descriptive placeholder instead of garbage
            previewBox.textContent = `PDF file detected: "${file.name}"\n\nFull text extraction, page segmentation, and vectorisation will occur during the processing stage.\n\nPage count and structure will be confirmed once indexing begins.`;
        }

        // Show modal
        openModal();
    }

    // ── Modal open / close ──────────────────────────────────────────────────
    function openModal() {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
    }

    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
        fileInput.value = ''; // allow re-selecting same file
    }

    modalClose.addEventListener('click',   closeModal);
    modalCancel.addEventListener('click',  closeModal);
    backdrop.addEventListener('click',     closeModal);

    // ── Confirm & process ───────────────────────────────────────────────────
    modalConfirm.addEventListener('click', () => {
        // Validate branch selection
        if (!branchSelect.value) {
            branchError.classList.remove('hidden');
            branchSelect.focus();
            return;
        }
        branchError.classList.add('hidden');

        const fileName = modalFileName.textContent;

        // Close modal
        closeModal();

        // Show success toast
        toastFileName.textContent = `"${fileName}" has been queued for indexing.`;
        successToast.classList.remove('hidden');
        setTimeout(() => successToast.classList.add('hidden'), 5000);
    });

    toastClose.addEventListener('click', () => successToast.classList.add('hidden'));

    // ── Utility ─────────────────────────────────────────────────────────────
    function formatBytes(bytes) {
        if (bytes < 1024)        return bytes + ' B';
        if (bytes < 1048576)     return (bytes / 1024).toFixed(1) + ' KB';
        return (bytes / 1048576).toFixed(2) + ' MB';
    }
});
</script>
@endsection
