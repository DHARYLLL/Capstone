<?php

test('landing page redirects successfully to login', function () {
    $response = $this->get(route('home'));
    $response->assertStatus(302);
    $response->assertRedirect(route('login'));
});

test('unauthenticated guest is redirected to login when accessing admin dashboard', function () {
    $response = $this->get(route('admin.dashboard'));
    $response->assertStatus(302);
    $response->assertRedirect(route('login'));
});

test('admin dashboard loads successfully for authenticated admin user', function () {
    $response = $this->withSession([
        'user_name' => 'Admin User',
        'user_role' => 'Administrator',
        'user_avatar' => '🛡️'
    ])->get(route('admin.dashboard'));

    $response->assertStatus(200);
    $response->assertSee('Waterproofing Console');
    $response->assertSee('DARIV Waterproofing');
});

test('chat console page loads successfully for authenticated operator user', function () {
    $response = $this->withSession([
        'user_name' => 'Mae S.',
        'user_role' => 'Lead Operator',
        'user_avatar' => '☔'
    ])->get(route('staff.chat'));

    $response->assertStatus(200);
    $response->assertSee('Live Chat & Staff Handoff');
    $response->assertSee('Maria D.');
});

test('knowledge base upload page loads successfully for authenticated user', function () {
    $response = $this->withSession([
        'user_role' => 'Administrator'
    ])->get(route('admin.knowledge-base'));

    $response->assertStatus(200);
    $response->assertSee('Knowledge Base Upload');
});

test('reporting and analytics page loads successfully for authenticated user', function () {
    $response = $this->withSession([
        'user_role' => 'Administrator'
    ])->get(route('admin.analytics'));

    $response->assertStatus(200);
    $response->assertSee('Reporting & Analytics');
});

test('staff console page loads successfully for authenticated user', function () {
    $response = $this->withSession([
        'user_role' => 'Administrator'
    ])->get(route('admin.staff'));

    $response->assertStatus(200);
    $response->assertSee('Staff & Roles');
});

test('ingestion logs page loads successfully for authenticated user', function () {
    $response = $this->withSession([
        'user_role' => 'Administrator'
    ])->get(route('admin.logs'));

    $response->assertStatus(200);
    $response->assertSee('Knowledge Base Logs');
});

test('authenticated operators are restricted with 403 when accessing admin-only pages', function () {
    $response = $this->withSession([
        'user_name' => 'Mae S.',
        'user_role' => 'Lead Operator'
    ])->get(route('admin.dashboard'));

    $response->assertStatus(403);
});

test('admin settings page loads successfully for authenticated admin', function () {
    $response = $this->withSession([
        'user_role' => 'Administrator'
    ])->get(route('admin.settings'));

    $response->assertStatus(200);
    $response->assertSee('Account Settings');
});

test('staff settings page loads successfully for authenticated operator', function () {
    $response = $this->withSession([
        'user_role' => 'Lead Operator'
    ])->get(route('staff.settings'));

    $response->assertStatus(200);
    $response->assertSee('Account Settings');
});

test('submitting setting updates changes session credentials successfully', function () {
    $response = $this->withSession([
        'user_name' => 'Admin User',
        'user_role' => 'Administrator'
    ])->post(route('admin.settings.post'), [
        'name' => 'Updated Name',
        'email' => 'updated@dariv.com'
    ]);

    $response->assertStatus(302);
    $this->assertEquals('Updated Name', session('user_name'));
    $this->assertEquals('updated@dariv.com', session('user_email'));
});

test('customer request to speak with a human routes directly to a live agent', function () {
    $company = \App\Models\Company::create(['name' => 'Acme Co']);
    $businessUnit = \App\Models\BusinessUnit::create([
        'company_id' => $company->id,
        'name' => 'Support Desk',
    ]);

    $response = $this->postJson(route('chat.ask', ['businessUnit' => $businessUnit->id]), [
        'prompt' => 'I need to speak to a human support agent.',
        'user_identifier' => 'customer-123',
    ]);

    $response->assertStatus(200)
        ->assertJsonPath('status', 'human_active')
        ->assertJsonPath('message', 'I need to speak to a human support agent.');

    $this->assertDatabaseHas('chat_sessions', [
        'company_id' => $company->id,
        'business_unit_id' => $businessUnit->id,
        'user_identifier' => 'customer-123',
        'status' => 'human_active',
    ]);
});

test('live chat history only includes messages created after the handoff timestamp', function () {
    $company = \App\Models\Company::create(['name' => 'Acme Co']);
    $businessUnit = \App\Models\BusinessUnit::create([
        'company_id' => $company->id,
        'name' => 'Support Desk',
    ]);

    $session = \App\Models\ChatSession::create([
        'company_id' => $company->id,
        'business_unit_id' => $businessUnit->id,
        'user_identifier' => 'customer-456',
        'status' => 'human_active',
        'handed_off_at' => now()->subMinutes(5),
    ]);

    \Illuminate\Support\Facades\DB::table('chat_messages')->insert([
        [
            'chat_session_id' => $session->id,
            'sender_type' => 'customer',
            'message_text' => 'Before handoff message',
            'created_at' => now()->subMinutes(10),
            'updated_at' => now()->subMinutes(10),
        ],
        [
            'chat_session_id' => $session->id,
            'sender_type' => 'customer',
            'message_text' => 'After handoff message',
            'created_at' => now()->subMinute(),
            'updated_at' => now()->subMinute(),
        ],
        [
            'chat_session_id' => $session->id,
            'sender_type' => 'agent',
            'message_text' => 'Live reply',
            'created_at' => now(),
            'updated_at' => now(),
        ],
    ]);

    $response = $this->getJson(route('chat.messages', ['sessionId' => $session->id]));

    $response->assertStatus(200)
        ->assertJsonCount(2, 'messages')
        ->assertJsonPath('messages.0.message_text', 'After handoff message')
        ->assertJsonPath('messages.1.message_text', 'Live reply');
});

test('widget renders the loading transfer notice and timeout fallback contact info', function () {
    $company = \App\Models\Company::create(['name' => 'Acme Co']);
    $businessUnit = \App\Models\BusinessUnit::create([
        'company_id' => $company->id,
        'name' => 'Support Desk',
    ]);

    $response = $this->get('/chat/widget?business=' . urlencode($businessUnit->name));

    $response->assertStatus(200)
        ->assertSee('Connecting you to a live representative...')
        ->assertSee('Taking too long? Show Contact Info')
        ->assertSee('support@darivwaterproofing.com')
        ->assertSee('1-800-555-DARIV')
        ->assertSee('Mon–Fri: 7:30 AM–5:30 PM, Sat: 8:00 AM–1:00 PM');
});
