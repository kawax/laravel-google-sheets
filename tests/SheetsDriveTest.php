<?php

namespace Revolution\Google\Sheets\Tests;

use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Google\Service\Drive\Resource\Files;
use Mockery as m;
use Revolution\Google\Sheets\GoogleSheetClient;
use Revolution\Google\Sheets\Facades\Sheets;

class SheetsDriveTest extends TestCase
{
    /**
     * @var Drive
     */
    protected $service;

    /**
     * @var Files
     */
    protected $files;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = m::mock('Google_Service_Drive');
        $this->files = m::mock('Google_Service_Drive_Resource_Files');
        $this->service->files = $this->files;

        Sheets::setDriveService($this->service);
    }

    public function testList()
    {
        $file = new DriveFile([
            'id'   => 'id',
            'name' => 'name',
        ]);

        $files = [
            $file,
        ];

        $this->files->shouldReceive('listFiles->getFiles')->once()->andReturn($files);

        $list = Sheets::spreadsheetList();

        $this->assertSame(['id' => 'name'], $list);
    }

    public function testNull()
    {
        $this->mock(GoogleSheetClient::class, function ($mock) {
            $mock->shouldReceive('make')->andReturn($this->service);
        });

        $drive = Sheets::setDriveService(null)->getDriveService();

        $this->assertSame($this->service, $drive);
    }
}
