<?php

namespace App\Generators;

use Illuminate\Support\Str;

class GeneratorUtils
{
    /**
     * Set input type by table field data type
     *
     * @param String $path
     * @return String
     */
    public static function getTemplate(string $path)
    {
        return file_get_contents(resource_path("/stubs/$path.stub"));
    }

    /**
     * Set input type by table field data type
     *
     * @param String $destination
     * @param String $template
     * @return Bool
     */
    public static function generateTemplate(string $destination, string $template)
    {
        return file_put_contents($destination, $template);
    }

    /**
     * Set input type by table field data type
     *
     * @param String $path
     * @return Bool
     */
    public static function checkFolder(string $path)
    {
        if (!file_exists($path)) {
            return mkdir($path, 0777, true);
        }

        return;
    }

    /**
     * Set input type by table field data type
     *
     * @param String $dataType
     * @return String
     */
    public static function setInputType(string $dataType)
    {
        if (
            Str::contains($dataType, 'integer') ||
            $dataType == 'float' ||
            $dataType == 'double' ||
            $dataType == 'decimal' ||
            $dataType == 'boolean'
        ) {
            return 'number';
        } elseif ($dataType == 'date') {
            return 'date';
        } elseif ($dataType == 'email') {
            return 'email';
        } elseif ($dataType == 'time') {
            return 'time';
        } elseif ($dataType == 'dateTime') {
            return 'datetime-local';
        } else {
            return 'text';
        }
    }

    /**
     * Convert string to plural pascal case
     *
     * @param String $string
     * @return String $string
     */
    public static function pluralPascalCase(string $string)
    {
    }
}
