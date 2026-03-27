<?php

use Partymeister\Competitions\Models\AccessKey;
use Partymeister\Competitions\Models\Competition;
use Partymeister\Competitions\Models\CompetitionPrize;
use Partymeister\Competitions\Models\CompetitionType;
use Partymeister\Competitions\Models\Entry;
use Partymeister\Competitions\Models\OptionGroup;
use Partymeister\Competitions\Models\Vote;
use Partymeister\Competitions\Models\VoteCategory;

/**
 * @param  int  $count
 * @return mixed
 */
function create_test_option_group($count = 1)
{
    return factory(OptionGroup::class, $count)->create();
}

/**
 * @param  int  $count
 * @return mixed
 */
function create_test_competition_type($count = 1)
{
    return factory(CompetitionType::class, $count)->create();
}

/**
 * @param  int  $count
 * @return mixed
 */
function create_test_competition($count = 1)
{
    return factory(Competition::class, $count)->create();
}

/**
 * @param  int  $count
 * @return mixed
 */
function create_test_vote_category($count = 1)
{
    return factory(VoteCategory::class, $count)->create();
}

/**
 * @param  int  $count
 * @return mixed
 */
function create_test_entry($count = 1)
{
    return factory(Entry::class, $count)->create();
}

/**
 * @param  int  $count
 * @return mixed
 */
function create_test_access_key($count = 1)
{
    return factory(AccessKey::class, $count)->create();
}

/**
 * @param  int  $count
 * @return mixed
 */
function create_test_competition_prize($count = 1)
{
    return factory(CompetitionPrize::class, $count)->create();
}

/**
 * @param  int  $count
 * @return mixed
 */
function create_test_vote($count = 1)
{
    return factory(Vote::class, $count)->create();
}
