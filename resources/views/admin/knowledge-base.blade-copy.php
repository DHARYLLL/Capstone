@extends('layouts.admin')

@section('page_title', 'Knowledge Base Upload')
@section('breadcrumbs', 'Admin / Knowledge Base')

@section('content')
    <div class="space-y-8">
        <style>
            .hide-scrollbar {
                -ms-overflow-style: none;
                scrollbar-width: none;
            }

            .hide-scrollbar::-webkit-scrollbar {
                display: none;
            }
        </style>

        {{-- ── Page header ────────────────────────────────────────────────────── --}}
        <section class="rounded-[2rem] border border-[#e2e8f0] bg-white p-6 shadow-sm lg:p-8">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <h1 class="text-3xl font-black tracking-tight text-gray-900">Knowledge Base Upload</h1>
                    <p class="mt-2 max-w-3xl text-sm leading-6 text-gray-500">
                        Upload PDFs and CSVs, review staged files, and approve knowledge entries for chatbot use.
                    </p>
                </div>
            </div>
            {{-- <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div class="rounded-2xl border border-white/10 bg-slate-950/50 p-4">
                    <h3 class="text-lg font-semibold">Upload PDF Knowledge</h3>
                    <p class="mt-1 text-sm text-slate-400">Use a small one-to-two page PDF for quick staging.</p>

                    <form method="POST" enctype="multipart/form-data" action="{{ route('admin.knowledge.upload-pdf', ['businessUnit' => $selectedBusinessUnit->id ?? 1]) }}" class="mt-4 space-y-4">
                        @csrf
                        <input type="file" name="pdf" accept="application/pdf" class="block w-full rounded-xl border border-dashed border-white/15 bg-slate-950 px-4 py-3 text-sm text-slate-300 file:mr-4 file:rounded-lg file:border-0 file:bg-cyan-400 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-slate-950 hover:file:bg-cyan-300">
                        @error('pdf')
                            <p class="text-sm text-rose-300">{{ $message }}</p>
                        @enderror
                        <button type="submit" class="rounded-xl bg-cyan-400 px-5 py-3 text-sm font-semibold text-slate-950 transition hover:bg-cyan-300">Upload and Extract</button>
                    </form>
                </div>
            </div> --}}
        </section>

        <div id="kb-processing-banner"
            class="hidden items-center gap-3 rounded-[1.5rem] border border-blue-200 bg-blue-50 px-5 py-4 text-sm font-medium text-blue-800 shadow-sm">
            <span class="h-4 w-4 animate-spin rounded-full border-2 border-blue-300 border-t-blue-700"></span>
            <span id="kb-processing-banner-text">Training AI bot and vectorizing parsed content...</span>
        </div>

        {{-- ── Main grid ──────────────────────────────────────────────────────── --}}
        <section class="space-y-6">
            <div class="min-w-0 space-y-6">

                {{-- ── Drop zone ─────────────────────────────────────────────── --}}
                {{-- <input type="file" id="kb-file-input" accept=".pdf,.csv" class="sr-only"
                    aria-label="Upload knowledge base file"> --}}

                <form method="POST" enctype="multipart/form-data" action="{{ route('admin.knowledge.upload-pdf', ['businessUnit' => $selectedBusinessUnit->id ?? 1]) }}">
                    @csrf
                    <input type="file" id="kb-file-input" name="pdf" accept=".pdf,.csv" class="sr-only" aria-label="Upload knowledge base file">
                    @error('pdf')
                        <p class="text-sm text-rose-300">{{ $message }}</p>
                    @enderror
                    
                </form>

                <div id="kb-drop-zone"
                    class="cursor-pointer rounded-[2rem] border-2 border-dashed border-[#e2e8f0] bg-[#ffffff] p-8 transition-colors hover:border-[#1e293b] hover:bg-[#F4EEE4]"
                    role="button" tabindex="0" aria-label="Click or drag to upload a file">
                    <div class="flex flex-col items-center gap-3 text-center">
                        <div id="kb-plus-btn"
                            class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[#1e293b] text-white shadow-sm transition-transform hover:scale-105">
                            <span class="text-2xl font-black leading-none">+</span>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">Drop PDF policy papers or CSV tables here</h2>
                            <p class="mt-1 text-sm text-gray-500">Supported: PDF, CSV · Max size: 25 MB per file</p>
                            <p class="mt-2 text-xs text-gray-400">Or click anywhere in this area to browse files</p>
                        </div>
                    </div>
                </div>

                {{-- ── Status KPI cards (4 cards) ──────────────────────────── --}}
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    {{-- Staged --}}
                    <div class="card bg-base-100 shadow-sm">
                        <div class="card-body p-5">
                            <div class="flex items-center gap-2">
                                <span class="h-2 w-2 rounded-full bg-amber-400"></span>
                                <div class="text-sm text-gray-500">Staged</div>
                            </div>
                            <div class="mt-2 text-3xl font-black text-amber-600" id="kpi-staged">3</div>
                            <p class="mt-2 text-sm text-gray-500">Awaiting review</p>
                        </div>
                    </div>
                    {{-- Queued --}}
                    <div class="card bg-base-100 shadow-sm">
                        <div class="card-body p-5">
                            <div class="text-sm text-gray-500">Queued</div>
                            <div class="mt-2 text-3xl font-black text-gray-900" id="kpi-queued">4</div>
                            <p class="mt-2 text-sm text-gray-500">Waiting for processing</p>
                        </div>
                    </div>
                    {{-- Processing --}}
                    <div class="card bg-base-100 shadow-sm">
                        <div class="card-body p-5">
                            <div class="text-sm text-gray-500">Processing</div>
                            <div class="mt-2 text-3xl font-black text-gray-900" id="kpi-processing">2</div>
                            <p class="mt-2 text-sm text-gray-500">Indexing and validation</p>
                        </div>
                    </div>
                    {{-- Indexed --}}
                    <div class="card bg-base-100 shadow-sm">
                        <div class="card-body p-5">
                            <div class="text-sm text-gray-500">Indexed</div>
                            <div class="mt-2 text-3xl font-black text-gray-900" id="kpi-indexed">18</div>
                            <p class="mt-2 text-sm text-gray-500">Ready for assistant responses</p>
                        </div>
                    </div>
                </div>

                {{-- ── Processing Queue ─────────────────────────────────────── --}}
                <div class="card bg-base-100 shadow-sm" id="staged-files-panel">
                    <div class="card-body p-6">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <h2 class="text-lg font-bold text-gray-900">Processing queue</h2>
                                <p class="mt-0.5 text-xs text-gray-500">Approved files move here while they are trained and indexed.</p>
                            </div>
                        </div>
                        <div class="mt-4 space-y-3" id="processing-queue-container">

                            {{-- Queue item: uploading --}}
                            <div class="rounded-2xl bg-[#ffffff] p-4">
                                <div class="flex items-center justify-between gap-4">
                                    <div>
                                        <div class="font-semibold text-gray-800">Villa Carmelita policies.pdf</div>
                                        <div class="text-sm text-gray-500">PDF · 12 pages · Uploading</div>
                                    </div>
                                    <span
                                        class="inline-flex items-center rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700 ring-1 ring-amber-200">62%</span>
                                </div>
                                <div class="mt-3 h-2 rounded-full bg-gray-200">
                                    <div class="h-2 w-[62%] rounded-full bg-[#1e293b]"></div>
                                </div>
                            </div>
                            {{-- Queue item: validated / ready --}}
                            <div class="rounded-2xl bg-[#ffffff] p-4">
                                <div class="flex items-center justify-between gap-4">
                                    <div>
                                        <div class="font-semibold text-gray-800">Restaurant menu.csv</div>
                                        <div class="text-sm text-gray-500">CSV · 42 rows · Validated</div>
                                    </div>
                                    <span
                                        class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-200">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                        Ready
                                    </span>
                                </div>
                                <p class="mt-2 text-xs text-emerald-600">Columns mapped successfully · 2 duplicate rows
                                    skipped</p>
                            </div>
                            {{-- Queue item: staged badge example --}}
                            <div class="rounded-2xl bg-[#ffffff] p-4 queue-staged-example">
                                <div class="flex items-center justify-between gap-4">
                                    <div>
                                        <div class="font-semibold text-gray-800">Pool rates summer.pdf</div>
                                        <div class="text-sm text-gray-500">PDF · 5 pages · Pending approval</div>
                                    </div>
                                    <span
                                        class="inline-flex items-center gap-1 rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700 ring-1 ring-amber-200">
                                        <span class="h-1.5 w-1.5 rounded-full bg-amber-400"></span>
                                        Staged
                                    </span>
                                </div>
                            </div>
                        </div>{{-- /processing-queue-container --}}
                    </div>
                </div>

            </div>{{-- /left column --}}

        </section>
    </div>

    {{-- ════════════════════════════════════════════════════════════════════════
    UPLOAD WIZARD MODAL — 4-Phase Workflow
    ════════════════════════════════════════════════════════════════════════ --}}
    <div id="kb-upload-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4" role="dialog"
        aria-modal="true" aria-labelledby="modal-title">

        <div id="kb-modal-backdrop" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

        <div class="relative flex h-[90vh] w-full max-w-5xl flex-col overflow-hidden rounded-[2rem] bg-white shadow-2xl ring-1 ring-black/5">
            <div class="flex items-start justify-between gap-4 border-b border-[#e2e8f0] bg-[#f8fafc] px-6 py-5 sm:px-8">
                <div class="min-w-0">
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-gray-400">Knowledge Base Upload</p>
                    <h2 id="modal-title" class="mt-1 truncate text-2xl font-black tracking-tight text-gray-900">—</h2>
                    <div class="mt-2 flex flex-wrap items-center gap-2 text-sm text-gray-500">
                        <span id="modal-file-status" class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-200">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                            Parsed Successfully
                        </span>
                        <span id="modal-file-summary">—</span>
                    </div>
                </div>
                <button type="button" id="kb-modal-close"
                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full text-gray-400 transition hover:bg-white hover:text-gray-700"
                    aria-label="Close">
                    <svg viewBox="0 0 24 24" class="h-5 w-5 fill-current">
                        <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 19 17.59 13.41 12z" />
                    </svg>
                </button>
            </div>

            <div class="border-b border-[#e2e8f0] px-6 py-4 sm:px-8">
                <div class="grid gap-3 sm:grid-cols-4">
                    <div id="phase-pill-1" class="rounded-2xl border border-[#1e293b] bg-[#1e293b] px-4 py-3 text-sm font-semibold text-white">1. Upload &amp; Detect</div>
                    <div id="phase-pill-2" class="rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm font-semibold text-gray-500">2. Staging Review</div>
                    <div id="phase-pill-3" class="rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm font-semibold text-gray-500">3. Processing</div>
                    <div id="phase-pill-4" class="rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm font-semibold text-gray-500">4. Success &amp; Sandbox</div>
                </div>
            </div>

            <div id="modal-step-review" class="hide-scrollbar flex-1 min-h-0 grid gap-6 overflow-y-auto px-6 py-6 sm:px-8 lg:grid-cols-[1fr_1.2fr]">
                <div class="space-y-5">
                    <div class="rounded-[1.75rem] border border-[#e2e8f0] bg-white p-5 shadow-sm">
                        <div class="rounded-2xl bg-[#f8fafc] p-4 ring-1 ring-[#e2e8f0]">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-gray-400">File Snapshot</p>
                            <div class="mt-3 grid grid-cols-2 gap-3 text-sm">
                                <div class="rounded-2xl bg-white p-3 ring-1 ring-[#e2e8f0]">
                                    <span class="text-xs text-gray-400">Name</span>
                                    <p id="modal-file-name" class="mt-1 truncate font-semibold text-gray-900">—</p>
                                </div>
                                <div class="rounded-2xl bg-white p-3 ring-1 ring-[#e2e8f0]">
                                    <span class="text-xs text-gray-400">Size</span>
                                    <p id="modal-file-meta" class="mt-1 font-semibold text-gray-900">—</p>
                                </div>
                            </div>
                            <div id="modal-type-error" class="mt-3 hidden rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-700">
                                Unsupported file type. Only PDF and CSV files are accepted.
                            </div>
                        </div>
                    </div>

                    <div class="rounded-[1.75rem] border border-[#e2e8f0] bg-[#f8fafc] p-5 shadow-sm">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-gray-400">Upload Feedback</p>
                                <h3 class="mt-1 text-base font-bold text-gray-900">Browser-side check and backend parse</h3>
                            </div>
                            <span id="modal-size-badge" class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-200">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                Within limit
                            </span>
                        </div>
                        <div class="mt-4 rounded-2xl bg-white p-4 ring-1 ring-[#e2e8f0]">
                            <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-[0.18em] text-gray-400">
                                <span>Processing file...</span>
                                <span id="upload-progress-text">0%</span>
                            </div>
                            <div class="mt-3 h-3 rounded-full bg-gray-100">
                                <div id="upload-progress-bar" class="h-3 w-0 rounded-full bg-[#1e293b] transition-all duration-300"></div>
                            </div>
                            <p id="upload-progress-message" class="mt-3 text-sm text-gray-600">Waiting for file parsing to begin.</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-[1.75rem] border border-[#e2e8f0] bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-gray-400">Text Previewer</p>
                            <h3 class="mt-1 text-base font-bold text-gray-900">Review the extracted text</h3>
                        </div>
                        <span class="rounded-full bg-[#ecfeff] px-3 py-1 text-[11px] font-semibold text-cyan-700 ring-1 ring-cyan-200">Scroll to inspect</span>
                    </div>
                    <div id="modal-preview-chunks" class="hide-scrollbar mt-4 max-h-[56vh] space-y-3 overflow-y-auto pr-1"></div>
                </div>
            </div>

            <div id="modal-step-confirm" class="hide-scrollbar hidden flex-1 min-h-0 overflow-y-auto border-t border-[#e2e8f0] px-6 py-6 sm:px-8">
                <div class="grid gap-6 lg:grid-cols-[1fr_1.1fr]">
                    <div class="rounded-[1.75rem] border border-[#e2e8f0] bg-[#f8fafc] p-5 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-gray-400">Processing / Embedding State</p>
                        <h3 class="mt-1 text-xl font-black tracking-tight text-gray-900">Background ingestion is running</h3>
                        <div class="mt-4 space-y-3 text-sm text-gray-700">
                            <div class="flex items-center gap-3 rounded-2xl bg-white p-4 ring-1 ring-[#e2e8f0]" id="processing-step-1">
                                <span class="h-3 w-3 rounded-full bg-emerald-500"></span>
                                Securing Data Boundaries...
                            </div>
                            <div class="flex items-center gap-3 rounded-2xl bg-white p-4 ring-1 ring-[#e2e8f0]" id="processing-step-2">
                                <span class="h-3 w-3 rounded-full bg-amber-400"></span>
                                Generating Mathematical Context...
                            </div>
                            <div class="flex items-center gap-3 rounded-2xl bg-white p-4 ring-1 ring-[#e2e8f0]" id="processing-step-3">
                                <span class="h-3 w-3 rounded-full bg-gray-300"></span>
                                Updating Chatbot Knowledge...
                            </div>
                        </div>
                        <div class="mt-5 rounded-2xl bg-blue-50 p-4 text-sm text-blue-800 ring-1 ring-blue-200">
                            Your file is being indexed and prepared for the chatbot database.
                        </div>
                    </div>

                    <div class="rounded-[1.75rem] border border-[#e2e8f0] bg-white p-5 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-gray-400">Edit extracted content</p>
                        <h3 class="mt-1 text-base font-bold text-gray-900">Fix anything that looks wrong before training</h3>
                        <p class="mt-2 text-sm leading-6 text-gray-600">This is the last chance to correct the parsed text before it is queued for indexing and vectorization.</p>

                        <label for="confirm-edit-box" class="mt-4 block text-xs font-semibold uppercase tracking-[0.18em] text-gray-400">Corrected content</label>
                        <textarea id="confirm-edit-box" rows="14"
                            class="mt-2 w-full rounded-2xl border border-[#e2e8f0] bg-[#f8fafc] p-4 font-mono text-sm leading-6 text-gray-700 focus:border-[#1e293b] focus:outline-none resize-y"
                            placeholder="Edit parsed content here..."></textarea>
                        <p class="mt-2 text-xs text-gray-500">You can remove bad OCR, fix wording, or rewrite chunks before confirming.</p>

                        <div class="mt-5 flex flex-wrap items-center justify-between gap-3 border-t border-[#e2e8f0] pt-4">
                            <button type="button" id="wizard-back-2"
                                class="btn rounded-full border border-gray-300 bg-white px-5 text-sm font-semibold text-gray-600 hover:bg-gray-50 hover:text-gray-800">
                                ← Back to Review
                            </button>
                            <div class="flex flex-wrap items-center gap-3">
                                <button type="button" id="kb-modal-cancel-2"
                                    class="btn rounded-full border border-gray-300 bg-white px-5 text-sm font-semibold text-gray-600 hover:bg-gray-50 hover:text-gray-800">
                                    Cancel / Re-upload
                                </button>
                                <button type="button" id="kb-modal-confirm"
                                    class="btn rounded-full border-0 bg-[#1e293b] px-6 text-sm font-bold text-white hover:bg-[#0f172a]">
                                    Approve &amp; Train AI
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="modal-step-success" class="hidden flex-1 min-h-0 overflow-y-auto border-t border-[#e2e8f0] px-6 py-6 sm:px-8">
                <div class="space-y-6">
                    <div class="rounded-[1.75rem] border border-emerald-200 bg-emerald-50 p-6 shadow-sm">
                        <div class="flex items-center gap-4">
                            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-600 text-2xl text-white">✓</div>
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-emerald-700">Upload Successful</p>
                                <h3 class="mt-1 text-2xl font-black tracking-tight text-emerald-950">The file is now active in the knowledge pipeline</h3>
                                <p class="mt-1 text-sm text-emerald-800">The chatbot database has been updated for this business unit.</p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="rounded-[1.75rem] border border-[#e2e8f0] bg-white p-5 shadow-sm">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-gray-400">Testing Sandbox</p>
                            <h3 class="mt-1 text-base font-bold text-gray-900">Try a question before you leave</h3>
                            <div class="mt-4 rounded-2xl bg-[#f8fafc] p-4 text-sm text-gray-600">
                                Test the new data: Try asking the AI a question about the file you just uploaded.
                            </div>
                            <div class="mt-4 rounded-2xl border border-dashed border-[#e2e8f0] bg-white p-4 text-sm text-gray-500">
                                Chat preview widget goes here.
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-3">
                            <button type="button" id="kb-success-close" class="btn w-full rounded-full border border-gray-300 bg-white px-5 text-sm font-semibold text-gray-600 hover:bg-gray-50 sm:w-auto">Close</button>
                            <button type="button" id="kb-success-open-review" class="btn w-full rounded-full border-0 bg-[#1e293b] px-5 text-sm font-bold text-white hover:bg-[#3a2f2e] sm:w-auto">Review Another File</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="shrink-0 border-t border-[#e2e8f0] bg-white px-6 py-4 sm:px-8">
                <div class="flex flex-col-reverse gap-3 sm:flex-row sm:flex-wrap sm:items-center sm:justify-between" id="modal-review-actions">
                    <button type="button" id="kb-modal-cancel"
                        class="btn w-full rounded-full border border-gray-300 bg-white px-5 text-sm font-semibold text-gray-600 hover:bg-gray-50 sm:w-auto">Cancel / Re-upload</button>
                    <button type="button" id="wizard-next-1"
                        class="btn w-full rounded-full border-0 bg-[#1e293b] px-6 text-sm font-bold text-white hover:bg-[#3a2f2e] sm:w-auto">Continue to Review →</button>
                </div>
            </div>
        </div>{{-- /modal card --}}
    </div>{{-- /modal --}}

    {{-- ════════════════════════════════════════════════════════════════════════
    SUCCESS TOAST
    ════════════════════════════════════════════════════════════════════════ --}}
    <div id="kb-success-toast"
        class="fixed bottom-6 right-6 z-[60] hidden max-w-sm rounded-2xl border border-emerald-200 bg-white p-4 shadow-xl">
        <div class="flex items-start gap-3">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                <svg viewBox="0 0 24 24" class="h-5 w-5 fill-current">
                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-bold text-gray-900" id="toast-title">File sent to staging</p>
                <p id="toast-file-name" class="mt-0.5 text-xs text-gray-500">Your file is awaiting review.</p>
            </div>
            <button type="button" id="kb-toast-close" class="ml-auto text-gray-400 hover:text-gray-600">
                <svg viewBox="0 0 24 24" class="h-4 w-4 fill-current">
                    <path
                        d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z" />
                </svg>
            </button>
        </div>
    </div>
@endsection