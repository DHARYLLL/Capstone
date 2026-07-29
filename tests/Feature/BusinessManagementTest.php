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
