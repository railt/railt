<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection;

use Railt\Compiler\Reflection\Contracts\Definitions\Definition;
use Railt\Compiler\Reflection\Contracts\Document;

/**
 * Interface Dictionary
 * @package Railt\Compiler\Compiler
 */
interface Dictionary
{
    /**
     * @param Definition $type
     * @param bool $force
     * @return Dictionary
     */
    public function register(Definition $type, bool $force = false): Dictionary;

    /**
     * @param string $name
     * @param Document|null $document
     * @return Definition
     */
    public function get(string $name, Document $document = null): Definition;

    /**
     * @param Document|null $document
     * @return Definition[]
     */
    public function all(Document $document = null): array;

    /**
     * @param string $name
     * @param Document|null $document
     * @return bool
     */
    public function has(string $name, Document $document = null): bool;
}
