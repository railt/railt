<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\SymbolTable\Extractors;

/**
 * Class InterfaceExtractor
 */
class InterfaceExtractor extends TypeDefinitionExtractor
{
    /**
     * @return int
     */
    protected function getPrefixLength(): int
    {
        return 9;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return 'Interface';
    }
}
