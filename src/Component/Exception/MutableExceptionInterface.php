<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Exception;

/**
 * Interface MutableExceptionInterface
 */
interface MutableExceptionInterface
{
    /**
     * @param string $name
     * @return ExternalExceptionInterface|$this
     */
    public function withFile(string $name): self;

    /**
     * @param int $line
     * @return ExternalExceptionInterface|$this
     */
    public function withLine(int $line): self;

    /**
     * @param int $column
     * @return ExternalExceptionInterface|$this
     */
    public function withColumn(int $column): self;

    /**
     * @param int $code
     * @return ExternalExceptionInterface|$this
     */
    public function withCode(int $code = 0): self;

    /**
     * @param string $message
     * @param array $args
     * @return ExternalExceptionInterface|$this
     */
    public function withMessage(string $message, ...$args): self;
}
