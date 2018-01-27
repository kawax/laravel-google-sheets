<?php
namespace Revolution\Google\Sheets\tests;

use Mockery as m;
use PHPUnit\Framework\TestCase;

use Revolution\Google\Sheets\Sheets;

class SheetsMockTest extends TestCase
{
    /**
     * @var Sheets
     */
    protected $sheet;

    /**
     * @var \Google_Service_Sheets
     */
    protected $service;

    /**
     * @var \Google_Service_Sheets_Resource_Spreadsheets
     */
    protected $spreadsheets;

    /**
     * @var \Google_Service_Sheets_Resource_SpreadsheetsValues
     */
    protected $values;

    public function setUp()
    {
        parent::setUp();

        $this->service = m::mock('Google_Service_Sheets');
        $this->spreadsheets = m::mock('Google_Service_Sheets_Resource_Spreadsheets');
        $this->service->spreadsheets = $this->spreadsheets;
        $this->values = m::mock('Google_Service_Sheets_Resource_SpreadsheetsValues');
        $this->service->spreadsheets_values = $this->values;

        $this->sheet = new Sheets();

        $this->sheet->setService($this->service);
    }

    public function tearDown()
    {
        m::close();
    }

    public function testSheetsGetMock()
    {
        $response = new \Google_Service_Sheets_BatchGetValuesResponse();
        $valueRange = new \Google_Service_Sheets_ValueRange();
        $valueRange->setValues([['test1' => '1'], ['test2' => '2']]);
        $response->setValueRanges([$valueRange]);

        $this->values->shouldReceive('batchGet')->with(m::any(), m::any())->once()->andReturn($response);

        $values = $this->sheet->all();

        $this->assertGreaterThan(1, count($values));
    }

    public function testSheetsUpdate()
    {
        $response = new \Google_Service_Sheets_UpdateValuesResponse();

        $this->values->shouldReceive('batchUpdate')->once()->andReturn($response);

        $values = $this->sheet->sheet('test')->range('A1')->update([['test']]);

        $this->assertEquals('test!A1', $this->sheet->ranges());
        $this->assertInstanceOf('Google_Service_Sheets_UpdateValuesResponse', $values);
    }
}
