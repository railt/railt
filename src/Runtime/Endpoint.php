<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Runtime;

use Serafim\Railgun\Compiler\Compiler;
use Serafim\Railgun\Compiler\File;
use Serafim\Railgun\Exceptions\RuntimeException;
use Serafim\Railgun\Http\RequestInterface;
use Serafim\Railgun\Http\ResponderInterface;
use Serafim\Railgun\Reflection\Abstraction\DocumentTypeInterface;

/**
 * Class Endpoint
 * @package Serafim\Railgun
 */
class Endpoint implements ResponderInterface
{
    use EndpointConstructors;

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
     * Endpoint constructor.
     * @param File $file
     * @throws \Serafim\Railgun\Exceptions\CompilerException
     * @throws \Serafim\Railgun\Exceptions\SemanticException
     */
    public function __construct(File $file)
    {
        $this->compiler = new Compiler();
        $this->file = $file;
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
     * @param string $path
     * @return Endpoint
     */
    public function autoloadDirectory(string $path): Endpoint
    {
        $this->compiler->getLoader()->dir($path);

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
            return Adapter::resolve($this->compileDocument())
                ->request($request);
        } catch (\Throwable $e) {
            return [
                'errors' => Error::render($e, $this->debug),
            ];
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
}
