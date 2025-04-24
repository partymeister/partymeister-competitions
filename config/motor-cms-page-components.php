<?php

return [
    'groups' => [
        'partymeister-competitions' => [
            'name' => 'Partymeister competitions',
        ],
    ],
    'components' => [
        'voting' => [
            'name' => 'Voting',
            'description' => 'Show Voting component',
            'view' => 'partymeister-competitions::frontend.components.voting',
            'route' => 'component.votings',
            'component_class' => 'Partymeister\Competitions\Components\ComponentVotings',
            'compatibility' => [

            ],
            'permissions' => [

            ],
            'group' => 'partymeister-competitions',
        ],
        'live-voting' => [
            'name' => 'LiveVoting',
            'description' => 'Show LiveVoting component',
            'view' => 'partymeister-competitions::frontend.components.live-voting',
            'component_class' => 'Partymeister\Competitions\Components\ComponentLiveVotings',
            'compatibility' => [

            ],
            'permissions' => [

            ],
            'group' => 'partymeister-competitions',
        ],
        'competition-list' => [
            'name' => 'CompetitionList',
            'description' => 'Show CompetitionList component',
            'view' => 'partymeister-competitions::frontend.components.competition-list',
            'component_class' => 'Partymeister\Competitions\Components\ComponentCompetitionLists',
            'compatibility' => [

            ],
            'permissions' => [

            ],
            'group' => 'partymeister-competitions',
        ],
        'releases' => [
            'name' => 'Releases',
            'description' => 'Show Release component',
            'view' => 'partymeister-competitions::frontend.components.releases',
            'component_class' => 'Partymeister\Competitions\Components\ComponentReleases',
            'compatibility' => [

            ],
            'permissions' => [

            ],
            'group' => 'partymeister-competitions',
        ],
        'entries' => [
            'name' => 'Entry',
            'description' => 'Show Entry component',
            'view' => 'partymeister-competitions::frontend.components.entries',
            'route' => 'component.entries',
            'component_class' => 'Partymeister\Competitions\Components\ComponentEntries',
            'compatibility' => [

            ],
            'permissions' => [

            ],
            'group' => 'partymeister-competitions',
        ],
        'entry-details' => [
            'name' => 'EntryDetail',
            'description' => 'Show EntryDetail component',
            'view' => 'partymeister-competitions::frontend.components.entry-details',
            'component_class' => 'Partymeister\Competitions\Components\ComponentEntryDetails',
            'compatibility' => [

            ],
            'permissions' => [

            ],
            'group' => 'partymeister-competitions',
        ],
        'entry-screenshots' => [
            'name' => 'EntryScreenshot',
            'description' => 'Show EntryScreenshot component',
            'view' => 'partymeister-competitions::frontend.components.entry-screenshots',
            'route' => 'component.entry-screenshots',
            'component_class' => 'Partymeister\Competitions\Components\ComponentEntryScreenshots',
            'compatibility' => [

            ],
            'permissions' => [

            ],
            'group' => 'partymeister-competitions',
        ],
        'entry-uploads' => [
            'name' => 'EntryUpload',
            'description' => 'Show EntryUpload component',
            'view' => 'partymeister-competitions::frontend.components.entry-uploads',
            'route' => 'component.entry-uploads',
            'component_class' => 'Partymeister\Competitions\Components\ComponentEntryUploads',
            'compatibility' => [

            ],
            'permissions' => [

            ],
            'group' => 'partymeister-competitions',
        ],
        'entry-comments' => [
            'name' => 'EntryComment',
            'description' => 'Show EntryComment component',
            'view' => 'partymeister-competitions::frontend.components.entry-comments',
            'component_class' => 'Partymeister\Competitions\Components\ComponentEntryComments',
            'compatibility' => [

            ],
            'permissions' => [

            ],
            'group' => 'partymeister-competitions',
        ],
    ],
];
