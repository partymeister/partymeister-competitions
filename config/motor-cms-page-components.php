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
            'view' => 'partymeister-competitions::frontend.components.voting-tw',
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
            'view' => 'partymeister-competitions::frontend.components.live-voting-tw',
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
            'view' => 'partymeister-competitions::frontend.components.competition-list-tw',
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
            'view' => 'partymeister-competitions::frontend.components.releases-tw',
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
            'view' => 'partymeister-competitions::frontend.components.entries-tw',
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
            'view' => 'partymeister-competitions::frontend.components.entry-details-tw',
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
            'view' => 'partymeister-competitions::frontend.components.entry-screenshots-tw',
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
            'view' => 'partymeister-competitions::frontend.components.entry-uploads-tw',
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
            'view' => 'partymeister-competitions::frontend.components.entry-comments-tw',
            'component_class' => 'Partymeister\Competitions\Components\ComponentEntryComments',
            'compatibility' => [

            ],
            'permissions' => [

            ],
            'group' => 'partymeister-competitions',
        ],
    ],
];
