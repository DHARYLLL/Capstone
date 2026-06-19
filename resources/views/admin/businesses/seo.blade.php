@extends('layouts.admin')

@section('page_title', 'SEO & Profile Wizard')
@section('page_description', 'Manage identity, metadata, operational fields, landing-page content, and QR codes in one guided flow.')
@section('breadcrumbs', 'Admin / Businesses / SEO & Profile')

@section('content')
    <div class="space-y-8">
        <section class="rounded-[2rem] border border-[#eadfce] bg-white p-6 shadow-sm lg:p-8">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <h1 class="text-3xl font-black tracking-tight text-gray-900">SEO & Profile Wizard</h1>
                    <p class="mt-2 max-w-3xl text-sm leading-6 text-gray-500">Manage identity, metadata, operational fields, landing-page content, and QR codes in one guided flow.</p>
                </div>
                <div class="badge badge-success badge-outline self-start">Step 3 of 5</div>
            </div>
        </section>

        <section class="grid gap-6 xl:grid-cols-[1.5fr_1fr]">
            <div class="card bg-base-100 shadow-sm">
                <div class="card-body p-6 lg:p-8 space-y-6">
                    <div class="grid gap-4 md:grid-cols-2">
                        <label class="form-control"><div class="label"><span class="label-text font-medium text-gray-700">Business name</span></div><input class="input input-bordered bg-base-100" value="Villa Carmelita"></label>
                        <label class="form-control"><div class="label"><span class="label-text font-medium text-gray-700">Slug</span></div><input class="input input-bordered bg-base-100" value="villa-carmelita"></label>
                        <label class="form-control"><div class="label"><span class="label-text font-medium text-gray-700">Meta title</span></div><input class="input input-bordered bg-base-100" value="Villa Carmelita | Rooms, Events, and Poolside Stay"></label>
                        <label class="form-control"><div class="label"><span class="label-text font-medium text-gray-700">Meta description</span></div><input class="input input-bordered bg-base-100" value="Discover rooms, event spaces, and hospitality details for Villa Carmelita."></label>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <label class="form-control"><div class="label"><span class="label-text font-medium text-gray-700">Location</span></div><input class="input input-bordered bg-base-100" value="Central business district"></label>
                        <label class="form-control"><div class="label"><span class="label-text font-medium text-gray-700">Contact number</span></div><input class="input input-bordered bg-base-100" value="+63 912 345 6789"></label>
                    </div>

                    <div class="rounded-[1.5rem] bg-[#FAF8F4] p-5">
                        <div class="font-semibold text-gray-900">Landing path preview</div>
                        <div class="mt-2 text-sm text-gray-500">projectredai.com/villa-carmelita</div>
                        <div class="mt-4 flex items-center gap-4">
                            <div class="flex h-32 w-32 items-center justify-center rounded-[1.5rem] bg-white shadow-sm">QR</div>
                            <div class="space-y-2 text-sm text-gray-600">
                                <div>Download PNG</div>
                                <div>Download SVG</div>
                                <div>Regenerate QR</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card bg-base-100 shadow-sm">
                <div class="card-body p-6 lg:p-8">
                    <h2 class="text-xl font-bold text-gray-900">Live preview</h2>
                    <div class="mt-4 rounded-[2rem] bg-[#F8F1E7] p-5">
                        <div class="text-sm font-semibold text-gray-900">Villa Carmelita</div>
                        <p class="mt-2 text-sm text-gray-600">A comfortable hospitality destination for stays and gatherings.</p>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection