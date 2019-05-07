<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http;

use Railt\Http\Request\ProvideOperation;
use Railt\Http\Request\ProvideQueryType;
use Railt\Http\Request\ProvideVariables;

/**
 * Interface RequestInterface
 */
interface RequestInterface extends ProvideQueryType, ProvideOperation, ProvideVariables, Identifiable
{
    /**
     * @return string
     */
    public function getQuery(): string;

    /**
     * @param string $query
     * @return RequestInterface|$this
     */
    public function withQuery(string $query): self;
}
