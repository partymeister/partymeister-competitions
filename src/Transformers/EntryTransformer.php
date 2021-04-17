<?php

namespace Partymeister\Competitions\Transformers;

use League\Fractal;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Motor\Backend\Helpers\Filesize;
use Motor\Backend\Transformers\MediaTransformer;
use Partymeister\Competitions\Models\Entry;
use Symfony\Component\Intl\Countries;
use Symfony\Component\Intl\Intl;

/**
 * Class EntryTransformer
 * @package Partymeister\Competitions\Transformers
 */
class EntryTransformer extends Fractal\TransformerAbstract
{

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'competition',
        'files',
        'screenshot',
        'video',
        'audio',
        'work_stages',
        'config_file',
        'options'
    ];

    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        'competition',
        'files',
        'screenshot',
        'video',
        'audio',
        'work_stages',
        'config_file',
        'options'
    ];

    /**
     * @var array
     */
    private $validParams = [
        'base64',
    ];


    /**
     * Transform record to array
     *
     * @param Entry $record
     *
     * @return array
     */
    public function transform(Entry $record)
    {
        return [
            'id'                                          => (int) $record->id,
            'title'                                       => $record->title,
            'author'                                      => $record->author,
            'description'                                 => $record->description,
            'organizer_description'                       => $record->organizer_description,
            'filesize_bytes'                              => (int) $record->filesize,
            'filesize_human'                              => Filesize::bytesToHuman((int) $record->filesize),
            'platform'                                    => $record->platform,
            'running_time'                                => $record->running_time,
            'playlist_position'                           => $record->sort_position,
            'custom_option'                               => $record->custom_option,
            'ip_address'                                  => $record->ip_address,
            'allow_release'                               => (bool) $record->allow_release,
            'is_remote'                                   => (bool) $record->is_remote,
            'is_recorded'                                 => (bool) $record->is_recorded,
            'upload_enabled'                              => (bool) $record->upload_enabled,
            'is_prepared'                                 => (bool) $record->is_prepared,
            'sort_position'                               => (int) $record->sort_position,
            'sort_position_prefixed'                      => (strlen($record->sort_position) == 1 ? '0' . $record->sort_position : $record->sort_position),
            'competition_name'                            => $record->competition->name,
            'status'                                      => trans('partymeister-competitions::backend/entries.stati.' . $record->status),
            'status_value'                                => $record->status,
            'last_file_uploaded_at'                       => str_replace(' ', 'T', $record->last_file_uploaded_at),
            'author_name'                                 => $record->author_name,
            'author_email'                                => $record->author_email,
            'author_phone'                                => $record->author_phone,
            'author_address'                              => $record->author_address,
            'author_zip'                                  => $record->author_zip,
            'author_city'                                 => $record->author_city,
            'author_country_iso_3166_1'                   => $record->author_country_iso_3166_1,
            'author_country'                              => $record->author_country_iso_3166_1 == '' ? '' : Countries::getName($record->author_country_iso_3166_1),
            'discord_name'                                => $record->discord_name,
            'composer_name'                               => $record->composer_name,
            'composer_email'                              => $record->composer_email,
            'composer_phone'                              => $record->composer_phone,
            'composer_address'                            => $record->composer_address,
            'composer_zip'                                => $record->composer_zip,
            'composer_city'                               => $record->composer_city,
            'composer_country_iso_3166_1'                 => $record->composer_country_iso_3166_1,
            'composer_country'                            => $record->composer_country_iso_3166_1 == '' ? '' : Countries::getName($record->composer_country_iso_3166_1),
            'composer_not_member_of_copyright_collective' => (bool) $record->composer_not_member_of_copyright_collective,
//            'screenshot' => MediaHelper::getFileInformation($record, 'screenshot', true),
//            'audio' => MediaHelper::getFileInformation($record, 'audio', true),
        ];
    }


    /**
     * Include options
     *
     * @param Entry $record
     * @return Item
     */
    public function includeCompetition(Entry $record)
    {
        return $this->item($record->competition, new CompetitionTransformer());
    }


    /**
     * Include files
     *
     * @param Entry $record
     * @return Collection
     */
    public function includeFiles(Entry $record)
    {
        return $this->collection($record->getMedia('file')->reverse(), new MediaTransformer());
    }


    /**
     * Include options
     *
     * @param Entry $record
     * @return Collection
     */
    public function includeOptions(Entry $record)
    {
        return $this->collection($record->options, new OptionTransformer());
    }


    /**
     * Include work stages
     *
     * @param Entry $record
     * @return Collection
     */
    public function includeWorkStages(Entry $record)
    {
        $workStages = $record->media()
                             ->where('collection_name', 'LIKE', 'work_stage%')
                             ->orderBy('collection_name')
                             ->get();
        if ($workStages->count() > 0) {
            return $this->collection($workStages, new MediaTransformer());
        }
    }


    /**
     * Include screenshot
     *
     * @param Entry $record
     * @return Item
     */
    public function includeScreenshot(Entry $record)
    {
        $media = $record->getFirstMedia('screenshot');
        if (! is_null($media)) {
            return $this->item($media, new MediaTransformer());
        }
    }


    /**
     * Include config file
     *
     * @param Entry $record
     * @return Item
     */
    public function includeConfigFile(Entry $record)
    {
        $media = $record->getFirstMedia('config_file');
        if (! is_null($media)) {
            return $this->item($media, new MediaTransformer());
        }
    }


    /**
     * Include audio
     *
     * @param Entry $record
     * @return Item
     */
    public function includeAudio(Entry $record)
    {
        $media = $record->getFirstMedia('audio');
        if (! is_null($media)) {
            return $this->item($media, new MediaTransformer());
        }
    }


    /**
     * Include screenshot
     *
     * @param Entry $record
     * @return Item
     */
    public function includeVideo(Entry $record)
    {
        $media = $record->getFirstMedia('video');
        if (! is_null($media)) {
            return $this->item($media, new MediaTransformer());
        }
    }
}
