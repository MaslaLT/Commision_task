<?php

declare(strict_types=1);

namespace Masel\CommissionTask\Service;

class CsvParser
{
    const TYPE_CSV = 'csv';

    /**
     * @var array
     */
    private $parsedCsvRows;

    /**
     * @var string
     */
    private $fileName;

    public function __construct(string $fileName)
    {
        $this->fileName = $fileName;
        $this->parseCsvFile();
    }

    public function getParsedCsvRows(): array
    {
        return $this->parsedCsvRows;
    }

    private function parseCsvFile(): CsvParser
    {
        if ($this->isValidFileType()) {
            $this->readFile();

            return $this;
        }
        throw new \Exception('Wrong file type passed .csv Expected');
    }

    /**
     * @throws \Exception
     */
    private function isValidFileType(): bool
    {
        $ext = pathinfo($this->fileName, PATHINFO_EXTENSION);

        if ($this::TYPE_CSV === $ext) {
            return true;
        }

        return false;
    }

    private function readFile()
    {
        $handle = fopen($this->fileName, 'r');
        while (false !== ($raw_string = fgets($handle))) {
            $this->parsedCsvRows[] = str_getcsv($raw_string);
        }
    }
}
