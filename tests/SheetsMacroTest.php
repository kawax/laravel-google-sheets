<?php

namespace Revolution\Google\Sheets\tests;

use PHPUnit\Framework\TestCase;

use Revolution\Google\Sheets\Sheets;

class SheetsMacroTest extends TestCase
{
    public function testMacro()
    {
        Sheets::macro('test', function () {
            return 'test';
        });

        $this->assertTrue(Sheets::hasMacro('test'));
        $this->assertTrue(is_callable(Sheets::class, 'test'));
    }
}
