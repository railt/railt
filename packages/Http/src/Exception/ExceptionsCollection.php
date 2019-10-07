<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Exception;

use Ramsey\Collection\AbstractSet;
use Railt\Http\Common\RenderableTrait;
use Railt\Http\Common\RenderableInterface;

/**
 * Class ExceptionsCollection
 */
class ExceptionsCollection extends AbstractSet implements RenderableInterface
{
    use RenderableTrait;

    /**
     * ExceptionsCollection constructor.
     *
     * @param array|\Throwable[] $data
     */
    public function __construct(array $data = [])
    {
        parent::__construct();

        foreach ($data as $index => $value) {
            $this->offsetSet($index, $value);
        }
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return \Throwable::class;
    }

    /**
     * @param \Throwable $exception
     * @return GraphQLExceptionInterface
     */
    private function transform(\Throwable $exception): GraphQLExceptionInterface
    {
        if ($exception instanceof GraphQLExceptionInterface) {
            return $exception;
        }

        return new GraphQLException($exception->getMessage(), $exception->getCode(), $exception);
    }

    /**
     * {@inheritDoc}
     */
    public function add($element): bool
    {
        return parent::add($this->transform($element));
    }

    /**
     * {@inheritDoc}
     */
    public function offsetSet($offset, $value): void
    {
        parent::offsetSet($offset, $this->transform($value));
    }
}
