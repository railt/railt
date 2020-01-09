<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\CodeGenerator;

use Railt\CodeGenerator\Value\Value;
use Railt\Config\MutableRepository;
use Railt\Config\MutableRepositoryInterface;
use Railt\Config\RepositoryInterface;
use Railt\TypeSystem\Value\ValueInterface;

/**
 * Class AbstractGenerator
 */
abstract class AbstractGenerator implements GeneratorInterface
{
    /**
     * @var string
     */
    public const CONFIG_MULTILINE = 'multiline';

    /**
     * @var string
     */
    public const CONFIG_DEPTH_CHARS = 'depthChars';

    /**
     * @var string
     */
    public const CONFIG_COMMA_SEPARATED = 'commaSeparated';

    /**
     * @var string
     */
    public const CONFIG_UNICODE = 'unicode';

    /**
     * @var string
     */
    public const CONFIG_DEPTH = 'depth';

    /**
     * @var string
     */
    public const CONFIG_VALUE_GENERATOR = 'value';

    /**
     * @var string
     */
    public const CONFIG_BRACES_AT_NEW_LINE = 'bracesNewLine';

    /**
     * @var MutableRepositoryInterface
     */
    protected MutableRepositoryInterface $config;

    /**
     * AbstractGenerator constructor.
     *
     * @param array|RepositoryInterface $config
     */
    public function __construct($config = [])
    {
        $this->config = $this->bootConfig($config);
    }

    /**
     * @param array $with
     * @return RepositoryInterface
     */
    protected function config(array $with = []): RepositoryInterface
    {
        $config = (clone $this->config);

        foreach ($with as $key => $value) {
            $config->set($key, $value);
        }

        return $config;
    }

    /**
     * @param ValueInterface $value
     * @param array $with
     * @return GeneratorInterface
     */
    protected function value(ValueInterface $value, array $with = []): GeneratorInterface
    {
        /** @var Value $factory */
        $factory = (string)$this->config->get(static::CONFIG_VALUE_GENERATOR, Value::class);

        return $factory::resolve($value, $this->config($with));
    }

    /**
     * @param array|RepositoryInterface $config
     * @return MutableRepositoryInterface
     */
    private function bootConfig($config = []): MutableRepositoryInterface
    {
        $config = $config instanceof RepositoryInterface ? $config->all() : $config;

        return new MutableRepository($config);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * @return bool
     */
    protected function isUnicode(): bool
    {
        return (bool)$this->config->get(static::CONFIG_UNICODE);
    }

    /**
     * @return bool
     */
    protected function isNewLineBraces(): bool
    {
        return (bool)$this->config->get(static::CONFIG_BRACES_AT_NEW_LINE);
    }


    /**
     * @return int
     */
    protected function depth(): int
    {
        return (int)$this->config->get(static::CONFIG_DEPTH, 0);
    }

    /**
     * @param array|string[] $lines
     * @param int $depth
     * @return string
     */
    protected function lines(array $lines, int $depth): string
    {
        if (! $this->isMultiline()) {
            return \implode($this->isCommaSeparated() ? ', ' : ' ', $lines);
        }

        $lines = \array_map(fn(string $line): string => $this->line($line, $depth), $lines);

        return \implode(($this->isCommaSeparated() ? ',' : '') . "\n", $lines);
    }

    /**
     * @return bool
     */
    protected function isMultiline(): bool
    {
        return (bool)$this->config->get(static::CONFIG_MULTILINE);
    }

    /**
     * @return bool
     */
    protected function isCommaSeparated(): bool
    {
        return (bool)$this->config->get(static::CONFIG_COMMA_SEPARATED, true);
    }

    /**
     * @param string $line
     * @param int $depth
     * @param string $suffix
     * @return string
     */
    protected function line(string $line, int $depth, string $suffix = ''): string
    {
        return $this->prefix($depth) . $line . $suffix;
    }

    /**
     * @param int $depth
     * @return string
     */
    protected function prefix(int $depth): string
    {
        return \str_repeat($this->depthChars(), $depth);
    }

    /**
     * @return string
     */
    protected function depthChars(): string
    {
        return (string)$this->config->get(static::CONFIG_DEPTH_CHARS, '    ');
    }
}
