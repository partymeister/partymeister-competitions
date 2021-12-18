<?php

namespace Partymeister\Competitions\Services;

use Motor\Backend\Services\BaseService;
use Partymeister\Competitions\Models\Competition;
use Partymeister\Competitions\Models\ManualVote;
use Partymeister\Competitions\Models\Vote;
use Request;

/**
 * Class VoteService
 *
 * @package Partymeister\Competitions\Services
 */
class VoteService extends BaseService
{
    /**
     * @var string
     */
    protected $model = Vote::class;

    /**
     * @param $request
     */
    public static function addVotes($request)
    {
        foreach ($request->get('entry') as $competitionId => $entries) {
            foreach ($entries as $entryId => $points) {
                if ((int) $points !== 0) {
                    $mv = new ManualVote();
                    $mv->competition_id = $competitionId;
                    $mv->entry_id = $entryId;
                    $mv->points = $points;
                    $mv->ip_address = Request::ip();
                    $mv->save();
                }
            }
        }
    }

    /**
     * @param string $direction
     * @return array
     */
    public static function getAllVotesByRank($direction = 'DESC')
    {
        $results = [];
        $maxPoints = [];
        foreach (Competition::where('has_prizegiving', true)
                            ->orderBy('prizegiving_sort_position', $direction)
                            ->get() as $competition) {
            $results[$competition->id] = [
                'id'          => $competition->id,
                'name'        => $competition->name,
                'has_comment' => isset($competition->vote_categories[0]) ? (bool) $competition->vote_categories[0]->has_comment : false,
                'entries'     => [],
            ];
            $maxPoints[$competition->id] = 0;
            foreach ($competition->entries()
                                 ->where('status', 1)
                                 ->get() as $entry) {
                $maxPoints[$competition->id] = max($entry->votes, $maxPoints[$competition->id]);
                $results[$competition->id]['entries'][$entry->id] = [
                    'id'                        => $entry->id,
                    'title'                     => $entry->title,
                    'author'                    => $entry->author,
                    'author_name'               => $entry->author_name,
                    'author_address'            => $entry->author_address,
                    'author_city'               => $entry->author_city,
                    'author_zip'                => $entry->author_zip,
                    'author_country_iso_3166_1' => $entry->author_country_iso_3166_1,
                    'author_email'              => $entry->author_email,
                    'author_phone'              => $entry->author_phone,

                    'points'   => $entry->votes,
                    'comments' => $entry->vote_comments,
                    'tie'      => false,
                ];
            }

            // Sort by points
            usort($results[$competition->id]['entries'], function ($item1, $item2) {
                return $item2['points'] <=> $item1['points'];
            });

            $uniquePoints = [];

            foreach ($results[$competition->id]['entries'] as $key => $entry) {

                if (! array_key_exists((string) $entry['points'], $uniquePoints)) {
                    $uniquePoints[$entry['points']] = 1;
                    $rank = array_sum($uniquePoints);
                } else {
                    $uniquePoints[$entry['points']]++;
                }

                $results[$competition->id]['entries'][$key]['max_points'] = $maxPoints[$competition->id];
                $results[$competition->id]['entries'][$key]['rank'] = $rank;

                // Identify ties
                if (isset($results[$competition->id]['entries'][$key - 1])) {
                    if ($results[$competition->id]['entries'][$key]['points'] == $results[$competition->id]['entries'][$key - 1]['points']) {
                        $results[$competition->id]['entries'][$key]['tie'] = true;
                        $results[$competition->id]['entries'][$key - 1]['tie'] = true;
                    }
                }
            }
        }

        return $results;
    }

    /**
     * @return array
     */
    public static function getAllSpecialVotesByRank()
    {
        $results = [];
        $maxPoints = 0;
        foreach (Competition::where('has_prizegiving', true)
                            ->orderBy('prizegiving_sort_position', 'DESC')
                            ->get() as $competition) {
            if (isset($competition->vote_categories[0]) && $competition->vote_categories[0]->has_special_vote) {
                foreach ($competition->entries()
                                     ->where('status', 1)
                                     ->get() as $entry) {
                    $specialVotes = (int) $entry->special_votes;
                    $maxPoints = max($specialVotes, $maxPoints);
                    if ($specialVotes > 0) {
                        $results[$entry->id] = [
                            'id'            => $entry->id,
                            'title'         => $entry->title,
                            'author'        => $entry->author,
                            'competition'   => $competition->name,
                            'special_votes' => (int) $specialVotes,
                            'points'        => (int) $specialVotes,
                            'tie'           => false,
                        ];
                    }
                }

                // Sort by points
                unset($results['entries']);
                usort($results, function ($item1, $item2) {
                    return $item2['special_votes'] <=> $item1['special_votes'];
                });

                foreach ($results as $key => $entry) {
                    $results[$key]['max_points'] = $maxPoints;
                    $results[$key]['rank'] = ($key + 1);

                    // Identify ties
                    if (isset($results[$key - 1])) {
                        if (! isset($results[$key]['points'])) {
                            continue;
                        }
                        if (! isset($results[$key - 1]['points'])) {
                            continue;
                        }
                        if ($results[$key]['points'] == $results[$key - 1]['points']) {
                            $results['entries'][$key]['tie'] = true;
                            $results['entries'][$key - 1]['tie'] = true;
                            $results['entries'][$key]['rank'] = ($key);
                        }
                    }
                }
            }
        }

        return $results;
    }
}
