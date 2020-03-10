<?php declare(strict_types=1);

namespace App\Behat\Traits;

trait PlaceholderTrait
{
    /** @var array */
    protected $placeholders = [];

    /**
     * Sets place holder for replacement.
     *
     * you can specify placeholders, which will
     * be replaced in URL, request or response body.
     *
     * @param string $key token name
     * @param string $value replace value
     */
    protected function setPlaceholder(string $key, string $value)
    {
        $this->placeholders[$key] = $value;
    }

    /**
     * Replaces placeholders in provided text.
     *
     * @param string $string
     *
     * @return string
     */
    protected function replacePlaceholder(string $string): string
    {
        foreach ($this->placeholders as $key => $val) {
            $string = str_replace($key, $val, $string);
        }

        return $string;
    }
}
