<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Schema;

/**
 * Class Registry
 * @package Serafim\Railgun\Schema
 */
class Registry
{
    /**
     * @var \Closure
     */
    private $onCreate;

    /**
     * @var array
     */
    private $schemas = [];

    /**
     * Registry constructor.
     * @param \Closure|null $onCreate
     */
    public function __construct(?\Closure $onCreate = null)
    {
        $this->onCreate = $onCreate
            ?? function (string $class) {
                return new $class;
            };
    }

    /**
     * @param string|SchemaInterface $schema
     * @return SchemaInterface|AbstractSchema
     * @throws \InvalidArgumentException
     */
    public function get(string $schema): SchemaInterface
    {
        if (! array_key_exists($schema, $this->schemas)) {
            $this->schemas[$schema] = $this->create($schema);
        }

        return $this->schemas[$schema];
    }

    /**
     * @param string $class
     * @return SchemaInterface
     * @throws \InvalidArgumentException
     */
    private function create(string $class): SchemaInterface
    {
        $result = ($this->onCreate)($class);

        if (! ($result instanceof SchemaInterface)) {
            throw new \InvalidArgumentException('Invalid schema ' . $class);
        }

        return $result;
    }
}
