<?php declare(strict_types=1);

namespace App\Behat;

use Exception;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\ExpressionLanguage\SyntaxError;

class Storage
{
    /**
     * @var array
     */
    private static $storage = [];

    /**
     * @param string $name
     * @param $value
     *
     * @throws Exception
     */
    public static function set(string $name, $value): void
    {
        self::$storage[$name] = $value;
    }

    /**
     * @param string $name
     *
     * @throws Exception
     */
    public static function unset(string $name): void
    {
        unset(self::$storage[$name]);
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public static function get(string $name)
    {
        $language = new ExpressionLanguage();

        if (array_key_exists($name, self::$storage)) {
            return self::$storage[$name];
        }

        try {
            return $language->evaluate($name, self::$storage);
        } catch (SyntaxError $e) {
            return null;
        }
    }

    /**
     * @param string $string
     *
     * @return string
     */
    public static function replaceVariables($string)
    {
        $pattern = '/%[^&]+.%/iU';

        preg_match_all($pattern, $string, $matches);

        if (count($matches) == 1) {
            foreach ($matches[0] as $match) {
                $variable = str_replace('%', '', $match);
                $string = str_replace($match, self::get($variable), $string);
            }
        }

        return $string;
    }

    public static function replaceVariablesRecursive(array $parameters)
    {
        foreach ($parameters as $key => $parameter) {
            if (is_array($parameter)) {
                $parameters[$key] = self::replaceVariablesRecursive($parameter);
            } else {
                if (is_null($parameter)) {
                    $parameters[$key] = $parameter;
                } else {
                    $parameters[$key] = self::replaceVariables((string) $parameter);
                }
            }
        }

        return $parameters;
    }

    /**
     * @return array
     */
    public static function getAll(): array
    {
        return self::$storage;
    }

    /**
     * @param array $data
     * @param string $prefix
     *
     * @throws Exception
     */
    public static function setData(array $data, string $prefix = ''): void
    {
        if ($prefix !== '') {
            $prefix = $prefix . '.';
        }

        foreach ($data as $key => $value) {
            //recursively append data
            if (is_array($value)) {
                self::setData($value, $prefix . $key);
            } else {
                self::set($prefix . $key, $value);
            }
        }
    }
}
