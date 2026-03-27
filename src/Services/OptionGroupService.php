<?php

namespace Partymeister\Competitions\Services;

use Motor\Admin\Services\BaseService;
use Partymeister\Competitions\Models\OptionGroup;

/**
 * Class OptionGroupService
 */
class OptionGroupService extends BaseService
{
    protected string $model = OptionGroup::class;

    protected array $loadColumns = ['options'];

    public function afterUpdate(): void
    {
        $this->record->options()
            ->delete();
        $this->afterCreate();
    }

    public function afterCreate(): void
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
