<?php
declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

use Cake\Http\Exception\UnauthorizedException;
use Cake\ORM\TableRegistry;

/**
 * TokenAuth middleware
 */
class TokenAuthMiddleware implements MiddlewareInterface
{
    /**
     * Process method.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request The request.
     * @param \Psr\Http\Server\RequestHandlerInterface $handler The request handler.
     * @return \Psr\Http\Message\ResponseInterface A response.
     */

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $path = $request->getUri()->getPath();
        $skip = ['/api/auth/login', '/api/auth/register'];

        if (in_array($path, $skip, true)) {
            return $handler->handle($request);
        }

        $header = $request->getHeaderLine('Authorization');

        if (preg_match('/Bearer\s+(.+)$/i', $header, $m)) {
            $hashedToken = hash('sha256', $m[1]);

            $tokens = TableRegistry::getTableLocator()->get('PersonalTokens');
            $token = $tokens->find()
                ->contain(['Users'])
                ->where([
                    'PersonalTokens.token' => $hashedToken,
                    'Users.is_active' => true,
                    'PersonalTokens.expires_at >' => (new \DateTimeImmutable())->format('Y-m-d H:i:s')
                ])
                ->first();

            if ($token && $token->user) {
                return $handler->handle(
                    $request->withAttribute('user', $token->user)
                );
            }
        }
        throw new UnauthorizedException('Invalid or expired token.');
    }
}
