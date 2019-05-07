<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Webonyx;

use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Arr;
use Railt\Http\Input as BaseInput;
use Railt\Http\RequestInterface;
use Railt\SDL\Contracts\Dependent\FieldDefinition;
use Railt\Foundation\Webonyx\Input\PathInfoLoader;
use Railt\Foundation\Webonyx\Input\PreferTypesLoader;

/**
 * Class Input
 */
class Input extends BaseInput
{
    use PathInfoLoader;
    use PreferTypesLoader;

    /**
     * @var ResolveInfo
     */
    private $info;

    /**
     * @var FieldDefinition
     */
    private $reflection;

    /**
     * Input constructor.
     *
     * @param RequestInterface $request
     * @param ResolveInfo $info
     * @param FieldDefinition $field
     * @param array $args
     */
    public function __construct(RequestInterface $request, ResolveInfo $info, FieldDefinition $field, array $args = [])
    {
        [$this->info, $this->reflection] = [$info, $field];

        $type = $this->resolveTypeName($field);

        parent::__construct($request, $type, $args);

        $this->withField($this->reflection->getName());
        $this->resolveDefaultArguments($field);
    }

    /**
     * @return array
     */
    public function getRelatedFields(): array
    {
        $result = parent::getRelatedFields();

        foreach ($this->info->fieldNodes as $fieldNode) {
            $result[] = $fieldNode->name->value;
        }

        return \array_unique($result);
    }

    /**
     * @param string $field
     * @return bool
     */
    public function wants(string $field): bool
    {
        $depth = \substr_count($field, '.');

        if ($depth === 0) {
            return \in_array($field, $this->getRelatedFields(), true);
        }

        return Arr::has($this->info->getFieldSelection($depth), $field);
    }

    /**
     * @return ResolveInfo
     */
    protected function getResolveInfo(): ResolveInfo
    {
        return $this->info;
    }

    /**
     * @param FieldDefinition $field
     * @return string
     */
    private function resolveTypeName(FieldDefinition $field): string
    {
        return $field->getParent()->getName();
    }

    /**
     * @param FieldDefinition $field
     */
    private function resolveDefaultArguments(FieldDefinition $field): void
    {
        foreach ($field->getArguments() as $argument) {
            if ($argument->hasDefaultValue() && ! $this->has($argument->getName())) {
                $this->withArgument($argument->getName(), $argument->getDefaultValue());
            }
        }
    }
}
