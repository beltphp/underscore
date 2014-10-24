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
}
