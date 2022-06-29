<?php

namespace Partymeister\Competitions\Services;

use Motor\Backend\Services\BaseService;
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

    /**
     * @param $request
     *
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
        foreach (AccessKey::get() as $key) {
            $key->delete();
        }

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

            // Check if the key is unique
            if (! in_array($key, $keys)) {

                // Save Access key in Database
                $accessKey = new AccessKey();
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
