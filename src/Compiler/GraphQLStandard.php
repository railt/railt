<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Compiler;

use Serafim\Railgun\Reflection\Abstraction\CalleeDirectiveInterface;
use Serafim\Railgun\Reflection\Abstraction\DocumentTypeInterface;
use Serafim\Railgun\Reflection\Abstraction\ScalarTypeInterface;
use Serafim\Railgun\Reflection\Abstraction\SchemaTypeInterface;
use Serafim\Railgun\Reflection\Common\HasDefinitions;

/**
 * Class GraphQLStandard
 * @package Serafim\Railgun\Compiler
 */
final class GraphQLStandard implements DocumentTypeInterface
{
    use HasDefinitions;

    /**
     * GraphQLStandard constructor.
     * @param Dictionary $dictionary
     */
    public function __construct(Dictionary $dictionary)
    {
        $this->dictionary = $dictionary
            ->register($this->scalar('ID'))
            ->register($this->scalar('Int'))
            ->register($this->scalar('Float'))
            ->register($this->scalar('String'))
            ->register($this->scalar('Boolean'))
            ->register($this->scalar('Any'));
    }

    /**
     * @param string $name
     * @return ScalarTypeInterface
     */
    private function scalar(string $name): ScalarTypeInterface
    {
        return new class($this, $name) implements ScalarTypeInterface
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
    }

    /**
     * @return string
     */
    public function getTypeName(): string
    {
        return 'GraphQL Standard';
    }

    /**
     * @return DocumentTypeInterface
     */
    public function getDocument(): DocumentTypeInterface
    {
        return $this;
    }

    /**
     * @return null|string
     */
    public function getFileName(): string
    {
        return 'GraphQL::StdLib';
    }

    /**
     * @return null|SchemaTypeInterface
     */
    public function getSchema(): ?SchemaTypeInterface
    {
        return null;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return 0;
    }

    /**
     * @return bool
     */
    public function isStdlib(): bool
    {
        return true;
    }
}
