<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Linker;

use Phplrt\Source\File;
use Railt\Parser\Ast\Node;
use Railt\Parser\Ast\NameNode;
use Phplrt\Contracts\Source\FileInterface;
use Railt\SDL\Compiler;
use Phplrt\Source\Exception\NotFoundException;
use Phplrt\Source\Exception\NotReadableException;

/**
 * Class FileLinker
 */
class FileLinker implements LinkerInterface
{
    /**
     * @var FileInterface
     */
    private FileInterface $file;

    /**
     * @var array|string[]
     */
    private array $types;

    /**
     * @var bool
     */
    private bool $loaded = false;

    /**
     * FileLinker constructor.
     *
     * @param string $pathname
     * @param array|string[]|null $types
     * @throws NotFoundException
     * @throws NotReadableException
     */
    public function __construct(string $pathname, array $types = null)
    {
        $this->file = File::fromPathname($pathname);
        $this->types = $types ?? $this->resolveTypes($pathname);
    }

    /**
     * @param string $pathname
     * @return array|string[]
     */
    private function resolveTypes(string $pathname): array
    {
        return [\pathinfo($pathname, \PATHINFO_FILENAME)];
    }

    /**
     * @param Compiler $compiler
     * @param NameNode $name
     * @param Node|null $from
     * @return void
     * @throws \Throwable
     */
    public function load(Compiler $compiler, NameNode $name, Node $from = null): void
    {
        if ($this->loaded === false && \in_array($name->value, $this->types, true)) {
            $this->loaded = true;

            $compiler->preload($this->file, [$name->value]);
        }
    }
}
