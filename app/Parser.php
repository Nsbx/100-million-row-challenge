<?php

namespace App;

use Exception;

final class Parser
{
    public function parse(string $inputPath, string $outputPath): void
    {
        $stream = fopen($inputPath, 'rb');

        $results = [];

        while (($buffer = fgets($stream, 4096)) !== false) {
            $commaPos = strpos($buffer, ',');
            $url = substr($buffer, 19, $commaPos - 19);
            $date = substr($buffer, $commaPos + 1, 10);

            if (!isset($results[$url])) {
                $results[$url] = [];
            }

            if (!isset($results[$url][$date])) {
                $results[$url][$date] = 1;
                continue;
            }

            $results[$url][$date]++;
        }

        foreach ($results as $index => $result) {
            ksort($result);
            $results[$index] = $result;
        }

        $output = json_encode($results, JSON_PRETTY_PRINT);

        file_put_contents($outputPath, $output);
    }
}
