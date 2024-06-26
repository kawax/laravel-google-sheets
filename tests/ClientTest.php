<?php

namespace Tests;

use Mockery as m;
use PHPUnit\Framework\Attributes\RequiresMethod;
use PulkitJalan\Google\Client as GoogleClient;
use Revolution\Google\Client\Exceptions\UnknownServiceException;
use Revolution\Google\Client\Facades\Google;
use Revolution\Google\Client\GoogleSheetClient;

class ClientTest extends TestCase
{
    public function tearDown(): void
    {
        m::close();

        parent::tearDown();
    }

    public function testClientGetter()
    {
        $client = m::mock(GoogleSheetClient::class, [[]])->makePartial();

        $this->assertInstanceOf('Google\Client', $client->getClient());
    }

    public function testClientGetterWithAdditionalConfig()
    {
        $client = m::mock(GoogleSheetClient::class, [[
            'config' => [
                'subject' => 'test',
            ],
        ]])->makePartial();

        $this->assertEquals('test', $client->getClient()->getConfig('subject'));
    }

    public function testServiceMake()
    {
        $client = m::mock(GoogleSheetClient::class, [[]])->makePartial();

        $this->assertInstanceOf('Google\Service\Storage', $client->make('storage'));
    }

    public function testServiceMakeException()
    {
        $client = m::mock(GoogleSheetClient::class, [[]])->makePartial();

        $this->expectException(UnknownServiceException::class);

        $client->make('storag');
    }

    public function testMagicMethodException()
    {
        $client = new GoogleSheetClient([]);

        $this->expectException('BadMethodCallException');

        $client->getAuthTest();
    }

    public function testNoCredentials()
    {
        $client = new GoogleSheetClient([]);

        $this->assertFalse($client->isUsingApplicationDefaultCredentials());
    }

    public function testDefaultCredentials()
    {
        $client = new GoogleSheetClient([
            'service' => [
                'enable' => true,
                'file' => __DIR__.'/data/test.json',
            ],
        ]);

        $this->assertTrue($client->isUsingApplicationDefaultCredentials());
    }

    #[RequiresMethod(GoogleClient::class, 'make')]
    public function test_original_client()
    {
        $this->assertInstanceOf(GoogleSheetClient::class, Google::getFacadeRoot());
        Google::clearResolvedInstances();

        $this->app->alias(GoogleClient::class, 'google-client');

        $this->assertInstanceOf(GoogleClient::class, app('google-client'));
        $this->assertInstanceOf(GoogleClient::class, Google::getFacadeRoot());
    }
}
