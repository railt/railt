<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Events;

use Railt\Http\InputInterface;

/**
 * Class ActionDispatching
 */
class ActionDispatching extends BaseFieldResolver
{
    /**
     * @var array
     */
    private $parameters;

    /**
     * ActionDispatching constructor.
     * @param InputInterface $input
     * @param array $parameters
     */
    public function __construct(InputInterface $input, array $parameters)
    {
        $this->parameters = $parameters;

        parent::__construct($input);
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }
}
