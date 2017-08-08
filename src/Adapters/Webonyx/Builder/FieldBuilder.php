<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Adapters\Webonyx\Builder;

use GraphQL\Type\Definition\Type;
use Serafim\Railgun\Adapters\Webonyx\Builder\Common\HasDescription;
use Serafim\Railgun\Adapters\Webonyx\Builder\Type\TypeBuilder;
use Serafim\Railgun\Reflection\Abstraction\FieldInterface;

/**
 * Class FieldBuilder
 * @package Serafim\Railgun\Adapters\Webonyx
 * @property-read FieldInterface $type
 */
class FieldBuilder extends Builder
{
    use HasDescription;

    /**
     * @return array
     */
    public function build(): array
    {
        return [
            'type'        => $this->makeType(),
            'description' => $this->getDescription(),
        ];
    }

    /**
     * @return Type
     */
    private function makeType(): Type
    {
        return $this->make($this->type->getType(), TypeBuilder::class);
    }
}
