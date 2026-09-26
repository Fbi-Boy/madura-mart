<?php

namespace Tests\Feature;

use App\Http\Middleware\PermissionMiddleware;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
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

        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);

        app(PermissionMiddleware::class)->handle(
            $request,
            fn () => response('should not run'),
            'purchases.manage',
        );
    }
}
