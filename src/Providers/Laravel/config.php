<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Endpoint schema name
    |--------------------------------------------------------------------------
    |
    | ...
    |
    */
    'schema' => 'test',

    /*
    |--------------------------------------------------------------------------
    | Schema configs
    |--------------------------------------------------------------------------
    |
    | ...
    |
    */
    'test'  => [
        'queries' => [
            // 'users' => \App\GraphQL\Queries\UsersQuery::class
        ],
        'mutations' => [
            // 'users' => \App\GraphQL\Mutations\UsersMutation::class
        ]
    ]
];
