<?php

namespace Tests\Feature;

use Tests\TestCase;

class RouteAccessTest extends TestCase
{
    public function test_guest_is_redirected_to_login_from_the_application_root(): void
    {
        $this->get('/')->assertRedirect('/login');
    }

    public function test_guest_cannot_access_the_dashboard(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_guest_cannot_access_admin_monitoring(): void
    {
        $this->get('/admin/monitoring/produk')->assertRedirect('/login');
    }

    public function test_guest_cannot_access_admin_reports(): void
    {
        $this->get('/admin/report/stok')->assertRedirect('/login');
    }
}
