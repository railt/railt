<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\GraphQL;

use Cache\Adapter\PHPArray\ArrayCachePool;
use Psr\SimpleCache\CacheInterface;
use Railt\GraphQL\Backend\Backend;
use Railt\GraphQL\Exception\GraphQLException;
use Railt\GraphQL\Exception\InternalErrorException;
use Railt\GraphQL\Frontend\Frontend;
use Railt\Io\Exception\NotReadableException;
use Railt\Io\File;
use Railt\Io\Readable;

/**
 * Class Compiler
 */
class Compiler implements CompilerInterface
{
    /**
     * @var int
     */
    protected const DEFAULT_CACHE_POOL_SIZE = 0x00ff;

    /**
     * @var string[]
     */
    protected const STANDARD_LIBRARY = [
        __DIR__ . '/../../resources/stdlib/stdlib.graphqls',
        __DIR__ . '/../../resources/stdlib/introspection.graphqls',
    ];

    /**
     * @var static|null
     */
    private static $instance;

    /**
     * @var Frontend
     */
    private $frontend;

    /**
     * @var Backend
     */
    private $backend;

    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * Compiler constructor.
     *
     * @param CacheInterface|null $cache
     * @throws InternalErrorException
     */
    public function __construct(CacheInterface $cache = null)
    {
        try {
            $this->boot($cache);
        } catch (\Exception $e) {
            throw new InternalErrorException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param CacheInterface|null $cache
     * @return void
     * @throws GraphQLException
     * @throws InternalErrorException
     * @throws NotReadableException
     * @throws \InvalidArgumentException
     * @throws \Railt\Lexer\Exception\BadLexemeException
     */
    private function boot(?CacheInterface $cache): void
    {
        $this->setCache($cache);

        $this->frontend = new Frontend();

        $this->bootStandardLibrary();
    }

    /**
     * @param CacheInterface|null $cache
     * @return void
     */
    public function setCache(?CacheInterface $cache): void
    {
        $this->cache = $cache ?? new ArrayCachePool(static::DEFAULT_CACHE_POOL_SIZE);
    }

    /**
     * @return void
     * @throws GraphQLException
     * @throws InternalErrorException
     * @throws NotReadableException
     */
    private function bootStandardLibrary(): void
    {
        foreach (static::STANDARD_LIBRARY as $pathname) {
            $this->preload(File::fromPathname($pathname));
        }
    }

    /**
     * @param Readable $schema
     * @return mixed
     * @throws GraphQLException
     * @throws InternalErrorException
     */
    public function preload(Readable $schema)
    {

    }

    /**
     * @param \Closure $command
     * @return mixed
     * @throws InternalErrorException
     * @throws GraphQLException
     */
    private function wrap(\Closure $command)
    {
        try {
            return $command();
        } catch (GraphQLException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new InternalErrorException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @return CompilerInterface
     * @throws InternalErrorException
     */
    public static function getInstance(): CompilerInterface
    {
        return self::$instance ?? self::$instance = new static();
    }

    /**
     * @param CompilerInterface|null $compiler
     * @return void
     */
    public static function setInstance(?CompilerInterface $compiler): void
    {
        self::$instance = $compiler;
    }

    /**
     * @param Readable $schema
     * @return mixed
     * @throws GraphQLException
     * @throws InternalErrorException
     */
    public function compile(Readable $schema)
    {

    }

    public function autoload(TypeLoaderInterface $loader): void
    {
        // TODO: Implement autoload() method.
    }

    public function extend(ExtensionInterface $extension): void
    {
        // TODO: Implement extend() method.
    }
}
