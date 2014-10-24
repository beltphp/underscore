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

    public function testCombine()
    {
        $this->assertEquals([1 => 'foo', 2 => 'bar', 3 => 'baz'], _::create([1, 2, 3])->combine(['foo', 'bar', 'baz'])->toArray());
    }

    public function testConcat()
    {
        $this->assertEquals([1, 2, 3, 4], _::create([1, 2])->concat([3, 4])->toArray());
    }

    public function testDict()
    {
        $this->assertEquals([1 => 2, 3 => 4], _::create([[1, 2], [3, 4]])->dict()->toArray());
        $this->assertEquals([1 => 2, 3 => 4], _::create([1, 2, 3, 4])->chunk(2)->dict()->toArray());
    }

    public function testEach()
    {
        $count = 0;

        _::create([1, 2, 3, 4])->each(function ($n) use (&$count) {
            $count++;
        });

        $this->assertEquals(4, $count);
    }

    public function testFind()
    {
        $this->assertEquals(3, _::create([1, 2, 3, 4])->find(function ($n) {
            return $n > 2;
        }));

        $this->assertNull(_::create([1, 2, 3, 4])->find(function ($n) {
            return $n < 0;
        }));
    }

    public function testFirst()
    {
        $this->assertEquals([1, 2], _::create([1, 2, 3, 4])->first(2)->toArray());
    }

    public function testFlatten()
    {
        $this->assertEquals([1, 2, 3, 4], _::create([1, [2], [3, [4]]])->flatten()->toArray());
    }

    public function testFlatMap()
    {
        $this->assertEquals([1, 1, 2, 2, 3, 3, 4, 4], _::create([1, 2, 3, 4])->flatMap(function ($n) {
            return [$n, $n];
        })->toArray());

        $this->assertEquals([1, [1], 2, [2], 3, [3], 4, [4]], _::create([1, 2, 3, 4])->flatMap(function ($n) {
            return [$n, [$n]];
        })->toArray());
    }

    public function testGroupBy()
    {
        $this->assertEquals(['f' => ['foo'], 'b' => ['bar', 'baz']], _::create(['foo', 'bar', 'baz'])->groupBy(function ($s) {
            return $s[0];
        })->toArray());
    }

    public function testHas()
    {
        $this->assertTrue(_::create([1, 2, 3, 4])->has(2));
        $this->assertFalse(_::create([1, 2, 3, 4])->has(0));
    }

    public function testIndexOf()
    {
        $this->assertEquals(2, _::create([1, 2, 3, 4])->indexOf(3));
        $this->assertEquals('foo', _::create(['foo' => 'bar', 'bar' => 'baz'])->indexOf('bar'));
        $this->assertNull(_::create([1, 2, 3, 4])->indexOf(0));
    }

    public function testInject()
    {
        $this->assertEquals([1 => 1, 2 => 4, 3 => 9], _::create([1, 2, 3])->inject([], function ($m, $n) {
            $m[$n] = $n * $n;

            return $m;
        }));
    }

    public function testJoin()
    {
        $this->assertEquals('1234', _::create([1, 2, 3, 4])->join(''));
        $this->assertEquals('1,2,3,4', _::create([1, 2, 3, 4])->join(','));
    }

    public function testLast()
    {
        $this->assertEquals([3, 4], _::create([1, 2, 3, 4])->last(2)->toArray());
    }

    public function testMap()
    {
        $this->assertEquals([1, 4, 9, 16], _::create([1, 2, 3, 4])->map(function ($n) {
            return $n * $n;
        })->toArray());

        $this->assertEquals([1, 9, 16], _::create([1, 2, 3, 4])->map(function ($n) {
            return $n == 2 ? null : $n * $n;
        })->toArray());
    }

    public function testMax()
    {
        $data = ['1', 'two', 'three'];
        $this->assertEquals('three', _::create($data)->max(function ($s) {
            return strlen($s);
        }));
    }

    public function testMin()
    {
        $data = ['tree', 'two', '1'];
        $this->assertEquals('1', _::create($data)->min(function ($s) {
            return strlen($s);
        }));
    }

    public function testNone()
    {
        $this->assertTrue(_::create([1, 2, 3, 4])->none(function ($n) {
            return $n > 4;
        }));
        $this->assertFalse(_::create([1, 2, 3, 4])->none(function ($n) {
            return $n > 0;
        }));
    }

    public function testPartition()
    {
        $this->assertEquals([['A', 'AA'], ['B', 'C']], _::create(['A', 'B', 'C', 'AA'])->partition(function ($s) {
            return $s[0] == 'A';
        })->toArray());
    }

    public function testPluck()
    {
        $users = [
            (object) [ 'foo' => 'bar' ],
            (object) [ 'username' => 'bob' ],
            (object) [ 'username' => 'alice' ],
        ];

        $this->assertEquals(['bob', 'alice'], _::create($users)->pluck('username')->toArray());
    }

    public function testReduce()
    {
        $this->assertEquals(10, _::create([1, 2, 3, 4])->reduce(function ($memo, $n) {
            return $memo + $n;
        }));
    }

    public function testReject()
    {
        $this->assertEquals([1, 3], _::create([1, 2, 3, 4])->reject(function ($n) {
            return ($n % 2) == 0;
        })->toArray());
    }

    public function testReverse()
    {
        $this->assertEquals([4, 3, 2, 1], _::create([1, 2, 3, 4])->reverse()->toArray());
    }

    public function testSelect()
    {
        $this->assertEquals([2, 4], _::create([1, 2, 3, 4])->select(function ($n) {
            return ($n % 2) == 0;
        })->toArray());
    }

    public function testSkip()
    {
        $this->assertEquals([3, 4, 5, 6], _::create([1, 2, 3, 4, 5, 6])->skip(2)->toArray());
    }

    public function testSlice()
    {
        $this->assertEquals([2, 3], _::create([1, 2, 3, 4])->slice(1, 2)->toArray());
    }

    public function testSnip()
    {
        $this->assertEquals([1, 2], _::create([1, 2, 3, 4])->snip(2)->toArray());
    }

    public function testSort()
    {
        $this->assertEquals([1, 2, 3, 4], _::create([4, 1, 3, 2])->sort()->toArray());
    }

    public function testSortBy()
    {
        $foo = (object) [ 'name' => 'foo' ];
        $bar = (object) [ 'name' => 'bar' ];
        $baz = (object) [ 'name' => 'baz' ];

        $this->assertEquals([ $bar, $baz, $foo ], _::create([ $foo, $bar, $baz ])->sortBy(function ($o) {
            return $o->name;
        })->toArray());
    }

    public function testTranspose()
    {
        $this->assertEquals([[1, 4], [2, 5], [3, 6]], _::create([[1, 2, 3], [4, 5, 6]])->transpose()->toArray());
    }

    public function testUniq()
    {
        $this->assertEquals([1, 2, 3], _::create([1, 2, 3, 1, 2, 3])->uniq()->toArray());
    }

    public function testWithout()
    {
        $this->assertEquals([1, 2, 3], _::create([1, 2, 3, 4])->without([4])->toArray());
        $this->assertEquals([2, 3], _::create([1, 2, 3, 4])->without([1, 4])->toArray());
    }

    public function testPop()
    {
        $_ = _::create([1, 2, 3, 4]);

        $this->assertEquals(4, $_->pop());
        $this->assertEquals([1, 2, 3], $_->toArray());
    }

    public function testPush()
    {
        $this->assertEquals([1, 2, 3], _::create()->push(1)->push(2)->push(3)->toArray());
    }

    public function testShift()
    {
        $_ = _::create([1, 2, 3]);

        $this->assertEquals(1, $_->shift());
        $this->assertEquals([2, 3], $_->toArray());
    }

    public function testUnshift()
    {
        $this->assertEquals([1, 2, 3], _::create()->unshift(3)->unshift(2)->unshift(1)->toArray());
    }
}
