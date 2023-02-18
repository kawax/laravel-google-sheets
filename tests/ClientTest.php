<?php

namespace Revolution\Google\Sheets\Tests;

use Mockery;
use PHPUnit\Framework\TestCase;
use Revolution\Google\Sheets\Exceptions\UnknownServiceException;
use Revolution\Google\Sheets\GoogleSheetClient;

class ClientTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close();
    }

    public function testClientGetter()
    {
        $client = Mockery::mock(GoogleSheetClient::class, [[]])->makePartial();

        $this->assertInstanceOf('Google\Client', $client->getClient());
    }

    public function testClientGetterWithAdditionalConfig()
    {
        $client = Mockery::mock(GoogleSheetClient::class, [[
            'config' => [
                'subject' => 'test',
            ],
        ]])->makePartial();

        $this->assertEquals($client->getClient()->getConfig('subject'), 'test');
    }

    public function testServiceMake()
    {
        $client = Mockery::mock(GoogleSheetClient::class, [[]])->makePartial();

        $this->assertInstanceOf('Google\Service\Storage', $client->make('storage'));
    }

    public function testServiceMakeException()
    {
        $client = Mockery::mock(GoogleSheetClient::class, [[]])->makePartial();

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
}
