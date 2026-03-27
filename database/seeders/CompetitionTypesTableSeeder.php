<?php

namespace Partymeister\Competitions\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Partymeister\Core\Models\User;

/**
 * Class AccountsTableSeeder
 */
class CompetitionTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $competitionTypes = [
            [
                'name' => 'Graphics',
                'has_platform' => false,
                'has_filesize' => false,
                'has_screenshot' => true,
                'has_audio' => false,
                'has_video' => false,
                'has_recordings' => false,
                'has_composer' => false,
                'has_running_time' => false,
                'is_anonymous' => false,
                'number_of_work_stages' => 4,
                'has_remote_entries' => false,
                'file_is_optional' => false,
                'has_config_file' => false,
            ],
            [
                'name' => 'Graphics Oldskool',
                'has_platform' => true,
                'has_filesize' => false,
                'has_screenshot' => true,
                'has_audio' => false,
                'has_video' => false,
                'has_recordings' => false,
                'has_composer' => false,
                'has_running_time' => false,
                'is_anonymous' => false,
                'number_of_work_stages' => 4,
                'has_remote_entries' => false,
                'file_is_optional' => false,
                'has_config_file' => false,
            ],
            [
                'name' => 'ASCII / ANSI',
                'has_platform' => false,
                'has_filesize' => false,
                'has_screenshot' => true,
                'has_audio' => false,
                'has_video' => false,
                'has_recordings' => false,
                'has_composer' => false,
                'has_running_time' => false,
                'is_anonymous' => false,
                'number_of_work_stages' => 0,
                'has_remote_entries' => false,
                'file_is_optional' => false,
                'has_config_file' => false,
            ],
            [
                'name' => 'Oldskool',
                'has_platform' => true,
                'has_filesize' => false,
                'has_screenshot' => true,
                'has_audio' => false,
                'has_video' => false,
                'has_recordings' => true,
                'has_composer' => true,
                'has_running_time' => true,
                'is_anonymous' => false,
                'number_of_work_stages' => 0,
                'has_remote_entries' => false,
                'file_is_optional' => true,
                'has_config_file' => false,
            ],
            [
                'name' => 'PC',
                'has_platform' => false,
                'has_filesize' => true,
                'has_screenshot' => true,
                'has_audio' => false,
                'has_video' => false,
                'has_recordings' => false,
                'has_composer' => true,
                'has_running_time' => true,
                'is_anonymous' => false,
                'number_of_work_stages' => 0,
                'has_remote_entries' => false,
                'file_is_optional' => false,
                'has_config_file' => false,
            ],
            [
                'name' => 'Music',
                'has_platform' => true,
                'has_filesize' => false,
                'has_screenshot' => false,
                'has_audio' => true,
                'has_video' => false,
                'has_recordings' => false,
                'has_composer' => true,
                'has_running_time' => false,
                'is_anonymous' => false,
                'number_of_work_stages' => 0,
                'has_remote_entries' => false,
                'file_is_optional' => false,
                'has_config_file' => false,
            ],
            [
                'name' => 'Other',
                'has_platform' => true,
                'has_filesize' => false,
                'has_screenshot' => true,
                'has_audio' => false,
                'has_video' => false,
                'has_recordings' => true,
                'has_composer' => true,
                'has_running_time' => true,
                'is_anonymous' => false,
                'number_of_work_stages' => 0,
                'has_remote_entries' => false,
                'file_is_optional' => true,
                'has_config_file' => false,
            ],
            [
                'name' => 'Amiga',
                'has_platform' => false,
                'has_filesize' => false,
                'has_screenshot' => true,
                'has_audio' => false,
                'has_video' => false,
                'has_recordings' => true,
                'has_composer' => true,
                'has_running_time' => false,
                'is_anonymous' => false,
                'number_of_work_stages' => 0,
                'has_remote_entries' => false,
                'file_is_optional' => true,
                'has_config_file' => false,
            ],
            [
                'name' => '256 Byte Intro',
                'has_platform' => true,
                'has_filesize' => true,
                'has_screenshot' => true,
                'has_audio' => false,
                'has_video' => false,
                'has_recordings' => false,
                'has_composer' => false,
                'has_running_time' => false,
                'is_anonymous' => false,
                'number_of_work_stages' => 0,
                'has_remote_entries' => false,
                'file_is_optional' => false,
                'has_config_file' => true,
            ],
        ];

        foreach ($competitionTypes as $competitionType) {
            DB::table('competition_types')->insert([
                'name' => $competitionType['name'],
                'has_platform' => $competitionType['has_platform'],
                'has_filesize' => $competitionType['has_filesize'],
                'has_screenshot' => $competitionType['has_screenshot'],
                'has_audio' => $competitionType['has_audio'],
                'has_video' => $competitionType['has_video'],
                'has_recordings' => $competitionType['has_recordings'],
                'has_composer' => $competitionType['has_composer'],
                'has_running_time' => $competitionType['has_running_time'],
                'is_anonymous' => $competitionType['is_anonymous'],
                'number_of_work_stages' => $competitionType['number_of_work_stages'],
                'has_remote_entries' => $competitionType['has_remote_entries'],
                'file_is_optional' => $competitionType['file_is_optional'],
                'has_config_file' => $competitionType['has_config_file'],
                'created_by' => User::get()->first()->id,
                'updated_by' => User::get()->first()->id,
            ]);
        }
    }
}
