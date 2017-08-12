<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Runtime\Webonyx;

use GraphQL\Type\Definition\ResolveInfo;
use Serafim\Railgun\Exceptions\RuntimeException;
use Serafim\Railgun\Reflection\Abstraction\ArgumentInterface;
use Serafim\Railgun\Reflection\Abstraction\FieldInterface;
use Serafim\Railgun\Runtime\RequestInterface;

/**
 * Class Request
 * @package Serafim\Railgun\Runtime\Webonyx
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
     * Request constructor.
     * @param array $args
     * @param FieldInterface $field
     * @param ResolveInfo $info
     * @throws \Serafim\Railgun\Exceptions\RuntimeException
     */
    public function __construct(array $args = [], FieldInterface $field, ResolveInfo $info)
    {
        $this->info = $info;
        $this->arguments = $this->resolveArguments($field->getArguments(), $args);
    }

    /**
     * @param iterable $defaults
     * @param array $input
     * @return array
     * @throws RuntimeException
     */
    private function resolveArguments(iterable $defaults, array $input): array
    {
        $result = [];

        /** @var ArgumentInterface $default */
        foreach ($defaults as $default) {
            $name = $default->getName();

            if (
                !array_key_exists($name, $input) && // Empty argument
                !$default->hasDefaultValue() && // And has no default value
                $default->getType()->nonNull() // And required
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

    public function getRelations(): iterable
    {
        throw new \LogicException(__METHOD__ . ' not implemented yet');
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
            $this->path = implode('.', $this->info->path);
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
}
