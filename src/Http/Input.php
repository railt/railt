<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http;

use Illuminate\Support\Traits\Macroable;
use Railt\Http\Input\HasArguments;
use Railt\Http\Input\HasField;
use Railt\Http\Input\HasParents;
use Railt\Http\Input\HasPath;
use Railt\Http\Input\HasPreferTypes;
use Railt\Http\Input\HasType;

/**
 * Class Input
 */
class Input implements InputInterface
{
    use Macroable;
    use HasType;
    use HasPath;
    use HasField;
    use HasParents;
    use HasArguments;
    use HasPreferTypes;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * Input constructor.
     *
     * @param RequestInterface $request
     * @param string $typeName
     * @param array $arguments
     */
    public function __construct(RequestInterface $request, string $typeName, array $arguments = [])
    {
        $this->withRequest($request);
        $this->withTypeName($typeName);
        $this->withArguments($arguments, true);
    }

    /**
     * @param RequestInterface $request
     * @return InputInterface|$this
     */
    public function withRequest(RequestInterface $request): InputInterface
    {
        $this->request = $request;

        return $this;
    }

    /**
     * @param RequestInterface $request
     * @param string $typeName
     * @param string $path
     * @return InputInterface|$this
     */
    public static function new(RequestInterface $request, string $typeName, string $path): InputInterface
    {
        $input = new static($request, $typeName);
        $input->withPathChunks(self::pathToChunks($path));

        return $input;
    }

    /**
     * @return RequestInterface
     */
    public function request(): RequestInterface
    {
        return $this->request;
    }

    /**
     * @param string $type
     * @return bool
     */
    public function wantsType(string $type): bool
    {
        return \in_array($type, $this->getPreferTypes(), true);
    }

    /**
     * @return array
     */
    public function __debugInfo(): array
    {
        return \array_filter([
            'type'      => $this->type,
            'path'      => $this->path,
            'alias'     => $this->alias,
            'arguments' => $this->arguments,
        ]);
    }
}
