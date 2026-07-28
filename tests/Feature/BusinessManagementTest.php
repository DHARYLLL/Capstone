<?php

test('landing page redirects successfully to admin dashboard', function () {
    $response = $this->get(route('home'));
    $response->assertStatus(302);
    $response->assertRedirect(route('admin.dashboard'));
});

test('admin dashboard loads successfully', function () {
    $response = $this->get(route('admin.dashboard'));
    $response->assertStatus(200);
    $response->assertSee('Waterproofing Console');
    $response->assertSee('AquaShield Waterproofing');
});

test('chat console page loads successfully', function () {
    $response = $this->get(route('admin.chat'));
    $response->assertStatus(200);
    $response->assertSee('Live Chat & Staff Handoff');
    $response->assertSee('Maria D.');
});

test('services catalog page loads successfully', function () {
    $response = $this->get(route('admin.products'));
    $response->assertStatus(200);
    $response->assertSee('Waterproofing Services Catalog');
    $response->assertSee('Roof Deck Waterproofing');
});

test('knowledge base upload page loads successfully', function () {
    $response = $this->get(route('admin.knowledge-base'));
    $response->assertStatus(200);
    $response->assertSee('Knowledge Base Upload');
});

test('reporting and analytics page loads successfully', function () {
    $response = $this->get(route('admin.analytics'));
    $response->assertStatus(200);
    $response->assertSee('Reporting & Analytics');
});

test('staff console page loads successfully', function () {
    $response = $this->get(route('admin.staff'));
    $response->assertStatus(200);
    $response->assertSee('Staff & Roles');
});

test('ingestion logs page loads successfully', function () {
    $response = $this->get(route('admin.logs'));
    $response->assertStatus(200);
    $response->assertSee('Knowledge Base Logs');
});
