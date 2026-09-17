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
                <input type="file" id="kb-file-input" accept=".pdf,.csv,application/pdf,text/csv" class="sr-only"
                    aria-label="Upload knowledge base file">

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

        {{-- ── Indexed Chunks Table ─────────────────────────────────────────── --}}
        <section class="rounded-[2rem] border border-[#e2e8f0] bg-white shadow-sm">
            <div class="flex items-center justify-between gap-4 border-b border-[#e2e8f0] px-6 py-5 lg:px-8">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Indexed Chunks</h2>
                    <p class="mt-0.5 text-xs text-gray-500">All knowledge entries currently active in the chatbot database.</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-200">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                        <span id="chunks-total-badge">{{ $chunks->total() }} total</span>
                    </span>
                    <button type="button" id="add-chunk-btn"
                        class="inline-flex items-center gap-1.5 rounded-full border-0 bg-[#1e293b] px-4 py-2 text-xs font-bold text-white transition-colors hover:bg-[#0f172a]">
                        + Add Chunk
                    </button>
                </div>
            </div>

            <div class="w-full overflow-hidden">
                <table class="w-full table-fixed text-sm">
                    <colgroup>
                        <col class="w-14">
                        <col class="w-36">
                        <col>{{-- content: takes remaining space --}}
                        <col class="w-44">
                        <col class="w-40">
                    </colgroup>
                    <thead>
                        <tr class="bg-[#1e293b] text-left text-xs font-semibold uppercase tracking-[0.18em] text-white">
                            <th class="px-4 py-3">#</th>
                            <th class="px-4 py-3">Business Unit</th>
                            <th class="px-4 py-3">Content Preview</th>
                            <th class="px-4 py-3">Created At</th>
                            <th class="px-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e2e8f0]" id="chunks-tbody">
                        @forelse ($chunks as $chunk)
                            <tr class="transition-colors hover:bg-[#f8fafc]">
                                <td class="px-4 py-4 font-mono text-xs text-gray-400">{{ $chunk->id }}</td>
                                <td class="px-4 py-4">
                                    <span class="inline-flex max-w-full items-center truncate rounded-full bg-[#f1f5f9] px-2.5 py-1 text-xs font-semibold text-gray-700">
                                        {{ $chunk->businessUnit?->name ?? '—' }}
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    <p class="truncate text-gray-700">{{ \Illuminate\Support\Str::limit($chunk->content, 120) }}</p>
                                </td>
                                <td class="px-4 py-4 text-gray-500">
                                    {{ $chunk->created_at->format('M d, Y · H:i') }}
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-2">
                                        <button type="button"
                                            class="btn-edit-chunk inline-flex shrink-0 items-center rounded-full border border-[#e2e8f0] bg-[#f8fafc] px-3 py-1 text-xs font-semibold text-gray-700 transition-colors hover:bg-[#e2e8f0]"
                                            data-id="{{ $chunk->id }}"
                                            data-content="{{ $chunk->content }}">
                                            Edit
                                        </button>
                                        <button type="button"
                                            class="btn-delete-chunk inline-flex shrink-0 items-center rounded-full border border-rose-200 bg-rose-50 px-3 py-1 text-xs font-semibold text-rose-700 transition-colors hover:bg-rose-100"
                                            data-id="{{ $chunk->id }}">
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-400">
                                    No indexed chunks yet. Upload and approve a file to populate this table.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($chunks->hasPages())
                <div class="border-t border-[#e2e8f0] px-6 py-4">
                    {{ $chunks->links() }}
                </div>
            @endif
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

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const fileInput = document.getElementById('kb-file-input');
            const dropZone = document.getElementById('kb-drop-zone');
            const modal = document.getElementById('kb-upload-modal');
            const backdrop = document.getElementById('kb-modal-backdrop');
            const modalClose = document.getElementById('kb-modal-close');
            const modalCancel = document.getElementById('kb-modal-cancel');
            const modalCancel2 = document.getElementById('kb-modal-cancel-2');
            const modalConfirm = document.getElementById('kb-modal-confirm');
            const wizardNext1 = document.getElementById('wizard-next-1');
            const wizardBack2 = document.getElementById('wizard-back-2');
            const modalTitle = document.getElementById('modal-title');
            const modalStatus = document.getElementById('modal-file-status');
            const modalFileName = document.getElementById('modal-file-name');
            const modalFileSummary = document.getElementById('modal-file-summary');
            const modalSizeBadge = document.getElementById('modal-size-badge');
            const modalTypeError = document.getElementById('modal-type-error');
            const modalPreviewChunks = document.getElementById('modal-preview-chunks');
            const modalStepReview = document.getElementById('modal-step-review');
            const modalStepConfirm = document.getElementById('modal-step-confirm');
            const modalStepSuccess = document.getElementById('modal-step-success');
            const modalReviewActions = document.getElementById('modal-review-actions');
            const branchSelect = document.getElementById('modal-branch');
            const branchError = document.getElementById('modal-branch-error');
            const processingBanner = document.getElementById('kb-processing-banner');
            const processingBannerText = document.getElementById('kb-processing-banner-text');
            const uploadProgressBar = document.getElementById('upload-progress-bar');
            const uploadProgressText = document.getElementById('upload-progress-text');
            const uploadProgressMessage = document.getElementById('upload-progress-message');
            const phasePill1 = document.getElementById('phase-pill-1');
            const phasePill2 = document.getElementById('phase-pill-2');
            const phasePill3 = document.getElementById('phase-pill-3');
            const phasePill4 = document.getElementById('phase-pill-4');
            const successToast = document.getElementById('kb-success-toast');
            const toastTitle = document.getElementById('toast-title');
            const toastFileName = document.getElementById('toast-file-name');
            const toastClose = document.getElementById('kb-toast-close');
            const successClose = document.getElementById('kb-success-close');
            const successOpenReview = document.getElementById('kb-success-open-review');

            const kpiStaged = document.getElementById('kpi-staged');
            const kpiQueued = document.getElementById('kpi-queued');
            const stagedBadge = document.getElementById('staged-count-badge');
            const stagedEmpty = document.getElementById('staged-empty-state');
            const stagedRows = document.getElementById('staged-rows-container');
            const queueCont = document.getElementById('processing-queue-container');

            const reviewEmpty = document.getElementById('review-empty');
            const reviewDetail = document.getElementById('review-detail');
            const reviewName = document.getElementById('review-name');
            const reviewType = document.getElementById('review-type');
            const reviewSize = document.getElementById('review-size');
            const reviewBranch = document.getElementById('review-branch');
            const reviewTopics = document.getElementById('review-topics');
            const reviewDupWrap = document.getElementById('review-duplicate-wrap');
            const reviewNoDupWrap = document.getElementById('review-no-duplicate-wrap');
            const reviewApproveBtn = document.getElementById('review-approve-btn');
            const reviewDiscardBtn = document.getElementById('review-discard-btn');
            const confirmFileName = document.getElementById('confirm-file-name');
            const confirmFileMeta = document.getElementById('confirm-file-meta');
            const confirmBranch = document.getElementById('confirm-branch');
            const confirmChunks = document.getElementById('confirm-chunks');
            const confirmEditBox = document.getElementById('confirm-edit-box');
            const managerBranchLabel = null;
            const csrfToken = @json(csrf_token());
            const uploadUrl = @json(route('admin.knowledge.upload'));
            const stagedUrl = @json(route('admin.knowledge.staged'));
            const approveUrlTemplate = @json(route('admin.knowledge.approve', ['stagedDocument' => '__ID__']));
            const discardUrlTemplate = @json(route('admin.knowledge.discard', ['stagedDocument' => '__ID__']));

            let currentFile = null;
            let currentParsedText = '';
            let currentEditedText = '';
            let currentPreviewChunkCount = 0;
            let selectedStagedId = null;
            let stagedCount = document.querySelectorAll('.staged-row').length;
            const maxFileSize = 25 * 1024 * 1024;

            const branchLabels = {
                dariv: 'DARIV Waterproofing',
                hydroguard: 'HydroGuard Solutions',
                drymax: 'DryMax Sealants'
            };

            function setModalStep(step) {
                if (modalStepReview) modalStepReview.classList.toggle('hidden', step !== 1);
                if (modalStepConfirm) modalStepConfirm.classList.toggle('hidden', step !== 2 && step !== 3);
                if (modalStepSuccess) modalStepSuccess.classList.toggle('hidden', step !== 4);
                if (modalReviewActions) modalReviewActions.classList.toggle('hidden', step !== 1);

                const pills = [phasePill1, phasePill2, phasePill3, phasePill4];
                pills.forEach((pill, index) => {
                    if (!pill) return;
                    const isActive = step === index + 1;
                    const isComplete = step > index + 1;
                    pill.className = isActive
                        ? 'rounded-2xl border border-[#1e293b] bg-[#1e293b] px-4 py-3 text-sm font-semibold text-white'
                        : isComplete
                            ? 'rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700'
                            : 'rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm font-semibold text-gray-500';
                });

                    if (step === 2 || step === 3) populateConfirmSummary();
            }

            function populateConfirmSummary() {
                if (!currentFile) return;

                const fileType = currentFile.name.toLowerCase().endsWith('.csv') || currentFile.type === 'text/csv' ? 'CSV' : 'PDF';
                const branchValue = branchSelect ? branchSelect.value : '';

                if (confirmFileName) confirmFileName.textContent = currentFile.name;
                if (confirmFileMeta) confirmFileMeta.textContent = `${fileType} · ${formatBytes(currentFile.size)}`;
                if (confirmBranch) confirmBranch.textContent = branchSelect && branchSelect.tagName === 'SELECT'
                    ? (branchLabels[branchValue] || '—')
                    : (managerBranchLabel || 'Selected business unit');
                if (confirmChunks) confirmChunks.textContent = `${currentPreviewChunkCount} chunk${currentPreviewChunkCount === 1 ? '' : 's'} prepared for training`;
                if (confirmEditBox) {
                    confirmEditBox.value = currentEditedText || currentParsedText || '';
                }
            }

            const openFilePicker = () => fileInput.click();

            dropZone.addEventListener('click', openFilePicker);
            dropZone.addEventListener('keydown', event => {
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    openFilePicker();
                }
            });

            dropZone.addEventListener('dragover', event => {
                event.preventDefault();
                dropZone.classList.add('border-[#1e293b]', 'bg-[#F4EEE4]');
            });
            dropZone.addEventListener('dragleave', () => dropZone.classList.remove('border-[#1e293b]', 'bg-[#F4EEE4]'));
            dropZone.addEventListener('drop', event => {
                event.preventDefault();
                dropZone.classList.remove('border-[#1e293b]', 'bg-[#F4EEE4]');
                const file = event.dataTransfer.files[0];
                if (file) {
                    handleFile(file);
                }
            });

            fileInput.addEventListener('change', () => {
                if (fileInput.files.length) {
                    handleFile(fileInput.files[0]);
                }
            });

            function handleFile(file) {
                currentFile = file;
                currentParsedText = '';
                currentEditedText = '';
                const isCsv = file.type === 'text/csv' || file.name.toLowerCase().endsWith('.csv');
                const isPdf = file.type === 'application/pdf' || file.name.toLowerCase().endsWith('.pdf');
                const isAllowedType = isCsv || isPdf;
                const isWithinLimit = file.size <= maxFileSize;

                if (modalTitle) modalTitle.textContent = file.name;
                if (modalFileName) modalFileName.textContent = file.name;
                if (modalFileSummary) modalFileSummary.textContent = `${formatBytes(file.size)} · ${isCsv ? 'CSV' : 'PDF'}`;

                if (uploadProgressText) uploadProgressText.textContent = '10%';
                if (uploadProgressMessage) uploadProgressMessage.textContent = 'Checking file type and size...';
                if (uploadProgressBar) uploadProgressBar.style.width = '10%';

                if (modalSizeBadge) {
                    modalSizeBadge.className = isWithinLimit
                        ? 'inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-200'
                        : 'inline-flex items-center gap-1.5 rounded-full bg-rose-50 px-3 py-1 text-xs font-semibold text-rose-700 ring-1 ring-rose-200';
                    modalSizeBadge.innerHTML = isWithinLimit
                        ? '<span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Within 25 MB'
                        : '<span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span> Exceeds 25 MB limit';
                }

                if (modalTypeError) modalTypeError.classList.toggle('hidden', isAllowedType);
                if (modalStatus) {
                    modalStatus.className = isAllowedType && isWithinLimit
                        ? 'inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-200'
                        : 'inline-flex items-center gap-1.5 rounded-full bg-rose-50 px-3 py-1 text-xs font-semibold text-rose-700 ring-1 ring-rose-200';
                    modalStatus.innerHTML = isAllowedType && isWithinLimit
                        ? '<span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Parsed Successfully'
                        : '<span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span> Needs attention';
                }

                if (wizardNext1) {
                    wizardNext1.disabled = !isAllowedType || !isWithinLimit;
                    wizardNext1.classList.toggle('opacity-50', wizardNext1.disabled);
                    wizardNext1.classList.toggle('cursor-not-allowed', wizardNext1.disabled);
                }

                if (branchSelect && branchSelect.tagName === 'SELECT') {
                    branchSelect.value = '';
                }
                if (branchError) branchError.classList.add('hidden');

                if (!isAllowedType || !isWithinLimit) {
                    renderPreviewChunks(file, 'No parsed text was returned by the file parser.');
                    setConfirmEnabled(false);
                    openModal();
                    return;
                }

                const reader = new FileReader();
                reader.onprogress = event => {
                    if (!event.lengthComputable) return;
                    const progress = Math.min(95, Math.max(35, Math.round((event.loaded / event.total) * 60) + 35));
                    if (uploadProgressText) uploadProgressText.textContent = `${progress}%`;
                    if (uploadProgressBar) uploadProgressBar.style.width = `${progress}%`;
                    if (uploadProgressMessage) uploadProgressMessage.textContent = isCsv
                        ? 'Reading CSV rows in the browser...'
                        : 'Reading PDF bytes and preparing document chunks...';
                };
                reader.onload = event => {
                    const parsedText = isCsv
                        ? (typeof event.target.result === 'string' ? event.target.result : '')
                        : buildPdfPreview(file, event.target.result);
                    renderPreviewChunks(file, parsedText);
                    if (uploadProgressText) uploadProgressText.textContent = '100%';
                    if (uploadProgressMessage) uploadProgressMessage.textContent = 'File parsed and ready for staging review.';
                    if (uploadProgressBar) uploadProgressBar.style.width = '100%';
                    setConfirmEnabled(true);
                    openModal();
                };
                reader.onerror = () => {
                    renderPreviewChunks(file, 'Could not read file for preview.');
                    if (uploadProgressText) uploadProgressText.textContent = '100%';
                    if (uploadProgressMessage) uploadProgressMessage.textContent = 'File parsed with warnings. Please review the staging screen.';
                    if (uploadProgressBar) uploadProgressBar.style.width = '100%';
                    setConfirmEnabled(true);
                    openModal();
                };

                if (isCsv) {
                    reader.readAsText(file);
                    return;
                }

                reader.readAsArrayBuffer(file);
            }

            function buildPdfPreview(file, buffer) {
                const bytes = buffer instanceof ArrayBuffer ? buffer.byteLength : 0;
                const chunkSize = 1024 * 1024;
                const chunkCount = Math.max(1, Math.min(8, Math.ceil(bytes / chunkSize)));
                return Array.from({ length: chunkCount }, (_, index) => {
                    const start = index * chunkSize;
                    const end = Math.min(bytes, start + chunkSize);
                    return `PDF CHUNK ${index + 1}\n${file.name}\nByte range: ${start.toLocaleString()}-${Math.max(start, end - 1).toLocaleString()}\nText extraction will continue during server-side ingestion.`;
                }).join('\n\n');
            }

            function renderPreviewChunks(file, parsedText) {
                if (!modalPreviewChunks) return;

                currentParsedText = String(parsedText || '');

                const chunks = buildChunks(file, parsedText);
                currentPreviewChunkCount = chunks.length;
                modalPreviewChunks.innerHTML = chunks.map((chunk, index) => `
                    <div class="rounded-2xl border border-[#e2e8f0] bg-[#f8fafc] p-4 shadow-sm">
                        <div class="flex items-center justify-between gap-3">
                            <span class="inline-flex items-center rounded-full bg-white px-2.5 py-1 text-[11px] font-bold uppercase tracking-[0.18em] text-gray-500 ring-1 ring-[#e2e8f0]">Chunk ${index + 1}</span>
                            <span class="text-[11px] font-medium text-gray-400">${chunk.length} characters</span>
                        </div>
                        <p class="mt-3 whitespace-pre-wrap rounded-xl border border-white bg-white p-3 font-mono text-sm leading-6 text-gray-700 shadow-sm">${escHtml(chunk)}</p>
                    </div>
                `).join('');
            }

            function buildChunks(file, parsedText) {
                const cleaned = String(parsedText || '').replace(/\r\n/g, '\n').trim();
                const isCsv = file.type === 'text/csv' || file.name.toLowerCase().endsWith('.csv');

                if (!cleaned) {
                    return ['No parsed text was returned by the file parser.'];
                }

                if (isCsv) {
                    const rows = cleaned.split('\n').map(row => row.trim()).filter(Boolean);
                    const header = rows.shift() || 'CSV content';
                    const chunks = [`CSV Header\n${header}`];
                    rows.slice(0, 8).forEach((row, index) => {
                        chunks.push(`Row ${index + 1}\n${row}`);
                    });
                    return chunks;
                }

                const paragraphs = cleaned.split(/\n{2,}/).map(part => part.trim()).filter(Boolean);
                if (paragraphs.length > 1) {
                    return paragraphs.slice(0, 8);
                }

                const sentences = cleaned.match(/[^.!?\n]+[.!?]?/g) || [cleaned];
                return sentences.map(sentence => sentence.trim()).filter(Boolean).slice(0, 8);
            }

            function setConfirmEnabled(enabled) {
                if (!modalConfirm) return;
                modalConfirm.disabled = !enabled;
                modalConfirm.classList.toggle('opacity-50', !enabled);
                modalConfirm.classList.toggle('cursor-not-allowed', !enabled);
            }

            function openModal() {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.classList.add('overflow-hidden');
                setModalStep(1);
            }

            function closeModal() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.classList.remove('overflow-hidden');
                fileInput.value = '';
                currentFile = null;
                currentParsedText = '';
                currentEditedText = '';
                currentPreviewChunkCount = 0;
                if (uploadProgressText) uploadProgressText.textContent = '0%';
                if (uploadProgressMessage) uploadProgressMessage.textContent = 'Waiting for file parsing to begin.';
                if (uploadProgressBar) uploadProgressBar.style.width = '0%';
                if (modalPreviewChunks) modalPreviewChunks.innerHTML = '';
                if (modalFileName) modalFileName.textContent = '—';
                if (modalFileSummary) modalFileSummary.textContent = '—';
                if (modalTitle) modalTitle.textContent = '—';
                if (modalTypeError) modalTypeError.classList.add('hidden');
                if (modalSizeBadge) {
                    modalSizeBadge.className = 'inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-200';
                    modalSizeBadge.innerHTML = '<span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Within 25 MB';
                }
                if (confirmEditBox) confirmEditBox.value = '';
                if (modalStatus) {
                    modalStatus.className = 'inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-200';
                    modalStatus.innerHTML = '<span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Parsed Successfully';
                }
                setModalStep(1);
                setConfirmEnabled(false);
            }

            if (modalClose) modalClose.addEventListener('click', closeModal);
            if (modalCancel) modalCancel.addEventListener('click', closeModal);
            if (modalCancel2) modalCancel2.addEventListener('click', closeModal);
            if (wizardBack2) wizardBack2.addEventListener('click', () => setModalStep(1));
            if (wizardNext1) wizardNext1.addEventListener('click', () => {
                // Skip division validation check

                if (branchError) branchError.classList.add('hidden');
                populateConfirmSummary();
                setModalStep(2);
            });
            if (backdrop) backdrop.addEventListener('click', closeModal);
            if (toastClose) toastClose.addEventListener('click', () => successToast.classList.add('hidden'));
            if (successClose) successClose.addEventListener('click', closeModal);
            if (successOpenReview) successOpenReview.addEventListener('click', () => setModalStep(1));

            function showProcessingBanner(fileName) {
                if (!processingBanner || !processingBannerText) return;
                processingBannerText.textContent = `Training AI bot for ${fileName} and vectorizing parsed chunks...`;
                processingBanner.classList.remove('hidden');
                processingBanner.classList.add('flex');
                window.setTimeout(() => {
                    processingBanner.classList.add('hidden');
                    processingBanner.classList.remove('flex');
                }, 2600);
            }

            function addProcessingQueueItem(name, type, size, branchVal) {
                if (!queueCont) return;

                const item = document.createElement('div');
                item.className = 'rounded-2xl border border-blue-100 bg-white p-4 shadow-sm';
                item.innerHTML = `
                    <div class="flex items-center justify-between gap-4">
                        <div class="min-w-0">
                            <div class="font-semibold text-gray-800 truncate">${escHtml(name)}</div>
                            <div class="text-sm text-gray-500">${escHtml(type)} · ${escHtml(size)} · ${escHtml(branchLabels[branchVal] || 'Selected business unit')}</div>
                        </div>
                        <span class="inline-flex items-center gap-2 rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700 ring-1 ring-blue-200">
                            <span class="h-2 w-2 animate-spin rounded-full border-2 border-blue-300 border-t-blue-700"></span>
                            Vectorizing
                        </span>
                    </div>`;

                queueCont.prepend(item);
                kpiQueued.textContent = String(parseInt(kpiQueued.textContent || '0', 10) + 1);
            }

            function showModalError(message) {
                if (modalStatus) {
                    modalStatus.className = 'inline-flex items-center gap-1.5 rounded-full bg-rose-50 px-3 py-1 text-xs font-semibold text-rose-700 ring-1 ring-rose-200';
                    modalStatus.innerHTML = '<span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span> Upload failed';
                }
                if (uploadProgressMessage) uploadProgressMessage.textContent = message;
            }

            function createStagedRow(data, branchVal) {
                const container = stagedRows || queueCont;
                if (!container) return null;

                const row = document.createElement('div');
                row.className = 'staged-row rounded-2xl bg-[#ffffff] p-4 border border-amber-100';
                row.dataset.id = data.id;
                row.dataset.name = data.name;
                row.dataset.type = data.type;
                row.dataset.size = formatBytes(data.size);
                row.dataset.branch = data.branch || branchLabels[branchVal] || 'Selected business unit';
                row.dataset.topics = 'Pending server-side extraction';
                row.dataset.duplicate = 'false';
                row.innerHTML = `
                    <div class="flex items-center justify-between gap-4">
                        <div class="min-w-0">
                            <div class="font-semibold text-gray-800 truncate">${escHtml(data.name)}</div>
                            <div class="text-sm text-gray-500">${escHtml(data.type)} · ${escHtml(formatBytes(data.size))} · Pending approval</div>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" class="btn-approve-queue rounded-full bg-[#1e293b] px-3 py-1 text-xs font-semibold text-white">Approve</button>
                            <button type="button" class="btn-discard rounded-full border border-gray-300 bg-white px-3 py-1 text-xs font-semibold text-gray-600">Discard</button>
                        </div>
                    </div>`;
                container.prepend(row);
                bindRowEvents(row);
                return row;
            }

            async function loadStagedRows() {
                try {
                    const response = await fetch(stagedUrl, {
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    const payload = await response.json();
                    if (!response.ok || !payload.success) return;

                    payload.data.forEach(data => {
                        if (!document.querySelector(`.staged-row[data-id="${data.id}"]`)) {
                            createStagedRow(data, data.branch);
                        }
                    });
                    stagedCount = payload.data.length;
                    if (kpiStaged) kpiStaged.textContent = String(stagedCount);
                    updateStagedCount(stagedCount);
                } catch (error) {
                    // The upload workflow remains available if the initial staged list cannot load.
                }
            }

            if (modalConfirm) {
                modalConfirm.addEventListener('click', async () => {
                    if (!currentFile) return;

                    // Skip division validation check

                    if (branchError) branchError.classList.add('hidden');

                    const fileName = currentFile.name;
                    const fileType = currentFile.name.toLowerCase().endsWith('.csv') || currentFile.type === 'text/csv' ? 'CSV' : 'PDF';
                    const fileSize = formatBytes(currentFile.size);
                    const branchVal = branchSelect ? branchSelect.value : '';
                    const correctedText = confirmEditBox ? confirmEditBox.value.trim() : '';
                    const editedContent = correctedText && correctedText !== currentParsedText ? correctedText : '';

                    modalConfirm.disabled = true;

                    currentEditedText = editedContent;

                    setModalStep(3);
                    if (uploadProgressText) uploadProgressText.textContent = '35%';
                    if (uploadProgressMessage) uploadProgressMessage.textContent = 'Saving the staged document...';
                    if (modalStatus) modalStatus.innerHTML = '<span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span> Saving';

                    const formData = new FormData();
                    formData.append('file', currentFile);
                    formData.append('branch', branchVal);
                    formData.append('edited_content', currentEditedText);

                    try {
                        const response = await fetch(uploadUrl, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: formData
                        });
                        const payload = await response.json();
                        if (!response.ok || !payload.success) {
                            throw new Error(payload.message || 'The server could not save this file.');
                        }

                        createStagedRow(payload.data, branchVal);
                        stagedCount += 1;
                        if (kpiStaged) kpiStaged.textContent = String(stagedCount);
                        updateStagedCount(stagedCount);
                        showProcessingBanner(fileName);
                        setModalStep(4);
                        modalConfirm.disabled = false;
                        if (toastTitle) toastTitle.textContent = 'File sent to staging';
                        toastFileName.textContent = `"${fileName}" is awaiting approval.`;
                        successToast.classList.remove('hidden');
                        window.setTimeout(() => successToast.classList.add('hidden'), 5000);
                    } catch (error) {
                        modalConfirm.disabled = false;
                        setModalStep(2);
                        showModalError(error.message || 'The upload failed. Please try again.');
                    }
                });
            }

            const updateStagedCount = count => {
                if (stagedBadge) {
                    stagedBadge.textContent = count + ' awaiting review';
                    if (count === 0) stagedBadge.textContent = '0 awaiting review';
                }
            };

            const checkStagedEmpty = () => {
                if (!stagedRows || !stagedEmpty) return;
                const hasRows = stagedRows.querySelectorAll('.staged-row').length > 0;
                stagedEmpty.classList.toggle('hidden', hasRows);
            };

            function bindRowEvents(row) {
                row.addEventListener('click', event => {
                    if (event.target.closest('.btn-approve-queue') || event.target.closest('.btn-discard') || event.target.closest('.toggle-preview')) return;
                    selectStagedRow(row);
                });

                const approveBtn = row.querySelector('.btn-approve-queue');
                if (approveBtn) {
                    approveBtn.addEventListener('click', event => {
                        event.stopPropagation();
                        approveRow(row.dataset.id);
                    });
                }

                const discardBtn = row.querySelector('.btn-discard');
                if (discardBtn) {
                    discardBtn.addEventListener('click', event => {
                        event.stopPropagation();
                        discardRow(row.dataset.id);
                    });
                }

                const toggleBtn = row.querySelector('.toggle-preview');
                if (toggleBtn) {
                    toggleBtn.addEventListener('click', event => {
                        event.stopPropagation();
                        const content = row.querySelector('.preview-content');
                        const isHidden = content.classList.contains('hidden');
                        content.classList.toggle('hidden', !isHidden);
                        const svg = toggleBtn.querySelector('svg');
                        if (svg) svg.style.transform = isHidden ? 'rotate(180deg)' : '';
                        toggleBtn.childNodes[toggleBtn.childNodes.length - 1].textContent = isHidden ? ' Hide content preview' : ' Show content preview';
                    });
                }
            }

            document.querySelectorAll('.staged-row').forEach(row => bindRowEvents(row));

            function selectStagedRow(row) {
                selectedStagedId = row.dataset.id;
                document.querySelectorAll('.staged-row').forEach(candidate => candidate.classList.remove('ring-2', 'ring-[#1e293b]'));
                row.classList.add('ring-2', 'ring-[#1e293b]');

                if (reviewName) reviewName.textContent = row.dataset.name || '—';
                if (reviewType) reviewType.textContent = row.dataset.type || '—';
                if (reviewSize) reviewSize.textContent = row.dataset.size || '—';
                if (reviewBranch) reviewBranch.textContent = row.dataset.branch || '—';
                if (reviewTopics) reviewTopics.textContent = row.dataset.topics || '—';

                const isDuplicate = row.dataset.duplicate === 'true';
                if (reviewDupWrap) reviewDupWrap.classList.toggle('hidden', !isDuplicate);
                if (reviewNoDupWrap) reviewNoDupWrap.classList.toggle('hidden', isDuplicate);

                if (reviewEmpty) reviewEmpty.classList.add('hidden');
                if (reviewDetail) reviewDetail.classList.remove('hidden');
                const reviewNote = document.getElementById('review-note');
                if (reviewNote) reviewNote.value = '';
            }

            async function approveRow(id) {
                const row = document.querySelector(`.staged-row[data-id="${id}"]`);
                if (!row) return;

                const name = row.dataset.name;
                const type = row.dataset.type;
                const size = row.dataset.size;

                const approveBtn = row.querySelector('.btn-approve-queue');
                if (approveBtn) approveBtn.disabled = true;

                try {
                    const response = await fetch(approveUrlTemplate.replace('__ID__', encodeURIComponent(id)), {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    const payload = await response.json();
                    if (!response.ok || !payload.success) throw new Error(payload.message || 'Approval failed.');

                    row.remove();
                    stagedCount = Math.max(0, stagedCount - 1);
                    if (kpiStaged) kpiStaged.textContent = String(stagedCount);
                    updateStagedCount(stagedCount);
                    checkStagedEmpty();

                    if (selectedStagedId === id) {
                        selectedStagedId = null;
                        if (reviewEmpty) reviewEmpty.classList.remove('hidden');
                        if (reviewDetail) reviewDetail.classList.add('hidden');
                    }

                    const queueItem = document.createElement('div');
                    queueItem.className = 'rounded-2xl bg-[#ffffff] p-4 border border-emerald-100';
                    queueItem.dataset.id = id;
                    queueItem.innerHTML = `
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <div class="font-semibold text-gray-800">${escHtml(name)}</div>
                            <div class="text-sm text-gray-500">${escHtml(type)} · ${escHtml(size)} · Approved</div>
                        </div>
                        <span class="inline-flex items-center gap-1 rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700 ring-1 ring-blue-200">
                            <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span>
                            Queued
                        </span>
                    </div>`;
                    if (queueCont) queueCont.appendChild(queueItem);
                    if (kpiQueued) kpiQueued.textContent = String(parseInt(kpiQueued.textContent || '0', 10) + 1);

                    if (toastTitle) toastTitle.textContent = 'File approved';
                    toastFileName.textContent = `"${name}" has been approved and queued for indexing.`;
                    successToast.classList.remove('hidden');
                    window.setTimeout(() => successToast.classList.add('hidden'), 5000);
                } catch (error) {
                    if (approveBtn) approveBtn.disabled = false;
                    if (toastTitle) toastTitle.textContent = 'Approval failed';
                    toastFileName.textContent = error.message || 'The file could not be approved.';
                    successToast.classList.remove('hidden');
                }
            }

            async function discardRow(id) {
                const row = document.querySelector(`.staged-row[data-id="${id}"]`);
                if (!row) return;
                const discardBtn = row.querySelector('.btn-discard');
                if (discardBtn) discardBtn.disabled = true;

                try {
                    const response = await fetch(discardUrlTemplate.replace('__ID__', encodeURIComponent(id)), {
                        method: 'DELETE',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    const payload = await response.json();
                    if (!response.ok || !payload.success) throw new Error(payload.message || 'Discard failed.');

                    const name = row.dataset.name || 'The file';
                    row.remove();
                    stagedCount = Math.max(0, stagedCount - 1);
                    if (kpiStaged) kpiStaged.textContent = String(stagedCount);
                    updateStagedCount(stagedCount);
                    checkStagedEmpty();
                    if (selectedStagedId === id) {
                        selectedStagedId = null;
                        if (reviewEmpty) reviewEmpty.classList.remove('hidden');
                        if (reviewDetail) reviewDetail.classList.add('hidden');
                    }

                    if (toastTitle) toastTitle.textContent = 'File discarded';
                    if (toastFileName) toastFileName.textContent = `"${name}" was removed from staging.`;
                    successToast.classList.remove('hidden');
                    window.setTimeout(() => successToast.classList.add('hidden'), 5000);
                } catch (error) {
                    if (discardBtn) discardBtn.disabled = false;
                    if (toastTitle) toastTitle.textContent = 'Discard failed';
                    toastFileName.textContent = error.message || 'The file could not be discarded.';
                    successToast.classList.remove('hidden');
                }
            }

            if (reviewApproveBtn) reviewApproveBtn.addEventListener('click', () => { if (selectedStagedId) approveRow(selectedStagedId); });
            if (reviewDiscardBtn) reviewDiscardBtn.addEventListener('click', () => { if (selectedStagedId) discardRow(selectedStagedId); });

            function formatBytes(bytes) {
                if (bytes < 1024) return bytes + ' B';
                if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
                return (bytes / 1048576).toFixed(2) + ' MB';
            }

            function escHtml(str) {
                return String(str)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#39;');
            }

            updateStagedCount(stagedCount);
            checkStagedEmpty();
            loadStagedRows();
        });
    </script>

    {{-- ── Edit Chunk Modal ──────────────────────────────────────────────────── --}}
    <div id="edit-chunk-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4" role="dialog" aria-modal="true">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" id="edit-chunk-backdrop"></div>
        <div class="relative w-full max-w-lg overflow-hidden rounded-[2rem] bg-white shadow-2xl ring-1 ring-black/5">
            <div class="flex items-center justify-between gap-4 border-b border-[#e2e8f0] bg-[#f8fafc] px-6 py-5">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-gray-400">Knowledge Base</p>
                    <h2 class="mt-1 text-xl font-black tracking-tight text-gray-900">Edit Knowledge Chunk</h2>
                </div>
                <button type="button" id="edit-chunk-modal-close"
                    class="flex h-10 w-10 items-center justify-center rounded-full text-gray-400 transition hover:bg-white hover:text-gray-700"
                    aria-label="Close">
                    <svg viewBox="0 0 24 24" class="h-5 w-5 fill-current">
                        <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                    </svg>
                </button>
            </div>

            <div class="space-y-4 px-6 py-6">
                <input type="hidden" id="edit-chunk-id">
                <div>
                    <label for="edit-chunk-content" class="mb-2 block text-xs font-semibold uppercase tracking-[0.18em] text-gray-400">Content</label>
                    <textarea id="edit-chunk-content" rows="6"
                        class="w-full resize-y rounded-2xl border border-[#e2e8f0] bg-[#f8fafc] px-4 py-3 font-mono text-sm leading-6 text-gray-700 focus:border-[#1e293b] focus:outline-none"
                        placeholder="Edit chunk content..."></textarea>
                </div>
                <p id="edit-chunk-error" class="hidden text-sm font-medium text-rose-600"></p>
            </div>

            <div class="flex items-center justify-end gap-3 border-t border-[#e2e8f0] bg-white px-6 py-4">
                <button type="button" id="edit-chunk-cancel"
                    class="btn rounded-full border border-gray-300 bg-white px-5 text-sm font-semibold text-gray-600 hover:bg-gray-50">
                    Cancel
                </button>
                <button type="button" id="edit-chunk-submit"
                    class="btn rounded-full border-0 bg-[#1e293b] px-6 text-sm font-bold text-white hover:bg-[#0f172a]">
                    Save Changes
                </button>
            </div>
        </div>
    </div>

    {{-- ── Add Chunk Modal ──────────────────────────────────────────────────── --}}
    <div id="add-chunk-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4" role="dialog" aria-modal="true">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" id="add-chunk-backdrop"></div>
        <div class="relative w-full max-w-lg overflow-hidden rounded-[2rem] bg-white shadow-2xl ring-1 ring-black/5">
            <div class="flex items-center justify-between gap-4 border-b border-[#e2e8f0] bg-[#f8fafc] px-6 py-5">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-gray-400">Knowledge Base</p>
                    <h2 class="mt-1 text-xl font-black tracking-tight text-gray-900">Add Knowledge Chunk</h2>
                </div>
                <button type="button" id="add-chunk-modal-close"
                    class="flex h-10 w-10 items-center justify-center rounded-full text-gray-400 transition hover:bg-white hover:text-gray-700"
                    aria-label="Close">
                    <svg viewBox="0 0 24 24" class="h-5 w-5 fill-current">
                        <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                    </svg>
                </button>
            </div>

            <div class="space-y-4 px-6 py-6">
                <div>
                    <label for="chunk-business-unit" class="mb-2 block text-xs font-semibold uppercase tracking-[0.18em] text-gray-400">Business Unit</label>
                    <select id="chunk-business-unit"
                        class="w-full rounded-2xl border border-[#e2e8f0] bg-[#f8fafc] px-4 py-3 text-sm text-gray-700 focus:border-[#1e293b] focus:outline-none">
                        @foreach($businessUnits as $unit)
                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="chunk-content" class="mb-2 block text-xs font-semibold uppercase tracking-[0.18em] text-gray-400">Content</label>
                    <textarea id="chunk-content" rows="6"
                        class="w-full resize-y rounded-2xl border border-[#e2e8f0] bg-[#f8fafc] px-4 py-3 font-mono text-sm leading-6 text-gray-700 focus:border-[#1e293b] focus:outline-none"
                        placeholder="Enter knowledge chunk content..."></textarea>
                </div>
                <p id="add-chunk-error" class="hidden text-sm font-medium text-rose-600"></p>
            </div>

            <div class="flex items-center justify-end gap-3 border-t border-[#e2e8f0] bg-white px-6 py-4">
                <button type="button" id="add-chunk-cancel"
                    class="btn rounded-full border border-gray-300 bg-white px-5 text-sm font-semibold text-gray-600 hover:bg-gray-50">
                    Cancel
                </button>
                <button type="button" id="add-chunk-submit"
                    class="btn rounded-full border-0 bg-[#1e293b] px-6 text-sm font-bold text-white hover:bg-[#0f172a]">
                    Save Chunk
                </button>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const csrfToken          = @json(csrf_token());
        const storeUrl           = @json(route('admin.knowledge.chunks.store'));
        const destroyUrlTemplate = @json(route('admin.knowledge.chunks.destroy', ['knowledge' => '__ID__']));

        const addBtn        = document.getElementById('add-chunk-btn');
        const modal         = document.getElementById('add-chunk-modal');
        const modalClose    = document.getElementById('add-chunk-modal-close');
        const modalCancel   = document.getElementById('add-chunk-cancel');
        const modalBackdrop = document.getElementById('add-chunk-backdrop');
        const submitBtn     = document.getElementById('add-chunk-submit');
        const contentField  = document.getElementById('chunk-content');
        const buField       = document.getElementById('chunk-business-unit');
        const errorMsg      = document.getElementById('add-chunk-error');
        const tbody         = document.getElementById('chunks-tbody');
        const totalBadge    = document.getElementById('chunks-total-badge');

        function openAddModal() {
            if (contentField) contentField.value = '';
            if (errorMsg) errorMsg.classList.add('hidden');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.classList.add('overflow-hidden');
        }

        function closeAddModal() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        }

        if (addBtn)        addBtn.addEventListener('click', openAddModal);
        if (modalClose)    modalClose.addEventListener('click', closeAddModal);
        if (modalCancel)   modalCancel.addEventListener('click', closeAddModal);
        if (modalBackdrop) modalBackdrop.addEventListener('click', closeAddModal);

        if (submitBtn) {
            submitBtn.addEventListener('click', async () => {
                const content = contentField ? contentField.value.trim() : '';
                const buId    = buField ? buField.value : '';

                if (!content) {
                    errorMsg.textContent = 'Content is required.';
                    errorMsg.classList.remove('hidden');
                    return;
                }
                errorMsg.classList.add('hidden');
                submitBtn.disabled = true;

                try {
                    const res = await fetch(storeUrl, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: JSON.stringify({ content, business_unit_id: buId }),
                    });
                    const payload = await res.json();
                    if (!res.ok || !payload.success) throw new Error(payload.message || 'Could not save chunk.');

                    const d  = payload.data;
                    const tr = document.createElement('tr');
                    tr.className    = 'transition-colors hover:bg-[#f8fafc]';
                    tr.dataset.id   = d.id;
                    tr.innerHTML = `
                        <td class="px-6 py-4 font-mono text-xs text-gray-400">${escHtml(String(d.id))}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center rounded-full bg-[#f1f5f9] px-2.5 py-1 text-xs font-semibold text-gray-700">
                                ${escHtml(d.business_unit)}
                            </span>
                        </td>
                        <td class="max-w-xl px-6 py-4">
                            <p class="truncate text-gray-700">${escHtml(d.content.length > 120 ? d.content.substring(0, 120) + '...' : d.content)}</p>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-gray-500">${escHtml(d.created_at)}</td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <div class="flex items-center gap-2">
                                <button type="button"
                                    class="btn-edit-chunk inline-flex items-center rounded-full border border-[#e2e8f0] bg-[#f8fafc] px-3 py-1 text-xs font-semibold text-gray-700 transition-colors hover:bg-[#e2e8f0]"
                                    data-id="${escHtml(String(d.id))}"
                                    data-content="${escHtml(d.content)}">
                                    Edit
                                </button>
                                <button type="button"
                                    class="btn-delete-chunk inline-flex items-center rounded-full border border-rose-200 bg-rose-50 px-3 py-1 text-xs font-semibold text-rose-700 transition-colors hover:bg-rose-100"
                                    data-id="${escHtml(String(d.id))}">
                                    Delete
                                </button>
                            </div>
                        </td>`;

                    if (tbody) {
                        const emptyRow = tbody.querySelector('td[colspan]');
                        if (emptyRow) emptyRow.closest('tr').remove();
                        tbody.prepend(tr);
                        bindDeleteBtn(tr.querySelector('.btn-delete-chunk'));
                        bindEditBtn(tr.querySelector('.btn-edit-chunk'));
                    }

                    if (totalBadge) {
                        const current = parseInt(totalBadge.textContent, 10) || 0;
                        totalBadge.textContent = (current + 1) + ' total';
                    }

                    closeAddModal();
                } catch (err) {
                    errorMsg.textContent = err.message || 'An error occurred.';
                    errorMsg.classList.remove('hidden');
                } finally {
                    submitBtn.disabled = false;
                }
            });
        }

        function bindDeleteBtn(btn) {
            if (!btn) return;
            btn.addEventListener('click', async () => {
                const id = btn.dataset.id;
                if (!confirm('Delete this chunk? This cannot be undone.')) return;
                btn.disabled = true;

                try {
                    const url = destroyUrlTemplate.replace('__ID__', encodeURIComponent(id));
                    const res = await fetch(url, {
                        method: 'DELETE',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });
                    const payload = await res.json();
                    if (!res.ok || !payload.success) throw new Error(payload.message || 'Delete failed.');

                    const row = btn.closest('tr');
                    if (row) row.remove();

                    if (totalBadge) {
                        const current = parseInt(totalBadge.textContent, 10) || 1;
                        totalBadge.textContent = Math.max(0, current - 1) + ' total';
                    }
                } catch (err) {
                    btn.disabled = false;
                    alert(err.message || 'Could not delete chunk.');
                }
            });
        }

        document.querySelectorAll('.btn-delete-chunk').forEach(btn => bindDeleteBtn(btn));

        // ── Edit chunk ───────────────────────────────────────────────────────
        const editModal         = document.getElementById('edit-chunk-modal');
        const editModalClose    = document.getElementById('edit-chunk-modal-close');
        const editModalCancel   = document.getElementById('edit-chunk-cancel');
        const editModalBackdrop = document.getElementById('edit-chunk-backdrop');
        const editSubmitBtn     = document.getElementById('edit-chunk-submit');
        const editContentField  = document.getElementById('edit-chunk-content');
        const editIdField       = document.getElementById('edit-chunk-id');
        const editErrorMsg      = document.getElementById('edit-chunk-error');
        const updateUrlTemplate = @json(route('admin.knowledge.chunks.update', ['knowledge' => '__ID__']));

        function openEditModal(id, content) {
            editIdField.value      = id;
            editContentField.value = content;
            editErrorMsg.classList.add('hidden');
            editModal.classList.remove('hidden');
            editModal.classList.add('flex');
            document.body.classList.add('overflow-hidden');
        }

        function closeEditModal() {
            editModal.classList.add('hidden');
            editModal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        }

        if (editModalClose)    editModalClose.addEventListener('click', closeEditModal);
        if (editModalCancel)   editModalCancel.addEventListener('click', closeEditModal);
        if (editModalBackdrop) editModalBackdrop.addEventListener('click', closeEditModal);

        if (editSubmitBtn) {
            editSubmitBtn.addEventListener('click', async () => {
                const id      = editIdField.value;
                const content = editContentField.value.trim();

                if (!content) {
                    editErrorMsg.textContent = 'Content is required.';
                    editErrorMsg.classList.remove('hidden');
                    return;
                }
                editErrorMsg.classList.add('hidden');
                editSubmitBtn.disabled = true;

                try {
                    const url = updateUrlTemplate.replace('__ID__', encodeURIComponent(id));
                    const res = await fetch(url, {
                        method: 'PATCH',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: JSON.stringify({ content }),
                    });
                    const payload = await res.json();
                    if (!res.ok || !payload.success) throw new Error(payload.message || 'Could not save changes.');

                    const d   = payload.data;
                    const row = tbody ? tbody.querySelector(`tr[data-id="${id}"]`) : null;
                    if (row) {
                        const cells = row.querySelectorAll('td');
                        // cells[2] = content preview
                        if (cells[2]) {
                            const p = cells[2].querySelector('p');
                            if (p) p.textContent = d.content.length > 120 ? d.content.substring(0, 120) + '...' : d.content;
                        }
                        // update data-content on the edit button for future opens
                        const editBtn = row.querySelector('.btn-edit-chunk');
                        if (editBtn) editBtn.dataset.content = d.content;
                    }

                    closeEditModal();
                } catch (err) {
                    editErrorMsg.textContent = err.message || 'An error occurred.';
                    editErrorMsg.classList.remove('hidden');
                } finally {
                    editSubmitBtn.disabled = false;
                }
            });
        }

        function bindEditBtn(btn) {
            if (!btn) return;
            btn.addEventListener('click', () => openEditModal(btn.dataset.id, btn.dataset.content));
        }

        document.querySelectorAll('.btn-edit-chunk').forEach(btn => bindEditBtn(btn));

        function escHtml(str) {
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');
        }
    });
    </script>
@endsection
