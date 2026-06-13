<?php

test('landing page renders successfully', function () {
    $response = $this->get(route('home'));
    $response->assertStatus(200);
    $response->assertSee('Villa Carmelita');
    $response->assertSee('Check Rooms & Live Availability', false);
});

test('admin dashboard renders successfully', function () {
    $response = $this->get(route('admin.dashboard'));
    $response->assertStatus(200);
    $response->assertSee('Manage Businesses');
    $response->assertSee('Villa Carmelita');
});

test('edit page loaded for dakong-balay defaults or accepts parameter', function () {
    $response = $this->get(route('admin.businesses.edit', 'dakong-balay'));
    $response->assertStatus(200);
    $response->assertSee('Dakong Balay selected');
    $response->assertSee('Chicken Inasal Meal');
});

test('edit page loads successfully for villa-carmelita', function () {
    $response = $this->get(route('admin.businesses.edit', 'villa-carmelita'));
    $response->assertStatus(200);
    $response->assertSee('Villa Carmelita selected');
    $response->assertSee('Rooms & Live Availability', false);
    $response->assertSee('RM 310');
    $response->assertSee('Standard');
    $response->assertSee('PHP 1,800');
});

test('edit page loads successfully for monclaire-pool', function () {
    $response = $this->get(route('admin.businesses.edit', 'monclaire-pool'));
    $response->assertStatus(200);
    $response->assertSee('Monclaire Pool selected');
    $response->assertSee('Day Pass - Adult');
});
