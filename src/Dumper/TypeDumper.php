<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Dumper;

use Railt\Dumper\Resolver\GenericResolver;
use Railt\Dumper\Resolver\ResolverInterface;
use Railt\Dumper\Resolver\SelfDisplayed;

/**
 * Class TypeDumper
 */
class TypeDumper implements TypeDumperInterface
{
    /**
     * @var string[]
     */
    private const DEFAULT_DUMPER_RESOLVERS = [
        Resolver\ClosureResolver::class,
        Resolver\CallableResolver::class,
        Resolver\NullResolver::class,
        Resolver\IntResolver::class,
        Resolver\FloatResolver::class,
        Resolver\BoolResolver::class,
        Resolver\ResourceResolver::class,
        Resolver\StringResolver::class,
        Resolver\GeneratorResolver::class,
        Resolver\ReflectionParameterResolver::class,
        Resolver\ReflectionTypeResolver::class,
        Resolver\IteratorResolver::class,
        Resolver\ArrayResolver::class,
        Resolver\ObjectResolver::class,
    ];

    /**
     * @var string
     */
    private const DUMP_PATTERN = '%s(%s)';

    /**
     * @var TypeDumperInterface|null
     */
    protected static $instance;

    /**
     * @var array|ResolverInterface[]
     */
    private $resolvers = [];

    /**
     * TypeDumper constructor.
     */
    public function __construct()
    {
        if (static::$instance === null) {
            static::$instance = $this;
        }

        foreach (self::DEFAULT_DUMPER_RESOLVERS as $resolver) {
            $this->add($resolver);
        }
    }

    /**
     * @param string $resolver
     * @return TypeDumperInterface
     */
    public function add(string $resolver): TypeDumperInterface
    {
        $this->resolvers[] = new $resolver($this);

        return $this;
    }

    /**
     * @return TypeDumperInterface
     */
    public static function getInstance(): TypeDumperInterface
    {
        return static::$instance ?? (static::$instance = new static());
    }

    /**
     * @param TypeDumperInterface|null $dumper
     * @return TypeDumperInterface|null
     */
    public static function setInstance(?TypeDumperInterface $dumper): ?TypeDumperInterface
    {
        return static::$instance = $dumper;
    }

    /**
     * @param mixed $value
     * @return string
     */
    public static function render($value): string
    {
        return static::getInstance()->dump($value);
    }

    /**
     * @param mixed $value
     * @return string
     */
    public function dump($value): string
    {
        $resolver = $this->resolve($value);

        if ($resolver instanceof SelfDisplayed) {
            return $resolver->render($resolver->type($value), $resolver->value($value));
        }

        return \vsprintf(self::DUMP_PATTERN, [
            $resolver->type($value),
            $resolver->value($value),
        ]);
    }

    /**
     * @param mixed $value
     * @return string
     */
    public function type($value): string
    {
        return $this->resolve($value)->type($value);
    }

    /**
     * @param mixed $value
     * @return string|ResolverInterface
     */
    private function resolve($value): ResolverInterface
    {
        foreach ($this->resolvers as $resolver) {
            if ($resolver->match($value)) {
                return $resolver;
            }
        }

        return new GenericResolver($this);
    }

    /**
     * @param mixed $value
     * @return string
     */
    public function value($value): string
    {
        return $this->resolve($value)->value($value);
    }
}
