<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Support;

/**
 * Interface ConfigurableRequestInterface
 */
interface ConfigurableRequestInterface
{
    /**
     * @return string
     */
    public function getQueryArgument(): string;

    /**
     * @param string $name
     * @return ConfigurableRequestInterface
     */
    public function setQueryArgument(string $name): self;

    /**
     * @return string
     */
    public function getVariablesArgument(): string;

    /**
     * @param string $name
     * @return ConfigurableRequestInterface
     */
    public function setVariablesArgument(string $name): self;

    /**
     * @return string
     */
    public function getOperationArgument(): string;

    /**
     * @param string $name
     * @return ConfigurableRequestInterface
     */
    public function setOperationArgument(string $name): self;
}
