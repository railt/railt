<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Response;

use Railt\Dumper\TypeDumper;
use Railt\Http\Exception\Factory;

/**
 * Trait MutableExceptionsProviderTrait
 */
trait MutableExceptionsProviderTrait
{
    use ExceptionsProviderTrait;

    /**
     * @param \Throwable ...$exceptions
     * @return MutableExceptionsProviderInterface|$this
     */
    public function withException(\Throwable ...$exceptions): MutableExceptionsProviderInterface
    {
        foreach ($exceptions as $exception) {
            $this->exceptions[] = Factory::wrap($exception);
        }

        return $this;
    }

    /**
     * @param \Closure $filter
     * @return MutableExceptionsProviderInterface|$this
     */
    public function withoutException(\Closure $filter): MutableExceptionsProviderInterface
    {
        $callback = static function (\Throwable $e) use ($filter): bool {
            return ! $filter($e->getPrevious());
        };

        $this->exceptions = \array_filter($this->exceptions, $callback);

        return $this;
    }

    /**
     * @param array|\Throwable[] $exceptions
     * @return MutableExceptionsProviderInterface|$this
     */
    public function setExceptions(array $exceptions): MutableExceptionsProviderInterface
    {
        $this->exceptions = \array_map(static function ($e) {
            \assert($e instanceof \Throwable, TypeDumper::render($e) . ' not a \Throwable');

            return Factory::wrap($e);
        }, $exceptions);

        return $this;
    }
}
