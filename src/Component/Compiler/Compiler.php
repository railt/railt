<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Compiler;

use Railt\Component\Io\Readable;
use Railt\Component\Parser\Driver\Proxy;
use Railt\Component\Parser\ParserInterface;
use Railt\Component\Compiler\Grammar\Reader;
use Zend\Code\Generator\ValueGenerator as Value;
use Zend\Code\Exception\InvalidArgumentException;
use Zend\Code\Generator\Exception\RuntimeException;
use Railt\Component\Io\Exception\NotReadableException;
use Railt\Component\Exception\ExternalException;

/**
 * Class Compiler
 */
class Compiler extends Proxy
{
    /**
     * @var string|null
     */
    private $namespace;

    /**
     * @var string
     */
    private $class = 'Parser';

    /**
     * @param Readable $grammar
     * @return Compiler
     * @throws ExternalException
     * @throws NotReadableException
     */
    public static function load(Readable $grammar): self
    {
        $reader = new Reader($grammar);

        return new static($reader->getParser());
    }

    /**
     * @param ParserInterface $parser
     * @return Compiler
     */
    public static function fromParser(ParserInterface $parser): self
    {
        return new static($parser);
    }

    /**
     * @param string $namespace
     * @return Compiler
     */
    public function setNamespace(string $namespace): self
    {
        $this->namespace = $namespace;

        return $this;
    }

    /**
     * @param string $name
     * @return Compiler
     */
    public function setClassName(string $name): self
    {
        $this->class = $name;

        return $this;
    }

    /**
     * @param string $path
     * @throws \Throwable
     */
    public function saveTo(string $path): void
    {
        $pathName = $path . '/' . $this->class . '.php';

        if (\is_file($pathName)) {
            \unlink($pathName);
        }

        \file_put_contents($pathName, $this->build());
    }

    /**
     * @return string
     * @throws \Throwable
     */
    public function build(): string
    {
        \ob_start();

        try {
            require __DIR__ . '/Resources/templates/parser.tpl.php';

            return \ob_get_contents();
        } catch (\Throwable $e) {
            throw $e;
        } finally {
            \ob_end_clean();
        }
    }

    /**
     * @param mixed $value
     * @return string
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    protected function render($value): string
    {
        $generator = new Value($value, Value::TYPE_AUTO, Value::OUTPUT_SINGLE_LINE);

        return $generator->generate();
    }
}
