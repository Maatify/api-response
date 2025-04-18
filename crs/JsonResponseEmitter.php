<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Library     api-response
 * @Project     api-response
 * @author      Mohamed Abdulalim (megyptm)
 * @link        https://github.com/Maatify/api-response
 * @since       2025-04-18 5:22 PM
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

class JsonResponseEmitter
{
    #[NoReturn] public static function emit(ResponseInterface $response): void
    {
        http_response_code($response->getStatusCode());

        foreach ($response->getHeaders() as $name => $values) {
            foreach ($values as $value) {
                header("$name: $value");
            }
        }

        echo (string) $response->getBody();
        exit;
    }
}
