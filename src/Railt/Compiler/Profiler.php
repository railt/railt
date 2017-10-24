<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler;

use Hoa\Compiler\Llk\Lexer;
use Hoa\Compiler\Llk\Rule\Entry;
use Hoa\Compiler\Llk\Rule\Invocation;
use Hoa\Compiler\Llk\Rule\Rule;
use Hoa\Compiler\Llk\Rule\Token;
use Hoa\Compiler\Llk\TreeNode;
use Hoa\Compiler\Visitor\Dump;
use Hoa\Compiler\Llk\Parser as LlkParser;

/**
 * Class Profiler
 */
class Profiler
{
    /**
     * @var LlkParser
     */
    private $parser;

    /**
     * Profiler constructor.
     * @param LlkParser $parser
     */
    public function __construct(LlkParser $parser)
    {
        $this->parser = $parser;
    }

    /**
     * @param TreeNode $ast
     * @return string
     */
    public function dump(TreeNode $ast): string
    {
        $result = (string)(new Dump())->visit($ast);

        $result = str_replace('>  ', '    ', $result);
        $result = preg_replace('/^\s{4}/ium', '', $result);

        $result = preg_replace_callback('/token\((\w+),(.*?)\)/isu', function ($args) {
            return 'token(' . $args[1] . ',' . str_replace(["\n"], ['\n'], $args[2]) . ')';
        }, $result);

        return trim($result);
    }

    /**
     * @return string
     */
    public function trace(): string
    {
        $result = '';

        $i = 0;

        /** @var Invocation $element */
        foreach ($this->parser->getTrace() as $element) {
            if ($element instanceof Entry) {
                $ruleName = $element->getRule();
                /** @var Rule $rule */
                $rule     = $this->parser->getRule($ruleName);

                $result .= str_repeat('>  ', ++$i) . 'enter ' . $ruleName;

                if (null !== $id = $rule->getNodeId()) {
                    $result .= ' (' . $id . ')';
                }

                echo "\n";
            } elseif ($element instanceof Token) {
                $result .= str_repeat('   ', $i + 1) .
                    'token ' . $element->getTokenName() .
                    ', consumed ' . $element->getValue() . "\n";
            } else {
                $result .= str_repeat('<  ', $i--) .
                    'exit ' . $element->getRule() . "\n";
            }
        }

        return $result;
    }

    /**
     * @param string $content
     * @return string
     * @throws \Hoa\Compiler\Exception\UnrecognizedToken
     */
    public function tokens(string $content): string
    {
        $result = '';

        $sequence = (new Lexer())->lexMe($content, $this->parser->getTokens());

        $template   = '| %4s | %-50s | %-50s |' . "\n";
        $header     = sprintf($template, 'ID', 'Token', 'Value');
        $delimiter  = '|' . str_repeat('-', strlen($header) - 3) . "|\n";

        $result  .= $delimiter . $header . $delimiter;

        foreach ($sequence as $data) {
            $value = str_replace("\n", ' ', '(' . $data['length'] . ') ' . $data['value']);
            $value = mb_strlen($value) > 48 ? mb_substr($value, 0, 48) : $value;

            $token = ($data['namespace'] === 'default' ? '' : ' -> ' . $data['namespace'] . ':') . $data['token'];
            $token = mb_strlen($token) > 48 ? mb_substr($token, 0, 48) : $token;

            $result .= sprintf($template, $data['offset'], $token, $value);
        }

        return $result . $delimiter;
    }
}
