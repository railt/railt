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
use Symfony\Component\EventDispatcher\Event;

/**
 * Class BaseFieldResolver
 */
abstract class BaseFieldResolver extends Event
{
    /**
     * @var InputInterface
     */
    private $input;

    /**
     * BaseFieldResolver constructor.
     * @param InputInterface $input
     */
    public function __construct(InputInterface $input)
    {
        $this->input = $input;
    }

    /**
     * @return InputInterface
     */
    public function getInput(): InputInterface
    {
        return $this->input;
    }
}
