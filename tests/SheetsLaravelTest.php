<?php
namespace GoogleSheet\tests;

use Mockery as m;
use PHPUnit_Framework_TestCase;

use GoogleSheets\SheetsLaravel;

class SheetsLaravelTest extends PHPUnit_Framework_TestCase
{
    protected $laravel;

    public function setUp()
    {
        parent::setUp();

        $this->laravel = new SheetsLaravel();
    }

//    public function tearDown()
//    {
//        m::close();
//    }

    public function testCollection()
    {
        $header = ['id', 'name', 'mail'];
        $rows = [
            ['1', 'name1', 'mail1'],
            ['2', 'name2', 'mail2']
        ];

        $collection = $this->laravel->collection($header, $rows);

//        dd($collection);
        $this->assertEquals('name1', $collection->first()['name']);

    }

    public function testCollection2()
    {
        $header = ['id', 'name', 'mail'];
        $rows = [
            ['1', 'name1', 'mail1'],
            ['2', 'name2']
        ];

        $collection = $this->laravel->collection($header, $rows);

//        dd($collection);
        $this->assertNotNull($collection->last()['mail']);

    }

}
