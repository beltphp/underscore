<?php
namespace Belt;

/**
 * @author Ramon Kleiss <ramonkleiss@gmail.com>
 */
class _
{
    /** @var array|string */
    private $container;

    /**
     * @param array
     */
    public function __construct(array $container)
    {
        $this->container = $container;
    }

    /**
     * @param array
     *
     * @return _
     */
    public static function create(array $container)
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
}
