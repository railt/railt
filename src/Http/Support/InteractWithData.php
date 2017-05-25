<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Http\Support;

use Serafim\Railgun\Http\RequestInterface;

/**
 * Trait InteractWithData
 * @package Serafim\Railgun\Http\Support
 * @mixin RequestInterface
 * @mixin ConfigurableRequestInterface
 */
trait InteractWithData
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * @return string
     */
    public function getQuery(): string
    {
        return $this->data[$this->getQueryArgument()] ?? '{}';
    }

    /**
     * @return null|string
     */
    public function getVariables(): ?string
    {
        return trim($this->data[$this->getVariablesArgument()] ?? '') ?: null;
    }

    /**
     * @return null|string
     */
    public function getOperation(): ?string
    {
        return $this->data[$this->getOperationArgument()] ?? null;
    }
}
