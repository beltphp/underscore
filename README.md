# Belt.Underscore

## Installation

Via Composer

```shell
$ composer require belt/underscore
```

## Usage

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
each element in the receiver.

```php
_::create([new User('bob'), new User('alice')])->pluck('username'); // ['bob', 'alice']
```

#### `reduce`

Reduces the container to a single value.

The usual example of reduce is to sum all values in an array.

```php
_::create([1, 2, 3, 4])->reduce(function ($memo, $n) {
    return $memo + $n;
}); // 10
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

#### `select`

Returns a new array containing all elements for which the given block returns
`true`.

```php
_::create([1, 2, 3, 4])->select(function ($n) {
    return ($n % 2) == 0;
}); // [2, 4]
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
receiver.

```php
_::create([1, 2, 4, 3])->without([4]); // [1, 2, 3]
_::create([1, 2, 3, 4, 5])->without([4, 5]); // [1, 2, 3]
```

#### `pop`

Treats receiver like a stack and removes the last object, returning it.

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
