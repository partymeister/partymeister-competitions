<?php

return [
    'items' => [
        120 => [
            'slug'        => 'partymeister-competitions',
            'name'        => 'partymeister-competitions.global.competitions',
            'icon'        => 'trophy',
            'route'       => null,
            'roles'       => ['SuperAdmin'],
            'permissions' => ['competitions.read'],
            'items'       => [
                100 => [ // <-- !!! replace 170 with your own sort position !!!
                         'slug'        => 'competitions',
                         'name'        => 'partymeister-competitions.competitions.competitions',
                         'icon'        => 'fa fa-angle-right',
                         'route'       => 'backend.competitions.index',
                         'roles'       => ['SuperAdmin'],
                         'permissions' => ['competitions.read'],
                ],
                110 => [ // <-- !!! replace 867 with your own sort position !!!
                         'slug'        => 'entries',
                         'name'        => 'partymeister-competitions.entries.entries',
                         'icon'        => 'fa fa-angle-right',
                         'route'       => 'backend.entries.index',
                         'roles'       => ['SuperAdmin'],
                         'permissions' => ['entries.read'],
                ],
                120 => [ // <-- !!! replace 893 with your own sort position !!!
                         'slug'        => 'option_groups',
                         'name'        => 'partymeister-competitions.option_groups.option_groups',
                         'icon'        => 'fa fa-angle-right',
                         'route'       => 'backend.option_groups.index',
                         'roles'       => ['SuperAdmin'],
                         'permissions' => ['option_groups.read'],
                ],
                130 => [ // <-- !!! replace 318 with your own sort position !!!
                         'slug'        => 'competition_types',
                         'name'        => 'partymeister-competitions.competition_types.competition_types',
                         'icon'        => 'fa fa-angle-right',
                         'route'       => 'backend.competition_types.index',
                         'roles'       => ['SuperAdmin'],
                         'permissions' => ['competition_types.read'],
                ],
                140 => [ // <-- !!! replace 929 with your own sort position !!!
                         'slug'        => 'vote_categories',
                         'name'        => 'partymeister-competitions.vote_categories.vote_categories',
                         'icon'        => 'fa fa-angle-right',
                         'route'       => 'backend.vote_categories.index',
                         'roles'       => ['SuperAdmin'],
                         'permissions' => ['vote_categories.read'],
                ],
                150 => [ // <-- !!! replace 617 with your own sort position !!!
                         'slug'        => 'access_keys',
                         'name'        => 'partymeister-competitions.access_keys.access_keys',
                         'icon'        => 'fa fa-plus',
                         'route'       => 'backend.access_keys.index',
                         'roles'       => ['SuperAdmin'],
                         'permissions' => ['access_keys.read'],
                ],
                170 => [ // <-- !!! replace 992 with your own sort position !!!
                         'slug'        => 'votes',
                         'name'        => 'partymeister-competitions.votes.votes',
                         'icon'        => 'fa fa-plus',
                         'route'       => 'backend.votes.index',
                         'roles'       => ['SuperAdmin'],
                         'permissions' => ['votes.read'],
                         'aliases'     => ['backend.competition_prizes'],
                ],
            ],
        ],
    ],
];
