<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railgun;

use Railgun\Http\ResponderInterface;
use Railgun\Support\Constructors;
use Railgun\Compiler\Compiler;
use Railgun\Exceptions\RuntimeException;
use Railgun\Http\RequestInterface;
use Railgun\Reflection\Abstraction\DocumentTypeInterface;
use Railgun\Support\Debuggable;
use Railgun\Support\Dispatcher;
use Railgun\Support\File;
use Railgun\Support\Loggable;

/**
 * Class Endpoint
 * @package Railgun
 */
class Endpoint implements ResponderInterface
{
    use Loggable;
    use Constructors;
    use Debuggable;

    /**
     * @var Compiler
     */
    private $compiler;

    /**
     * @var File
     */
    private $file;

    /**
     * @var Dispatcher
     */
    private $events;

    /**
     * Endpoint constructor.
     * @param File $file
     * @throws \Railgun\Exceptions\CompilerException
     * @throws \Railgun\Exceptions\SemanticException
     */
    public function __construct(File $file)
    {
        $this->file = $file;
        $this->compiler = new Compiler();
        $this->events = new Dispatcher();
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
     * @throws \Railgun\Exceptions\RuntimeException
     * @throws \LogicException
     * @throws \Railgun\Exceptions\UnrecognizedTokenException
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
     * @throws Exceptions\UnrecognizedTokenException
     * @throws RuntimeException
     */
    private function compileDocument(): DocumentTypeInterface
    {
        $document = $this->compiler->compile($this->file);

        if (!$document->getSchema()) {
            throw RuntimeException::new('%s does not contain available schema type', $this->file->getPathname());
        }

        return $document;
    }
}
