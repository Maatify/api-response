<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Library     api-response
 * @Project     api-response
 * @author      Mohamed Abdulalim (megyptm)
 * @link        https://github.com/Maatify/api-response
 * @since       2025-04-18 2:05 PM
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
use Maatify\SlimLogger\Log\Logger;
use Maatify\SlimLogger\Log\LogLevelEnum;
use Maatify\SlimLogger\Store\File\Path;

abstract class CoreResponder
{
    protected static function generateErrorTrace(int|string $line = 0): string
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '';
        $path = trim(parse_url($uri, PHP_URL_PATH), '/');
        $cleanPath = str_replace('/', ':', $path);
        return $cleanPath . '::' . $line;
    }

    protected static function logResponse(array $data): void
    {
        if (!empty($_ENV['JSON_POST_LOG'])) {
            $post = $_POST;
            if (isset($post['password'])) {
                $post['password'] = '******';
            }

            $logData = [
                'response'    => $data,
                'posted_data' => $post,
                'agent'       => $_SERVER['HTTP_USER_AGENT'] ?? '',
                'ip'          => $_SERVER['REMOTE_ADDR'] ?? '',
                'uri'         => $_SERVER['REQUEST_URI'] ?? '',
            ];

            $path = new Path(__DIR__ . '/../../logs');
            $logger = new Logger($path);

            $logger->record(
                message: $logData,
                request: null,
                logFile: 'api/response',
                level: LogLevelEnum::Info
            );
        }
    }


    #[NoReturn]
    public static function headerResponseJson(ResponseInterface $response, array $data): ResponseInterface
    {
        self::logResponse($data);

        $response->getBody()->write(
            json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        );

        return $response->withHeader('Content-Type', 'application/json');
    }

    #[NoReturn]
    public static function errorWithHeader400(
        ResponseInterface $response,
        int $code,
        string $varName = '',
        array|string $description = '',
        string $moreInfo = '',
        int|string $line = 0
    ): ResponseInterface {
        return self::headerResponseJson($response->withStatus(400), [
            'success'       => false,
            'response'      => $code,
            'var'           => $varName,
            'description'   => $description,
            'more_info'     => $moreInfo,
            'error_details' => self::generateErrorTrace($line),
        ]);
    }

    #[NoReturn]
    public static function headerResponseError(ResponseInterface $response, int $code, string $desc, string $more = '', string|int $line = ''): ResponseInterface
    {
        return self::headerResponseJson($response->withStatus($code), [
            'success'       => false,
            'response'      => $code,
            'description'   => $desc,
            'more_info'     => $more,
            'error_details' => self::generateErrorTrace($line),
        ]);
    }

    #[NoReturn]
    public static function headerResponseSuccess(ResponseInterface $response, array $data): ResponseInterface
    {
        return self::headerResponseJson($response, array_merge([
            'success' => true,
            'response' => 200,
            'description' => '',
        ], $data));
    }
}
