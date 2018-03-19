<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Generator\Renderer;

/**
 * Interface Renderer
 */
interface Renderer
{
    public const BASE_DIRECTORY = __DIR__ . '/../../resources/templates';

    /**
     * @param string $directory
     * @return Renderer
     */
    public function in(string $directory): self;

    /**
     * @param string $template
     * @param array $params
     * @return string
     */
    public function render(string $template, array $params): string;
}
