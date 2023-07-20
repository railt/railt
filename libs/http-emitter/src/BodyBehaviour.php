<?php

declare(strict_types=1);

namespace Railt\Http\Emitter;

enum BodyBehaviour
{
    /**
     * Throw an exception if the body has already been sent.
     */
    case ERROR;

    /**
     * Ignore sending body if it has already been sent.
     */
    case SKIP;

    /**
     * Add content to the sent body.
     */
    case APPEND;
}
