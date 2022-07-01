<?php

namespace Partymeister\Competitions\Services;

use Motor\Backend\Services\BaseService;
use Partymeister\Competitions\Models\OptionGroup;

/**
 * Class OptionGroupService
 */
class OptionGroupService extends BaseService
{
    /**
     * @var string
     */
    protected $model = OptionGroup::class;

    public function afterUpdate()
    {
        $this->record->options()
                     ->delete();
        $this->afterCreate();
    }

    public function afterCreate()
    {
        $sortPosition = 0;
        foreach ($this->request->get('options', []) as $option) {
            if (trim($option['name']) != '') {
                $this->record->options()
                             ->create(['name' => $option['name'], 'sort_position' => $sortPosition++]);
            }
        }
    }
}
