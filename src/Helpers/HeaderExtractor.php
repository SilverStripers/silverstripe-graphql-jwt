<?php declare(strict_types=1);

namespace Firesphere\GraphQLJWT\Helpers;

use SilverStripe\Control\HTTPRequest;

/**
 * Parent class can detect JWT tokens in a request
 */
trait HeaderExtractor
{
    /**
     * Get JWT from request, or null if not present
     *
     * @param HTTPRequest $request
     * @return string|null
     */
    protected function getAuthorizationHeader(HTTPRequest $request): ?string
    {
        $authHeader = $request->getHeader('Authorization');
        if (empty($authHeader)) {
            $possibleAuthVars = [
                'HTTP_AUTHORIZATION',
                'REDIRECT_HTTP_AUTHORIZATION'
            ];
            foreach ($possibleAuthVars as $servervar) {
                if (isset($_SERVER[$servervar]) && preg_match('/Bearer\s+(.*)$/i', $_SERVER[$servervar])) {
                    $authHeader = $_SERVER[$servervar];
                    break;
                }
            }
        }
        if ($authHeader && preg_match('/Bearer\s+(?<token>.*)$/i', $authHeader, $matches)) {
            return $matches['token'];
        }
        return null;
    }
}
