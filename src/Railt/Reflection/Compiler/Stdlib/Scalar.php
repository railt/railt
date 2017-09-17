<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Compiler\Stdlib;

use Railt\Reflection\Contracts\CalleeDirectiveInterface;
use Railt\Reflection\Contracts\DocumentInterface;
use Railt\Reflection\Contracts\ScalarTypeInterface;

/**
 * Class Scalar
 */
class Scalar implements ScalarTypeInterface
{
    /**
     * @var DocumentInterface
     */
    private $parent;

    /**
     * @var string
     */
    private $name;

    /**
     * Anonymous constructor.
     * @param DocumentInterface $parent
     * @param string $name
     */
    public function __construct(DocumentInterface $parent, string $name)
    {
        $this->parent = $parent;
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->name . ' scalar type';
    }

    /**
     * @return string
     */
    public function getTypeName(): string
    {
        return 'Scalar';
    }

    /**
     * @return DocumentInterface
     */
    public function getDocument(): DocumentInterface
    {
        return $this->parent;
    }

    /**
     * @return iterable
     */
    public function getDirectives(): iterable
    {
        return [];
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasDirective(string $name): bool
    {
        return false;
    }

    /**
     * @param string $name
     * @return null|CalleeDirectiveInterface
     */
    public function getDirective(string $name): ?CalleeDirectiveInterface
    {
        return null;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
