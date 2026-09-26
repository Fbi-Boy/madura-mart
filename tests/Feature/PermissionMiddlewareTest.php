<?php

namespace Tests\Feature;

use App\Http\Middleware\PermissionMiddleware;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class PermissionMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_allowed_role_can_use_a_permission(): void
    {
        $user = User::factory()->create(['role' => 'purchasing']);
        $request = Request::create('/');
        $request->setUserResolver(fn () => $user);

        $response = app(PermissionMiddleware::class)->handle(
            $request,
            fn () => response('allowed'),
            'purchases.manage',
        );

        $this->assertSame('allowed', $response->getContent());
    }

    public function test_disallowed_role_receives_forbidden_response(): void
    {
        $user = User::factory()->create(['role' => 'customer']);
        $request = Request::create('/');
        $request->setUserResolver(fn () => $user);

        $this->expectException(HttpException::class);

        app(PermissionMiddleware::class)->handle(
            $request,
            fn () => response('should not run'),
            'purchases.manage',
        );
    }

    public function test_unknown_permission_is_denied_by_default(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $request = Request::create('/');
        $request->setUserResolver(fn () => $user);

        $this->expectException(HttpException::class);

        app(PermissionMiddleware::class)->handle(
            $request,
            fn () => response('should not run'),
            'permission.that.does.not.exist',
        );
    }

    public function test_system_settings_update_remains_admin_only(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $this->actingAs($customer)
            ->patch(route('admin.settings.update'), ['store_name' => 'Blocked'])
            ->assertForbidden();

        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)
            ->patch(route('admin.settings.update'), [
                'store_name' => 'Allowed',
                'store_phone' => '',
                'store_email' => '',
                'tax_percent' => '0',
                'discount_percent' => '0',
                'shipping_fee' => '0',
            ])
            ->assertRedirect(route('admin.settings.index'));
    }
}
