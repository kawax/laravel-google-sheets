<?php

namespace Tests;

use Illuminate\Support\Collection;
use Mockery as m;

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

    public function setUp(): void
    {
        parent::setUp();

        $this->service = m::mock('Google_Service_Sheets')->makePartial();
        $this->spreadsheets = m::mock('Google_Service_Sheets_Resource_Spreadsheets');
        $this->service->spreadsheets = $this->spreadsheets;
        $this->values = m::mock('Google_Service_Sheets_Resource_SpreadsheetsValues');
        $this->service->spreadsheets_values = $this->values;

        $this->sheet = new Sheets();

        $this->sheet->setService($this->service);
    }

    public function tearDown(): void
    {
        m::close();
    }

    public function testSheetsAll()
    {
        $response = new \Google_Service_Sheets_BatchGetValuesResponse();
        $valueRange = new \Google_Service_Sheets_ValueRange();
        $valueRange->setValues([['test1' => '1'], ['test2' => '2']]);
        $response->setValueRanges([$valueRange]);

        $this->values->shouldReceive('batchGet')->with(m::any(), m::any())->once()->andReturn($response);

        $values = $this->sheet->spreadsheet('test')
                              ->sheet('test')
                              ->range('A1!A1')
                              ->majorDimension('test')
                              ->valueRenderOption('test')
                              ->dateTimeRenderOption('test')
                              ->all();

        $this->assertGreaterThan(1, count($values));
        $this->assertSame([['test1' => '1'], ['test2' => '2']], $values);
    }

    public function testSheetsEmpty()
    {
        $response = new \Google_Service_Sheets_BatchGetValuesResponse();
        $valueRange = new \Google_Service_Sheets_ValueRange();
        $valueRange->setValues(null);
        $response->setValueRanges([$valueRange]);

        $this->values->shouldReceive('batchGet')->with(m::any(), m::any())->once()->andReturn($response);

        $values = $this->sheet->all();

        $this->assertSame([], $values);
    }

    public function testSheetsGet()
    {
        $response = new \Google_Service_Sheets_BatchGetValuesResponse();
        $valueRange = new \Google_Service_Sheets_ValueRange();
        $valueRange->setValues([['test1' => '1'], ['test2' => '2']]);
        $response->setValueRanges([$valueRange]);

        $this->values->shouldReceive('batchGet')->with(m::any(), m::any())->once()->andReturn($response);

        $values = $this->sheet->get();

        $this->assertInstanceOf(Collection::class, $values);
        $this->assertSame([['test1' => '1'], ['test2' => '2']], $values->toArray());
    }

    public function testSheetsUpdate()
    {
        $response = new \Google_Service_Sheets_UpdateValuesResponse();

        $this->values->shouldReceive('batchUpdate')->once()->andReturn($response);

        $values = $this->sheet->sheet('test')->range('A1')->update([['test']]);

        $this->assertEquals('test!A1', $this->sheet->ranges());
        $this->assertInstanceOf('Google_Service_Sheets_UpdateValuesResponse', $values);
    }

    public function testSheetsFirst()
    {
        $response = new \Google_Service_Sheets_BatchGetValuesResponse();
        $valueRange = new \Google_Service_Sheets_ValueRange();
        $valueRange->setValues([['test1' => '1'], ['test2' => '2']]);
        $response->setValueRanges([$valueRange]);

        $this->values->shouldReceive('batchGet')->with(m::any(), m::any())->once()->andReturn($response);

        $value = $this->sheet->first();

        $this->assertSame(['test1' => '1'], $value);
    }

    public function testSheetsFirstEmpty()
    {
        $response = new \Google_Service_Sheets_BatchGetValuesResponse();
        $valueRange = new \Google_Service_Sheets_ValueRange();
        $valueRange->setValues(null);
        $response->setValueRanges([$valueRange]);

        $this->values->shouldReceive('batchGet')->with(m::any(), m::any())->once()->andReturn($response);

        $value = $this->sheet->first();

        $this->assertSame([], $value);
    }

    public function testSheetsClear()
    {
        $this->values->shouldReceive('clear')->once();

        $value = $this->sheet->clear();

        $this->assertNull($value);
    }

    public function testSheetsAppend()
    {
        $response = new \Google_Service_Sheets_AppendValuesResponse();
        $updates = new \Google_Service_Sheets_UpdateValuesResponse();
        $valueRange = new \Google_Service_Sheets_ValueRange();
        $valueRange->setValues([['test1' => '1'], ['test2' => '2']]);
        $response->setUpdates($updates);

        $this->values->shouldReceive('append')->once()->andReturn($response);

        $value = $this->sheet->append([]);

        $this->assertSame($response, $value);
    }

    public function testSpreadsheetProperties()
    {
        $this->spreadsheets->shouldReceive('get->getProperties->toSimpleObject')->once()->andReturn(new \stdClass());

        $properties = $this->sheet->spreadsheetProperties();

        $this->assertInstanceOf(\stdClass::class, $properties);
    }

    public function testSheetProperties()
    {
        $sheet = m::mock(\Google_Service_Sheets_Spreadsheet::class);
        $sheet->shouldReceive('getProperties->toSimpleObject')->once()->andReturn(new \stdClass());

        $this->spreadsheets->shouldReceive('get->getSheets')->once()->andReturn([$sheet]);

        $properties = $this->sheet->sheetProperties();

        $this->assertInstanceOf(\stdClass::class, $properties);
    }

    public function testMagicGet()
    {
        $spreadsheets = $this->sheet->spreadsheets;

        $this->assertNotNull($spreadsheets);
    }

    public function testSheetsList()
    {
        $sheets = new \Google_Service_Sheets_Sheet([
            'properties' => [
                'sheetId' => 'sheetId',
                'title'   => 'title',
            ],
        ]);

        $sheet = m::mock(Sheets::class)->makePartial()->shouldAllowMockingProtectedMethods();

        $sheet->shouldReceive('serviceSpreadsheets->get->getSheets')->andReturn([$sheets]);

        $values = $sheet->sheetList();

        $this->assertSame(['sheetId' => 'title'], $values);
    }

    public function testSheetById()
    {
        $sheets = new \Google_Service_Sheets_Sheet([
            'properties' => [
                'sheetId' => 'sheetId',
                'title'   => 'title',
            ],
        ]);

        $sheet = m::mock(Sheets::class)->makePartial();

        $sheet->shouldReceive('sheetList')->andReturn([$sheets]);

        $sheet->sheetById('sheetId');

        $this->assertNotNull($sheet);
    }

    public function testSpreadsheetByTitle()
    {
        $list = [
            'id' => 'title',
        ];

        $sheet = m::mock(Sheets::class)->makePartial();

        $sheet->shouldReceive('spreadsheetList')->andReturn($list);

        $sheet->spreadsheetByTitle('title');

        $this->assertNotNull($sheet);
    }

    public function testGetAccessToken()
    {
        $sheet = m::mock(Sheets::class)->makePartial();

        $token = $sheet->getAccessToken();

        $this->assertNull($token);
    }

    public function testProperty()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->sheet->test;
    }

    public function testGetClient()
    {
        $client = $this->sheet->getClient();

        $this->assertNull($client);
    }
}
