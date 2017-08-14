<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun;

use GraphQL\Type\Definition\ResolveInfo;
use Serafim\Railgun\Routing\Router;
use Serafim\Railgun\Runtime\Dispatcher;
use Serafim\Railgun\Http\ResponderInterface;
use Serafim\Railgun\Runtime\AdapterInterface;
use Serafim\Railgun\Runtime\Error;
use Serafim\Railgun\Runtime\Loggable;
use Serafim\Railgun\Runtime\Webonyx\Adapter;
use Serafim\Railgun\Compiler\Compiler;
use Serafim\Railgun\Compiler\File;
use Serafim\Railgun\Exceptions\RuntimeException;
use Serafim\Railgun\Http\RequestInterface;
use Serafim\Railgun\Reflection\Abstraction\DocumentTypeInterface;

/**
 * Class Endpoint
 * @package Serafim\Railgun
 */
class Endpoint implements ResponderInterface
{
    use Loggable;

    /**
     * @var Compiler
     */
    private $compiler;

    /**
     * @var File
     */
    private $file;

    /**
     * @var bool
     */
    private $debug = false;

    /**
     * @var array|AdapterInterface[]
     */
    private $adapters = [
        Adapter::class
    ];

    /**
     * @var Dispatcher
     */
    private $events;

    /**
     * @var Router
     */
    private $router;

    /**
     * @param \SplFileInfo $info
     * @return Endpoint
     * @throws \Serafim\Railgun\Exceptions\SemanticException
     * @throws \Serafim\Railgun\Exceptions\CompilerException
     * @throws \Serafim\Railgun\Exceptions\NotReadableException
     */
    public static function file(\SplFileInfo $info): Endpoint
    {
        return new static(File::physics($info));
    }

    /**
     * @param string $pathName
     * @return Endpoint
     * @throws \Serafim\Railgun\Exceptions\SemanticException
     * @throws \Serafim\Railgun\Exceptions\CompilerException
     * @throws \Serafim\Railgun\Exceptions\NotReadableException
     */
    public static function filePath(string $pathName): Endpoint
    {
        return new static(File::path($pathName));
    }

    /**
     * @param string $sources
     * @return Endpoint
     * @throws \Serafim\Railgun\Exceptions\SemanticException
     * @throws \Serafim\Railgun\Exceptions\CompilerException
     * @throws \Serafim\Railgun\Exceptions\NotReadableException
     */
    public static function sources(string $sources): Endpoint
    {
        return new static(File::virual($sources));
    }

    /**
     * Endpoint constructor.
     * @param File $file
     * @throws \Serafim\Railgun\Exceptions\CompilerException
     * @throws \Serafim\Railgun\Exceptions\SemanticException
     */
    public function __construct(File $file)
    {
        $this->file = $file;
        $this->compiler = new Compiler();
        $this->events = new Dispatcher();
        $this->router = new Router();

        $this->events->listen('*', function($info, string $event) {
            file_put_contents(__DIR__ . '/../some.txt', dump($info));
        });
    }

    /**
     * @param DocumentTypeInterface $document
     * @return AdapterInterface
     * @throws \LogicException
     */
    public function adapter(DocumentTypeInterface $document): AdapterInterface
    {
        foreach ($this->adapters as $adapter) {
            if ($adapter::isSupported()) {
                return new $adapter($document, $this->events);
            }
        }

        throw new \LogicException('Can not find allowed query adapter');
    }

    /**
     * @param bool $enabled
     * @return Endpoint
     */
    public function debugMode(bool $enabled = true): Endpoint
    {
        $this->debug = $enabled;

        return $this;
    }

    /**
     * @param \Closure $then
     * @param bool $prepend
     * @return Endpoint
     */
    public function autoload(\Closure $then, bool $prepend = false): Endpoint
    {
        $this->compiler->getLoader()->autoload($then, $prepend);

        return $this;
    }

    /**
     * @param string|array|string[] $directories
     * @return Endpoint
     */
    public function autoloadDirectory(string ...$directories): Endpoint
    {
        $this->compiler->getLoader()->dir($directories);

        return $this;
    }

    /**
     * @param RequestInterface $request
     * @return array
     * @throws \LogicException
     * @throws \Serafim\Railgun\Exceptions\UnrecognizedTokenException
     */
    public function request(RequestInterface $request): array
    {
        try {
            return $this->adapter($this->compileDocument())->request($request);
        } catch (\Throwable $e) {
            return ['errors' => Error::render($e, $this->debug)];
        }
    }

    /**
     * @return DocumentTypeInterface
     * @throws \Serafim\Railgun\Exceptions\UnrecognizedTokenException
     */
    private function compileDocument(): DocumentTypeInterface
    {
        $document = $this->compiler->compile($this->file);

        if (!$document->getSchema()) {
            throw RuntimeException::new('%s does not contain available schema type', $this->file->getPathname());
        }

        return $document;
    }

    /**
     * @return Router
     */
    public function routes(): Router
    {
        return $this->router;
    }
}
