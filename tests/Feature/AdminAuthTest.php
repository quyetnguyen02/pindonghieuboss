<?php

use Database\Seeders\AdminUserSeeder;

it('redirects unauthenticated admin access to the admin login page', function () {
    $response = $this->get('/admin');

    $response->assertRedirect('/admin/login');
});

it('allows an admin user to sign in and access the dashboard', function () {
    $this->seed(AdminUserSeeder::class);

    $response = $this->post('/admin/login', [
        'username' => 'Hieu',
        'password' => 'hieu',
    ]);

    $response->assertRedirect('/admin');
    $this->assertAuthenticated();
});
