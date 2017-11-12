<?php

namespace Revolution\Google\Sheets\tests;

use Mockery;
use PHPUnit\Framework\TestCase;

use Google_Service_Sheets;

use PulkitJalan\Google\Client;

use Revolution\Google\Sheets\Sheets;

class SheetsTest extends TestCase
{
    /**
     * @var Sheets
     */
    protected $sheet;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $spreadsheetId;

    /**
     * @var string
     */
    protected $spreadsheetTitle;

    /**
     * @var string
     */
    protected $sheetTitle;

    /**
     * @var integer
     */
    protected $sheetId;

    public function setUp()
    {
        parent::setUp();

        if ($this->checkDevConfig()) {
            $config = __DIR__ . '/data/test-credentials.json';
            include __DIR__ . '/data/test-config.php';
        } else {
            $config = __DIR__ . '/data/service-account.json';
        }

        $this->client = new Client([
            'scopes'  => [Google_Service_Sheets::DRIVE, Google_Service_Sheets::SPREADSHEETS],
            'service' => [
                'enable' => true,
                'file'   => $config,
            ],
        ]);

        $this->sheet = new Sheets();

        $this->sheet->setService($this->client->make('Sheets'));
    }

    public function tearDown()
    {
        Mockery::close();
    }

    /**
     * not mock
     *
     * @return bool
     */
    private function checkDevConfig()
    {
        return file_exists(__DIR__ . '/data/test-config.php');
    }

    public function testSheetsInstance()
    {
        $this->assertInstanceOf('Revolution\Google\Sheets\Sheets', $this->sheet);
    }

    public function testService()
    {
        $this->assertInstanceOf('Google_Service_Sheets', $this->sheet->getService());
    }

    public function testSpreadsheetByTitle()
    {
        if (!$this->checkDevConfig()) {
            return;
        }

        $this->sheet->setDriveService($this->client->make('drive'));
        $properties = $this->sheet->spreadsheetByTitle($this->spreadsheetTitle)->spreadsheetProperties();

        $this->assertEquals($this->spreadsheetTitle, $properties->title);
    }

    public function testSpreadsheetProperties()
    {
        if (!$this->checkDevConfig()) {
            return;
        }

        $properties = $this->sheet->spreadsheet($this->spreadsheetId)->spreadsheetProperties();

        //        dd($properties);

        $this->assertNotEmpty($properties->title);
    }

    public function testSheetProperties()
    {
        if (!$this->checkDevConfig()) {
            return;
        }

        $properties = $this->sheet->spreadsheet($this->spreadsheetId)->sheet($this->sheetTitle)->sheetProperties();

        //        dd($properties);

        $this->assertEquals($this->sheetTitle, $properties->title);
    }

    public function testSheetList()
    {
        if (!$this->checkDevConfig()) {
            return;
        }

        $list = $this->sheet->spreadsheet($this->spreadsheetId)->sheetList();

        $this->assertGreaterThan(1, count($list));
    }

    public function testSpreadSheetList()
    {
        if (!$this->checkDevConfig()) {
            return;
        }

        $this->sheet->setDriveService($this->client->make('drive'));

        $lists = $this->sheet->spreadsheetList();

        $this->assertGreaterThan(0, count($lists));
    }

    public function testSheetById()
    {
        if (!$this->checkDevConfig()) {
            return;
        }

        $list = $this->sheet->spreadsheet($this->spreadsheetId)->sheetList();
        $sheet = array_get($list, $this->sheetId);

        $this->assertNotEmpty($sheet);
    }

    public function testSheetValuesBatchGet()
    {
        if (!$this->checkDevConfig()) {
            return;
        }

        $sheets = $this->sheet->spreadsheets_values->batchGet($this->spreadsheetId, ['ranges' => $this->sheetTitle]);

        $this->assertNotEmpty($sheets);
    }

    public function testSheetValuesGet()
    {
        if (!$this->checkDevConfig()) {
            return;
        }

        $sheets = $this->sheet
            ->spreadsheet($this->spreadsheetId)
            ->sheet($this->sheetTitle)
            ->all();

        $this->assertGreaterThan(1, count($sheets));
    }

    public function testSheetValuesFirst()
    {
        if (!$this->checkDevConfig()) {
            return;
        }

        $sheets = $this->sheet
            ->spreadsheet($this->spreadsheetId)
            ->sheet($this->sheetTitle)
            ->first();

        $this->assertNotEmpty($sheets);
    }

    public function testSheetValuesRange()
    {
        if (!$this->checkDevConfig()) {
            return;
        }

        $sheets = $this->sheet
            ->spreadsheet($this->spreadsheetId)
            ->sheet($this->sheetTitle)
            ->range('A1:E3')
            ->all();

        //        dd($sheets);
        $this->assertEquals(3, count($sheets));
    }

    public function testSheetValuesMajorDimension()
    {
        if (!$this->checkDevConfig()) {
            return;
        }

        $sheets = $this->sheet
            ->spreadsheet($this->spreadsheetId)
            ->sheet($this->sheetTitle)
            ->range('A1:E3')
            ->majorDimension('COLUMNS')
            ->all();

        //        dd($sheets);
        $this->assertEquals(5, count($sheets));
    }

    public function testSheetUpdate()
    {
        if (!$this->checkDevConfig()) {
            return;
        }

        $response = $this->sheet
            ->spreadsheet($this->spreadsheetId)
            ->sheet('test')
            ->range('A1:B3')
            ->update([['test', 'test2'], ['test3']]);

        //        dd($response);
        $this->assertEquals($this->spreadsheetId, $response->getSpreadsheetId());
    }

    public function testSheetAppend()
    {
        if (!$this->checkDevConfig()) {
            return;
        }

        $response = $this->sheet
            ->spreadsheet($this->spreadsheetId)
            ->sheet('test')
            ->range('')
            ->append([['test_append']]);

        //                dd($response);
        $this->assertEquals(1, $response->updates->updatedRows);
    }

    public function testSheetsClear()
    {
        if (!$this->checkDevConfig()) {
            return;
        }

        $response = $this->sheet
            ->spreadsheet($this->spreadsheetId)
            ->sheet('test')
            ->range('')
            ->clear();

        $this->assertEquals("test!A1:Z1000", $response->clearedRange);
    }
}
