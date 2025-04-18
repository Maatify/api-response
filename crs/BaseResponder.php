<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Library     api-response
 * @Project     api-response
 * @author      Mohamed Abdulalim (megyptm)
 * @link        https://github.com/Maatify/api-response
 * @since       2025-04-18 3:29 PM
 * @see         https://www.maatify.dev
 *
 * @note        This program is distributed in the hope that it will be useful - WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.
 */

declare(strict_types=1);

namespace Maatify\ApiResponse;

use Psr\Http\Message\ResponseInterface;
use JetBrains\PhpStorm\NoReturn;

abstract class BaseResponder extends CoreResponder
{
    #[NoReturn]
    protected static function postErrorHandler(ResponseInterface $response, string $varName, int $codeRange, string $moreInfo = '', int|string $line = 0): ResponseInterface
    {
        return self::errorWithHeader400(
            $response,
            $codeRange,
            $varName,
            self::errorDescription($varName, $codeRange),
            $moreInfo,
            $line
        );
    }

    protected static function errorDescription(string $varName, int $codeRange): string
    {
        $label = ucwords(str_replace('_', ' ', $varName));
        return match ($codeRange) {
            1000 => "MISSING $label",
            2000 => "Incorrect $label",
            3000 => "$label is already exist",
            4000 => "INVALID $label",
            5000 => "$label is Not verified",
            6000 => "$label is Not exist",
            7000 => "$label is Not Allowed To Use",
            8000 => "$label In Use",
            9000 => "$label Unexpected",
            default => "$label Error",
        };
    }
}
