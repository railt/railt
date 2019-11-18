<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\Contracts\Http\Input;

/**
 * Interface ProvidesTypeInfoInterface
 */
interface ProvidesTypeInfoInterface
{
    /**
     * Returns a field's GraphQL type name.
     *
     * <code>
     *  //
     *  // schema { query: Character }
     *  // union SearchResult = Human | Droid | Starship
     *  //
     *
     *  hero {
     *      ... on Human {
     *          name
     *      }
     *      ... on Droid {
     *          name
     *      }
     *      ... on Starship {
     *          name
     *      }
     *  }
     *
     *  // ...execution...
     *
     *  // For "hero" execution:
     *  $input->type(); // "Character"
     *
     *  // For "hero { ... xxx { name } }" execution:
     *  $input->type(); // "SearchResult"
     * </code>
     *
     * @return string
     */
    public function type(): string;

    /**
     * Returns a list of types that are defined in
     * the GraphQL query.
     *
     * <code>
     *  //
     *  // schema { query: Character }
     *  // union SearchResult = Human | Droid | Starship
     *  //
     *
     *  hero {
     *      ... on Human {
     *          name
     *      }
     *      ... on Droid {
     *          name
     *      }
     *      ... on Starship {
     *          name
     *      }
     *  }
     *
     *  // ...execution...
     *
     *  // For "hero" execution:
     *  $input->desiredTypes(); // ["Character"]
     *
     *  // For "hero { ... xxx { name } }" execution:
     *  $input->desiredTypes(); // ["Human", "Droid", "Starship"]
     * </code>
     *
     * @return array|string[]
     */
    public function desiredTypes(): array;
}
