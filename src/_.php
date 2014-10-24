<?php
namespace Belt;

use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * @author Ramon Kleiss <ramonkleiss@gmail.com>
 */
class _
{
    /** @var array */
    private $container;

    /**
     * @param array
     */
    public function __construct(array $container = array())
    {
        $this->container = $container;
    }

    /**
     * @param array
     *
     * @return _
     */
    public static function create(array $container = array())
    {
        return new self($container);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->container;
    }

    /**
     * Call the given `callback` for each element in the container. Should the
     * callback return `false`, the method immediately returns `false` and
     * ceases enumeration. If all invocations of the callback return `true`,
     * `all` returns `true`.
     *
     * @param Callable
     *
     * @return Boolean
     */
    public function all(Callable $callback)
    {
        foreach ($this->container as $element) {
            if ($callback($element) === false) {
                return false;
            }
        }

        return true;
    }

    /**
     * Call the given `callback` for each element in the container. Should the
     * callback return `true`, the method immediately returns `true` and
     * enumeration is ceased. If all invocations of the callback return `false`,
     * `any` returns `false`.
     *
     * @param Callable
     *
     * @return Boolean
     */
    public function any(Callable $callback)
    {
        foreach ($this->container as $element) {
            if ($callback($element) === true) {
                return true;
            }
        }

        return false;
    }

    /**
     * Chunks the container into a new array of `n`-sized chunks.
     *
     * @param integer
     *
     * @return _
     */
    public function chunk($n)
    {
        return static::create(array_chunk($this->container, $n));
    }

    /**
     * Returns a new array that is the container with the given `array`
     * concatenated to the end.
     *
     * @param array
     *
     * @return _
     */
    public function concat(array $array)
    {
        return static::create(array_merge($this->container, $array));
    }

    /**
     * Convert an array of key/value pairs into the logical dictionary.
     *
     * @return _
     */
    public function dict()
    {
        $result = [];

        foreach ($this->container as $kv) {
            $result[$kv[0]] = $kv[1];
        }

        return _::create($result);
    }

    /**
     * Calls the given callback once for each element in the container, passing
     * that element as the argument.
     *
     * @param Callback
     */
    public function each(Callable $callback)
    {
        for ($i = 0; $i < count($this->container); $i++) {
            $callback($this->container[$i], $i, $this->container);
        }
    }

    /**
     * Passes each entry in the container to the given callback, returning the
     * first element for which callback is not `false`. If no entry matches,
     * returns `null`.
     *
     * @param Callable
     *
     * @return mixed
     */
    public function find(Callable $callback)
    {
        foreach ($this->container as $element) {
            if ($callback($element) !== false) {
                return $element;
            }
        }

        return null;
    }

    /**
     * Returns the first `n` elements in the container.
     *
     * @param integer
     *
     * @return _
     */
    public function first($n)
    {
        return static::create(array_slice($this->container, 0, $n));
    }

    /**
     * Returns a new, one-dimensional array that is a recursive flattening of
     * the container.
     *
     * @return _
     */
    public function flatten()
    {
        $r = array();

        array_walk_recursive($this->container, function($a) use (&$r) { $r[] = $a; });

        return static::create($r);
    }

    /**
     * Returns a new array with the concatenated results of invoking the
     * callback once for every element in the container.
     *
     * @param Callable
     *
     * @return _
     */
    public function flatMap(Callable $callback)
    {
        return array_reduce($this->container, function ($r, $n) use ($callback) {
            return $r->concat($callback($n));
        }, static::create());
    }

    /**
     * Groups the container by result of the given callback.
     *
     * @param Callable
     *
     * @return _
     */
    public function groupBy(Callable $callback)
    {
        $result = [];

        foreach ($this->container as $element) {
            $result[$callback($element)][] = $element;
        }

        return static::create($result);
    }

    /**
     * @param mixed
     *
     * @return Boolean
     */
    public function has($element)
    {
        return in_array($element, $this->container);
    }

    /**
     * Returns the index of the given object in the container or `null` if the
     * element was not found.
     *
     * @param mixed
     *
     * @return integer|null
     */
    public function indexOf($element)
    {
        return array_search($element, $this->container) ?: null;
    }

    /**
     * Combines all elements of the container by applying a binary operation.
     *
     * @param mixed
     * @param Callable
     *
     * @return mixed
     */
    public function inject($memo, Callable $callback)
    {
        foreach ($this->container as $element) {
            $memo = $callback($memo, $element);
        }

        return $memo;
    }

    /**
     * Returns a string of all the container's elements joined with the provided
     * separator string.
     *
     * @param string
     *
     * @return string
     */
    public function join($seperator)
    {
        return implode($seperator, $this->container);
    }

    /**
     * Returns the last `n` elements from the container.
     *
     * @param integer
     *
     * @return _
     */
    public function last($n)
    {
        return static::create(array_values(array_slice($this->container, count($this->container) - $n)));
    }

    /**
     * Invokes the given callback for each element in the container. Creates a
     * new array containing the values returned by the block.
     *
     * If the given callback returns `null`, that element is skipped in the
     * returned array.
     *
     * @param Callable
     *
     * @return _
     */
    public function map(Callable $callback)
    {
        return static::create(array_values(array_filter(array_map(function ($e) use ($callback) {
            return $callback($e);
        }, $this->container))));
    }

    /**
     * Returns the element for which the given callback returns the largest
     * integer.
     *
     * @param Callable
     *
     * @return mixed
     */
    public function max(Callable $callback)
    {
        $data = array_values($this->container);
        $max  = [$callback($data[0]), $data[0]];

        for ($i = 1; $i < count($data); $i++) {
            if (($new = $callback($data[$i])) > $max[0]) {
                $max[0] = $new;
                $max[1] = $data[$i];
            }
        }

        return $max[1];
    }

    /**
     * Returns the element for which the given callback returns the smallest
     * integer.
     *
     * @param Callable
     *
     * @return mixed
     */
    public function min(Callable $callback)
    {
        $data = array_values($this->container);
        $min  = [$callback($data[0]), $data[0]];

        for ($i = 1; $i < count($data); $i++) {
            if (($new = $callback($data[$i])) < $min[0]) {
                $min[0] = $new;
                $min[1] = $data[$i];
            }
        }

        return $min[1];
    }

    /**
     * Test if the given callback returns `false` for each element in the
     * container.
     *
     * @param Callable
     *
     * @return Boolean
     */
    public function none(Callable $callback)
    {
        foreach ($this->container as $element) {
            if ($callback($element)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Partitions the container into two arrays based on the boolean return
     * value of the given block.
     *
     * @param Callback
     *
     * @return _
     */
    public function partition(Callable $callback)
    {
        $result = [];

        foreach ($this->container as $element) {
            $result[!((bool) $callback($element))][] = $element;
        }

        return static::create($result);
    }

    /**
     * Returns a new array that is the result of retrieving the given property
     * path on each element in the receiver.
     *
     * @param string
     *
     * @return _
     */
    public function pluck($path)
    {
        $accessor = PropertyAccess::createPropertyAccessor();

        return static::create(array_values(array_filter(array_map(function ($e) use ($path, $accessor) {
            try {
                return $accessor->getValue($e, $path);
            } catch (\Exception $e) {
                return null;
            }
        }, $this->container))));
    }

    /**
     * Reduces the container to a single value.
     *
     * @param Callback
     *
     * @return mixed
     */
    public function reduce(Callable $callback)
    {
        return array_reduce($this->container, function ($m, $e) use ($callback) {
            return $callback($m, $e);
        });
    }

    /**
     * Returns a new array containing all elements for which the given callback
     * returns `false`.
     *
     * @param Callable
     *
     * @return _
     */
    public function reject(Callable $callback)
    {
        return static::create(array_values(array_filter($this->container, function ($e) use ($callback) {
            return $callback($e) === false;
        })));
    }

    /**
     * Returns a new array that is the container, reversed.
     *
     * @return _
     */
    public function reverse()
    {
        return static::create(array_reverse($this->container));
    }

    /**
     * Returns a new array containing all elements for which the given block
     * returns `true`.
     *
     * @param Callable
     *
     * @return _
     */
    public function select(Callable $callback)
    {
        return static::create(array_values(array_filter($this->container, function ($e) use ($callback) {
            return $callback($e) === true;
        })));
    }

    /**
     * Skips the first `n` elements and returns the rest of the array.
     *
     * @param integer
     *
     * @return _
     */
    public function skip($n)
    {
        return static::create(array_slice($this->container, $n));
    }

    /**
     * Returns a subarray consisting of the given number of elements from the
     * given starting index.
     *
     * @param integer
     * @param integer
     *
     * @return _
     */
    public function slice($offset, $length)
    {
        return static::create(array_values(array_slice($this->container, $offset, $length)));
    }

    /**
     * Snips the end off the array. Returns the container _without_ the last `n`
     * elements.
     *
     * @param integer
     *
     * @return _
     */
    public function snip($n)
    {
        return static::create(array_slice($this->container, 0, count($this->container) - $n));
    }

    /**
     * Returns the container, sorted.
     *
     * @return _
     */
    public function sort()
    {
        $result = $this->container;
        sort($result);

        return static::create(array_values($result));
    }

    /**
     * Sorts all objects using the return value of the given callback as the
     * sorting criteria.
     *
     * @param Callable
     *
     * @return _
     */
    public function sortBy(Callable $callback)
    {
        $result = [];

        foreach ($this->container as $element) {
            $result[$callback($element)] = $element;
        }

        ksort($result);

        return static::create(array_values($result));
    }

    /**
     * Assumes that the container is an array of arrays and transposes the rows
     * and columns.
     *
     * @return _
     */
    public function transpose()
    {
        $result = $this->container;
        array_unshift($result, null);

        return static::create(call_user_func_array('array_map', $result));
    }

    /**
     * Returns a new array by removing duplicate values in the container.
     *
     * @return _
     */
    public function uniq()
    {
        return static::create(array_unique($this->container, SORT_REGULAR));
    }

    /**
     * Returns a new array where objects in the given array are removed from
     * the receiver.
     *
     * @param array
     *
     * @return _
     */
    public function without(array $filter)
    {
        $result = [];

        foreach ($this->container as $element) {
            if (!in_array($element, $filter)) {
                $result[] = $element;
            }
        }

        return static::create($result);
    }

    /**
     * Treats container like a stack and removes the last object, returning it.
     *
     * @return mixed
     */
    public function pop()
    {
        return array_pop($this->container);
    }

    /**
     * Treats container like a stack and adds the given object to the end of
     * the container.
     *
     * @param mixed
     *
     * @return self
     */
    public function push($element)
    {
        $this->container[] = $element;

        return $this;
    }

    /**
     * Removes the container's first object and returns it.
     *
     * @return mixed
     */
    public function shift()
    {
        return array_shift($this->container);
    }

    /**
     * Inserts the given object at the front of container, moving all other
     * objects in the container up one index.
     *
     * @param mixed
     *
     * @return self
     */
    public function unshift($element)
    {
        array_unshift($this->container, $element);

        return $this;
    }
}
