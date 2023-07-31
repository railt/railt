<?php

declare(strict_types=1);

namespace Railt\SDL\Config;

enum Specification: string
{
    /**
     * Modern extended version of specification.
     */
    case RAILT = 'railt';

    /**
     * @link https://spec.graphql.org/draft/
     */
    case DRAFT = 'draft';

    /**
     * @link https://spec.graphql.org/October2021/
     */
    case OCTOBER_2021 = 'october-2021';

    /**
     * @link https://spec.graphql.org/June2018/
     *
     * @deprecated This version of the specification is outdated. Please use
     *             the {@see Specification::ACTUAL} version instead.
     */
    case JUNE_2018 = 'june-2018';

    /**
     * @link https://spec.graphql.org/October2016/
     *
     * @deprecated This version of the specification is outdated. Please use
     *             the {@see Specification::ACTUAL} version instead.
     */
    case OCTOBER_2016 = 'october-2016';

    /**
     * @link https://spec.graphql.org/April2016/
     *
     * @deprecated This version of the specification is outdated. Please use
     *             the {@see Specification::ACTUAL} version instead.
     */
    case APRIL_2016 = 'april-2016';

    /**
     * @link https://spec.graphql.org/October2015/
     *
     * @deprecated This version of the specification is outdated. Please use
     *             the {@see Specification::ACTUAL} version instead.
     */
    case OCTOBER_2015 = 'october-2015';

    /**
     * @link https://spec.graphql.org/July2015/
     *
     * @deprecated This version of the specification is outdated. Please use
     *             the {@see Specification::ACTUAL} version instead.
     */
    case JULY_2015 = 'july-2015';

    final public const ACTUAL = self::OCTOBER_2021;
    final public const DEFAULT = self::RAILT;

    /**
     * @psalm-suppress DeprecatedConstant : Allow deprecation relations
     */
    private function getPrevious(): ?self
    {
        return match ($this) {
            self::JULY_2015 => null,
            self::OCTOBER_2015 => self::JULY_2015,
            self::APRIL_2016 => self::OCTOBER_2015,
            self::OCTOBER_2016 => self::APRIL_2016,
            self::JUNE_2018 => self::OCTOBER_2016,
            self::OCTOBER_2021 => self::JUNE_2018,
            self::DRAFT => self::OCTOBER_2021,
            self::RAILT => self::ACTUAL,
            default => null,
        };
    }

    /**
     * @return iterable<self>
     */
    public function getDependencies(): iterable
    {
        $current = $this;

        do {
            yield $current;
        } while ($current = $current->getPrevious());
    }
}
