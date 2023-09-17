<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\ClassConst\VarConstantCommentRector;
use Rector\CodingStyle\Rector\ClassMethod\UnSpreadOperatorRector;
use Rector\CodingStyle\Rector\FuncCall\ConsistentPregDelimiterRector;
use Rector\Config\RectorConfig;
use Rector\EarlyReturn\Rector\If_\ChangeOrIfReturnToEarlyReturnRector;
use Rector\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector;
use Rector\Php81\Rector\Property\ReadOnlyPropertyRector;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;

return static function (RectorConfig $config): void {
    $config->paths([__DIR__ . '/libs']);

    $config->sets([
        LevelSetList::UP_TO_PHP_81,
        SetList::TYPE_DECLARATION,
        SetList::CODE_QUALITY,
        SetList::CODING_STYLE,
        SetList::EARLY_RETURN,
    ]);

    $config->ruleWithConfiguration(ConsistentPregDelimiterRector::class, [
        ConsistentPregDelimiterRector::DELIMITER => '/',
    ]);

    $config->skip([
        //
        // Do not replace classic properties to promoted eq. These are
        // completely different statements.
        //
        ClassPropertyAssignToConstructorPromotionRector::class,

        //
        // Does not take into account already existing annotations and
        // can replace:
        //  - @var list<string>
        // into:
        //  - @var string[]
        //
        // What is not quite correct behavior.
        VarConstantCommentRector::class,

        //
        // This rector can break the Doctrine that replaces implementations
        // with proxies, like:
        //  - private Collection $relation;          // OK This can be replaced with a proxy
        //  + private readonly Collection $relation; // FAIL
        //
        ReadOnlyPropertyRector::class,

        //
        // Replaces expressions like a "if ($a || $b)" into 2 separate
        // expressions, which may not always be convenient and beautiful :3
        //
        ChangeOrIfReturnToEarlyReturnRector::class,

        //
        // This rector may break the code:
        //  - public function __construct(Some ...$arg) { ... } // OK
        //  + public function __construct(array $arg) { ... }   // BC FAIL
        //
        UnSpreadOperatorRector::class,

        // Totally pointless "improvements"
        Rector\CodingStyle\Rector\Catch_\CatchExceptionNameMatchingTypeRector::class,
        Rector\CodingStyle\Rector\Assign\SplitDoubleAssignRector::class,
        Rector\Php81\Rector\ClassConst\FinalizePublicClassConstantRector::class,
        Rector\CodeQuality\Rector\Identical\FlipTypeControlToUseExclusiveTypeRector::class,
        Rector\TypeDeclaration\Rector\ClassMethod\AddMethodCallBasedStrictParamTypeRector::class,
        Rector\EarlyReturn\Rector\Return_\ReturnBinaryOrToEarlyReturnRector::class
    ]);
};
