# Belt.`Underscore`

[![Latest Version](http://img.shields.io/packagist/v/belt/underscore.svg?style=flat-square)](https://github.com/beltphp/underscore/releases)
[![Software License](http://img.shields.io/packagist/l/belt/underscore.svg?style=flat-square)](LICENSE)
[![Build Status](https://img.shields.io/travis/beltphp/underscore/master.svg?style=flat-square)](https://travis-ci.org/beltphp/releases)
[![Coverage Status](http://img.shields.io/scrutinizer/coverage/g/beltphp/underscore.svg?style=flat-square)](https://scrutinizer-ci.com/g/beltphp/underscore/code-structure)
[![Quality Score](http://img.shields.io/scrutinizer/g/beltphp/underscore.svg?style=flat-square)](https://scrutinizer-ci.com/g/beltphp/underscore/)

> Pushing round things down square holes.

Belt`.Underscore` is an utility library that makes working with arrays in PHP
a little bit more pleasant.

## Installation

Via Composer

```shell
$ composer require belt/underscore
```

## Usage

The following examples assume that you have included the Underscore utility:

```php
use Belt\_;
```

Some of the examples might seem a bit contrived, but they're actually really
handy. For example, let's say that we have a fictional social network and (for
some reason) we want to get the names of all the authenticated user's 2nd
degree friends (friends-of-friends) that are over the age of 18. Suddenly, that
becomes real easy!

```php
_::create($user->getFriends())->map(function ($f) {
    return $f->getFriends();
})->select(function ($f) {
    return $f->getAge() > 18;
})->pluck('username');
```

And now (for some even stranger reason) we want to know the total number of
_third_ degree friends (friends-of-friends-of-friends) of the 2nd degree friends
that are over the age of 18.

```php
_::create($user->getFriends())->map(function ($f) {
    return $f->getFriends();
})->select(function ($f) {
    return $f->getAge() > 18;
})->reduce(function ($s, $f) {
    return $s + count($f->getFriends());
});
```

That's it!

> __Note__: When, in the examples, the return value comment indicates an array
> the actual return value is a new `_` instance! You can get the actual
> PHP array value by calling the `toArray` method.
> 

The `_` class implements `ArrayAccess` too, so you can access it like an usual array:

```php
$groups = _::create($user->getFriends())->groupBy(function ($friend) {
    $name = $friend->getName();

    return $name[0];
});

$groups['A'] = ...; // All friends with the letter 'A' as the first letter in their name
```

Additionally, you can traverse the container in a `foreach` loop as well:

```php
$users = _::create(['alice', 1337, 'bob', 42])->chunk(2);

foreach ($users as $name => $karma) {
    // ...
}
```

#### `all`

Call the given `callback` for each element in the container. Should the callback
return `false`, the method immediately returns `false` and ceases enumeration.
If all invocations of the callback return `true`, `all` returns `true`.

```php
_::create([1, 2, 3])->all(function ($n) {
    return $n > 0;
}); // true
```

#### `any`

Call the given `callback` for each element in the container. Should the callback
return `true`, the method immediately returns `true` and enumeration is ceased.
If all invocations of the callback return `false`, `any` returns `false`.

```php
_::create([1, 2, 3])->any(function ($n) {
    return $n > 2;
}); // true
```

#### `chunk`

Chunks the container into a new array of `n`-sized chunks.

```php
_::create([1, 2, 3, 4])->chunk(2); // [[1, 2], [3, 4]]
```

#### `combine`

Combine the container with another array into key/value pairs.

```php
_::create([1, 2, 3])->combine(['foo', 'bar', 'baz']); // [1 => 'foo', 2 => 'bar', 3 => 'baz']
```

#### `concat`

Returns a new array that is the container with the given `array` concatenated
to the end.

```php
_::create([1, 2])->concat([3, 4]); // [1, 2, 3, 4]
```

#### `dict`

Convert an array of key/value pairs into the logical dictionary.

```php
_::create([[1, 2], [3, 4]])->dict(); // [1 => 2, 3 => 4]
```

If you have a flat array you can call `chunk(2)` before `dict`.

```php
_::create([1, 2, 3, 4])->chunk(2)->dict(); // [1 => 2, 3 => 4]
```

#### `each`

Calls the given callback once for each element in the container, passing that
element as the argument.

```php
_::create([1, 2, 3, 4])->each(function ($n) {
    printf("%d\n", $n);
}); // outputs: 1\n2\n3\n4\n
```

`each` also supports two and three parameter versions:

```php
_::create([1, 2, 3, 4])->each(function ($n, $i) {
    printf("%d: %d\n", $i, $n);
}); //outputs: 0: 1\n1: 2\n2: 3\n3: 4\n

_::create([1, 2, 3, 4]->each(function ($n, $i, $array) {
    // ...
}));
```

#### `find`

Passes each entry in the container to the given callback, returning the first
element for which callback is not `false`. If no entry matches, returns `null`.

```php
_::create([1, 2, 3, 4])->find(function ($n) {
    return $n > 2;
}); // 3
```

#### `first`

Returns the first `n` elements in the container.

```php
_::create([1, 2, 3, 4])->first(2); // [1, 2]
```

#### `flatten`

Returns a new, one-dimensional array that is a recursive flattening of the
container.

```php
_::create([1, [2], [3, [4]]])->flatten(); [1, 2, 3, 4]
```

> __Tip:__ If you only want to flatten one level of an array, `flatMap` might
> be useful for you!

#### `flatMap`

Returns a new array with the concatenated results of invoking the callback
once for every element in the container.

```php
_::create([1, 2, 3, 4])->flatMap(function ($n) {
    return [$n, $n];
}); // [1, 1, 2, 2, 3, 3, 4, 4]

_::create([1, 2, 3, 4])->flatMap(function ($n) {
    return [$n, [$n]];
}); // [1, [1], 2, [2], 3, [3], 4, [4]]
```

It might look a bit silly, but this is actually a really useful function when
you combine it with other functions! For example, you can create a dictionary
for (fictional) users.

```php
_::create([new User('bob', 32), new User('alice', 35)])->flatMap(function ($u) {
    return [$n->getName(), $n->getAge()];
})->chunk(2)->dict(); // ['bob' => 32, 'alice' => 35]
```

Which finally allows us, as developers, to create key/value pairs when mapping
arrays! Hoorah!

#### `groupBy`

Groups the container by result of the given callback.

```php
_::create([1, 2, 3, 4])->groupBy(function ($n) {
    return $n % 2;
}); // [0 => [2, 4], 1 => [1, 3]]

_::create(['foo', 'bar', 'baz'])->groupBy(function ($s) {
    return $s[0];
}); // ['f' => ['foo'], 'b' => ['bar', 'baz']]
```

#### `has`

```php
_::create([1, 2, 3, 4])->has(2); // true
_::create([1, 2, 3, 4])->has(0); // false
```

#### `indexOf`

Returns the index of the given object in the container or `null` if the element
was not found.

```php
_::create([1, 2, 3, 4])->indexOf(2); // 1
_::create([1, 2, 3, 4])->indexOf(0); // null
```

#### `inject`

Combines all elements of the container by applying a binary operation.

```php
_::create([1, 2, 3])->inject([], function ($m, $n) {
    $m[$n] = $n * $n;

    return $m;
}); // [1 => 1, 2 => 4, 3 => 9]

_::create(['foo', 'bar', 'baz'])->inject('', function ($m, $s) {
    $m .= $s;
}); // foobarbaz
```

> __Note__: This is the only exception to the note earlier. The return value
> here is the return value of the _last_ itertation.

#### `join`

Returns a string of all the container's elements joined with the provided
separator string.

```php
_::create([1, 2, 3, 4])->join('');  // 1234
_::create([1, 2, 3, 4])->join(','); // 1,2,3,4
```

#### `last`

Returns the last `n` elements from the container.

```php
_::create([1, 2, 3, 4, 5, 6])->last(2); // [5, 6]
```

#### `map`

Invokes the given callback for each element in the container. Creates a new
array containing the values returned by the block.

If the given callback returns `null`, that element is skipped in the returned
array.

```php
_::create([1, 2, 3, 4])->map(function ($n) {
    return $n * $n;
}); // [1, 4, 9, 16]

_::create([1, 2, 3, 4])->map(function ($n) {
    return $n % 2 ? $n * $n : null;
}); // [1, 9]
```

#### `max`

Returns the element for which the given callback returns the largest integer.

```php
_::create('1', 'two', 'three')->max(function ($s) {
    return strlen($s);
}); // 'three'
```

#### `min`

Returns the element for which the given callback returns the smallest integer.

```php
_::create('1', 'two', 'three')->min(function ($s) {
    return strlen($s);
}); // '1'
```

#### `none`

Test if the given callback returns `false` for each element in the container.

```php
_::create([1, 2, 3, 4])->none(function ($n) {
    return $n < 0;
}); // true

_::create([1, 2, 3, 4])->none(function ($n) {
    return $n > 0;
}); // false
```

#### `partition`

Partitions the container into two arrays based on the boolean return value of
the given block.

```php
_::create(['A', 'B', 'C', 'AA'])->partition(function ($s) {
    return $s[0] == 'A';
}); // [['A', 'AA'], ['B', 'C']]
```

#### `pluck`

Returns a new array that is the result of retrieving the given property path on
each element in the container.

```php
_::create([new User('bob'), new User('alice')])->pluck('username'); // ['bob', 'alice']
```

#### `product`

Calculate the product of the container by assuming that all values can be casted to a double value.

```php
_::create([1, 2, 3])->product(); // 6
```

#### `reduce`

Reduces the container to a single value.

The usual example of reduce is to sum all values in an array.

```php
_::create([1, 2, 3, 4])->reduce(function ($memo, $n) {
    return $memo + $n;
}); // 10
```

Reduce also allows you to set an initial value before reducing the array.

```php
_::create([1, 2, 3, 4])->reduce(function ($s, $n) {
    return $s + $n;
}, 10); // 20
```

#### `reject`

Returns a new array containing all elements for which the given callback
returns `false`.

```php
_::create([1, 2, 3, 4])->reject(function ($n) {
    return ($n % 2) == 0;
}); // [1, 3]
```

#### `reverse`

Returns a new array that is the container, reversed.

```php
_::create([1, 2, 3, 4])->reverse(); // [4, 3, 2, 1]
```

#### `rotate`

Returns a new array rotated about the provided index.

```php
_::create([1, 2, 3, 4, 5, 6])->rotate(2); // [3, 4, 5, 6, 1, 2]
_::create([1, 2, 3, 4, 5, 6])->rotate(-2); // [5, 6, 1, 2, 3, 4]
```

#### `sample`

Returns a random element from the container.

```php
_::create([1, 2, 3, 4, 5, 6])->sample(); // Basically a dice roll...
```

#### `select`

Returns a new array containing all elements for which the given block returns
`true`.

```php
_::create([1, 2, 3, 4])->select(function ($n) {
    return ($n % 2) == 0;
}); // [2, 4]
```

#### `shuffle`

Returns a new array that is shuffled.

```php
_::create([1, 2])->shuffle(); // Either [1, 2] or [2, 1]
```

#### `skip`

Skips the first `n` elements and returns the rest of the array.

```php
_::create([1, 2, 3, 4, 5, 6])->skip(2); // [3, 4, 5, 6]
```

#### `slice`

Returns a subarray consisting of the given number of elements from the given
starting index.

```php
_::create([1, 2, 3, 4])->slice(1, 2); // [2, 3]
```

#### `snip`

Snips the end off the array. Returns the container _without_ the last `n`
elements.

```php
_::create([1, 2, 3, 4, 5, 6])->snip(2); // [1, 2, 3, 4]
```

#### `sort`

Returns the container, sorted.

```php
_::create([1, 4, 2, 3])->sort(); // [1, 2, 3, 4]
```

#### `sortBy`

Sorts all objects using the return value of the given callback as the sorting
criteria.

```php
$rhombas = new Shape('rhombas');
$ellipse = new Shape('ellipse');
$hexagon = new Shape('hexagon');

_::create([ $rhombas, $ellipse, $hexagon ])->sortBy(function ($s) {
    return $s->getName();
}); // [ $ellipse, $hexagon, $rhombas ]
```

#### `sum`

Sum all objects by casting the values to a double.

```php
_::create([1, 2, 3, 4])->sum(); // 10
```

#### `transpose`

Assumes that the container is an array of arrays and transposes the rows and
columns.

```php
_::create([[1, 2, 3], [4, 5, 6]])->transpose(); // [[1, 4], [2, 5], [3, 6]]
```

#### `uniq`

Returns a new array by removing duplicate values in the container.

```php
_::create([1, 2, 3, 1, 2, 4, 1, 2, 5])->uniq(); // [3, 4, 5]
```

#### `without`

Returns a new array where objects in the given array are removed from the
container.

```php
_::create([1, 2, 4, 3])->without([4]); // [1, 2, 3]
_::create([1, 2, 3, 4, 5])->without([4, 5]); // [1, 2, 3]
```

#### `pop`

Treats container like a stack and removes the last object, returning it.

```php
_::create()->push(1)->push(2)->push(3)->pop(); // 3
```

#### `push`

Treats container like a stack and adds the given object to the end of the
container.

```php
_::create()->push(1)->push(2)->push(3); // [1, 2, 3]
```

#### `shift`

Removes the container's first object and returns it.

```php
_::create([1, 2, 3])->shift(); // 1
```

#### `unshift`

Inserts the given object at the front of container, moving all other objects in
the container up one index.

```php
_::create([2, 3])->unshift(1); // [1, 2, 3]
```

#### `split`

Returns a new array of the strings in the given string that are separated by the
given separator.

```php
_::split('foo bar baz', ' '); // ['foo', 'bar', 'baz']
```

The second parameter is optional and `null` by default, if you pass `null` or
an empty string as seperator, you will get an array of the individual characters
in the given string.

```php
_::split('1234'); // ['1', '2', '3', '4']
```

We can do some pretty neat stuff with this!

```php
_::split('1234')->sum(); // 10
```

#### `first`, `last`, `skip`, `snip` & `slice`

These functions are strongly related and useful to remember.

```php
_::create([1, 2, 3, 4, 5])->first(2);       // [1, 2]
_::create([1, 2, 3, 4, 5])->last(2);        // [4, 5]
_::create([1, 2, 3, 4, 5])->skip(2);        // [3, 4, 5]
_::create([1, 2, 3, 4, 5])->snip(2);        // [1, 2, 3]
_::create([1, 2, 3, 4, 5])->slice(2, 2);    // [3, 4]
```

## Contributing

Please see [CONTRIBUTING](https://github.com/beltphp/underscore/blob/master/CONTRIBUTING.md).

## Credits

This project is heavily inspired by [YOLOKit](http://mxcl.github.io/YOLOKit/).
For all of you who develop in Objective-C, I highly recommend you check it out!

## License

Please see [LICENSE](https://github.com/beltphp/underscore/blob/master/LICENSE).
