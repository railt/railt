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
     * @var RuleInterface
     */
    private $ast;

    /**
     * TypeRecord constructor.
     * @param string $name
     * @param string $type
     * @param int $offset
     * @param RuleInterface $ast
     */
    public function __construct(string $name, string $type, int $offset, RuleInterface $ast = null)
    {
        $this->name   = $name;
        $this->type   = $type;
        $this->offset = $offset;
        $this->ast    = $ast;
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
     * @return RuleInterface
     */
    public function getAst(): RuleInterface
    {
        return $this->ast;
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
        ];
    }
}
