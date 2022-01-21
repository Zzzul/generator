<?php

namespace App\Generators;

use Illuminate\Support\Str;

class GeneratorUtils
{
    /**
     * Get template/stub file
     *
     * @param String $path
     * @return String
     */
    public static function getTemplate(string $path)
    {
        return file_get_contents(resource_path("/stubs/generators/$path.stub"));
    }

    /**
     * Generate template to php file
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
     * Check folder if doesnt exist, then make folder
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
    public static function setInputType(string $dataType, string $field = null)
    {
        if (
            Str::contains($dataType, 'integer') ||
            $dataType == 'float' ||
            $dataType == 'double' ||
            $dataType == 'decimal' ||
            $dataType == 'boolean'
        ) {
            return 'number';
        } elseif ($field == 'email') {
            return 'email';
        } elseif ($field == 'password') {
            return 'password';
        } elseif ($dataType == 'date') {
            return 'date';
        } elseif ($dataType == 'time') {
            return 'time';
        } elseif ($dataType == 'dateTime') {
            return 'datetime-local';
        } else {
            return 'text';
        }
    }

    /**
     * Convert string to singular pascal case
     *
     * @param String $string
     * @return String
     */
    public static function singularPascalCase(string $string)
    {
        return trim(ucfirst(Str::camel(Str::singular($string))));
    }

    /**
     * Convert string to plural pascal case
     *
     * @param String $string
     * @return String
     */
    public static function pluralPascalCase(string $string)
    {
        return trim(ucfirst(Str::camel(Str::plural($string, 2))));
    }

    /**
     * Convert string to plural snake case
     *
     * @param String $string
     * @return String
     */
    public static function pluralSnakeCase(string $string)
    {
        return trim(strtolower(Str::snake(Str::plural($string, 2))));
    }

    /**
     * Convert string to singular snake case
     *
     * @param String $string
     * @return String
     */
    public static function singularSnakeCase(string $string)
    {
        return trim(strtolower(Str::snake(Str::singular($string))));
    }

    /**
     * Convert string to plural pascal case
     *
     * @param String $string
     * @return String
     */
    public static function pluralCamelCase(string $string)
    {
        return trim(Str::camel(Str::plural($string, 2)));
    }

    /**
     * Convert string to singular pascal case
     *
     * @param String $string
     * @return String
     */
    public static function singularCamelCase(string $string)
    {
        return trim(Str::camel(Str::singular($string)));
    }

    /**
     * Convert string to plural, kebab case, and lowercase
     *
     * @param String $string
     * @return String
     */
    public static function pluralKebabCase(string $string)
    {
        return trim(Str::kebab(Str::plural($string, 2)));
    }

    /**
     * Convert string to singular, remove special caracters, and lowercase
     *
     * @param String $string
     * @return String
     */
    public static function cleanSingularLowerCase(string $string)
    {
        return Str::singular(trim(preg_replace('/[^A-Za-z0-9() -]/', ' ', $string)));
    }

    /**
     * Convert string to plural, remove special caracters, and uppercase every first letters
     *
     * @param String $string
     * @return String
     */
    public static function cleanPluralUcWords(string $string)
    {
        return trim(ucwords(Str::plural(trim(preg_replace('/[^A-Za-z0-9() -]/', ' ', $string)), 2)));
    }

    /**
     * Convert string to singular, remove special caracters, and uppercase every first letters
     *
     * @param String $string
     * @return String
     */
    public static function cleanSingularUcWords(string $string)
    {
        return ucwords(Str::singular(trim(preg_replace('/[^A-Za-z0-9() -]/', ' ', $string))));
    }

    /**
     * Convert string to plural, remove special caracters, and lowercase
     *
     * @param String $string
     * @return String
     */
    public static function cleanPluralLowerCase(string $string)
    {
        return strtolower(Str::plural(trim(preg_replace('/[^A-Za-z0-9() -]/', ' ', $string)), 2));
    }
}
