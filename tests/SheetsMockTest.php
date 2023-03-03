<?php

namespace Revolution\Google\Sheets\Tests;

use Google\Service\Sheets\AppendValuesResponse;
use Google\Service\Sheets as GoogleSheets;
use Google\Service\Sheets\BatchGetValuesResponse;
use Google\Service\Sheets\BatchUpdateSpreadsheetResponse;
use Google\Service\Sheets\BatchUpdateValuesResponse;
use Google\Service\Sheets\Resource\Spreadsheets;
use Google\Service\Sheets\Resource\SpreadsheetsValues;
use Google\Service\Sheets\Sheet;
use Google\Service\Sheets\Spreadsheet;
use Google\Service\Sheets\UpdateValuesResponse;
use Google\Service\Sheets\ValueRange;
use Illuminate\Support\Collection;
use Mockery as m;
use Revolution\Google\Sheets\Sheets;

class SheetsMockTest extends TestCase
{
    protected Sheets $sheet;

    protected GoogleSheets $service;

    protected Spreadsheets $spreadsheets;

    protected SpreadsheetsValues $values;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = m::mock(GoogleSheets::class)->makePartial();
        $this->spreadsheets = m::mock(Spreadsheets::class);
        $this->service->spreadsheets = $this->spreadsheets;
        $this->values = m::mock(SpreadsheetsValues::class);
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
        $response = new BatchGetValuesResponse();
        $valueRange = new ValueRange();
        $valueRange->setValues([['test1' => '1'], ['test2' => '2']]);
        $response->setValueRanges([$valueRange]);

        $this->values->shouldReceive('batchGet')->with(m::any(), m::any())->once()->andReturn($response);

        $values = $this->sheet->spreadsheet('test')
                              ->sheet('test')
                              ->majorDimension('test')
                              ->valueRenderOption('test')
                              ->dateTimeRenderOption('test')
                              ->all();

        $this->assertGreaterThan(1, count($values));
        $this->assertSame([['test1' => '1'], ['test2' => '2']], $values);
    }

    public function testSheetsEmpty()
    {
        $response = new BatchGetValuesResponse();
        $valueRange = new ValueRange();
        $valueRange->setValues(null);
        $response->setValueRanges([$valueRange]);

        $this->values->shouldReceive('batchGet')->with(m::any(), m::any())->once()->andReturn($response);

        $values = $this->sheet->all();

        $this->assertSame([], $values);
    }

    public function testSheetsGet()
    {
        $response = new BatchGetValuesResponse();
        $valueRange = new ValueRange();
        $valueRange->setValues([['test1' => '1'], ['test2' => '2']]);
        $response->setValueRanges([$valueRange]);

        $this->values->shouldReceive('batchGet')->with(m::any(), m::any())->once()->andReturn($response);

        $values = $this->sheet->get();

        $this->assertInstanceOf(Collection::class, $values);
        $this->assertSame([['test1' => '1'], ['test2' => '2']], $values->toArray());
    }

    public function testSheetsUpdate()
    {
        $response = new BatchUpdateValuesResponse();

        $this->values->shouldReceive('batchUpdate')->once()->andReturn($response);

        $values = $this->sheet->sheet('test')->range('A1')->update([['test']]);

        $this->assertEquals('test!A1', $this->sheet->ranges());
        $this->assertInstanceOf(BatchUpdateValuesResponse::class, $values);
    }

    public function testSheetsFirst()
    {
        $response = new BatchGetValuesResponse();
        $valueRange = new ValueRange();
        $valueRange->setValues([['test1' => '1'], ['test2' => '2']]);
        $response->setValueRanges([$valueRange]);

        $this->values->shouldReceive('batchGet')->with(m::any(), m::any())->once()->andReturn($response);

        $value = $this->sheet->first();

        $this->assertSame(['test1' => '1'], $value);
    }

    public function testSheetsFirstEmpty()
    {
        $response = new BatchGetValuesResponse();
        $valueRange = new ValueRange();
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
        $response = new AppendValuesResponse;
        $updates = new UpdateValuesResponse;
        $valueRange = new ValueRange;
        $valueRange->setValues([['test1' => '1'], ['test2' => '2']]);
        $response->setUpdates($updates);

        $this->values->shouldReceive('append')->once()->andReturn($response);

        $value = $this->sheet->append([[]]);

        $this->assertSame($response, $value);
    }

    public function testSheetsAppendWithKeys()
    {
        $response = new BatchGetValuesResponse();
        $valueRange = new ValueRange();
        $valueRange->setValues([['header1', 'header2'], ['value1', 'value2']]);
        $response->setValueRanges([$valueRange]);

        $this->values->shouldReceive('batchGet')
                     ->with(m::any(), m::any())
                     ->andReturn($response);

        $ordered = $this->sheet->orderAppendables([['header2' => 'value3', 'header1' => null]]);
        $this->assertSame([['', 'value3']], $ordered);
    }

    public function testSpreadsheetProperties()
    {
        $this->spreadsheets->shouldReceive('get->getProperties->toSimpleObject')->once()->andReturn(new \stdClass());

        $properties = $this->sheet->spreadsheetProperties();

        $this->assertInstanceOf(\stdClass::class, $properties);
    }

    public function testSheetProperties()
    {
        $sheet = m::mock(Spreadsheet::class);
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
        $sheets = new Sheet([
            'properties' => [
                'sheetId' => 'sheetId',
                'title' => 'title',
            ],
        ]);

        $this->spreadsheets->shouldReceive('get->getSheets')->andReturn([$sheets]);
        $values = $this->sheet->sheetList();

        $this->assertSame(['sheetId' => 'title'], $values);
    }

    public function testSheetById()
    {
        $sheets = new Sheet([
            'properties' => [
                'sheetId' => 'sheetId',
                'title' => 'title',
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

    public function testAddSheet()
    {
        $this->spreadsheets
            ->shouldReceive('batchUpdate')
            ->andReturn(new BatchUpdateSpreadsheetResponse);

        $response = $this->sheet->addSheet('new sheet');
        $this->assertNotNull($response);
    }

    public function testDeleteSheet()
    {
        $sheets = new Sheet([
            'properties' => [
                'sheetId' => 'sheetId',
                'title' => 'title',
            ],
        ]);

        $this->spreadsheets->shouldReceive('get->getSheets')->andReturn([$sheets]);
        $this->spreadsheets
            ->shouldReceive('batchUpdate')
            ->andReturn(new BatchUpdateSpreadsheetResponse);

        $this->sheet->shouldReceive('sheetList')->andReturn([$sheets]);
        $response = $this->sheet->deleteSheet('title');
        $this->assertNotNull($response);
    }

    public function testGetProperRanges()
    {
        $this->values
            ->shouldReceive('batchUpdate')
            ->times(3)
            ->andReturn(new BatchUpdateValuesResponse());

        // If no range is provided, we get the sheet automatically
        $this->sheet->sheet('test')->update([['test']]);
        $this->assertEquals('test', $this->sheet->ranges());

        // If we provide the full range, it returns accurately
        $this->sheet->sheet('test')->range('test!A1')->update([['test']]);
        $this->assertEquals('test!A1', $this->sheet->ranges());

        // If we only provide part of the range, we get the full proper range
        $this->sheet->sheet('test')->range('A1')->update([['test']]);
        $this->assertEquals('test!A1', $this->sheet->ranges());
    }
}
