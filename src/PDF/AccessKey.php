<?php

namespace Partymeister\Competitions\PDF;

/**
 * Class AccessKey
 *
 * @package Partymeister\Competitions\PDF
 */
class AccessKey extends PDF
{
    public function __construct()
    {
        parent::__construct();

        $this->setMargins(5, 5, 5);
        $this->AddStyle('Accesskey', 'Courier', 'B', 14);
        $this->setAutoPageBreak(false);
        $this->setTemplate('logo', resource_path('assets/pdf/partymeister-competitions-accesskey'));
    }

    public function generate()
    {
        $this->SetStyle('Accesskey');
        $this->addPage();
        foreach (\Partymeister\Competitions\Models\AccessKey::all() as $key => $row) {
            if ($key % 2 == 0) {
                $x_offset = 0;
            } else {
                $x_offset = 100;
            }
            $this->useTemplate('logo', 10 + $x_offset, $this->getY(), 30);
            $y = $this->getY();
            $this->multiCell(100, 20, $row->access_key, 0, 'L', 0, 1, $x_offset + 45, $y + 7);
            $this->setY($y);

            if ($key % 2 == 1) {
                $this->setY($this->getY() + 22);
                if ($this->getY() > 280) {
                    $this->SetStyle('Accesskey');
                    $this->addPage();
                }
            }
        }
    }
}
