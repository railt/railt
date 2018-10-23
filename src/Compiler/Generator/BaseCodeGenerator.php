<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Generator;

use Railt\Compiler\Generator\Renderer\Renderer;
use Railt\Compiler\Generator\Renderer\TwigRenderer;

/**
 * Class BaseCodeGenerator
 */
abstract class BaseCodeGenerator implements CodeGenerator
{
    protected const HEADER_LENGTH      = 80;
    protected const DEFAULT_CLASS_NAME = 'GeneratedLexer';

    /**
     * @var string
     */
    protected $self = __NAMESPACE__;

    /**
     * @var array|string[]|null
     */
    protected $header;

    /**
     * @var bool
     */
    protected $strict = true;

    /**
     * @var string|null
     */
    protected $namespace;

    /**
     * @var string
     */
    protected $class = self::DEFAULT_CLASS_NAME;

    /**
     * @var string
     */
    protected $template;

    /**
     * @var Renderer
     */
    protected $renderer;

    /**
     * @param string $name
     * @return CodeGenerator
     */
    public function namespace(string $name): CodeGenerator
    {
        $this->namespace = $name;

        return $this;
    }

    /**
     * @param string $name
     * @return CodeGenerator
     */
    public function class(string $name): CodeGenerator
    {
        $this->class = $name;

        return $this;
    }

    /**
     * @param bool $enabled
     * @return CodeGenerator
     */
    public function strict(bool $enabled): CodeGenerator
    {
        $this->strict = $enabled;

        return $this;
    }

    /**
     * @param string ...$lines
     * @return CodeGenerator
     */
    public function header(string ...$lines): CodeGenerator
    {
        $this->header = [];

        foreach ($lines as $line) {
            [$length, $text] = [0, ''];

            foreach (\preg_split('/\s+/iu', $line) ?? [] as $word) {
                if ($length + \mb_strlen($word) > static::HEADER_LENGTH) {
                    $this->header[]  = $text;
                    [$length, $text] = [0, ''];
                }

                if ($length > 0) {
                    $text .= ' ';
                }

                $text .= $word;
                $length += \mb_strlen($word);
            }

            $this->header[] = $text;
        }

        return $this;
    }

    /**
     * @param string $template
     * @return CodeGenerator
     */
    public function using(string $template): CodeGenerator
    {
        $this->template = $template;

        return $this;
    }

    /**
     * @return GeneratedResult
     */
    public function build(): GeneratedResult
    {
        \assert(\is_string($this->template));

        if ($this->header === null) {
            $this->header = $this->getDefaultHeader();
        }

        return new GeneratedResult($this->render(), $this->class);
    }

    /**
     * @return array
     */
    private function getDefaultHeader(): array
    {
        $package = \basename(\str_replace('\\', '/', $this->namespace));

        return [
            \sprintf('This file is part of %s package.', $package),
            '',
            'For the full copyright and license information, please view the',
            'LICENSE file that was distributed with this source code.',
        ];
    }

    /**
     * @return string
     */
    private function render(): string
    {
        return $this->getRenderer()->render($this->template, \iterator_to_array($this->getContext()));
    }

    /**
     * @return Renderer
     */
    protected function getRenderer(): Renderer
    {
        if ($this->renderer === null) {
            $this->renderer = new TwigRenderer();
        }

        return $this->renderer;
    }

    /**
     * @return \Generator
     */
    protected function getContext(): \Generator
    {
        $reflection = new \ReflectionObject($this);

        foreach ($reflection->getProperties() as $property) {
            if ($property->isPublic() || $property->isProtected()) {
                $property->setAccessible(true);

                yield $property->getName() => $property->getValue($this);
            }
        }
    }
}
