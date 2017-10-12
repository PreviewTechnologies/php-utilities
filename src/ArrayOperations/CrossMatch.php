<?php

namespace Previewtechs\PHPUtilities\ArrayOperations;


class CrossMatch
{
    /**
     * @param $data
     * @param $required
     * @return bool
     */
    public static function requiredFieldCheck($data, $required)
    {
        foreach ($required as $key => $value) {
            if (!isset($data[$key])/* && $data[$key] === $value */) {
                return false;
            }
            if (is_array($data[$key]) && false === self::requiredFieldCheck($data[$key], $value)) {
                return false;
            }
        }
        return true;
    }
}