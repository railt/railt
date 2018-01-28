<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Pipeline;

/**
 * Class BaseStage
 */
abstract class BaseStage implements Stage
{
    /**
     * @var \SplStack
     */
    private $data;

    /**
     * BaseStage constructor.
     */
    public function __construct()
    {
        $this->data = new \SplStack();
    }

    /**
     * @param mixed $data
     * @return Stage
     */
    public function push($data): Stage
    {
        $this->data->push($data);

        return $this;
    }

    /**
     * @return mixed|null
     */
    protected function pop()
    {
        return $this->data->count() > 0 ? $this->data->pop() : null;
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->data->count() === 0;
    }
}
