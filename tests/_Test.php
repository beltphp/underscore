<?php
namespace Belt;

use Belt\_;

class _Test extends \PHPUnit_Framework_TestCase
{
    public function testAll()
    {
        $this->assertTrue(_::create([1, 2, 3, 4])->all(function ($n) {
            return $n > 0;
        }));

        $this->assertFalse(_::create([1, 2, 3, 4])->all(function ($n) {
            return $n < 0;
        }));
    }

    public function testAny()
    {
        $this->assertTrue(_::create([1, 2, 3, 4])->any(function ($n) {
            return $n > 0;
        }));

        $this->assertFalse(_::create([1, 2, 3, 4])->any(function ($n) {
            return $n < 0;
        }));
    }

    public function testChunk()
    {
        $this->assertEquals([[1, 2], [3, 4]], _::create([1, 2, 3, 4])->chunk(2)->toArray());
    }

    public function testConcat()
    {
        $this->assertEquals([1, 2, 3, 4], _::create([1, 2])->concat([3, 4])->toArray());
    }
}
