<?php

namespace Previewtechs\PHPUtilities;


use Previewtechs\PHPUtilities\Locale\ISOCountries;

/**
 * Class VisitorIP
 * @package Previewtechs\PHPUtilities
 */
class VisitorIP
{
    /**
     * @return array|null
     */
    public static function getIP()
    {
        if (array_key_exists('HTTP_CLIENT_IP', $_SERVER) && !empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        }

        if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }

        if (array_key_exists('HTTP_X_FORWARDED', $_SERVER) && !empty($_SERVER['HTTP_X_FORWARDED'])) {
            return $_SERVER['HTTP_X_FORWARDED'];
        }

        if (array_key_exists('HTTP_FORWARDED_FOR', $_SERVER) && !empty($_SERVER['HTTP_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_FORWARDED_FOR'];
        }

        if (array_key_exists('HTTP_FORWARDED', $_SERVER) && !empty($_SERVER['HTTP_FORWARDED'])) {
            return $_SERVER['HTTP_FORWARDED'];
        }

        if (array_key_exists('REMOTE_ADDR', $_SERVER) && !empty($_SERVER['REMOTE_ADDR'])) {
            return $_SERVER['REMOTE_ADDR'];
        }

        return null;
    }

    /**
     * @return array
     */
    public static function getLocation()
    {
        $country = null;
        $city = null;
        $region = null;
        $locationPoint = null;

        if (array_key_exists('HTTP_X_APPENGINE_COUNTRY', $_SERVER)) {
            $country = ISOCountries::getFullName($_SERVER['HTTP_X_APPENGINE_COUNTRY']);
        } elseif (function_exists('geoip_country_name_by_name')) {
            $country = geoip_country_name_by_name(self::getIP());
        }

        if (array_key_exists('HTTP_X_APPENGINE_CITY', $_SERVER)) {
            $city = $_SERVER['HTTP_X_APPENGINE_CITY'];
        } elseif (function_exists('geoip_region_by_name')) {
            $city = geoip_region_by_name(self::getIP());
        }

        if (array_key_exists('HTTP_X_APPENGINE_REGION', $_SERVER)) {
            $region = $_SERVER['HTTP_X_APPENGINE_REGION'];
        } elseif (function_exists('geoip_region_by_name')) {
            $region = geoip_region_by_name(self::getIP());
        }

        if (array_key_exists('HTTP_X_APPENGINE_CITYLATLONG', $_SERVER)) {
            $locationPoint = isset($_SERVER['HTTP_X_APPENGINE_CITYLATLONG']) ? explode(',',
                $_SERVER['HTTP_X_APPENGINE_CITYLATLONG']) : null;
        }

        $location = [
            'country' => isset($country) ? strtolower($country) : null,
            'city' => isset($city) ? strtolower($city) : null,
            'region' => isset($region) ? strtolower($region) : null,
            'geoPoint' => [
                'lat' => (is_array($locationPoint) ? floatval($locationPoint[0]) : null),
                'long' => (is_array($locationPoint) ? floatval($locationPoint[1]) : null)
            ]
        ];

        return $location;
    }
}