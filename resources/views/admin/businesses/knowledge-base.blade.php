@extends('layouts.admin')

@section('page_title', 'Knowledge Base Upload')
@section('page_description', 'Upload PDFs and CSVs, review processing results, and approve knowledge entries for chatbot use.')
@section('breadcrumbs', 'Admin / Businesses / Knowledge Base')

@section('content')
    <div class="space-y-8">
        <section class="rounded-[2rem] border border-[#eadfce] bg-white p-6 shadow-sm lg:p-8">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <h1 class="text-3xl font-black tracking-tight text-gray-900">Knowledge Base Upload</h1>
                    <p class="mt-2 max-w-3xl text-sm leading-6 text-gray-500">
                        Upload PDFs and CSVs, review processing results, and approve knowledge entries for chatbot use.
                    </p>
                </div>
                <div class="flex gap-3">
                    <button class="btn rounded-full border-0 bg-[#5A3E2B] text-white hover:bg-[#453020]">Upload files</button>
                    <button class="btn btn-outline rounded-full border-gray-300 text-gray-700 hover:bg-[#F4EEDF]">View logs</button>
                </div>
            </div>
        </section>

        <section class="grid gap-6 xl:grid-cols-[1.3fr_1fr]">
            <div class="space-y-6">
                <div class="rounded-[2rem] border-2 border-dashed border-[#d8c6b0] bg-[#FBF8F2] p-8">
                    <div class="flex flex-col items-center gap-3 text-center">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[#5A3E2B] text-white">
                            <span class="text-xl font-black">+</span>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">Drop PDF policy papers or CSV tables here</h2>
                            <p class="mt-1 text-sm text-gray-500">Supported: PDF, CSV · Max size: 25 MB per file</p>
                        </div>
                    </div>
                </div>

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
                                    <span class="badge badge-warning badge-outline">62%</span>
                                </div>
                                <div class="mt-3 h-2 rounded-full bg-gray-200"><div class="h-2 w-[62%] rounded-full bg-[#5A3E2B]"></div></div>
                            </div>
                            <div class="rounded-2xl bg-[#FAF8F4] p-4">
                                <div class="flex items-center justify-between gap-4">
                                    <div>
                                        <div class="font-semibold text-gray-800">Restaurant menu.csv</div>
                                        <div class="text-sm text-gray-500">CSV · 42 rows · Validated</div>
                                    </div>
                                    <span class="badge badge-success badge-outline">Ready</span>
                                </div>
                                <p class="mt-2 text-xs text-emerald-600">Columns mapped successfully · 2 duplicate rows skipped</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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
                        <div class="mt-4 space-y-3 text-sm text-gray-600">
                            <div>Preview extracted text</div>
                            <div>Replace upload</div>
                            <div>Mark approved</div>
                            <div>Archive source file</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection