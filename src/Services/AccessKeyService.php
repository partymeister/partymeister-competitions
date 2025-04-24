<?php

namespace Partymeister\Competitions\Services;

use Motor\Backend\Services\BaseService;
use Motor\Core\Filter\Renderers\SelectRenderer;
use Partymeister\Competitions\Models\AccessKey;

/**
 * Class AccessKeyService
 */
class AccessKeyService extends BaseService
{
    /**
     * @var string
     */
    protected $model = AccessKey::class;

    public function filters()
    {
        $this->filter->add(new SelectRenderer('is_remote'))
            ->setOptionPrefix(trans('partymeister-competitions::backend/access_keys.is_remote'))
            ->setEmptyOption('-- '.trans('partymeister-competitions::backend/access_keys.is_remote').' --')
            ->setOptions([
                1 => trans('motor-backend::backend/global.yes'),
                0 => trans('motor-backend::backend/global.no'),
            ]);

        $this->filter->add(new SelectRenderer('is_satellite'))
            ->setOptionPrefix(trans('partymeister-competitions::backend/access_keys.is_satellite'))
            ->setEmptyOption('-- '.trans('partymeister-competitions::backend/access_keys.is_satellite').' --')
            ->setOptions([
                1 => trans('motor-backend::backend/global.yes'),
                0 => trans('motor-backend::backend/global.no'),
            ]);

        $this->filter->add(new SelectRenderer('is_prepaid'))
            ->setOptionPrefix(trans('partymeister-competitions::backend/access_keys.is_prepaid'))
            ->setEmptyOption('-- '.trans('partymeister-competitions::backend/access_keys.is_prepaid').' --')
            ->setOptions([
                1 => trans('motor-backend::backend/global.yes'),
                0 => trans('motor-backend::backend/global.no'),
            ]);
    }

    /**
     * @throws \Exception
     */
    public static function generate($request)
    {
        $quantity = (int) $request->get('quantity');

        // Chars to use
        $chars = config('partymeister-competitions-access-key.chars');
        $length = config('partymeister-competitions-access-key.length');
        $divideEvery = config('partymeister-competitions-access-key.divide_every');
        $divider = config('partymeister-competitions-access-key.divider');

        // Initialize the array to check for unique keys
        $keys = [];

        // Delete existing access keys
        AccessKey::whereNull('visitor_id')
            ->where('is_prepaid', false)
            ->delete();

        // Generate keys until the given amount of unique keys has been generated
        while ($quantity > 0) {
            $key = '';

            // Add a new character to the key or add a divider after a certain amount of characters
            for ($position = 0; $position < $length; $position++) {
                if ($position > 0 && $position % $divideEvery == 0) {
                    $key .= $divider;
                }
                $key .= $chars[rand(0, count($chars) - 1)];
            }

            // check if code exists in the database
            if (AccessKey::where('access_key', $key)
                ->exists()) {
                continue;
            }

            // Check if the key is unique
            if (! in_array($key, $keys)) {

                // Save Access key in Database
                $accessKey = new AccessKey;
                $accessKey->access_key = $key;
                $accessKey->save();

                // Add key to the check array
                $keys[] = $key;

                // Decrease number of keys to be generated
                $quantity--;
            }
        }
    }
}
