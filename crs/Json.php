<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Library     api-response
 * @Project     api-response
 * @author      Mohamed Abdulalim (megyptm)
 * @link        https://github.com/Maatify/api-response
 * @since       2025-04-18 3:30 PM
 * @see         https://www.maatify.dev
 *
 * @note        This program is distributed in the hope that it will be useful - WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.
 */

declare(strict_types=1);

namespace Maatify\ApiResponse;

use JetBrains\PhpStorm\NoReturn;
use Psr\Http\Message\ResponseInterface;

final class Json extends BaseResponder
{
    #[NoReturn]
    public static function missing(ResponseInterface $response, string $varName, string $moreInfo = '', int|string $line = 0): ResponseInterface
    {
        return self::postErrorHandler($response, $varName, 1000, $moreInfo, $line);
    }

    #[NoReturn]
    public static function incorrect(ResponseInterface $response, string $varName, string $moreInfo = '', int|string $line = 0): ResponseInterface
    {
        return self::postErrorHandler($response, $varName, 2000, $moreInfo, $line);
    }

    #[NoReturn]
    public static function invalid(ResponseInterface $response, string $varName, string $moreInfo = '', int|string $line = 0): ResponseInterface
    {
        return self::postErrorHandler($response, $varName, 4000, $moreInfo, $line);
    }

    #[NoReturn]
    public static function notExist(ResponseInterface $response, string $varName, string $moreInfo = '', int|string $line = 0): ResponseInterface
    {
        return self::postErrorHandler($response, $varName, 6000, $moreInfo, $line);
    }

    #[NoReturn]
    public static function success(ResponseInterface $response, array $result = [], string $description = '', string $moreInfo = '', int|string $line = 0): ResponseInterface
    {
        return self::headerResponseSuccess($response, [
            'result' => $result,
            'description' => $description,
            'more_info' => $moreInfo,
            'error_details' => self::generateErrorTrace($line),
        ]);
    }

    #[NoReturn]
    public static function dbError(ResponseInterface $response, int|string $line = 0): ResponseInterface
    {
        return self::headerResponseJson($response->withStatus(500), [
            'success' => false,
            'response' => 500,
            'description' => 'Internal Server Error',
            'error_details' => self::generateErrorTrace($line),
        ]);
    }

    #[NoReturn]
    public static function tooManyAttempts(ResponseInterface $response, string $varName = '', int $retryAfterSeconds = 300, int|string $line = 0): ResponseInterface
    {
        return self::headerResponseJson(
            $response->withStatus(429)->withHeader('Retry-After', (string)$retryAfterSeconds),
            [
                'success' => false,
                'response' => 429,
                'error_code' => 'TOO_MANY_ATTEMPTS',
                'description' => 'Too Many Attempts',
                'retry_after' => $retryAfterSeconds,
                'details' => [
                    'var' => $varName,
                    'more_info' => 'You have exceeded the number of allowed requests.',
                ],
                'error_details' => self::generateErrorTrace($line),
            ]
        );
    }
}
