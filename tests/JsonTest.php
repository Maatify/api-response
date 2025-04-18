<?php

namespace Maatify\ApiResponse\Tests;

use Maatify\ApiResponse\Json;
use PHPUnit\Framework\TestCase;
use Slim\Psr7\Factory\ResponseFactory;

class JsonTest extends TestCase
{
    public function testMissingResponseStructure(): void
    {
        $response = (new ResponseFactory())->createResponse();
        $response = Json::missing($response, 'email', 'Required', 99);

        $this->assertSame(400, $response->getStatusCode());

        $body = (string) $response->getBody();
        $data = json_decode($body, true);

        $this->assertIsArray($data);
        $this->assertFalse($data['success']);
        $this->assertSame(1000, $data['response']);
        $this->assertSame('email', $data['var']);
        $this->assertSame('MISSING Email', $data['description']);
        $this->assertSame('Required', $data['more_info']);
        $this->assertStringContainsString('::99', $data['error_details']);
    }

    public function testSuccessResponse(): void
    {
        $response = (new ResponseFactory())->createResponse();
        $response = Json::success($response, ['id' => 42], 'All good', 'More info', 123);

        $this->assertSame(200, $response->getStatusCode());

        $body = (string) $response->getBody();
        $data = json_decode($body, true);
        $this->assertIsArray($data['result']);
        $this->assertTrue($data['success']);
        $this->assertSame(200, $data['response']);
        $this->assertSame('All good', $data['description']);
        $this->assertSame('More info', $data['more_info']);
        $this->assertSame(['id' => 42], $data['result']);
        $this->assertStringContainsString('::123', $data['error_details']);
    }

    public function testTooManyAttempts(): void
    {
        $response = (new ResponseFactory())->createResponse();
        $response = Json::tooManyAttempts($response, 'ip', 60, 500);

        $this->assertSame(429, $response->getStatusCode());

        $body = (string) $response->getBody();
        $data = json_decode($body, true);

        $this->assertFalse($data['success']);
        $this->assertSame('TOO_MANY_ATTEMPTS', $data['error_code']);
        $this->assertSame(60, $data['retry_after']);
        $this->assertSame('ip', $data['details']['var']);
        $this->assertStringContainsString('::500', $data['error_details']);
    }
}
