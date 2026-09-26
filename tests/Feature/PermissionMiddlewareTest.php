<?php

namespace TestsFeature;

use App\Http\Middleware\PermissionMiddleware;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Tests\TestCase;

class PermissionMiddlewareTest extends TestCase
{
    public function test_allowed_role_can_use_a_permission(): void
    {
        $user = User::factory()->create(['role' => 'purchasing']);

        $response = app(Pipeline::class)
            ->send(Request::create('/'))
            ->through([function (Request $request, $next) use ($user) {
                $request->setUserResolver(fn () => $user);

                return app(PermissionMiddleware::class)->handle(
                    $request,
                    $next,
                    'purchases.manage',
                );
            }])
            ->then(fn () => response('allowed'));

        $this->assertSame('allowed', $response->getContent());
    }

    public function test_disallowed_role_receives_forbidden_response(): void
    {
        $user = User::factory()->create(['role' => 'customer']);

        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);

        $request = Request::create('/');
        $request->setUserResolver(fn () => $user);

        app(PermissionMiddleware::class)->handle(
            $request,
            fn () => response('should not run'),
            'purchases.manage',
        );
    }
}
