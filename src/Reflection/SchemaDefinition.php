<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Reflection;

use Hoa\Compiler\Llk\TreeNode;
use Serafim\Railgun\Compiler\Dictionary;
use Serafim\Railgun\Reflection\Abstraction\FieldInterface;
use Serafim\Railgun\Reflection\Abstraction\NamedDefinitionInterface;
use Serafim\Railgun\Reflection\Abstraction\ObjectTypeInterface;
use Serafim\Railgun\Reflection\Abstraction\SchemaTypeInterface;
use Serafim\Railgun\Reflection\Common\HasDirectives;
use Serafim\Railgun\Reflection\Common\HasFields;

/**
 * Class SchemaDefinition
 * @package Serafim\Railgun\Reflection
 */
class SchemaDefinition extends Definition implements SchemaTypeInterface
{
    use HasFields;
    use HasDirectives;

    /**
     *
     */
    private const QUERY_FIELD_NAME = 'query';

    /**
     *
     */
    private const MUTATION_FIELD_NAME = 'mutation';

    /**
     * @var array
     */
    protected $astHasFields = [
        '#Query',
        '#Mutation'
    ];

    protected function compile(TreeNode $ast, Dictionary $dictionary): void
    {
        throw new \LogicException(__METHOD__ . ' not implemented yet');
    }

    /**
     * @return ObjectTypeInterface|NamedDefinitionInterface
     * @throws \LogicException
     */
    public function getQuery(): ObjectTypeInterface
    {
        /** @var FieldInterface $query */
        $query = $this->getField(self::QUERY_FIELD_NAME);

        if ($query === null) {
            throw new \LogicException('Can not find query. Probably compiler internal error?');
        }

        return $query->getType()->getDefinition();
    }

    /**
     * @return null|ObjectTypeInterface|NamedDefinitionInterface
     */
    public function getMutation(): ?ObjectTypeInterface
    {
        $mutation = $this->getField(self::MUTATION_FIELD_NAME);

        if ($mutation === null) {
            return null;
        }

        return $mutation->getType()->getDefinition();
    }

    /**
     * @return bool
     */
    public function hasMutation(): bool
    {
        return $this->getField(self::MUTATION_FIELD_NAME) !== null;
    }
}
