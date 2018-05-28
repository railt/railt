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
 * Class ActionDispatched
 */
class ActionDispatched extends BaseFieldResolver
{
    /**
     * @var mixed
     */
    private $response;

    /**
     * ActionDispatched constructor.
     * @param InputInterface $input
     * @param $response
     */
    public function __construct(InputInterface $input, $response)
    {
        $this->response = $response;
        parent::__construct($input);
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param mixed $value
     * @return ActionDispatched
     */
    public function setResponse($value): self
    {
        $this->response = $value;

        return $this;
    }
}
