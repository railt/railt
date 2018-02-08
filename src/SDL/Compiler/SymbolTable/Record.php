<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\SymbolTable;

use Railt\Compiler\Ast\NodeInterface;
use Railt\Compiler\Ast\RuleInterface;
use Railt\Io\Readable;

/**
 * Class Record
 */
class Record
{
    public const TYPE_AST    = '#Node';
    public const TYPE_SCHEMA = 'Schema';

    /**
     * @var string|null
     */
    private $namespace;

    /**
     * @var string
     */
    private $fqn;

    /**
     * @var string
     */
    private $type;

    /**
     * @var int
     */
    private $offset;

    /**
     * @var RuleInterface
     */
    private $ast;

    /**
     * @var Readable
     */
    private $file;

    /**
     * Record constructor.
     * @param null|string $fqn
     * @param string $type
     * @param int $offset
     * @param NodeInterface $ast
     */
    public function __construct(string $fqn, string $type, int $offset, NodeInterface $ast)
    {
        $this->fqn    = $fqn;
        $this->type   = $type;
        $this->offset = $offset;
        $this->ast    = $ast;
    }

    /**
     * @param Readable $readable
     * @return Record
     */
    public function setFile(Readable $readable): self
    {
        $this->file = $readable;

        return $this;
    }

    /**
     * @return Readable
     */
    public function getFile(): Readable
    {
        \assert($this->file !== null);

        return $this->file;
    }

    /**
     * @return RuleInterface
     */
    public function getAst(): RuleInterface
    {
        return $this->ast;
    }

    /**
     * @param string $namespace
     */
    public function setNamespace(string $namespace): self
    {
        $this->namespace = $namespace ?: null;

        return $this;
    }

    /**
     * @return string
     */
    public function getFullyQualifiedName(): string
    {
        if ($this->namespace) {
            return $this->namespace . '/' . $this->fqn;
        }

        return $this->fqn;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * @return array
     */
    public function __debugInfo(): array
    {
        return [
            'fqn'    => $this->fqn,
            'type'   => $this->type,
            'offset' => $this->offset,
        ];
    }
}
