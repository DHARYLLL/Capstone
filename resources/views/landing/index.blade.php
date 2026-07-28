@extends('layouts.landing')

@section('header')
    @include('partials.platform-navbar')
@endsection

@section('content')
    @php
        $heroBadge = 'Now Onboarding New Businesses';
        $heroHeadline = 'Automate customer interactions across every branch with localized AI built for multi-unit operations.';
        $heroDescription = 'Project RED AI centralizes customer support, branch knowledge, and staff handoff for hotels, restaurants, pools, and other business groups that need one system with many tailored experiences.';
        $heroSupportLine = 'One core database. Many business instances. Faster service for owners, staff, and customers.';
        $primaryCtaLabel = 'Sign Up Your Business';
        $secondaryCtaLabel = 'Take the Feature Tour';
        $heroHighlights = [
            'Multi-tenant knowledge base architecture',
            'PDF and CSV ingestion workflows',
            'Live staff handoff terminal',
        ];
        $unitCards = [
            [
                'title' => 'AI Knowledge Engine',
                'description' => 'Project RED AI reads the business knowledge base and generates customer-ready answers from approved company information.',
                'points' => ['Context-aware replies', 'Business policy matching', 'Consistent answer quality'],
            ],
            [
                'title' => 'Document Intelligence',
                'description' => 'Uploaded PDFs and CSV files are transformed into structured operational knowledge the AI can search and explain.',
                'points' => ['PDF content extraction', 'CSV data organization', 'Searchable knowledge records'],
            ],
            [
                'title' => 'Smart Staff Handoff',
                'description' => 'When automation is not enough, unresolved conversations can move to staff with the customer context preserved.',
                'points' => ['Escalation detection', 'Conversation history', 'Live support routing'],
            ],
        ];
        $wizardSteps = [
            [
                'step' => '01',
                'title' => 'Account Creation',
                'description' => 'Create the owner account that will control the business workspace and registration flow.',
                'fields' => ['Owner name', 'Email address', 'Master secure credentials'],
            ],
            [
                'step' => '02',
                'title' => 'Company Setup',
                'description' => 'Define the legal business profile and operating context used to shape the AI instance.',
                'fields' => ['Legal business name', 'Primary industry category', 'Operating hours'],
            ],
            [
                'step' => '03',
                'title' => 'Platform Subpath Provisioning',
                'description' => 'Claim a branded system URL subpath and validate availability and resource bounds in real time.',
                'fields' => ['Custom URL subpath', 'Availability check', 'Resource limit validation'],
            ],
        ];
        $comparisonRows = [
            ['Capability', 'Project RED AI', 'Rigid Old-School Chat Setup'],
            ['Multi-tenant knowledge base', 'Branch-specific, centrally managed, and scalable', 'Single shared script with limited tenant awareness'],
            ['PDF parsing workflows', 'Structured onboarding from business documents', 'Manual copy-paste or no document ingestion'],
            ['CSV parsing workflows', 'Bulk operational data can be imported and organized', 'Weak or no structured file support'],
            ['Branch-specific responses', 'Answers adapt to hotels, dining, and leisure contexts', 'Generic replies regardless of business type'],
            ['Live staff handoff', 'Active handoff terminal routes unresolved issues to humans', 'Bot-only loop or dead-end escalation'],
            ['Operational control', 'One core database framework for all branches', 'Disconnected setups across separate tools'],
        ];
        $benefitCards = [
            'Faster onboarding without custom bot rebuilding',
            'Better customer response quality across units',
            'Clear visibility into live support handoff needs',
            'Less manual upkeep for owners and operators',
        ];
        $pricingPlans = [
            [
                'name' => 'Free',
                'price' => 'Free',
                'billing' => 'forever',
                'description' => 'Start with one business workspace and basic AI support tools.',
                'limit' => 'Up to 1 business',
                'features' => ['PDF and CSV ingestion', 'Staff handoff terminal', 'Centralized AI knowledge'],
                'highlighted' => false,
            ],
            [
                'name' => 'Growth',
                'price' => 'Plan 2',
                'billing' => 'paid SaaS tier',
                'description' => 'For growing teams managing several connected business units.',
                'limit' => 'Up to 3 businesses',
                'features' => ['PDF and CSV ingestion', 'Staff handoff terminal', 'Centralized AI knowledge'],
                'highlighted' => true,
            ],
            [
                'name' => 'Scale',
                'price' => 'Plan 3',
                'billing' => 'paid SaaS tier',
                'description' => 'For operators that need more coverage across a larger business group.',
                'limit' => 'Up to 5 businesses',
                'features' => ['PDF and CSV ingestion', 'Staff handoff terminal', 'Centralized AI knowledge'],
                'highlighted' => false,
            ],
        ];
    @endphp

    <section id="home" class="mx-auto max-w-7xl px-4 py-10 lg:px-8 lg:py-16">
        <div class="grid items-center gap-10 lg:grid-cols-[1.05fr_0.95fr]">
            <div class="space-y-6">
                <div
                    class="badge border-0 bg-[#f5f3ff] px-4 py-3 text-xs font-semibold uppercase tracking-[0.22em] text-brand-primary">
                    {{ $heroBadge }}
                </div>

                <div class="space-y-5">
                    <h1 class="max-w-2xl text-4xl font-black leading-tight tracking-tight text-gray-950 md:text-6xl">
                        {{ $heroHeadline }}
                    </h1>
                    <p class="max-w-2xl text-base leading-7 text-gray-600 md:text-lg">
                        {{ $heroDescription }}
                    </p>
                    <p class="max-w-xl text-sm font-medium leading-6 text-gray-500 md:text-base">
                        {{ $heroSupportLine }}
                    </p>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row">
                    <button type="button" onclick="signup_modal.showModal()" class="btn rounded-full border-0 bg-brand-primary px-6 text-white hover:bg-[#6d28d9]">
                        {{ $primaryCtaLabel }}
                    </button>
                    <a href="#feature-tour"
                        class="btn rounded-full border border-brand-primary bg-transparent px-6 text-brand-primary hover:bg-[#f5f3ff] hover:text-brand-primary">
                        {{ $secondaryCtaLabel }}
                    </a>
                </div>

                <div class="flex flex-wrap gap-3">
                    @foreach ($heroHighlights as $highlight)
                        <div
                            class="rounded-full border border-[#e2e8f0] bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm">
                            {{ $highlight }}
                        </div>
                    @endforeach
                </div>

                <div class="rounded-[2rem] border border-[#e2e8f0] bg-[#f8fafc] p-5 shadow-sm">
                    <div class="flex items-start gap-4">
                        <div
                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-brand-primary text-white">
                            <svg viewBox="0 0 24 24" class="h-5 w-5 fill-current" aria-hidden="true">
                                <path
                                    d="M12 2a4 4 0 0 0-4 4v1H7a3 3 0 0 0-3 3v7a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3v-7a3 3 0 0 0-3-3h-1V6a4 4 0 0 0-4-4zm-2 5V6a2 2 0 1 1 4 0v1h-4zm2 5a1.5 1.5 0 0 1 .75 2.8V16h-1.5v-1.2A1.5 1.5 0 0 1 12 12z" />
                            </svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h2 class="text-sm font-bold text-gray-950">Active multi-tenant support</h2>
                                <span
                                    class="rounded-full bg-emerald-100 px-2.5 py-1 text-[10px] font-bold uppercase tracking-[0.18em] text-emerald-700">Live</span>
                            </div>
                            <p class="mt-1 text-sm leading-6 text-gray-600">
                                Each business instance can be configured separately while still feeding the same centralized
                                knowledge and support framework.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <div
                    class="rounded-[2rem] border border-white/70 bg-white p-5 shadow-[0_20px_60px_rgba(124,58,237,0.12)] lg:p-6">
                    <div class="space-y-4 rounded-[1.6rem] bg-[#ffffff] p-5">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-gray-400">System status</p>
                                <p class="mt-1 text-lg font-black text-gray-950">Tenant orchestration online</p>
                            </div>
                            <div class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-700">Healthy
                            </div>
                        </div>

                        <div class="grid gap-3 sm:grid-cols-2">
                            <div class="rounded-2xl border border-[#e2e8f0] bg-white p-4">
                                <div class="text-xs font-semibold uppercase tracking-[0.18em] text-gray-400">Core framework
                                </div>
                                <div class="mt-2 text-lg font-black text-gray-950">Single database spine</div>
                                <div class="mt-1 text-sm text-gray-600">Every branch feeds one shared support model.</div>
                            </div>
                            <div class="rounded-2xl border border-[#e2e8f0] bg-white p-4">
                                <div class="text-xs font-semibold uppercase tracking-[0.18em] text-gray-400">Availability
                                </div>
                                <div class="mt-2 flex items-center gap-2 text-lg font-black text-gray-950">
                                    <span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                                    Ready for onboarding
                                </div>
                                <div class="mt-1 text-sm text-gray-600">New tenants can register immediately.</div>
                            </div>
                        </div>

                        <div class="rounded-2xl border border-[#e2e8f0] bg-[#f8fafc] p-4">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">Branch support model</p>
                                    <p class="text-sm text-gray-600">Hotels, dining, pools, and more all branch from the
                                        same core.</p>
                                </div>
                                <div class="rounded-full bg-brand-primary px-3 py-1 text-xs font-semibold text-white">Core</div>
                            </div>
                            <div class="mt-4 grid grid-cols-3 gap-3">
                                <div
                                    class="rounded-2xl bg-white p-3 text-center text-xs font-semibold text-gray-700 shadow-sm">
                                    Branch A</div>
                                <div
                                    class="rounded-2xl bg-white p-3 text-center text-xs font-semibold text-gray-700 shadow-sm">
                                    Branch B</div>
                                <div
                                    class="rounded-2xl bg-white p-3 text-center text-xs font-semibold text-gray-700 shadow-sm">
                                    Branch C</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="feature-tour" class="mx-auto max-w-7xl px-4 py-14 lg:px-8">
        <div class="max-w-3xl space-y-3">
            <div
                class="badge border-0 bg-[#f5f3ff] px-4 py-3 text-xs font-semibold uppercase tracking-[0.22em] text-brand-primary">
                AI system showcase</div>
            <h2 class="text-3xl font-black tracking-tight text-gray-950 md:text-5xl">A business support AI that understands,
                answers, and escalates.</h2>
            <p class="text-gray-600">Project RED AI connects uploaded business knowledge, customer conversations, and staff
                handoff workflows so the system can resolve common questions while keeping humans in control.</p>
        </div>

        <div class="mt-10 grid gap-6 lg:grid-cols-3">
            @foreach ($unitCards as $unitCard)
                <div class="rounded-[2rem] border border-[#e2e8f0] bg-white p-6 shadow-sm">
                    <div class="flex items-start justify-between gap-4">
                        <div class="space-y-3">
                            <div
                                class="inline-flex rounded-full bg-[#f5f3ff] px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-brand-primary">
                                AI capability</div>
                            <h3 class="text-2xl font-black tracking-tight text-gray-950">{{ $unitCard['title'] }}</h3>
                        </div>
                        <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-700">Synced</span>
                    </div>

                    <p class="mt-4 text-sm leading-6 text-gray-600">{{ $unitCard['description'] }}</p>

                    <div class="mt-6 space-y-3">
                        @foreach ($unitCard['points'] as $point)
                            <div class="flex items-center gap-3 rounded-2xl bg-[#ffffff] p-3 text-sm text-gray-700">
                                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-brand-primary text-white">
                                    <svg viewBox="0 0 24 24" class="h-4 w-4 fill-current" aria-hidden="true">
                                        <path d="M9.2 16.2 4.8 11.8l1.4-1.4 3 3 8.6-8.6 1.4 1.4-10 10z" />
                                    </svg>
                                </span>
                                {{ $point }}
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6 rounded-2xl border border-[#e2e8f0] bg-[#f8fafc] p-4">
                        <div class="text-xs font-semibold uppercase tracking-[0.18em] text-gray-400">System role</div>
                        <div class="mt-1 text-sm font-semibold text-gray-950">This capability works with the shared AI
                            knowledge and support workflow.</div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <section id="pricing" class="mx-auto max-w-7xl px-4 py-16 lg:px-8">
        <div class="mx-auto max-w-3xl space-y-3 text-center">
            <div
                class="badge border-0 bg-[#f5f3ff] px-4 py-3 text-xs font-semibold uppercase tracking-[0.22em] text-brand-primary">
                SaaS pricing</div>
            <h2 class="text-3xl font-black tracking-tight text-gray-950 md:text-5xl">Choose the plan that fits your business
                coverage.</h2>
            <p class="text-gray-600">Start free with one business, then upgrade as your operation grows across more
                business profiles and support workflows.</p>
        </div>

        <div class="mt-10 grid gap-6 lg:grid-cols-3">
            @foreach ($pricingPlans as $pricingPlan)
                <div
                    class="relative rounded-[2rem] border p-6 shadow-sm {{ $pricingPlan['highlighted'] ? 'border-brand-primary bg-[#f5f3ff] shadow-[0_20px_60px_rgba(124,58,237,0.14)]' : 'border-[#e2e8f0] bg-white' }}">
                    @if ($pricingPlan['highlighted'])
                        <div
                            class="absolute right-5 top-5 rounded-full bg-brand-primary px-3 py-1 text-xs font-bold uppercase tracking-[0.16em] text-white">
                            Popular</div>
                    @endif

                    <div class="space-y-4">
                        <div>
                            <h3 class="text-2xl font-black tracking-tight text-gray-950">{{ $pricingPlan['name'] }}</h3>
                            <p class="mt-2 max-w-xs text-sm leading-6 text-gray-600">{{ $pricingPlan['description'] }}</p>
                        </div>

                        <div class="flex items-end gap-2">
                            <div class="text-5xl font-black tracking-tight text-gray-950">{{ $pricingPlan['price'] }}</div>
                            <div class="pb-2 text-sm font-semibold text-gray-500">{{ $pricingPlan['billing'] }}</div>
                        </div>

                        <div class="rounded-2xl bg-white p-4 text-sm font-bold text-gray-900 shadow-sm">
                            {{ $pricingPlan['limit'] }}
                        </div>
                    </div>

                    <div class="mt-6 space-y-3">
                        @foreach ($pricingPlan['features'] as $feature)
                            <div class="flex items-center gap-3 text-sm text-gray-700">
                                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-emerald-100 text-emerald-700">
                                    <svg viewBox="0 0 24 24" class="h-4 w-4 fill-current" aria-hidden="true">
                                        <path d="M9.2 16.2 4.8 11.8l1.4-1.4 3 3 8.6-8.6 1.4 1.4-10 10z" />
                                    </svg>
                                </span>
                                {{ $feature }}
                            </div>
                        @endforeach
                    </div>

                    <button type="button" onclick="signup_modal.showModal()"
                        class="btn mt-7 w-full rounded-full border-0 {{ $pricingPlan['highlighted'] ? 'bg-brand-primary text-white hover:bg-[#6d28d9]' : 'bg-[#f5f3ff] text-brand-primary hover:bg-[#ede9fe]' }}">
                        Sign Up
                    </button>
                </div>
            @endforeach
        </div>
    </section>

    <section id="register" class="bg-[#f5f3ff] py-16">
        <div class="mx-auto max-w-7xl px-4 lg:px-8">
            <div class="grid gap-8 lg:grid-cols-[0.95fr_1.05fr] lg:items-start">
                <div class="space-y-5">
                    <div
                        class="badge border-0 bg-white px-4 py-3 text-xs font-semibold uppercase tracking-[0.22em] text-brand-primary">
                        Self-service onboarding</div>
                    <h2 class="max-w-xl text-3xl font-black tracking-tight text-gray-950 md:text-5xl">Register your company
                        in a three-step guided setup.</h2>
                    <p class="max-w-xl text-gray-600">The wizard is designed to keep the experience calm and low-friction
                        while still collecting everything needed to create a secure tenant, configure the company, and
                        provision the platform subpath.</p>

                    <div class="grid gap-3 sm:grid-cols-2">
                        <div class="rounded-2xl border border-[#e2e8f0] bg-white p-4 shadow-sm">
                            <div class="text-xs font-semibold uppercase tracking-[0.18em] text-gray-400">Step guidance</div>
                            <div class="mt-2 text-lg font-black text-gray-950">Clear progress at every stage</div>
                            <div class="mt-1 text-sm text-gray-600">Progressive disclosure keeps the form manageable.</div>
                        </div>
                        <div class="rounded-2xl border border-[#e2e8f0] bg-white p-4 shadow-sm">
                            <div class="text-xs font-semibold uppercase tracking-[0.18em] text-gray-400">Validation</div>
                            <div class="mt-2 text-lg font-black text-gray-950">Immediate checks and feedback</div>
                            <div class="mt-1 text-sm text-gray-600">Email, credentials, and subpath claims are verified
                                inline.</div>
                        </div>
                    </div>
                </div>

                <div class="rounded-[2rem] border border-[#e2e8f0] bg-white p-6 shadow-sm lg:p-8">
                    <div class="flex flex-wrap items-center gap-3">
                        @foreach ($wizardSteps as $wizardStep)
                            <div class="flex items-center gap-3 rounded-full border border-[#e2e8f0] px-4 py-2">
                                <div
                                    class="flex h-8 w-8 items-center justify-center rounded-full bg-brand-primary text-xs font-bold text-white">
                                    {{ $wizardStep['step'] }}</div>
                                <div class="text-sm font-semibold text-gray-700">{{ $wizardStep['title'] }}</div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6 space-y-4">
                        @foreach ($wizardSteps as $wizardStep)
                            <div class="rounded-[1.75rem] border border-[#e2e8f0] bg-[#ffffff] p-5">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <div class="text-xs font-semibold uppercase tracking-[0.18em] text-gray-400">Step
                                            {{ $wizardStep['step'] }}</div>
                                        <h3 class="mt-1 text-xl font-black text-gray-950">{{ $wizardStep['title'] }}</h3>
                                        <p class="mt-2 text-sm leading-6 text-gray-600">{{ $wizardStep['description'] }}</p>
                                    </div>
                                    <div class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-700">Guided
                                    </div>
                                </div>

                                <div class="mt-5 grid gap-3 sm:grid-cols-3">
                                    @foreach ($wizardStep['fields'] as $field)
                                        <div class="rounded-2xl border border-white bg-white p-4 shadow-sm">
                                            <div class="text-sm font-semibold text-gray-900">{{ $field }}</div>
                                            <div class="mt-2 text-xs leading-5 text-gray-500">
                                                @if ($loop->first && $wizardStep['step'] === '03')
                                                    Dynamic availability and resource validation apply here.
                                                @elseif ($wizardStep['step'] === '03' && $loop->last)
                                                    The system checks whether the claim fits available platform bounds.
                                                @elseif ($wizardStep['step'] === '01' && $loop->last)
                                                    Strong password rules should be shown inline.
                                                @else
                                                    Collect this value as a structured onboarding input.
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div
                        class="mt-6 grid gap-4 rounded-[1.75rem] border border-[#e2e8f0] bg-[#f8fafc] p-5 sm:grid-cols-[1fr_auto] sm:items-center">
                        <div>
                            <div class="text-sm font-semibold text-gray-900">Subpath claim behavior</div>
                            <p class="mt-1 text-sm text-gray-600">Users can claim a custom system URL subpath while the
                                interface checks availability, invalid characters, length limits, and resource bounds in
                                real time.</p>
                        </div>
                        <div class="rounded-full bg-brand-primary px-4 py-2 text-sm font-semibold text-white">Availability Check
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-16 lg:px-8">
        <div class="max-w-3xl space-y-3">
            <div
                class="badge border-0 bg-[#f5f3ff] px-4 py-3 text-xs font-semibold uppercase tracking-[0.22em] text-brand-primary">
                Comparison matrix</div>
            <h2 class="text-3xl font-black tracking-tight text-gray-950 md:text-5xl">Why Project RED AI outperforms rigid
                chat setups.</h2>
            <p class="text-gray-600">This shows the platform’s
                practical advantage in knowledge management, support routing, and multi-unit scale.</p>
        </div>

        <div class="mt-10 overflow-hidden rounded-[2rem] border border-[#e2e8f0] bg-white shadow-sm">
            <div class="grid grid-cols-3 border-b border-[#e2e8f0] bg-[#ffffff] px-6 py-4 text-sm font-bold text-gray-900">
                <div>Capability</div>
                <div>Project RED AI</div>
                <div>Rigid Old-School Chat Setup</div>
            </div>
            <div class="divide-y divide-[#e2e8f0]">
                @foreach (array_slice($comparisonRows, 1) as $comparisonRow)
                    <div class="grid grid-cols-1 gap-4 px-6 py-5 md:grid-cols-3 md:items-start">
                        <div class="text-sm font-semibold text-gray-900">{{ $comparisonRow[0] }}</div>
                        <div class="text-sm leading-6 text-gray-600">{{ $comparisonRow[1] }}</div>
                        <div class="text-sm leading-6 text-gray-600">{{ $comparisonRow[2] }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="mt-6 grid gap-4 md:grid-cols-2 lg:grid-cols-4">
            @foreach ($benefitCards as $benefitCard)
                <div class="rounded-2xl border border-[#e2e8f0] bg-[#ffffff] p-4 shadow-sm">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">
                        <svg viewBox="0 0 24 24" class="h-5 w-5 fill-current" aria-hidden="true">
                            <path d="M9.2 16.2 4.8 11.8l1.4-1.4 3 3 8.6-8.6 1.4 1.4-10 10z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-sm font-semibold text-gray-900">{{ $benefitCard }}</div>
                </div>
            @endforeach
        </div>
    </section>

    <section class="bg-[#f5f3ff] py-16">
        <div class="mx-auto grid max-w-7xl gap-6 px-4 lg:grid-cols-[1.15fr_0.85fr] lg:px-8">
            <div class="space-y-4">
                <div
                    class="badge border-0 bg-white px-4 py-3 text-xs font-semibold uppercase tracking-[0.22em] text-brand-primary">
                    Why owners choose it</div>
                <h2 class="text-3xl font-black tracking-tight text-gray-950 md:text-4xl">Reduce manual support, keep
                    knowledge structured, and launch faster.</h2>
                <p class="max-w-2xl text-gray-600">The public experience should make the platform feel trustworthy, easy to
                    start, and clearly suited to businesses that need one support system across multiple operational units.
                </p>
            </div>

            <div class="grid gap-3">
                <div class="rounded-2xl border border-[#e2e8f0] bg-white p-4 shadow-sm">
                    <div class="text-sm font-semibold text-gray-900">Centralized customer support</div>
                    <div class="mt-1 text-sm text-gray-600">One system handles inquiries across the business group.</div>
                </div>
                <div class="rounded-2xl border border-[#e2e8f0] bg-white p-4 shadow-sm">
                    <div class="text-sm font-semibold text-gray-900">Live staff handoff terminal</div>
                    <div class="mt-1 text-sm text-gray-600">Escalate to humans when automation is not enough.</div>
                </div>
                <div class="rounded-2xl border border-[#e2e8f0] bg-white p-4 shadow-sm">
                    <div class="text-sm font-semibold text-gray-900">Multi-location scalability</div>
                    <div class="mt-1 text-sm text-gray-600">Add new business instances without breaking the core framework.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <dialog id="signup_modal" class="modal modal-bottom sm:modal-middle">
        <div class="modal-box max-w-2xl rounded-[2rem] border border-[#e2e8f0] bg-[#f8fafc] p-6 lg:p-8">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-4 top-4 text-gray-500 hover:bg-[#f5f3ff]">✕</button>
            </form>

            <div class="space-y-6">
                <div class="space-y-2">
                    <div
                        class="inline-flex rounded-full bg-[#f5f3ff] px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-brand-primary">
                        Sign up</div>
                    <h3 class="text-3xl font-black tracking-tight text-gray-950">Sign Up for Project RED AI</h3>
                    <p class="text-sm leading-6 text-gray-600">
                        Choose a plan and start creating your AI-powered business workspace. This form is a visual preview
                        while account registration is being connected.
                    </p>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <label class="form-control">
                        <div class="label">
                            <span class="label-text font-semibold text-gray-700">Full name</span>
                        </div>
                        <input type="text" placeholder="Enter your full name"
                            class="input input-bordered rounded-2xl border-[#e2e8f0] bg-white" />
                    </label>

                    <label class="form-control">
                        <div class="label">
                            <span class="label-text font-semibold text-gray-700">Email address</span>
                        </div>
                        <input type="email" placeholder="you@example.com"
                            class="input input-bordered rounded-2xl border-[#e2e8f0] bg-white" />
                    </label>

                    <label class="form-control">
                        <div class="label">
                            <span class="label-text font-semibold text-gray-700">Business name</span>
                        </div>
                        <input type="text" placeholder="Your business name"
                            class="input input-bordered rounded-2xl border-[#e2e8f0] bg-white" />
                    </label>

                    <label class="form-control">
                        <div class="label">
                            <span class="label-text font-semibold text-gray-700">Plan</span>
                        </div>
                        <select class="select select-bordered rounded-2xl border-[#e2e8f0] bg-white">
                            <option>Free - up to 1 business</option>
                            <option>Growth - up to 3 businesses</option>
                            <option>Scale - up to 5 businesses</option>
                        </select>
                    </label>
                </div>

                <div class="rounded-2xl border border-[#e2e8f0] bg-white p-4">
                    <div class="text-sm font-bold text-gray-900">All plans include</div>
                    <div class="mt-3 grid gap-2 text-sm text-gray-600 sm:grid-cols-3">
                        <div>PDF and CSV ingestion</div>
                        <div>Staff handoff terminal</div>
                        <div>Centralized AI knowledge</div>
                    </div>
                </div>

                <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                    <form method="dialog">
                        <button class="btn w-full rounded-full border border-[#e2e8f0] bg-white px-6 text-gray-700 hover:bg-[#f8fafc] sm:w-auto">Cancel</button>
                    </form>
                    <button type="button"
                        class="btn rounded-full border-0 bg-brand-primary px-6 text-white hover:bg-[#6d28d9]">
                        Continue Sign Up
                    </button>
                </div>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>

    @include('partials.platform-footer')
    {{-- @include('partials.chatbot') --}}
@endsection
