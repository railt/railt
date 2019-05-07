<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Dumper;

use Railt\Dumper\VarDumper\HtmlDumper;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\CliDumper;
use Symfony\Component\VarDumper\Dumper\DataDumperInterface;

/**
 * Class VarDumper
 */
class VarDumper
{
    /**
     * @var VarDumper
     */
    protected static $instance;

    /**
     * VarDumper constructor.
     */
    public function __construct()
    {
        if (static::$instance === null) {
            static::$instance = $this;
        }
    }

    /**
     * @return VarDumper
     */
    public static function getInstance(): self
    {
        return static::$instance ?? (static::$instance = new static());
    }

    /**
     * @param VarDumper|null $dumper
     * @return VarDumper|null
     */
    public static function setInstance(?self $dumper): ?self
    {
        return static::$instance = $dumper;
    }

    /**
     * @param mixed $value
     * @return string
     */
    public function dump($value): string
    {
        return $this->stdoutToString(function () use ($value): void {
            if (\class_exists(CliDumper::class)) {
                $dumper = $this->getDumper();
                $dumper->dump((new VarCloner())->cloneVar($value));
            } else {
                \var_dump($value);
            }
        });
    }

    /**
     * @param \Closure $context
     * @return string
     */
    private function stdoutToString(\Closure $context): string
    {
        \ob_start();
        $context();
        return \ob_get_clean();
    }

    /**
     * @return DataDumperInterface
     * @throws \InvalidArgumentException
     */
    private function getDumper(): DataDumperInterface
    {
        if (\PHP_SAPI === 'cli') {
            return new CliDumper();
        }

        return new HtmlDumper();
    }
}
