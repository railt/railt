<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Adapters\Webonyx\Builder;

use Railt\Reflection\Abstraction\ArgumentInterface;
use Railt\Adapters\Webonyx\Builder\Common\HasDescription;
use Railt\Adapters\Webonyx\Builder\Type\TypeBuilder;

/**
 * Class ArgumentBuilder
 * @package Railt\Adapters\Webonyx\Builder
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
