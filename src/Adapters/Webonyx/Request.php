<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Adapters\Webonyx;

use GraphQL\Type\Definition\ResolveInfo;
use Railt\Adapters\RequestInterface;
use Railt\Exceptions\RuntimeException;
use Railt\Reflection\Abstraction\ArgumentInterface;
use Railt\Reflection\Abstraction\Common\HasArgumentsInterface;
use Railt\Routing\Route;
use Railt\Routing\Router;

/**
 * Class Request
 * @package Railt\Adapters\Webonyx
 */
class Request implements RequestInterface
{
    /**
     * @var array
     */
    private $arguments = [];

    /**
     * @var ResolveInfo
     */
    private $info;

    /**
     * @var null|string
     */
    private $path;

    /**
     * @param HasArgumentsInterface $node
     * @param array $input
     * @return array
     * @throws RuntimeException
     */
    public static function resolveArguments(HasArgumentsInterface $node, array $input = []): array
    {
        $result = [];

        /** @var ArgumentInterface $default */
        foreach ($node->getArguments() as $default) {
            $name = $default->getName();

            if (
                !array_key_exists($name, $input) && // Empty argument
                !$default->hasDefaultValue() &&     // And has no default value
                $default->getType()->nonNull()      // And required
            ) {
                $message = 'Argument %s required for field %s';
                throw RuntimeException::new($message, $name, $default->getParent()->getName());
            }

            if (array_key_exists($name, $input)) {
                $result[$name] = $input[$name];
            } elseif ($default->hasDefaultValue()) {
                $result[$name] = $default->getDefaultValue();
            }
        }

        return $result;
    }

    /**
     * Request constructor.
     * @param array $arguments
     * @param ResolveInfo $info
     */
    public function __construct(array $arguments = [], ResolveInfo $info)
    {
        $this->info = $info;
        $this->arguments = $arguments;
    }

    /**
     * @return ResolveInfo
     */
    public function getResolveInfo(): ResolveInfo
    {
        return $this->info;
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->arguments;
    }

    /**
     * @param string $argument
     * @param null $default
     * @return mixed|null
     */
    public function get(string $argument, $default = null)
    {
        return $this->arguments[$argument] ?? $default;
    }

    /**
     * @param string $argument
     * @return bool
     */
    public function has(string $argument): bool
    {
        return array_key_exists($argument, $this->arguments);
    }

    /**
     * TODO
     * @return iterable
     */
    public function getRelations(): iterable
    {
        throw new \LogicException(__METHOD__ . ' not implemented yet');
    }

    /**
     * @return string
     */
    public function getFieldName(): string
    {
        return $this->info->fieldName;
    }

    public function hasRelation(string $name): bool
    {
        throw new \LogicException(__METHOD__ . ' not implemented yet');
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        if ($this->path === null) {
            $path = $this->info->path;
            $path[count($path) - 1] = $this->info->fieldName;

            $this->path = implode(Route::DEFAULT_DELIMITER, $path);
        }

        return $this->path;
    }

    /**
     * @return array
     */
    public function __debugInfo(): array
    {
        return [
            'path' => $this->path,
            'arguments' => $this->arguments
        ];
    }

    /**
     * @return string
     * @throws \LogicException
     */
    public function getAlias(): string
    {
        return $this->info->path[count($this->info->path) - 1];
    }

    /**
     * @return bool
     * @throws \LogicException
     */
    public function hasAlias(): bool
    {
        return $this->getAlias() !== $this->getFieldName();
    }
}
