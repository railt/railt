<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railgun\Webonyx\Builder;

use Railgun\Reflection\Abstraction\ArgumentInterface;
use Railgun\Webonyx\Builder\Common\HasDescription;
use Railgun\Webonyx\Builder\Type\TypeBuilder;

/**
 * Class ArgumentBuilder
 * @package Railgun\Webonyx\Builder
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
