<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Generator\Renderer;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\Loader\LoaderInterface;
use Twig\TwigFilter;
use Zend\Code\Generator\ValueGenerator;

/**
 * Class TwigRenderer
 */
class TwigRenderer implements Renderer
{
    /**
     * @var LoaderInterface
     */
    private $loader;

    /**
     * @var Environment
     */
    private $env;

    /**
     * TwigRenderer constructor.
     */
    public function __construct()
    {
        $this->loader = new FilesystemLoader([self::BASE_DIRECTORY], self::BASE_DIRECTORY);
        $this->env    = new Environment($this->loader);

        foreach ($this->bootFilters() as $filter) {
            $this->env->addFilter($filter);
        }
    }

    /**
     * @return \Generator|TwigFilter[]
     */
    private function bootFilters(): \Generator
    {
        yield new TwigFilter('string', function (string $string): string {
            $generator = new ValueGenerator($string, ValueGenerator::TYPE_STRING);

            return $generator->generate();
        });

        yield new TwigFilter('boolean', function (string $string): string {
            $generator = new ValueGenerator($string, ValueGenerator::TYPE_BOOL);

            return $generator->generate();
        });

        yield new TwigFilter('array', function (array $items, int $depth = 0): string {
            $generator = new ValueGenerator($items, ValueGenerator::TYPE_ARRAY_SHORT);

            $generator->setArrayDepth($depth);

            return $generator->generate();
        });

        yield new TwigFilter('class', function ($object): string {
            if (\is_object($object)) {
                return \get_class($object);
            }

            return \json_encode($object);
        });

        yield new TwigFilter('value', function ($value): string {
            return (new ValueGenerator($value))->generate();
        });
    }

    /**
     * @param string $directory
     * @return Renderer
     * @throws \Twig_Error_Loader
     */
    public function in(string $directory): Renderer
    {
        $this->loader->addPath($directory);

        return $this;
    }

    /**
     * @param string $template
     * @param array $params
     * @return string
     * @throws \Twig_Error_Syntax
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Loader
     */
    public function render(string $template, array $params): string
    {
        return $this->env->render($template, $params);
    }
}
