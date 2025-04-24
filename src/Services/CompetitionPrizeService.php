<?php

namespace Partymeister\Competitions\Services;

use Illuminate\Support\Arr;
use Motor\Backend\Services\BaseService;
use Partymeister\Competitions\Models\Competition;
use Partymeister\Competitions\Models\CompetitionPrize;

/**
 * Class CompetitionPrizeService
 */
class CompetitionPrizeService extends BaseService
{
    /**
     * @var string
     */
    protected $model = CompetitionPrize::class;

    /**
     * @throws \Exception
     */
    public static function createOrUpdatePrizes($request)
    {
        $competitions = Competition::where('has_prizegiving', true)
            ->orderBy('prizegiving_sort_position', 'ASC')
            ->get();

        foreach (CompetitionPrize::get() as $prize) {
            $prize->delete();
        }

        foreach ($competitions as $competition) {
            for ($i = 1; $i <= 3; $i++) {
                $p = new CompetitionPrize;
                $p->competition_id = $competition->id;
                $p->rank = $i;
                $p->amount = Arr::get($request->all(), 'amount.'.$competition->id.'.'.$i);
                $p->additional = Arr::get($request->all(), 'additional.'.$competition->id.'.'.$i);
                $p->save();
            }
        }
    }
}
