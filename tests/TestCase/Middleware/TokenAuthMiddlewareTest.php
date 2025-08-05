<?php
declare(strict_types=1);

namespace App\Test\TestCase\Middleware;

use App\Middleware\TokenAuthMiddleware;
use Cake\TestSuite\TestCase;

/**
 * App\Middleware\TokenAuthMiddleware Test Case
 */
class TokenAuthMiddlewareTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Middleware\TokenAuthMiddleware
     */
    protected $TokenAuth;

    /**
     * Test process method
     *
     * @return void
     * @link \App\Middleware\TokenAuthMiddleware::process()
     */
    public function testProcess(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
