<?php

declare(strict_types=1);

namespace MyGarden\Views;

use MyGarden\Interfaces\ResponseInterface;
use MyGarden\Interfaces\ViewInterface;

class HtmlView implements ViewInterface
{

    /*
     * This class is not a legitimate attempt at creating an html view of MyGarden,
     * it is a quick exercise to experiment with the merits and success of the ViewInterface
     * in keeping the Controllers ignorant of how the data is being displayed
    */

    protected bool $headersSet = false;

    public function display(ResponseInterface $response): void
    {
        header('content-type: text/html');

        http_response_code($response->getCode());

        echo 'HTTP Status: ' . $response->getCode() . '<br><br>';

        echo '<style>
            body{font-family: monospace;}
            table{text-align: left;border-collapse: collapse;}
            td, th{border:1px solid #d2d2d2;}
        </style>';

        echo '<table>';

        $tdArray = [];

        $this->makeRow($response->getBody(), $tdArray);

        echo implode('', $tdArray);

        echo "</table>";
    }

    /**
     * @param  array<string, int|string>|array<array<string, int|string>> $array
     * @return bool
     */
    protected function isArrayOfArrays(array $array): bool
    {
        $item = current($array);

        if (is_array($item)) {
            return true;
        }
        return false;
    }

    /**
     * @param array<string, int|string>|array<array<string, int|string>> $multilevelArray
     * @param array<int, string>                                         $pushArray
     */
    protected function makeRow(array $multilevelArray, array &$pushArray): void
    {
        if ($this->isArrayOfArrays($multilevelArray)) {
            foreach ($multilevelArray as $array) {
                $this->makeRow($array, $pushArray);
            }
        } else {
            if ($this->headersSet === false) {
                $pushArray[] = '<tr>';
                foreach (array_keys($multilevelArray) as $key) {
                    $pushArray[] = '<th>' . $key . '</th>';
                }
                $pushArray[] = '</tr>';
                $this->headersSet = true;
            }
            $pushArray[] = '<tr>';
            foreach ($multilevelArray as $cell) {
                $pushArray[] = '<td>' . $cell . '</td>';
            }
            $pushArray[] = '</tr>';
        }
    }
}
