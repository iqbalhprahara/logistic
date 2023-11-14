<?php

namespace App\Exports;

use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Border;
use OpenSpout\Common\Entity\Style\BorderPart;
use OpenSpout\Common\Entity\Style\Color;
use OpenSpout\Common\Entity\Style\Style;
use Spatie\SimpleExcel\SimpleExcelWriter;

abstract class BaseExport
{
    protected Style $headerStyle;

    protected Style $rowsStyle;

    protected Border $borderStyle;

    protected SimpleExcelWriter $writer;

    protected int $writtenRows = 0;

    public function __construct()
    {
        $this->borderStyle = $this->buildBorderStyle();
        $this->headerStyle = $this->buildHeaderStyle();
        $this->rowsStyle = $this->buildRowsStyle();
    }

    /**
     * Export then download awb import template
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse|string
     */
    public function download(string $filename = null)
    {
        return response()->streamDownload(fn () => $this->handleDownload($filename), $filename);
    }

    private function handleDownload($filename)
    {
        $this->writer = SimpleExcelWriter::streamDownload($filename)
            ->setHeaderStyle($this->headerStyle);

        $this->handle();

        $this->writer->close();
    }

    abstract public function handle();

    protected function addRow(Row|array $row, Style $style = null)
    {
        $this->writer->addRow($row, $style ?? $this->rowsStyle);
        $this->writtenRows++;

        if ($this->writtenRows % 1000 === 0) {
            flush(); // Flush the buffer every 1000 rows written
        }
    }

    protected function buildHeaderStyle(): Style
    {
        return (new Style)->setFontBold()->setBorder($this->borderStyle);
    }

    protected function buildRowsStyle(): Style
    {
        return (new Style)->setShouldWrapText()->setBorder(($this->borderStyle));
    }

    protected function buildBorderStyle()
    {
        return new Border(
            new BorderPart(Border::BOTTOM, Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID),
            new BorderPart(Border::LEFT, Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID),
            new BorderPart(Border::RIGHT, Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID),
            new BorderPart(Border::TOP, Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID)
        );
    }
}
