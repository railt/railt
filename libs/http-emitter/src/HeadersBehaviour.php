<?php

declare(strict_types=1);

namespace Railt\Http\Emitter;

enum HeadersBehaviour
{
    /**
     * Throw an exception in case of the headers has already been sent.
     */
    case ERROR;

    /**
     * Ignore sending headers if they have already been sent.
     */
    case SKIP;
}
