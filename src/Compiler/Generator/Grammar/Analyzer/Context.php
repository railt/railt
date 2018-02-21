<?php
/**
 * This file is part of Lexer package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Generator\Grammar\Analyzer;

use Hoa\Iterator\Lookahead;
use Railt\Compiler\Generator\Grammar\Analyzer;
use Railt\Compiler\Generator\Grammar\Reader\ParsingState;
use Railt\Compiler\Lexer\Tokens\Output;
use Railt\Io\Readable;

/**
 * Class Context
 */
class Context
{
    /**
     * @var Analyzer
     */
    private $analyzer;

    /**
     * @var string
     */
    private $name;

    /**
     * @var bool
     */
    private $kept;

    /**
     * @var int
     */
    private $offset;

    /**
     * @var Readable
     */
    private $file;

    /**
     * @var Lookahead
     */
    private $tokens;

    /**
     * Context constructor.
     * @param Analyzer $analyzer
     * @param string $name
     * @param array $data
     */
    public function __construct(Analyzer $analyzer, string $name, array $data)
    {
        $this->analyzer = $analyzer;
        $this->bootNameInfo($name);
        $this->bootData($data);
    }

    /**
     * @param string $name
     * @return void
     */
    private function bootNameInfo(string $name): void
    {
        [$this->name, $this->kept] = $name[0] === '#'
            ? [\substr($name, 1), true]
            : [$name, false];
    }

    /**
     * @param array $data
     * @return void
     */
    private function bootData(array $data): void
    {
        $this->offset = $data[ParsingState::I_RULE_OFFSET];
        $this->file   = $data[ParsingState::I_RULE_FILE];
        $this->tokens = new Lookahead($data[ParsingState::I_RULE_BODY]);
    }

    /**
     * @param bool $next
     * @return array
     */
    private function token(bool $next = false): array
    {
        if ($next) {
            return $this->tokens->getNext();
        }

        return $this->tokens->current();
    }

    /**
     * @param bool $next
     * @return mixed
     */
    private function name(bool $next = false)
    {
        return $this->token($next)[Output::I_TOKEN_NAME];
    }

    /**
     * @param bool $next
     * @return string
     */
    private function value(bool $next = false): string
    {
        return $this->token($next)[Output::I_TOKEN_BODY];
    }

    /**
     * @param int $index
     * @param bool $next
     * @return string
     */
    private function context(int $index = 0, bool $next = false): string
    {
        return $this->token($next)[Output::I_TOKEN_CONTEXT][$index];
    }

    public function reduce(): void
    {
    }
}
