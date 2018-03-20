<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Generator;

/**
 * Interface CodeGenerator
 */
interface CodeGenerator
{
    /**
     * @param string $name
     * @return CodeGenerator
     */
    public function namespace(string $name): self;

    /**
     * @param string $name
     * @return CodeGenerator
     */
    public function class(string $name): self;

    /**
     * @param bool $enabled
     * @return CodeGenerator
     */
    public function strict(bool $enabled): self;

    /**
     * @param string[] ...$lines
     * @return CodeGenerator
     */
    public function header(string ...$lines): self;

    /**
     * @param string $template
     * @return CodeGenerator
     */
    public function using(string $template): self;

    /**
     * @return GeneratedResult
     */
    public function build(): GeneratedResult;
}
