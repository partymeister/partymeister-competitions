<?php

/**
 * @param  int  $count
 * @return mixed
 */
function create_test_option_group($count = 1)
{
    return factory(Partymeister\Competitions\Models\OptionGroup::class, $count)->create();
}

/**
 * @param  int  $count
 * @return mixed
 */
function create_test_competition_type($count = 1)
{
    return factory(Partymeister\Competitions\Models\CompetitionType::class, $count)->create();
}

/**
 * @param  int  $count
 * @return mixed
 */
function create_test_competition($count = 1)
{
    return factory(Partymeister\Competitions\Models\Competition::class, $count)->create();
}

/**
 * @param  int  $count
 * @return mixed
 */
function create_test_vote_category($count = 1)
{
    return factory(Partymeister\Competitions\Models\VoteCategory::class, $count)->create();
}

/**
 * @param  int  $count
 * @return mixed
 */
function create_test_entry($count = 1)
{
    return factory(Partymeister\Competitions\Models\Entry::class, $count)->create();
}

/**
 * @param  int  $count
 * @return mixed
 */
function create_test_access_key($count = 1)
{
    return factory(Partymeister\Competitions\Models\AccessKey::class, $count)->create();
}

/**
 * @param  int  $count
 * @return mixed
 */
function create_test_competition_prize($count = 1)
{
    return factory(Partymeister\Competitions\Models\CompetitionPrize::class, $count)->create();
}

/**
 * @param  int  $count
 * @return mixed
 */
function create_test_vote($count = 1)
{
    return factory(Partymeister\Competitions\Models\Vote::class, $count)->create();
}
