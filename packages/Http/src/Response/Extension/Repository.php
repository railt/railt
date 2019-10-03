<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Response\Extension;

/**
 * Class Repository
 */
class Repository implements RepositoryInterface
{
    /**
     * @var array|mixed[]
     */
    private array $extensions = [];

    /**
     * @param string $name
     * @param mixed $data
     * @return RepositoryInterface
     */
    public function add(string $name, $data): RepositoryInterface
    {
        $this->extensions[$name] = $data;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->extensions;
    }
}
