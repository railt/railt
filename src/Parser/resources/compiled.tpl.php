<?='<?php'; ?>

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace <?=$namespace; ?>;

/**
 * This is generated file.
 * Do not update it manually.
 * Generated at <?=\date('d-m-Y H:i:s'); ?>

 */
class <?=$class; ?> extends <?=$base; ?>

{
    /**
     * The list of defined tokens.
     */
    private const TOKENS = <?=$this->value($tokens); ?>;

    public function __construct()
    {
        parent::__construct(
            self::TOKENS,
            [<?=$rules; ?>],
            <?=$this->value($pragmas);?>
        );

        <?=$extra;?>

    }
}
