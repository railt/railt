<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler;

use Zend\Code\Generator\ValueGenerator;

/**
 * Class Generator
 */
class Generator
{
    /**
     * Base parser class
     */
    private const BASE_PARSER_CLASS_NAME = Parser::class;

    /**
     * Code indention
     */
    private const INDENTION = "\n        ";

    /**
     * @var Parser
     */
    private $parser;

    /**
     * @var string
     */
    private $namespace;

    /**
     * Generator constructor.
     * @param Parser $parser
     */
    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }

    /**
     * @param string $namespace
     * @return Generator
     */
    public function setNamespace(string $namespace): self
    {
        $this->namespace = $namespace;

        return $this;
    }

    /**
     * @param string $className
     * @param string $filePath
     * @return void
     */
    public function saveTo(string $className, string $filePath): void
    {
        $sources = $this->generate($className);

        \file_put_contents($this->fileName($filePath, $className), $sources);
    }

    /**
     * Save in-memory parser to PHP code.
     * The generated PHP code will load the same in-memory parser. The state
     * will be reset. The parser will be saved as a class, named after
     * `$className`. To retrieve the parser, one must instanciate this class.
     *
     * @param string $className Parser classname.
     * @return  string
     */
    public function generate(string $className): string
    {
        $rules = '';
        $extra = '';

        foreach ($this->getArguments() as $rule => $arguments) {
            $rules .= $this->buildRule($rule, $arguments);
            $extra .= $this->buildExtra($rule, $arguments);
        }

        return $this->read(__DIR__ . '/resources/compiled.tpl.php', [
            'namespace' => $this->namespace,
            'class'     => $className,
            'base'      => '\\' . self::BASE_PARSER_CLASS_NAME,
            'tokens'    => $this->getTokens(),
            'rules'     => $rules,
            'pragmas'   => $this->getPragmas(),
            'extra'     => $extra,
        ]);
    }

    /**
     * @return \Traversable
     */
    private function getArguments(): \Traversable
    {
        foreach ($this->parser->getRules() as $rule) {
            $arguments = [];

            // Name.
            $arguments['name'] = $this->value($rule->getName());

            if ($rule instanceof Rule\Token) {
                // Token name.
                $arguments['tokenName'] = $this->value($rule->getTokenName());
            } else {
                if ($rule instanceof Rule\Repetition) {
                    $arguments['min'] = $rule->getMin();
                    $arguments['max'] = $rule->getMax();
                }

                $arguments['children'] = $this->value($rule->getChildren(), true);
            }

            // Node ID.
            $arguments['nodeId'] = $this->value($rule->getNodeId());

            if ($rule instanceof Rule\Token) {
                $arguments['unification'] = $rule->getUnificationIndex();
                $arguments['kept']        = $rule->isKept() ? 'true' : 'false';
            }

            yield $rule => $arguments;
        }
    }

    /**
     * @param $rule
     * @param array $arguments
     * @return string
     */
    private function buildRule($rule, array $arguments): string
    {
        $sub = \str_repeat(' ', 8);

        return self::INDENTION . $sub . $arguments['name'] . ' => new \\' . \get_class($rule) . '(' .
            \implode(', ', $arguments) .
        '),';
    }

    /**
     * @param $rule
     * @param array $arguments
     * @return string
     */
    private function buildExtra($rule, array $arguments): string
    {
        $result = '';

        // Resolve default node ID.
        if (($defaultNodeId = $rule->getDefaultId()) !== null) {
            if ($rule->getDefaultOptions()) {
                $defaultNodeId .= ':' . \implode('', $rule->getDefaultOptions());
            }

            $result .= self::INDENTION . '$this->getRule(' . $arguments['name'] . ')' .
                '->setDefaultId(' . $this->value($defaultNodeId, true) . ');';
        }

        // PP representation.
        if (($ppRepresentation = $rule->getPPRepresentation()) !== null) {
            $result .= self::INDENTION . '$this->getRule(' . $arguments['name'] . ')' .
                '->setPPRepresentation(' . $this->value($ppRepresentation, true) . ');';
        }

        return $result;
    }

    /**
     * @return array
     */
    private function getTokens(): array
    {
        return $this->parser->getTokens();
    }

    /**
     * @return array
     */
    private function getPragmas(): array
    {
        return $this->parser->getPragmas();
    }

    /**
     * @param mixed $value
     * @param bool $inline
     * @param string|null $type
     * @return string
     */
    private function value($value, bool $inline = false, string $type = null): string
    {
        $generator = new ValueGenerator($value, $type ?? ValueGenerator::TYPE_AUTO);

        if ($inline) {
            $generator->setIndentation('');

            return \str_replace(["\r", "\n"], '', $generator->generate());
        }

        return $generator->generate();
    }

    /**
     * @param string $file
     * @param array $params
     * @return string
     */
    private function read(string $file, array $params = []): string
    {
        \ob_start();
        \extract($params, \EXTR_OVERWRITE);
        require $file;
        $content = \ob_get_contents();
        \ob_end_clean();

        return $content;
    }

    /**
     * @param string $filePath
     * @param string $class
     * @return string
     */
    private function fileName(string $filePath, string $class): string
    {
        return $filePath . '/' . $class . '.php';
    }
}
