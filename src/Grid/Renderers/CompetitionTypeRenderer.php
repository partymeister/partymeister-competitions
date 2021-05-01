<?php

namespace Partymeister\Competitions\Grid\Renderers;

use Illuminate\Support\Facades\App;

/**
 * Class CompetitionTypeRenderer
 *
 * @package Partymeister\Competitions\Grid\Renderers
 */
class CompetitionTypeRenderer
{
    /**
     * @var string
     */
    protected $value = '';

    /**
     * @var array
     */
    protected $options = [];

    /**
     * CompetitionTypeRenderer constructor.
     *
     * @param $value
     * @param $options
     */
    public function __construct($value, $options)
    {
        $this->value = $value;
        $this->options = $options;
    }

    /**
     * @return string
     */
    public function render()
    {
        if (! is_array($this->value)) {
            return '';
        }

        //$list = [];
        //foreach ($this->value as $property) {
        //    $list[] = trans('partymeister-competitions::backend/competition_types.'.$property);
        //}
        return App::make('html')
                  ->ul($this->value, ['class' => 'list-unstyled']);
    }
}
