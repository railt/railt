<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\SymbolTable;

use Railt\Compiler\Ast\RuleInterface;
use Railt\Io\Readable;

/**
 * Class Record
 */
class Record
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $type;

    /**
     * @var int
     */
    private $offset;

    /**
     * @var Readable
     */
    private $input;

    /**
     * @var RuleInterface
     */
    private $ast;

    /**
     * TypeRecord constructor.
     * @param string $name
     * @param string $type
     * @param int $offset
     * @param Readable $input
     */
    public function __construct(string $name, string $type, int $offset, Readable $input)
    {
        $this->name   = $name;
        $this->type   = $type;
        $this->input  = $input;
        $this->offset = $offset;
    }

    /**
     * @return RuleInterface
     */
    public function getAst(): RuleInterface
    {
        return $this->ast;
    }

    /**
     * @param RuleInterface $ast
     * @return Record
     */
    public function setAst(RuleInterface $ast): self
    {
        $this->ast = $ast;

        return $this;
    }

    /**
     * @return Readable
     */
    public function getInput(): Readable
    {
        return $this->input;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
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
            'type'   => $this->type,
            'name'   => $this->name,
            'offset' => $this->offset,
            'file'   => $this->input,
        ];
    }
}
