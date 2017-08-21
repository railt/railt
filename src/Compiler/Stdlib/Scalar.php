<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railgun\Compiler\Stdlib;

use Railgun\Reflection\Abstraction\CalleeDirectiveInterface;
use Railgun\Reflection\Abstraction\DocumentTypeInterface;
use Railgun\Reflection\Abstraction\ScalarTypeInterface;

/**
 * Class Scalar
 * @package Railgun\Compiler\Stdlib
 */
class Scalar implements ScalarTypeInterface
{
    /**
     * @var DocumentTypeInterface
     */
    private $parent;

    /**
     * @var string
     */
    private $name;

    /**
     * Anonymous constructor.
     * @param DocumentTypeInterface $parent
     * @param string $name
     */
    public function __construct(DocumentTypeInterface $parent, string $name)
    {
        $this->parent = $parent;
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getTypeName(): string
    {
        return 'Scalar';
    }

    /**
     * @return DocumentTypeInterface
     */
    public function getDocument(): DocumentTypeInterface
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
};
