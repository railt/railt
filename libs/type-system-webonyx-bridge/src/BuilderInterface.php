<?php

declare(strict_types=1);

namespace Railt\TypeSystem\Bridge\Webonyx;

/**
 * @template TInput of object
 * @template TOutput of mixed
 */
interface BuilderInterface
{
    /**
     * @param TInput $input
     * @return TOutput
     */
    public function build(object $input): mixed;
}
