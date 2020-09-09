<?php

declare(strict_types=1);

namespace Masel\CommissionTask\Service;

class CsvParser
{
    /**
     * Array of parsed csv file rows.
     * Every row is new array element.
     *
     * @var array
     */
    protected $parsedCsvRows;

    /**
     * Full name of file.
     *
     * @var string
     */
    protected $fileName;

    public function __construct(ScriptArgumentsManager $scriptArguments, string $argumentId)
    {
        $this->fileName = $scriptArguments->getArgument($argumentId);
        $this->parseCsvFile();
    }

    public function getParsedCsvRows(): array
    {
        return $this->parsedCsvRows;
    }

    private function parseCsvFile(): CsvParser
    {
        $this->validateFileType('csv');

        $handle = fopen($this->fileName, 'r');
        while (false !== ($raw_string = fgets($handle))) {
            $row = str_getcsv($raw_string);
            $this->parsedCsvRows[] = $row;
        }

        return $this;
    }

    private function validateFileType(string $fileType)
    {
        $ext = pathinfo($this->fileName, PATHINFO_EXTENSION);

        if ($ext !== $fileType) {
            throw new \Exception("Wrong file type passed. $fileType Expected");
        }
    }
}
