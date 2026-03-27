<?php

namespace Partymeister\Competitions\PDF;

use setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException;
use setasign\Fpdi\PdfParser\Filter\FilterException;
use setasign\Fpdi\PdfParser\PdfParserException;
use setasign\Fpdi\PdfParser\Type\PdfTypeException;
use setasign\Fpdi\PdfReader\PdfReaderException;
use setasign\Fpdi\Tcpdf\Fpdi;

/**
 * Class PDF
 */
class PDF extends Fpdi
{
    /**
     * @var array
     */
    protected $fpdi_templates = [];

    /**
     * @var bool
     */
    protected $StartY = false;

    protected $FontSpacing;

    /**
     * @var array
     */
    protected $FontStyles = [];

    /**
     * @param  string  $style
     * @param  string  $fontfile
     * @param  string  $subset
     * @return array
     */
    public function AddFont($family, $style = '', $fontfile = '', $subset = 'default')
    {
        if ($fontfile !== '') {
            $file = base_path().'/vendor/tcpdf/fonts/'.$fontfile.'.php';
            if (! is_file($file)) {
                $file = public_path().'/pdf/fonts/'.$fontfile.'.php';
            }
            if ($file) {
                $fontfile = $file;
            }
        }

        return parent::AddFont($family, $style, $fontfile, $subset);
    }

    /*
    public function SetFont($family, $style='', $size=0, $fontfile='', $spacing=FALSE)
    {
        if ($spacing > 0) {
            $this->FontSpacing = $spacing;
        }

        if (!empty($family)) {
            return parent::SetFont($family, $style, $size, $fontfile);
        }
    }
    */

    /**
     * @param  int  $spacing
     */
    public function SetFontSpacing($spacing = 0)
    {
        $this->FontSpacing = $spacing;
    }

    /**
     * @param  int  $spacing
     * @param  array  $color
     */
    public function AddStyle($name, $family, $style, $size, $spacing = 0, $color = [])
    {
        $this->FontStyles[$name] = [
            'family' => $family,
            'style' => $style,
            'size' => $size,
            'spacing' => $spacing,
            'color' => $color,
        ];
    }

    public function SetStyle($style)
    {
        $style = $this->FontStyles[$style];
        $this->SetFont($style['family'], $style['style'], $style['size']);
        if (isset($style['color']) && count($style['color']) == 3) {
            $this->SetTextColor($style['color'][0], $style['color'][1], $style['color'][2]);
        } else {
            $this->SetTextColor(0, 0, 0);
        }
        $this->FontSpacing = $style['spacing'];
    }

    /**
     * @param  int  $page
     *
     * @throws CrossReferenceException
     * @throws FilterException
     * @throws PdfParserException
     * @throws PdfTypeException
     * @throws PdfReaderException
     */
    public function SetTemplate($alias, $path, $page = 1)
    {
        $this->setSourceFile($path.'.pdf');
        $this->fpdi_templates[$alias] = $this->importPage($page);
    }

    /**
     * @param  mixed  $alias
     * @param  int  $x
     * @param  int  $y
     * @param  null  $width
     * @param  null  $height
     * @param  bool  $adjustPageSize
     * @return array|void
     */
    public function UseTemplate($alias, $x = 0, $y = 0, $width = null, $height = null, $adjustPageSize = false)
    {
        parent::UseTemplate($this->fpdi_templates[$alias], $x, $y, $width, $height, $adjustPageSize);
    }

    public function Header() {}

    public function Footer() {}

    /**
     * @param  array  $arguments
     * @param  bool  $page_break_method
     */
    public function RenderParagraph($render_method, $arguments = [], $page_break_method = false)
    {
        $pdf = clone $this;
        $pdf->SetCellPadding(0);
        $pdf->AddPage();
        $pdf->SetY(0);
        $pdf->$render_method($arguments);
        $y = $pdf->GetY();
        unset($pdf);

        if ($this->CheckPageBreak($y, $this->GetY(), false)) {
            $this->AddPage();
            if (isset($this->StartY)) {
                $this->SetY($this->StartY);
            }
            if (! $page_break_method) {
                $this->$render_method($arguments);
            } else {
                $this->$page_break_method();
                $this->$render_method($arguments);
            }
        } else {
            $this->$render_method($arguments);
        }
    }

    /**
     * @param  int  $h
     * @param  string  $txt
     * @param  int  $border
     * @param  int  $ln
     * @param  string  $align
     * @param  int  $fill
     * @param  string  $link
     * @param  int  $stretch
     * @param  bool  $ignore_min_height
     * @param  string  $calign
     * @param  string  $valign
     * @return string
     */
    protected function getCellCode(
        $w,
        $h = 0,
        $txt = '',
        $border = 0,
        $ln = 0,
        $align = '',
        $fill = 0,
        $link = '',
        $stretch = 0,
        $ignore_min_height = false,
        $calign = 'T',
        $valign = 'M'
    ) {
        $rs = '';
        if ($this->FontSpacing > 0) {
            $rs .= sprintf('BT %.2F Tc ET ', $this->FontSpacing);
        }

        return $rs.parent::getCellCode($w, $h, $txt, $border, $ln, $align, $fill, $link, $stretch, $ignore_min_height, $calign, $valign);
    }
}
