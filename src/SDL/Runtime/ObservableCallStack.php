<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Runtime;

use Railt\Reflection\Contracts\Definitions\TypeDefinition;

/**
 * Class ObservableCallStack
 */
class ObservableCallStack extends CallStack
{
    public const PUSH_EVENT = 'push';
    public const POP_EVENT  = 'pop';

    /**
     * @var array
     */
    private $observers = [];

    /**
     * @param \Closure $observer
     * @return ObservableCallStack
     */
    public function subscribe(\Closure $observer): self
    {
        $this->observers[] = $observer;

        return $this;
    }

    /**
     * @param string $event
     * @param TypeDefinition $data
     * @return void
     */
    private function fire(string $event, TypeDefinition $data): void
    {
        foreach ($this->observers as $observer) {
            $observer($event, $data);
        }
    }

    /**
     * @param TypeDefinition[] ...$definitions
     * @return CallStackInterface
     */
    public function push(TypeDefinition ...$definitions): CallStackInterface
    {
        foreach ($definitions as $definition) {
            parent::push($definition);
            $this->fire(self::PUSH_EVENT, $definition);
        }

        return $this;
    }

    /**
     * @param int $size
     * @return CallStackInterface
     */
    public function pop(int $size = 1): CallStackInterface
    {
        for ($i = 0; $i < $size; ++$i) {
            $this->fire(self::POP_EVENT, $this->last());
        }

        return $this;
    }
}
