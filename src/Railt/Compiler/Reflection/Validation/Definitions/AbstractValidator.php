<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Validation\Definitions;

use Railt\Compiler\Reflection\Support;
use Railt\Compiler\Reflection\Validation\Validator;

/**
 * Class AbstractValidator
 */
abstract class AbstractValidator implements DefinitionValidator
{
    use Support;

    /**
     * @var Validator
     */
    private $validator;

    /**
     * AbstractValidator constructor.
     * @param Validator $validator
     */
    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
    }
}
