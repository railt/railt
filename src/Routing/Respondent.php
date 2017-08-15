<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railgun\Routing;

use Railgun\Http\RequestInterface;

/**
 * Class Respondent
 * @package Railgun\Routing
 */
class Respondent
{
    /**
     * @var \Closure
     */
    private $invocation;

    /**
     * @param string $definition
     * @return static|Respondent
     */
    public static function fromStringDefinition(string $definition): Respondent
    {
        return new static(function (Schema $schema, RequestInterface $request) {
            return 23;
        });
    }

    /**
     * @param callable $callable
     * @return static|Respondent
     */
    public static function fromCallable(callable $callable): Respondent
    {
        if ($callable instanceof \Closure) {
            return new static($callable);
        }

        return new static(\Closure::fromCallable($callable));
    }

    /**
     * @param string|callable $relation
     * @return Respondent
     * @throws \InvalidArgumentException
     */
    public static function new($relation): Respondent
    {
        switch (true) {
            case is_string($relation):
                return static::fromStringDefinition($relation);
            case is_callable($relation):
                return static::fromCallable($relation);
        }

        throw new \InvalidArgumentException('Invalid respondend argument definition');
    }

    /**
     * Respondent constructor.
     * @param \Closure $invocation
     */
    public function __construct(\Closure $invocation)
    {
        $this->invocation = $invocation;
    }

    /**
     * @param Schema $schema
     * @param RequestInterface $request
     * @return mixed
     */
    public function __invoke(Schema $schema, RequestInterface $request)
    {
        return ($this->invocation)($schema, $request);
    }
}
