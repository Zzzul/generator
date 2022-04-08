<?php

namespace App\Generators;

use Illuminate\Support\Facades\Schema;

class GeneratorUtils
{
    /**
     * Get template/stub file.
     *
     * @param string $path
     * @return string
     */
    public static function getTemplate(string $path)
    {
        return file_get_contents(resource_path("/stubs/generators/$path.stub"));
    }

    /**
     * Check folder if doesnt exist, then make folder.
     *
     * @param string $path
     * @return mixed
     */
    public static function checkFolder(string $path)
    {
        if (!file_exists($path)) {
            return mkdir($path, 0777, true);
        }
    }

    /**
     * Convert string to singular pascal case.
     *
     * @param string $string
     * @return string
     */
    public static function singularPascalCase(string $string)
    {
        return trim(ucfirst(str($string)->singular()->camel()));
    }

    /**
     * Convert string to plural pascal case.
     *
     * @param string $string
     * @return string
     */
    public static function pluralPascalCase(string $string)
    {
        return trim(ucfirst(str($string)->plural()->camel()));
    }

    /**
     * Convert string to plural snake case.
     *
     * @param string $string
     * @return string
     */
    public static function pluralSnakeCase(string $string)
    {
        return trim(str($string)->plural()->snake()->lower());
    }

    /**
     * Convert string to singular snake case.
     *
     * @param string $string
     * @return string
     */
    public static function singularSnakeCase(string $string)
    {
        return trim(str($string)->singular()->snake()->lower());
    }

    /**
     * Convert string to plural pascal case.
     *
     * @param string $string
     * @return string
     */
    public static function pluralCamelCase(string $string)
    {
        return trim(str($string)->plural()->camel());
    }

    /**
     * Convert string to singular pascal case.
     *
     * @param string $string
     * @return string
     */
    public static function singularCamelCase(string $string)
    {
        return trim(str($string)->singular()->camel());
    }

    /**
     * Convert string to plural, kebab case, and lowercase.
     *
     * @param string $string
     * @return string
     */
    public static function pluralKebabCase(string $string)
    {
        return trim(str($string)->plural()->kebab());
    }

    /**
     * Convert string to singular, kebab case, and lowercase.
     *
     * @param string $string
     * @return string
     */
    public static function singularKebabCase(string $string)
    {
        return trim(str($string)->singular()->kebab());
    }

    /**
     * Convert string to singular, remove special caracters, and lowercase.
     *
     * @param string $string
     * @return string
     */
    public static function cleanSingularLowerCase(string $string)
    {
        return trim(str(preg_replace('/[^A-Za-z0-9() -]/', ' ', $string))->singular()->lower());
    }

    /**
     * Convert string to plural, remove special caracters, and uppercase every first letters.
     *
     * @param string $string
     * @return string
     */
    public static function cleanPluralUcWords(string $string)
    {
        return trim(ucwords(str(preg_replace('/[^A-Za-z0-9() -]/', ' ', $string))->plural()));
    }

    /**
     * Convert string to singular, remove special caracters, and uppercase every first letters.
     *
     * @param string $string
     * @return string
     */
    public static function cleanSingularUcWords(string $string)
    {
        return trim(ucwords(str(preg_replace('/[^A-Za-z0-9() -]/', ' ', $string))->singular()));
    }

    /**
     * Remove special caracters, and uppercase every first letters.
     *
     * @param string $string
     * @return string
     */
    public static function cleanUcWords(string $string)
    {
        return trim(ucwords(preg_replace('/[^A-Za-z0-9() -]/', ' ', $string)));
    }

    /**
     * Convert string to plural, remove special caracters, and lowercase.
     *
     * @param string $string
     * @return string
     */
    public static function cleanPluralLowerCase(string $string)
    {
        return trim(str(preg_replace('/[^A-Za-z0-9() -]/', ' ', $string))->plural()->lower());
    }

    /**
     * Get 1 column after id on the table.
     *
     * @param string $table
     * @return string $column
     */
    public static function getColumnAfterId(string $table)
    {
        $table = GeneratorUtils::pluralSnakeCase($table);
        $allColums = Schema::getColumnListing($table);

        if (sizeof($allColums) > 0) {
            $column = $allColums[1];
        } else {
            $column = "id";
        }

        return $column;
    }

    /**
     * Select id and column after id on the table.
     *
     * @param string $table
     * @return string $selectedField
     */
    public static function selectColumnAfterIdAndIdItself(string $table)
    {
        $table = GeneratorUtils::pluralSnakeCase($table);
        $allColums = Schema::getColumnListing($table);

        if (sizeof($allColums) > 0) {
            $selectedField = "id,$allColums[1]";
        } else {
            $selectedField = "id";
        }

        return $selectedField;
    }

    /**
     * Get model location/path if contains '/'.
     *
     * @param string $model
     * @return string $path
     */
    public static function getModelLocation(string $model)
    {
        $arrModel = explode('/', $model);
        $totalArrModel = count($arrModel);

        /**
         * will generate something like:
         * Main\Product
         */
        $path = "";
        for ($i = 0; $i < $totalArrModel - 1; $i++) {
            $path .= GeneratorUtils::pluralPascalCase($arrModel[$i]);
            if ($i + 1 != $totalArrModel - 1) {
                $path .= "\\";
            }
        }

        return $path;
    }

    /**
     * Set model name from the latest of array(if exists).
     *
     * @param string $model
     * @return string
     */
    public static function setModelName(string $model)
    {
        $arrModel = explode('/', $model);
        $totalArrModel = count($arrModel);

        /**
         * get the latest index value of array
         */
        return GeneratorUtils::singularPascalCase($arrModel[$totalArrModel - 1]);
    }

    /**
     * Check menu if active
     *
     * @param string|array $route
     * @return string
     */
    public static function isActiveMenu(string|array $route)
    {
        $activeClass = ' active';

        if (is_string($route)) {
            if (request()->is(substr($route . '*', 1))) {
                return $activeClass;
            }

            if (request()->is(str($route)->slug() . '*')) {
                return $activeClass;
            }

            if (request()->segment(2) == str($route)->before('/')) {
                return $activeClass;
            }

            if (request()->segment(3) == str($route)->after('/')) {
                return $activeClass;
            }

        }

        if (is_array($route)) {
            foreach ($route as $value) {
                $actualRoute = str($value)->remove('view ')->plural();

                if (request()->is(substr($actualRoute . '*', 1))) {
                    return $activeClass;
                }

                if (request()->is(str($actualRoute)->slug() . '*')) {
                    return $activeClass;
                }

                if (request()->segment(2) == $actualRoute) {
                    return $activeClass;
                }

                if (request()->segment(3) == $actualRoute) {
                    return $activeClass;
                }
            }
        }

        return '';
    }
}
