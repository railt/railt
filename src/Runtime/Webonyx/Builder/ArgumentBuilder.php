<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Runtime\Webonyx\Builder;

use Serafim\Railgun\Reflection\Abstraction\ArgumentInterface;
use Serafim\Railgun\Runtime\Webonyx\Builder\Common\HasDescription;
use Serafim\Railgun\Runtime\Webonyx\Builder\Type\TypeBuilder;

/**
 * Class ArgumentBuilder
 * @package Serafim\Railgun\Runtime\Webonyx\Builder
 * @property-read ArgumentInterface $type
 */
class ArgumentBuilder extends Builder
{
    use HasDescription;

    /**
     * @return array
     * @throws \LogicException
     */
    public function build(): array
    {
        $result = [
            'description' => $this->getDescription(),
            'type'        => $this->make($this->type->getType(), TypeBuilder::class),
        ];

        if ($this->type->hasDefaultValue()) {
            $result['defaultValue'] = $this->type->getDefaultValue();
        }

        return $result;
    }
}
