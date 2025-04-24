<?php

namespace Partymeister\Competitions\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Partymeister\Competitions\Models\OptionGroup;
use Partymeister\Core\Models\User;

/**
 * Class AccountsTableSeeder
 */
class OptionGroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $optionGroups = [
            [
                'name' => 'Test compo',
                'type' => 'multiple',
                'options' => [
                    'Test 1',
                    'Test 2',
                    'Test 3',
                ],
            ],
            [
                'name' => 'Amiga',
                'type' => 'multiple',
                'options' => [
                    'A500 ECS',
                    'A1200 AGA',
                    '16:9',
                ],
            ],
            [
                'name' => 'PC',
                'type' => 'multiple',
                'options' => [
                    'Windows',
                    'Linux',
                    'Mac OS',
                    'Web',
                ],
            ],
            [
                'name' => 'Oldskool',
                'type' => 'multiple',
                'options' => [
                    'Commodore 64 Old SID',
                    'Commodore 64 New SID',
                    'NES',
                    'SNES',
                    'Gameboy',
                    'Gameboy Advanced',
                    'Gameboy Color',
                    'ZX Spectrum',
                    'Sega Master System',
                    'Sega Megadrive',
                    'Atari 8bit',
                ],
            ],
            [
                'name' => 'ASCII / ANSI',
                'type' => 'multiple',
                'options' => [
                    'PC ANSI',
                    'PC ASCII',
                    'Amiga ANSI',
                    'Amiga ASCII',
                    'PETSCII',
                ],
            ],
            [
                'name' => 'Photo',
                'type' => 'single',
                'options' => [
                    'Pure Photo',
                    'Retouched',
                    'Collage',
                ],
            ],
            [
                'name' => '256 Byte Intro',
                'type' => 'multiple',
                'options' => [
                    'Dosbox X',
                    'Dosbox',
                    'Freedos',
                ],
            ],
            [
                'name' => 'Oldskool Graphics',
                'type' => 'multiple',
                'options' => [
                    'Commodore 64',
                    'Commodore Plus4',
                    'Commodore VIC20',
                    'Amiga OCS',
                    'Sega Master System',
                    'Sega Megadrive',
                    'Atari 8bit',
                    'NES',
                    'SNES',
                    'Gameboy',
                    'Gameboy Advanced',
                    'Gameboy Color',
                    'ZX Spectrum',
                ],
            ],
        ];

        foreach ($optionGroups as $group) {
            DB::table('option_groups')->insert([
                'name' => $group['name'],
                'type' => $group['type'],
                'created_by' => User::get()->first()->id,
                'updated_by' => User::get()->first()->id,
            ]);

            $optionGroup = OptionGroup::where('name', $group['name'])->first();
            foreach ($group['options'] as $key => $option) {
                DB::table('options')->insert([
                    'name' => $option,
                    'sort_position' => $key,
                    'option_group_id' => $optionGroup->id,
                    'created_by' => User::get()->first()->id,
                    'updated_by' => User::get()->first()->id,
                ]);
            }
        }
    }
}
