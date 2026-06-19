@extends('layouts.admin')

@section('page_title', 'Live Chat & Staff Handoff')
@section('page_description', 'Operate a split-screen console for active queues, conversation timelines, and live handoff actions.')
@section('breadcrumbs', 'Admin / Businesses / Chat & Handoff')

@section('content')
    <div class="space-y-8">
        <section class="rounded-[2rem] border border-[#eadfce] bg-white p-6 shadow-sm lg:p-8">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <h1 class="text-3xl font-black tracking-tight text-gray-900">Live Chat & Staff Handoff</h1>
                    <p class="mt-2 max-w-3xl text-sm leading-6 text-gray-500">Operate a split-screen console for active queues, conversation timelines, and live handoff actions.</p>
                </div>
                <span class="badge badge-warning badge-outline self-start">3 waiting · 2 escalated</span>
            </div>
        </section>

        <section class="grid gap-6 xl:grid-cols-[0.8fr_1.3fr_0.9fr]">
            <div class="card bg-base-100 shadow-sm">
                <div class="card-body p-6">
                    <h2 class="text-lg font-bold text-gray-900">Client queue</h2>
                    <div class="mt-4 space-y-3">
                        <div class="rounded-2xl bg-[#FAF8F4] p-4"><div class="font-semibold text-gray-800">Maria D.</div><div class="text-sm text-gray-500">Waiting · Booking confirmation</div></div>
                        <div class="rounded-2xl bg-[#FAF8F4] p-4"><div class="font-semibold text-gray-800">John P.</div><div class="text-sm text-gray-500">Bot override · Payment issue</div></div>
                        <div class="rounded-2xl bg-[#FAF8F4] p-4"><div class="font-semibold text-gray-800">Aya R.</div><div class="text-sm text-gray-500">Live with operator · Room inquiry</div></div>
                    </div>
                </div>
            </div>

            <div class="card bg-base-100 shadow-sm">
                <div class="card-body p-6">
                    <h2 class="text-lg font-bold text-gray-900">Conversation timeline</h2>
                    <div class="mt-4 space-y-3 text-sm text-gray-600">
                        <div class="rounded-2xl bg-[#F8F1E7] p-4">Bot answered: room availability</div>
                        <div class="rounded-2xl bg-white border border-gray-100 p-4">System: routed to human because payment details were requested</div>
                        <div class="rounded-2xl bg-[#F8F1E7] p-4">Operator joined the conversation</div>
                    </div>
                    <div class="mt-5 rounded-[1.5rem] border border-gray-200 bg-white p-4">
                        <div class="text-sm font-semibold text-gray-800">Live reply</div>
                        <div class="mt-3 h-28 rounded-2xl bg-gray-50"></div>
                    </div>
                </div>
            </div>

            <div class="card bg-base-100 shadow-sm">
                <div class="card-body p-6">
                    <h2 class="text-lg font-bold text-gray-900">Operator controls</h2>
                    <div class="mt-4 space-y-3 text-sm text-gray-600">
                        <div>Assign to self</div>
                        <div>Transfer to another operator</div>
                        <div>Mark resolved</div>
                        <div>Internal note</div>
                        <div>Send canned response</div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection