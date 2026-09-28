@extends('layouts.admin')

@section('page_title', 'Knowledge Base & Document Training')
@section('breadcrumbs', 'Admin / Knowledge Base')

@section('content')
<div class="space-y-8">
    <style>
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

    {{-- ── Top Hero Header Banner ────────────────────────────────────────── --}}
    <section class="relative overflow-hidden rounded-[2rem] border border-slate-200/80 bg-gradient-to-br from-white via-slate-50 to-purple-50/40 p-6 shadow-sm lg:p-8">
        <div class="absolute -right-16 -top-16 h-64 w-64 rounded-full bg-purple-500/5 blur-3xl pointer-events-none"></div>
        <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between relative z-10">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-purple-50 border border-purple-200/60 text-purple-700 text-xs font-bold tracking-wide uppercase mb-3">
                    <span class="w-2 h-2 rounded-full bg-purple-600 animate-pulse"></span>
                    RAG Vector Ingestion Pipeline
                </div>
                <h1 class="text-3xl font-black tracking-tight text-slate-900">Knowledge Base &amp; Training</h1>
                <p class="mt-1.5 max-w-2xl text-sm leading-relaxed text-slate-500">
                    Ingest policy PDFs, CSV pricing sheets, or direct manual knowledge chunks. All documents are parsed, chunked, and embedded into the vector search database.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <button type="button" id="add-chunk-btn"
                    class="inline-flex items-center gap-2 rounded-2xl bg-purple-600 px-5 py-2.5 text-xs font-bold text-white shadow-md shadow-purple-600/20 transition hover:bg-purple-700 active:scale-95">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Knowledge Manually
                </button>
            </div>
        </div>
    </section>

    {{-- ── Active Background Processing Banner ──────────────────────────── --}}
    <div id="kb-processing-banner"
        class="hidden items-center gap-3 rounded-2xl border border-purple-200 bg-purple-50 px-5 py-4 text-xs font-bold text-purple-800 shadow-sm">
        <span class="h-4 w-4 animate-spin rounded-full border-2 border-purple-300 border-t-purple-700"></span>
        <span id="kb-processing-banner-text">Training AI bot and vectorizing parsed content in background...</span>
    </div>

    {{-- ── Drag & Drop Upload Zone + Status KPIs ─────────────────────────── --}}
    <section class="space-y-6">
        <input type="file" id="kb-file-input" accept=".pdf,.csv,application/pdf,text/csv" class="sr-only" aria-label="Upload knowledge base file">

        <div id="kb-drop-zone"
            class="group cursor-pointer rounded-[2rem] border-2 border-dashed border-slate-200 bg-white p-8 text-center transition-all duration-300 hover:border-purple-500 hover:bg-purple-50/20 hover:shadow-md"
            role="button" tabindex="0" aria-label="Click or drag to upload a file">
            <div class="flex flex-col items-center gap-3">
                <div id="kb-plus-btn"
                    class="flex h-16 w-16 items-center justify-center rounded-3xl bg-purple-50 text-purple-600 ring-1 ring-purple-200 shadow-sm transition-transform duration-300 group-hover:scale-110 group-hover:bg-purple-600 group-hover:text-white">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-slate-900 group-hover:text-purple-700 transition-colors">
                        Drop PDF specification docs or CSV pricing tables here
                    </h2>
                    <p class="mt-1 text-xs text-slate-500">Supports PDF &amp; CSV documents up to 25 MB per upload</p>
                    <div class="mt-3 inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1 text-[11px] font-semibold text-slate-600">
                        <span>Click anywhere inside to browse local files</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- KPI Summary Strip --}}
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-3xl border border-slate-200/80 bg-white p-5 shadow-sm transition-all hover:border-purple-200">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Queued</span>
                    <span class="h-2.5 w-2.5 rounded-full bg-amber-400 animate-pulse"></span>
                </div>
                <div class="mt-2 text-3xl font-black text-slate-900" id="kpi-queued">0</div>
                <p class="mt-1 text-xs text-slate-400 font-medium">Awaiting background worker</p>
            </div>

            <div class="rounded-3xl border border-slate-200/80 bg-white p-5 shadow-sm transition-all hover:border-purple-200">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Processing</span>
                    <span class="h-2.5 w-2.5 rounded-full bg-blue-500"></span>
                </div>
                <div class="mt-2 text-3xl font-black text-slate-900" id="kpi-processing">0</div>
                <p class="mt-1 text-xs text-slate-400 font-medium">OCR parsing &amp; vector embedding</p>
            </div>

            <div class="rounded-3xl border border-slate-200/80 bg-white p-5 shadow-sm transition-all hover:border-purple-200">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Indexed Chunks</span>
                    <span class="h-2.5 w-2.5 rounded-full bg-purple-600"></span>
                </div>
                <div class="mt-2 text-3xl font-black text-purple-700" id="kpi-indexed">{{ $chunks->total() }}</div>
                <p class="mt-1 text-xs text-slate-400 font-medium">Active in AI search index</p>
            </div>

            <div class="rounded-3xl border border-slate-200/80 bg-white p-5 shadow-sm transition-all hover:border-purple-200">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Records</span>
                    <span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                </div>
                <div class="mt-2 text-3xl font-black text-slate-900" id="kpi-total">{{ $chunks->total() }}</div>
                <p class="mt-1 text-xs text-slate-400 font-medium">Lifetime chunks stored</p>
            </div>
        </div>

        {{-- Active Queue Status Card --}}
        <div class="rounded-3xl border border-slate-200/80 bg-white p-6 shadow-sm" id="staged-files-panel">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Ingestion Pipeline Monitor</h2>
                    <p class="mt-0.5 text-xs text-slate-400">Files appear here automatically while OCR tokenization and vector embeddings run.</p>
                </div>
                <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-600">
                    Auto-Polling Enabled
                </span>
            </div>
            <div class="mt-4 space-y-3" id="processing-queue-container">
                <div class="py-4 text-center text-xs text-slate-400" id="queue-empty-msg">
                    No active ingestion tasks in the pipeline queue.
                </div>
            </div>
        </div>
    </section>

    {{-- ── Indexed Knowledge Repository Explorer (Split Card / Table View) ── --}}
    <section class="rounded-3xl border border-slate-200/80 bg-white shadow-sm overflow-hidden">
        {{-- Header & View Toggle Toolbar --}}
        <div class="p-6 lg:p-7 border-b border-slate-100">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <div class="flex items-center gap-3">
                        <h2 class="text-lg font-bold text-slate-900">Indexed Knowledge Entries</h2>
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-purple-50 px-3 py-1 text-xs font-bold text-purple-700 ring-1 ring-purple-200">
                            <span class="h-1.5 w-1.5 rounded-full bg-purple-600"></span>
                            <span id="chunks-total-badge">{{ $chunks->total() }} chunks active</span>
                        </span>
                    </div>
                    <p class="mt-1 text-xs text-slate-400">Search and manage vectorized text chunks referenced by the AI assistant.</p>
                </div>

                {{-- Toolbar Actions (Search, Category Filter & View Mode) --}}
                <div class="flex flex-wrap items-center gap-3">
                    {{-- Quick Search Input --}}
                    <div class="relative min-w-[220px]">
                        <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" id="chunk-search-input" placeholder="Search chunk content..."
                            class="w-full rounded-full border border-slate-200 bg-slate-50/50 py-1.5 pl-9 pr-4 text-xs text-slate-800 placeholder-slate-400 transition focus:border-purple-600 focus:bg-white focus:outline-none" />
                    </div>

                    {{-- Business Unit Filter Select --}}
                    <select id="bu-filter-select"
                        class="rounded-full border border-slate-200 bg-slate-50/50 px-3.5 py-1.5 text-xs font-bold text-slate-700 transition focus:border-purple-600 focus:bg-white focus:outline-none">
                        <option value="all">All Divisions</option>
                        @foreach($businessUnits as $bu)
                            <option value="{{ strtolower($bu->name) }}">{{ $bu->name }}</option>
                        @endforeach
                    </select>

                    {{-- View Mode Switcher (Card Grid vs Dense Table) --}}
                    <div class="inline-flex rounded-full bg-slate-100 p-1">
                        <button type="button" id="view-card-btn" class="view-toggle-btn active rounded-full px-3 py-1 text-xs font-bold bg-purple-600 text-white shadow-sm transition">
                            Cards
                        </button>
                        <button type="button" id="view-table-btn" class="view-toggle-btn rounded-full px-3 py-1 text-xs font-bold text-slate-600 hover:text-slate-900 transition">
                            Table
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- View Option 1: Modern Split / Card Grid View (Default) --}}
        <div id="chunks-card-grid" class="p-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($chunks as $chunk)
                <div class="chunk-card group relative flex flex-col justify-between rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-purple-300 hover:shadow-lg hover:shadow-purple-500/5"
                    data-chunk-id="{{ $chunk->id }}"
                    data-bu="{{ strtolower($chunk->businessUnit?->name ?? 'general') }}"
                    data-content="{{ $chunk->content }}">
                    
                    <div>
                        <div class="flex items-center justify-between gap-2 border-b border-slate-100 pb-3">
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-purple-50 px-2.5 py-1 text-[11px] font-bold text-purple-700">
                                <span class="h-1.5 w-1.5 rounded-full bg-purple-600"></span>
                                {{ $chunk->businessUnit?->name ?? 'General' }}
                            </span>
                            <span class="font-mono text-[11px] text-slate-400 font-bold">#{{ $chunk->id }}</span>
                        </div>

                        <p class="chunk-text mt-3 text-xs leading-relaxed text-slate-700 font-normal line-clamp-4">
                            {{ $chunk->content }}
                        </p>
                    </div>

                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px]">
                        <span class="text-slate-400 font-medium">{{ $chunk->created_at->format('M d, Y') }}</span>
                        <div class="flex items-center gap-1.5">
                            <button type="button"
                                class="btn-edit-chunk rounded-lg border border-slate-200 bg-slate-50 px-2.5 py-1 font-bold text-slate-700 hover:bg-purple-50 hover:text-purple-700 hover:border-purple-200 transition"
                                data-id="{{ $chunk->id }}"
                                data-content="{{ $chunk->content }}">
                                Edit
                            </button>
                            <button type="button"
                                class="btn-delete-chunk rounded-lg border border-rose-200 bg-rose-50 px-2.5 py-1 font-bold text-rose-700 hover:bg-rose-100 transition"
                                data-id="{{ $chunk->id }}">
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center text-slate-400 text-xs">
                    No indexed chunks found. Upload a file above or click "+ Add Knowledge Manually" to create your first chunk.
                </div>
            @endforelse
        </div>

        {{-- View Option 2: Dense Table View (Hidden by default) --}}
        <div id="chunks-table-view" class="hidden overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/70 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                        <th class="px-6 py-3.5">#</th>
                        <th class="px-6 py-3.5">Division / Scope</th>
                        <th class="px-6 py-3.5">Content Excerpt</th>
                        <th class="px-6 py-3.5">Indexed At</th>
                        <th class="px-6 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100" id="chunks-tbody">
                    @forelse ($chunks as $chunk)
                        <tr class="chunk-table-row group transition-colors hover:bg-purple-50/30"
                            data-chunk-id="{{ $chunk->id }}"
                            data-bu="{{ strtolower($chunk->businessUnit?->name ?? 'general') }}"
                            data-content="{{ $chunk->content }}">
                            
                            <td class="px-6 py-4 font-mono text-xs font-bold text-slate-400">#{{ $chunk->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center rounded-full bg-purple-50 px-2.5 py-1 text-xs font-bold text-purple-700">
                                    {{ $chunk->businessUnit?->name ?? 'General' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-xs text-slate-700 max-w-xl truncate">{{ $chunk->content }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-400 font-medium">
                                {{ $chunk->created_at->format('M d, Y · H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button"
                                        class="btn-edit-chunk rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-bold text-slate-700 shadow-sm transition hover:border-purple-300 hover:bg-purple-50 hover:text-purple-700"
                                        data-id="{{ $chunk->id }}"
                                        data-content="{{ $chunk->content }}">
                                        Edit
                                    </button>
                                    <button type="button"
                                        class="btn-delete-chunk rounded-full border border-rose-200 bg-rose-50 px-3 py-1 text-xs font-bold text-rose-700 transition hover:bg-rose-100"
                                        data-id="{{ $chunk->id }}">
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-xs text-slate-400">
                                No indexed chunks yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($chunks->hasPages())
            <div class="border-t border-slate-100 p-4 sm:px-6">
                {{ $chunks->links() }}
            </div>
        @endif
    </section>
</div>

{{-- ════════════════════════════════════════════════════════════════════════
UPLOAD WIZARD MODAL (3-Step Pipeline)
════════════════════════════════════════════════════════════════════════ --}}
<div id="kb-upload-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4" role="dialog" aria-modal="true" aria-labelledby="modal-title">
    <div id="kb-modal-backdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>

    <div class="relative flex h-[88vh] w-full max-w-5xl flex-col overflow-hidden rounded-[2rem] bg-white shadow-2xl ring-1 ring-slate-900/10">
        {{-- Modal Header --}}
        <div class="flex items-start justify-between gap-4 border-b border-slate-100 bg-slate-50/80 px-6 py-5 sm:px-8">
            <div class="min-w-0">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold uppercase tracking-wider text-purple-700">RAG Document Ingestion</span>
                </div>
                <h2 id="modal-title" class="mt-1 truncate text-xl font-black tracking-tight text-slate-900">—</h2>
                <div class="mt-1.5 flex flex-wrap items-center gap-2 text-xs text-slate-500">
                    <span id="modal-file-status" class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-bold text-emerald-700 ring-1 ring-emerald-200">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                        Parsed Successfully
                    </span>
                    <span id="modal-file-summary">—</span>
                </div>
            </div>
            <button type="button" id="kb-modal-close" class="rounded-full p-1.5 text-slate-400 hover:bg-slate-200/60 hover:text-slate-600" aria-label="Close">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- 3-Step Wizard Navigation Pills --}}
        <div class="border-b border-slate-100 px-6 py-3.5 sm:px-8 bg-white">
            <div class="grid gap-3 sm:grid-cols-3">
                <div id="phase-pill-1" class="rounded-2xl border border-purple-600 bg-purple-600 px-4 py-2.5 text-xs font-bold text-white shadow-sm flex items-center gap-2">
                    <span class="flex h-5 w-5 items-center justify-center rounded-full bg-white/20 text-[10px]">1</span>
                    Upload &amp; Detect
                </div>
                <div id="phase-pill-2" class="rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-bold text-slate-500 flex items-center gap-2">
                    <span class="flex h-5 w-5 items-center justify-center rounded-full bg-slate-100 text-[10px]">2</span>
                    Verify Extracted Text
                </div>
                <div id="phase-pill-3" class="rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-bold text-slate-500 flex items-center gap-2">
                    <span class="flex h-5 w-5 items-center justify-center rounded-full bg-slate-100 text-[10px]">3</span>
                    Vectorize &amp; Train
                </div>
            </div>
        </div>

        {{-- Step 1: Upload & Detect --}}
        <div id="modal-step-review" class="hide-scrollbar flex-1 min-h-0 grid gap-6 overflow-y-auto px-6 py-6 sm:px-8 lg:grid-cols-[1fr_1.3fr]">
            <div class="space-y-4">
                <div class="rounded-2xl border border-slate-200/80 bg-slate-50/50 p-4">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-2">Document Snapshot</span>
                    <div class="grid grid-cols-2 gap-3 text-xs">
                        <div class="rounded-xl bg-white p-3 border border-slate-200/60">
                            <span class="text-slate-400 text-[10px] uppercase font-bold block">File Name</span>
                            <p id="modal-file-name" class="mt-1 truncate font-bold text-slate-800">—</p>
                        </div>
                        <div class="rounded-xl bg-white p-3 border border-slate-200/60">
                            <span class="text-slate-400 text-[10px] uppercase font-bold block">Size &amp; Type</span>
                            <p id="modal-file-meta" class="mt-1 font-bold text-slate-800">—</p>
                        </div>
                    </div>
                    <div id="modal-type-error" class="mt-3 hidden rounded-xl border border-rose-200 bg-rose-50 px-3.5 py-2.5 text-xs font-bold text-rose-700">
                        Unsupported file format. Only PDF and CSV files are accepted.
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200/80 bg-slate-50/50 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Browser Parsing</span>
                            <h3 class="mt-0.5 text-xs font-bold text-slate-900">OCR &amp; Token Extraction</h3>
                        </div>
                        <span id="modal-size-badge" class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-bold text-emerald-700 ring-1 ring-emerald-200">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                            Within limit
                        </span>
                    </div>

                    <div class="mt-3 rounded-xl bg-white p-3 border border-slate-200/60">
                        <div class="flex items-center justify-between text-[11px] font-bold text-slate-500 mb-1.5">
                            <span id="upload-progress-message">Reading file...</span>
                            <span id="upload-progress-text" class="text-purple-700 font-mono">0%</span>
                        </div>
                        <div class="h-2 w-full rounded-full bg-slate-100 overflow-hidden">
                            <div id="upload-progress-bar" class="h-full w-0 rounded-full bg-gradient-to-r from-purple-600 to-indigo-600 transition-all duration-300"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Text Preview Chunks --}}
            <div class="rounded-2xl border border-slate-200/80 bg-white p-4 flex flex-col">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-purple-700 block">Live Parser Output</span>
                        <h3 class="text-xs font-bold text-slate-900">Detected Chunk Sections</h3>
                    </div>
                    <span class="rounded-full bg-purple-50 px-2.5 py-0.5 text-[10px] font-bold text-purple-700">Preview</span>
                </div>
                <div id="modal-preview-chunks" class="hide-scrollbar mt-3 max-h-[48vh] space-y-3 overflow-y-auto pr-1"></div>
            </div>
        </div>

        {{-- Step 2: Verify Content --}}
        <div id="modal-step-verify" class="hide-scrollbar hidden flex-1 min-h-0 overflow-y-auto px-6 py-6 sm:px-8">
            <div class="space-y-4 max-w-3xl mx-auto">
                <div class="rounded-2xl border border-slate-200/80 bg-slate-50/60 p-5">
                    <h3 class="text-sm font-bold text-slate-900">Review &amp; Edit Content Before Vectorization</h3>
                    <p class="mt-1 text-xs text-slate-500">
                        Verify the parsed text below. You can refine formatting or fix OCR misreads before training the vector model.
                    </p>

                    <label for="confirm-edit-box" class="mt-4 block text-[10px] font-bold uppercase tracking-wider text-slate-400">Extracted Text Content</label>
                    <textarea id="confirm-edit-box" rows="12"
                        class="mt-1.5 w-full rounded-2xl border border-slate-200 bg-white p-4 font-mono text-xs leading-relaxed text-slate-800 focus:border-purple-600 focus:outline-none"
                        placeholder="Edit parsed text..."></textarea>

                    <div class="mt-4">
                        <label for="ingestion-mode" class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Ingestion Mode</label>
                        <select id="ingestion-mode"
                            class="mt-1.5 w-full rounded-xl border border-slate-200 bg-white p-2.5 text-xs font-bold text-slate-700 focus:border-purple-600 focus:outline-none">
                            <option value="append">Append to existing business knowledge</option>
                            <option value="overwrite">Overwrite this business unit's existing knowledge</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <button type="button" id="wizard-back-2"
                        class="rounded-full border border-slate-200 bg-white px-5 py-2 text-xs font-bold text-slate-700 hover:bg-slate-50">
                        ← Back
                    </button>
                    <div class="flex items-center gap-3">
                        <button type="button" id="kb-modal-cancel-2"
                            class="rounded-full border border-slate-200 bg-white px-5 py-2 text-xs font-bold text-slate-700 hover:bg-slate-50">
                            Cancel
                        </button>
                        <button type="button" id="kb-modal-confirm"
                            class="rounded-full bg-purple-600 px-6 py-2 text-xs font-bold text-white shadow-md shadow-purple-600/20 hover:bg-purple-700">
                            Approve &amp; Train AI
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Step 3: Training / Success --}}
        <div id="modal-step-training" class="hidden flex-1 min-h-0 overflow-y-auto px-6 py-8 sm:px-8">
            <div class="max-w-xl mx-auto space-y-6">
                {{-- In Progress Panel --}}
                <div id="training-progress-panel" class="rounded-3xl border border-purple-100 bg-purple-50/40 p-6 text-center space-y-4">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-3xl bg-purple-600 text-white shadow-lg shadow-purple-600/30">
                        <svg class="h-7 w-7 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Vectorizing &amp; Indexing Knowledge</h3>
                        <p class="text-xs text-slate-500 mt-1">Staging document chunks and computing embeddings in the background...</p>
                    </div>

                    <div class="space-y-2 text-left text-xs">
                        <div class="flex items-center gap-2.5 rounded-xl bg-white p-3 border border-slate-100" id="processing-step-1">
                            <span class="h-2 w-2 rounded-full bg-purple-600 animate-pulse"></span>
                            <span class="font-medium text-slate-700">Uploading &amp; staging file...</span>
                        </div>
                        <div class="flex items-center gap-2.5 rounded-xl bg-white p-3 border border-slate-100" id="processing-step-2">
                            <span class="h-2 w-2 rounded-full bg-slate-300"></span>
                            <span class="font-medium text-slate-700">Generating vector embeddings...</span>
                        </div>
                        <div class="flex items-center gap-2.5 rounded-xl bg-white p-3 border border-slate-100" id="processing-step-3">
                            <span class="h-2 w-2 rounded-full bg-slate-300"></span>
                            <span class="font-medium text-slate-700">Syncing live chatbot index...</span>
                        </div>
                    </div>
                </div>

                {{-- Success Panel --}}
                <div id="training-success-panel" class="hidden rounded-3xl border border-emerald-200 bg-emerald-50/60 p-6 text-center space-y-4">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-3xl bg-emerald-500 text-white text-2xl shadow-lg shadow-emerald-500/30">
                        ✓
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-emerald-950">Successfully Queued &amp; Trained!</h3>
                        <p class="text-xs text-emerald-800 mt-1">Your document has been sent to the vector pipeline and will be live in seconds.</p>
                    </div>
                    <div class="flex justify-center gap-3 pt-2">
                        <button type="button" id="kb-success-close" class="rounded-full bg-emerald-600 px-6 py-2 text-xs font-bold text-white shadow-sm hover:bg-emerald-700">
                            Done
                        </button>
                    </div>
                </div>

                {{-- Error Panel --}}
                <div id="training-error-panel" class="hidden rounded-3xl border border-rose-200 bg-rose-50 p-6 text-center space-y-3">
                    <p class="text-xs font-bold uppercase tracking-wider text-rose-600">Ingestion Error</p>
                    <p class="text-xs font-medium text-rose-800" id="training-error-message">An unexpected error occurred.</p>
                    <button type="button" id="training-retry-btn" class="rounded-full border border-rose-300 bg-white px-5 py-2 text-xs font-bold text-rose-700 hover:bg-rose-50">
                        ← Try Again
                    </button>
                </div>
            </div>
        </div>

        {{-- Step 1 Footer Action --}}
        <div class="border-t border-slate-100 bg-slate-50/50 px-6 py-4 sm:px-8 flex justify-between items-center" id="modal-review-actions">
            <button type="button" id="kb-modal-cancel" class="rounded-full border border-slate-200 bg-white px-5 py-2 text-xs font-bold text-slate-700 hover:bg-slate-50">
                Cancel
            </button>
            <button type="button" id="wizard-next-1" class="rounded-full bg-purple-600 px-6 py-2 text-xs font-bold text-white shadow-md shadow-purple-600/20 hover:bg-purple-700">
                Continue to Verification →
            </button>
        </div>
    </div>
</div>

{{-- ── Edit Chunk Modal ──────────────────────────────────────────────────── --}}
<div id="edit-chunk-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" id="edit-chunk-backdrop"></div>
    <div class="relative w-full max-w-lg overflow-hidden rounded-3xl bg-white shadow-2xl ring-1 ring-slate-900/10">
        <div class="flex items-center justify-between border-b border-slate-100 bg-slate-50/70 px-6 py-4">
            <div class="flex items-center gap-2">
                <div class="flex h-7 w-7 items-center justify-center rounded-xl bg-purple-50 text-purple-600 font-bold text-xs">
                    ✏️
                </div>
                <h3 class="text-base font-bold text-slate-900">Edit Knowledge Chunk</h3>
            </div>
            <button type="button" id="edit-chunk-modal-close" class="rounded-full p-1.5 text-slate-400 hover:bg-slate-200/60 hover:text-slate-600">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="p-6 space-y-4">
            <input type="hidden" id="edit-chunk-id">
            <div>
                <label for="edit-chunk-content" class="mb-1.5 block text-[10px] font-bold uppercase tracking-wider text-slate-400">Content</label>
                <textarea id="edit-chunk-content" rows="6"
                    class="w-full resize-y rounded-2xl border border-slate-200 bg-slate-50/50 p-4 font-mono text-xs leading-relaxed text-slate-800 focus:border-purple-600 focus:bg-white focus:outline-none"
                    placeholder="Edit chunk content..."></textarea>
            </div>
            <p id="edit-chunk-error" class="hidden text-xs font-bold text-rose-600"></p>
        </div>
        <div class="flex items-center justify-end gap-3 border-t border-slate-100 bg-slate-50/50 px-6 py-4">
            <button type="button" id="edit-chunk-cancel" class="rounded-full border border-slate-200 bg-white px-5 py-2 text-xs font-bold text-slate-700 hover:bg-slate-50">
                Cancel
            </button>
            <button type="button" id="edit-chunk-submit" class="rounded-full bg-purple-600 px-6 py-2 text-xs font-bold text-white shadow-md shadow-purple-600/20 hover:bg-purple-700">
                Save Changes
            </button>
        </div>
    </div>
</div>

{{-- ── Add Chunk Modal ──────────────────────────────────────────────────── --}}
<div id="add-chunk-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" id="add-chunk-backdrop"></div>
    <div class="relative w-full max-w-lg overflow-hidden rounded-3xl bg-white shadow-2xl ring-1 ring-slate-900/10">
        <div class="flex items-center justify-between border-b border-slate-100 bg-slate-50/70 px-6 py-4">
            <div class="flex items-center gap-2">
                <div class="flex h-7 w-7 items-center justify-center rounded-xl bg-purple-50 text-purple-600 font-bold text-xs">
                    ➕
                </div>
                <h3 class="text-base font-bold text-slate-900">Add Knowledge Chunk Manually</h3>
            </div>
            <button type="button" id="add-chunk-modal-close" class="rounded-full p-1.5 text-slate-400 hover:bg-slate-200/60 hover:text-slate-600">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="p-6 space-y-4">
            <div>
                <label for="chunk-business-unit" class="mb-1.5 block text-[10px] font-bold uppercase tracking-wider text-slate-400">Target Business Unit</label>
                <select id="chunk-business-unit"
                    class="w-full rounded-xl border border-slate-200 bg-slate-50/50 p-2.5 text-xs font-bold text-slate-700 focus:border-purple-600 focus:bg-white focus:outline-none">
                    @foreach($businessUnits as $unit)
                        <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="chunk-content" class="mb-1.5 block text-[10px] font-bold uppercase tracking-wider text-slate-400">Knowledge Content</label>
                <textarea id="chunk-content" rows="6"
                    class="w-full resize-y rounded-2xl border border-slate-200 bg-slate-50/50 p-4 font-mono text-xs leading-relaxed text-slate-800 focus:border-purple-600 focus:bg-white focus:outline-none"
                    placeholder="Enter precise policies, FAQs, or operational specs..."></textarea>
            </div>
            <p id="add-chunk-error" class="hidden text-xs font-bold text-rose-600"></p>
        </div>
        <div class="flex items-center justify-end gap-3 border-t border-slate-100 bg-slate-50/50 px-6 py-4">
            <button type="button" id="add-chunk-cancel" class="rounded-full border border-slate-200 bg-white px-5 py-2 text-xs font-bold text-slate-700 hover:bg-slate-50">
                Cancel
            </button>
            <button type="button" id="add-chunk-submit" class="rounded-full bg-purple-600 px-6 py-2 text-xs font-bold text-white shadow-md shadow-purple-600/20 hover:bg-purple-700">
                Save &amp; Index
            </button>
        </div>
    </div>
</div>

{{-- PDF.js for Client-Side Extraction --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // ── Global references & tokens ──────────────────────────────────────────
    const csrfToken          = @json(csrf_token());
    const uploadUrl          = @json(route('admin.knowledge.upload'));
    const approveUrlTemplate = @json(route('admin.knowledge.approve', ['stagedDocument' => '__ID__']));
    const stagedUrl          = @json(route('admin.knowledge.staged'));
    const storeUrl           = @json(route('admin.knowledge.chunks.store'));
    const updateUrlTemplate  = @json(route('admin.knowledge.chunks.update', ['knowledge' => '__ID__']));
    const destroyUrlTemplate = @json(route('admin.knowledge.chunks.destroy', ['knowledge' => '__ID__']));

    // Upload wizard elements
    const fileInput             = document.getElementById('kb-file-input');
    const dropZone              = document.getElementById('kb-drop-zone');
    const modal                 = document.getElementById('kb-upload-modal');
    const backdrop              = document.getElementById('kb-modal-backdrop');
    const modalClose            = document.getElementById('kb-modal-close');
    const modalCancel           = document.getElementById('kb-modal-cancel');
    const modalCancel2          = document.getElementById('kb-modal-cancel-2');
    const modalConfirm          = document.getElementById('kb-modal-confirm');
    const wizardNext1           = document.getElementById('wizard-next-1');
    const wizardBack2           = document.getElementById('wizard-back-2');
    const trainingRetryBtn      = document.getElementById('training-retry-btn');
    const modalTitle            = document.getElementById('modal-title');
    const modalStatus           = document.getElementById('modal-file-status');
    const modalFileName         = document.getElementById('modal-file-name');
    const modalFileSummary      = document.getElementById('modal-file-summary');
    const modalSizeBadge        = document.getElementById('modal-size-badge');
    const modalTypeError        = document.getElementById('modal-type-error');
    const modalPreviewChunks    = document.getElementById('modal-preview-chunks');
    const modalStepReview       = document.getElementById('modal-step-review');
    const modalStepVerify       = document.getElementById('modal-step-verify');
    const modalStepTraining     = document.getElementById('modal-step-training');
    const modalReviewActions    = document.getElementById('modal-review-actions');
    const uploadProgressBar     = document.getElementById('upload-progress-bar');
    const uploadProgressText    = document.getElementById('upload-progress-text');
    const uploadProgressMessage = document.getElementById('upload-progress-message');
    const phasePill1            = document.getElementById('phase-pill-1');
    const phasePill2            = document.getElementById('phase-pill-2');
    const phasePill3            = document.getElementById('phase-pill-3');
    const confirmEditBox        = document.getElementById('confirm-edit-box');
    const ingestionModeField    = document.getElementById('ingestion-mode');
    const trainingProgressPanel = document.getElementById('training-progress-panel');
    const trainingSuccessPanel  = document.getElementById('training-success-panel');
    const trainingErrorPanel    = document.getElementById('training-error-panel');
    const trainingErrorMessage  = document.getElementById('training-error-message');
    const successClose          = document.getElementById('kb-success-close');
    const queueCont             = document.getElementById('processing-queue-container');
    const kpiQueued             = document.getElementById('kpi-queued');
    const processingBanner      = document.getElementById('kb-processing-banner');
    const processingBannerText  = document.getElementById('kb-processing-banner-text');
    const processingStep1       = document.getElementById('processing-step-1');
    const processingStep2       = document.getElementById('processing-step-2');
    const processingStep3       = document.getElementById('processing-step-3');

    let currentFile       = null;
    let currentParsedText = '';
    const maxFileSize     = 25 * 1024 * 1024;

    // View toggle (Cards vs Table)
    const viewCardBtn  = document.getElementById('view-card-btn');
    const viewTableBtn = document.getElementById('view-table-btn');
    const cardGrid     = document.getElementById('chunks-card-grid');
    const tableView    = document.getElementById('chunks-table-view');

    viewCardBtn?.addEventListener('click', () => {
        viewCardBtn.classList.add('active', 'bg-purple-600', 'text-white', 'shadow-sm');
        viewCardBtn.classList.remove('text-slate-600');
        viewTableBtn.classList.remove('active', 'bg-purple-600', 'text-white', 'shadow-sm');
        viewTableBtn.classList.add('text-slate-600');
        cardGrid.classList.remove('hidden');
        tableView.classList.add('hidden');
    });

    viewTableBtn?.addEventListener('click', () => {
        viewTableBtn.classList.add('active', 'bg-purple-600', 'text-white', 'shadow-sm');
        viewTableBtn.classList.remove('text-slate-600');
        viewCardBtn.classList.remove('active', 'bg-purple-600', 'text-white', 'shadow-sm');
        viewCardBtn.classList.add('text-slate-600');
        tableView.classList.remove('hidden');
        cardGrid.classList.add('hidden');
    });

    // Client-side Search & BU filtering
    const searchInput = document.getElementById('chunk-search-input');
    const buFilter    = document.getElementById('bu-filter-select');

    function filterKnowledge() {
        const query = (searchInput?.value || '').toLowerCase().trim();
        const bu    = (buFilter?.value || 'all').toLowerCase();

        // Cards filter
        document.querySelectorAll('.chunk-card').forEach(card => {
            const content = (card.dataset.content || '').toLowerCase();
            const cardBu  = (card.dataset.bu || '').toLowerCase();
            const matchesQuery = !query || content.includes(query);
            const matchesBu    = bu === 'all' || cardBu.includes(bu);
            card.style.display = matchesQuery && matchesBu ? '' : 'none';
        });

        // Table rows filter
        document.querySelectorAll('.chunk-table-row').forEach(row => {
            const content = (row.dataset.content || '').toLowerCase();
            const rowBu   = (row.dataset.bu || '').toLowerCase();
            const matchesQuery = !query || content.includes(query);
            const matchesBu    = bu === 'all' || rowBu.includes(bu);
            row.style.display = matchesQuery && matchesBu ? '' : 'none';
        });
    }

    searchInput?.addEventListener('input', filterKnowledge);
    buFilter?.addEventListener('change', filterKnowledge);

    // ── Upload Wizard Steps ────────────────────────────────────────────────
    function setModalStep(step) {
        modalStepReview?.classList.toggle('hidden', step !== 1);
        modalStepVerify?.classList.toggle('hidden', step !== 2);
        modalStepTraining?.classList.toggle('hidden', step !== 3);
        modalReviewActions?.classList.toggle('hidden', step !== 1);

        const pills = [phasePill1, phasePill2, phasePill3];
        pills.forEach((pill, idx) => {
            if (!pill) return;
            const active   = step === idx + 1;
            const complete = step > idx + 1;
            pill.className = active
                ? 'rounded-2xl border border-purple-600 bg-purple-600 px-4 py-2.5 text-xs font-bold text-white shadow-sm flex items-center gap-2'
                : complete
                    ? 'rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-2.5 text-xs font-bold text-emerald-700 flex items-center gap-2'
                    : 'rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-bold text-slate-500 flex items-center gap-2';
        });

        if (step === 2 && confirmEditBox) confirmEditBox.value = currentParsedText || '';
    }

    const openFilePicker = () => fileInput.click();
    dropZone?.addEventListener('click', openFilePicker);
    dropZone?.addEventListener('dragover', (e) => { e.preventDefault(); dropZone.classList.add('border-purple-600', 'bg-purple-50/30'); });
    dropZone?.addEventListener('dragleave', () => dropZone.classList.remove('border-purple-600', 'bg-purple-50/30'));
    dropZone?.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-purple-600', 'bg-purple-50/30');
        if (e.dataTransfer.files[0]) handleFile(e.dataTransfer.files[0]);
    });
    fileInput?.addEventListener('change', () => { if (fileInput.files.length) handleFile(fileInput.files[0]); });

    function handleFile(file) {
        currentFile = file;
        currentParsedText = '';
        const isCsv = file.type === 'text/csv' || file.name.toLowerCase().endsWith('.csv');
        const isPdf = file.type === 'application/pdf' || file.name.toLowerCase().endsWith('.pdf');
        const isAllowed = isCsv || isPdf;
        const isWithinLimit = file.size <= maxFileSize;

        if (modalTitle) modalTitle.textContent = file.name;
        if (modalFileName) modalFileName.textContent = file.name;
        if (modalFileSummary) modalFileSummary.textContent = `${formatBytes(file.size)} · ${isCsv ? 'CSV' : 'PDF'}`;
        if (uploadProgressText) uploadProgressText.textContent = '15%';
        if (uploadProgressBar) uploadProgressBar.style.width = '15%';

        if (!isAllowed || !isWithinLimit) {
            renderPreviewChunks(file, 'Unsupported file format or exceeds 25 MB limit.');
            openModal();
            return;
        }

        const reader = new FileReader();
        if (isCsv) {
            reader.onload = (e) => {
                currentParsedText = e.target.result || '';
                renderPreviewChunks(file, currentParsedText);
                if (uploadProgressText) uploadProgressText.textContent = '100%';
                if (uploadProgressBar) uploadProgressBar.style.width = '100%';
                openModal();
            };
            reader.readAsText(file);
        } else {
            reader.onload = async (e) => {
                try {
                    const pdfjsLib = window['pdfjs-dist/build/pdf'];
                    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
                    const typedArray = new Uint8Array(e.target.result);
                    const pdf = await pdfjsLib.getDocument({ data: typedArray }).promise;
                    const pageTexts = [];
                    for (let i = 1; i <= pdf.numPages; i++) {
                        const page = await pdf.getPage(i);
                        const content = await page.getTextContent();
                        const text = content.items.map(item => item.str).join(' ');
                        if (text.trim()) pageTexts.push(text.trim());
                    }
                    currentParsedText = pageTexts.join('\n\n');
                    renderPreviewChunks(file, currentParsedText);
                    if (uploadProgressText) uploadProgressText.textContent = '100%';
                    if (uploadProgressBar) uploadProgressBar.style.width = '100%';
                } catch (err) {
                    renderPreviewChunks(file, 'PDF OCR preview unavailable.');
                }
                openModal();
            };
            reader.readAsArrayBuffer(file);
        }
    }

    function renderPreviewChunks(file, parsedText) {
        if (!modalPreviewChunks) return;
        const cleaned = String(parsedText || '').trim();
        const chunks = cleaned.split(/\n{2,}/).filter(Boolean).slice(0, 8);
        modalPreviewChunks.innerHTML = chunks.map((c, i) => `
            <div class="rounded-xl border border-slate-100 bg-slate-50/60 p-3 text-xs">
                <span class="font-bold text-purple-700 text-[10px] uppercase">Chunk ${i + 1}</span>
                <p class="mt-1 text-slate-700 line-clamp-3">${escHtml(c)}</p>
            </div>
        `).join('');
    }

    function openModal() {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setModalStep(1);
    }
    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        fileInput.value = '';
    }

    modalClose?.addEventListener('click', closeModal);
    modalCancel?.addEventListener('click', closeModal);
    modalCancel2?.addEventListener('click', closeModal);
    backdrop?.addEventListener('click', closeModal);
    successClose?.addEventListener('click', () => { closeModal(); window.location.reload(); });
    wizardNext1?.addEventListener('click', () => setModalStep(2));
    wizardBack2?.addEventListener('click', () => setModalStep(1));

    // Confirm & Train
    modalConfirm?.addEventListener('click', async () => {
        if (!currentFile) return;
        modalConfirm.disabled = true;
        setModalStep(3);

        try {
            const formData = new FormData();
            formData.append('file', currentFile);
            formData.append('edited_content', confirmEditBox ? confirmEditBox.value.trim() : '');
            formData.append('ingestion_mode', ingestionModeField ? ingestionModeField.value : 'append');

            const res = await fetch(uploadUrl, {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
                body: formData,
            });
            const payload = await res.json();
            if (!res.ok || !payload.success) throw new Error(payload.message || 'Upload failed.');

            const stagedId = payload.data.id;
            const approveUrl = approveUrlTemplate.replace('__ID__', encodeURIComponent(stagedId));
            const approveRes = await fetch(approveUrl, {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
            });
            const approvePayload = await approveRes.json();
            if (!approveRes.ok || !approvePayload.success) throw new Error(approvePayload.message || 'Approval failed.');

            trainingProgressPanel.classList.add('hidden');
            trainingSuccessPanel.classList.remove('hidden');
            setTimeout(() => window.location.reload(), 1500);
        } catch (err) {
            trainingProgressPanel.classList.add('hidden');
            trainingErrorPanel.classList.remove('hidden');
            if (trainingErrorMessage) trainingErrorMessage.textContent = err.message || 'Pipeline failed.';
            modalConfirm.disabled = false;
        }
    });

    // ── Add Chunk Modal Logic ──────────────────────────────────────────────
    const addModal = document.getElementById('add-chunk-modal');
    const addBtn   = document.getElementById('add-chunk-btn');
    const addClose = document.getElementById('add-chunk-modal-close');
    const addCancel= document.getElementById('add-chunk-cancel');
    const addBackdrop = document.getElementById('add-chunk-backdrop');
    const addSubmit= document.getElementById('add-chunk-submit');
    const buSelect = document.getElementById('chunk-business-unit');
    const addContent = document.getElementById('chunk-content');
    const addError = document.getElementById('add-chunk-error');

    function openAddModal() {
        if (addContent) addContent.value = '';
        addError?.classList.add('hidden');
        addModal?.classList.remove('hidden');
        addModal?.classList.add('flex');
    }
    function closeAddModal() {
        addModal?.classList.add('hidden');
        addModal?.classList.remove('flex');
    }

    addBtn?.addEventListener('click', openAddModal);
    addClose?.addEventListener('click', closeAddModal);
    addCancel?.addEventListener('click', closeAddModal);
    addBackdrop?.addEventListener('click', closeAddModal);

    addSubmit?.addEventListener('click', async () => {
        const content = addContent?.value.trim();
        const buId = buSelect?.value;
        if (!content) {
            addError.textContent = 'Please enter chunk content.';
            addError.classList.remove('hidden');
            return;
        }
        addSubmit.disabled = true;
        try {
            const res = await fetch(storeUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ content, business_unit_id: buId })
            });
            const d = await res.json();
            if (!res.ok || !d.success) throw new Error(d.message || 'Failed to save.');
            window.location.reload();
        } catch (e) {
            addError.textContent = e.message;
            addError.classList.remove('hidden');
            addSubmit.disabled = false;
        }
    });

    // ── Edit & Delete Chunk Bindings ─────────────────────────────────────────
    const editModal = document.getElementById('edit-chunk-modal');
    const editClose = document.getElementById('edit-chunk-modal-close');
    const editCancel= document.getElementById('edit-chunk-cancel');
    const editBackdrop = document.getElementById('edit-chunk-backdrop');
    const editSubmit= document.getElementById('edit-chunk-submit');
    const editId    = document.getElementById('edit-chunk-id');
    const editContent = document.getElementById('edit-chunk-content');
    const editError = document.getElementById('edit-chunk-error');

    function openEdit(id, content) {
        if (editId) editId.value = id;
        if (editContent) editContent.value = content;
        editError?.classList.add('hidden');
        editModal?.classList.remove('hidden');
        editModal?.classList.add('flex');
    }
    function closeEdit() {
        editModal?.classList.add('hidden');
        editModal?.classList.remove('flex');
    }

    editClose?.addEventListener('click', closeEdit);
    editCancel?.addEventListener('click', closeEdit);
    editBackdrop?.addEventListener('click', closeEdit);

    document.querySelectorAll('.btn-edit-chunk').forEach(btn => {
        btn.addEventListener('click', () => openEdit(btn.dataset.id, btn.dataset.content));
    });

    editSubmit?.addEventListener('click', async () => {
        const id = editId.value;
        const content = editContent.value.trim();
        if (!content) return;
        editSubmit.disabled = true;
        try {
            const url = updateUrlTemplate.replace('__ID__', encodeURIComponent(id));
            const res = await fetch(url, {
                method: 'PATCH',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ content })
            });
            const d = await res.json();
            if (!res.ok || !d.success) throw new Error(d.message || 'Update failed.');
            window.location.reload();
        } catch (e) {
            editError.textContent = e.message;
            editError.classList.remove('hidden');
            editSubmit.disabled = false;
        }
    });

    document.querySelectorAll('.btn-delete-chunk').forEach(btn => {
        btn.addEventListener('click', async () => {
            if (!confirm('Are you sure you want to delete this knowledge chunk?')) return;
            const id = btn.dataset.id;
            const url = destroyUrlTemplate.replace('__ID__', encodeURIComponent(id));
            try {
                const res = await fetch(url, {
                    method: 'DELETE',
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
                });
                const d = await res.json();
                if (res.ok && d.success) window.location.reload();
            } catch (e) {
                alert('Could not delete chunk.');
            }
        });
    });

    function formatBytes(b) {
        if (b < 1024) return b + ' B';
        if (b < 1048576) return (b / 1024).toFixed(1) + ' KB';
        return (b / 1048576).toFixed(2) + ' MB';
    }

    function escHtml(s) {
        return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#39;');
    }
});
</script>
@endsection
