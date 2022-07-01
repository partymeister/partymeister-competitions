<?php

namespace Partymeister\Competitions\PDF;

use NumberToWords\NumberToWords;
use Partymeister\Competitions\Models\Entry;

/**
 * Class Prize
 */
class Prize extends PDF
{
    public function __construct()
    {
        parent::__construct();

        $this->AddStyle('Prizesheet Headline', 'Helvetica', 'B', 30, false, [
            64,
            64,
            64,
        ]);
        $this->AddStyle('Prizesheet Name', 'Helvetica', 'B', 12);
        $this->AddStyle('Prizesheet Text', 'Helvetica', 'R', 12);

        $this->AddStyle('Receipt Amount', 'Helvetica', 'B', 30);
        $this->AddStyle('Receipt Headline', 'Helvetica', 'R', 20);
        $this->AddStyle('Receipt Description', 'Helvetica', 'R', 10);
        $this->AddStyle('Receipt Text', 'Helvetica', 'R', 14);
    }

    /**
     * @param $name
     */
    public function renderCompetitionName($name)
    {
        $this->SetStyle('Prizesheet Headline');
        $this->MultiCell(0, 30, $name, 0, 'C');
    }

    /**
     * @param $entry
     * @param $prize
     */
    public function renderCompetitionRankings($entry, $prize)
    {
        $this->SetStyle('Prizesheet Name');
        $text = '#'.$prize->rank.' - '.$entry->title.' by '.$entry->author;
        $this->MultiCell(0, 5, $text, 0, 'L');

        $this->SetStyle('Prizesheet Text');
        if ($prize->amount > 0) {
            $text = PDFHelper::format_currency($prize->amount).' '.config('partymeister-competitions-receipt.currency')."\n".$prize->additional;
        } else {
            $text = $prize->additional;
        }

        $this->setX(27.5);
        $this->MultiCell(0, 5, $text, 0, 'L');

        $this->setY($this->getY() + 10);
    }

    /**
     * @param  bool  $entry
     * @param  bool  $prize
     */
    public function renderReceipt($entry = false, $prize = false)
    {
        // Descriptions
        $this->SetStyle('Receipt Headline');
        $this->setXY(17, 19);
        $this->MultiCell(0, 0, config('partymeister-competitions-receipt.localization.receipt'), 0, 'L');

        $this->SetStyle('Receipt Description');

        $this->setXY(147, 14);
        $this->MultiCell(0, 0, config('partymeister-competitions-receipt.currency'), 0, 'L');

        $this->setXY(15, 37);
        $this->MultiCell(0, 0, config('partymeister-competitions-receipt.localization.amount'), 0, 'L');

        $this->setXY(15, 62);
        $this->MultiCell(0, 0, config('partymeister-competitions-receipt.localization.to'), 0, 'L');

        $this->setXY(15, 84);
        $this->MultiCell(0, 0, config('partymeister-competitions-receipt.localization.from'), 0, 'L');

        $this->setXY(15, 105);
        $this->MultiCell(0, 0, config('partymeister-competitions-receipt.localization.for'), 0, 'L');

        $this->setXY(15, 123);
        $this->MultiCell(0, 0, config('partymeister-competitions-receipt.localization.thanks'), 0, 'R');

        $this->setXY(15, 133);
        $this->MultiCell(0, 0, config('partymeister-competitions-receipt.localization.signature'), 0, 'L');

        $this->SetStyle('Receipt Text');

        // Words
        if ($prize) {
            $this->setXY(15, 44);

            // create the number to words "manager" class
            $numberToWords = new NumberToWords();

            // build a new number transformer using the RFC 3066 language identifier
            $numberTransformer = $numberToWords->getNumberTransformer(config('partymeister-competitions-receipt.localize_number_words'));

            $text = $numberTransformer->toWords($prize->amount);

            $this->MultiCell(0, 5, $text, 0, 'L');
        }

        if ($entry) {
            $entry_info = Entry::find($entry->id);
        }

        // Receiver
        if ($entry && $entry_info) {
            $this->setXY(15, 68);
            $text = $entry_info->author_name.', '.$entry_info->author_address.', '.$entry_info->author_zip.' '.$entry_info->author_city.', '.$entry_info->author_country_iso_3166_1;
            $this->MultiCell(0, 5, $text, 0, 'L');
        }

        // Issuer
        $this->setXY(15, 90.5);
        $text = config('partymeister-competitions-receipt.issued_by');
        $this->MultiCell(0, 5, $text, 0, 'L');

        // For
        if ($entry && $entry_info) {
            $this->setXY(15, 111);
            $text = $prize->rank.'. '.config('partymeister-competitions-receipt.localization.rank').' '.$entry_info->competition->name.' Competition';
            $this->MultiCell(0, 5, $text, 0, 'L');
        }

        // Place, date
        $this->setXY(15, 140);
        $text = config('partymeister-competitions-receipt.issued_in').', '.date('d.m.Y');
        $this->MultiCell(0, 5, $text, 0, 'L');

        // Amount
        $this->SetStyle('Receipt Amount');

        if ($prize) {
            $this->setXY(0, 18);
            $text = PDFHelper::format_currency($prize->amount, 0).',-';
            $this->MultiCell(0, 5, $text, 0, 'R');
        }
    }
}
