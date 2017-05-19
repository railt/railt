<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Requests\Support;

/**
 * Interface ConfigurableRequestInterface
 * @package Serafim\Railgun\Requests\Support
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
    public function setQueryArgument(string $name): ConfigurableRequestInterface;

    /**
     * @return string
     */
    public function getVariablesArgument(): string;

    /**
     * @param string $name
     * @return ConfigurableRequestInterface
     */
    public function setVariablesArgument(string $name): ConfigurableRequestInterface;

    /**
     * @return string
     */
    public function getOperationArgument(): string;

    /**
     * @param string $name
     * @return ConfigurableRequestInterface
     */
    public function setOperationArgument(string $name): ConfigurableRequestInterface;
}
