<?php

namespace PulkitJalan\Google\tests;

use Mockery;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close();
    }

    public function testClientGetter()
    {
        $client = Mockery::mock('PulkitJalan\Google\Client', [[]])->makePartial();

        $this->assertInstanceOf('Google_Client', $client->getClient());
    }

    public function testClientGetterWithAdditionalConfig()
    {
        $client = Mockery::mock('PulkitJalan\Google\Client', [[
            'config' => [
                'subject' => 'test',
            ],
        ]])->makePartial();

        $this->assertEquals($client->getClient()->getConfig('subject'), 'test');
    }

    public function testServiceMake()
    {
        $client = Mockery::mock('PulkitJalan\Google\Client', [[]])->makePartial();

        $this->assertInstanceOf('Google_Service_Storage', $client->make('storage'));
    }

    public function testServiceMakeException()
    {
        $client = Mockery::mock('PulkitJalan\Google\Client', [[]])->makePartial();

        $this->expectException('PulkitJalan\Google\Exceptions\UnknownServiceException');

        $client->make('storag');
    }

    public function testMagicMethodException()
    {
        $client = new \PulkitJalan\Google\Client([]);

        $this->expectException('BadMethodCallException');

        $client->getAuthTest();
    }

    public function testNoCredentials()
    {
        $client = new \PulkitJalan\Google\Client([]);

        $this->assertFalse($client->isUsingApplicationDefaultCredentials());
    }

    public function testDefaultCredentials()
    {
        $client = new \PulkitJalan\Google\Client([
            'service' => [
                'enable' => true,
                'file' => __DIR__.'/data/test.json',
            ],
        ]);

        $this->assertTrue($client->isUsingApplicationDefaultCredentials());
    }
}
