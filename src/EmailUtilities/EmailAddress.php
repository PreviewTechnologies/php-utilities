<?php

namespace Previewtechs\PHPUtilities\EmailUtilities;


class EmailAddress
{
    /**
     * @param $email
     *
     * @return bool
     */
    public static function validate_email($email)
    {
        return (filter_var($email, FILTER_VALIDATE_EMAIL)) ? true : false;
    }

    /**
     * @param $fullEmailAddressIncludingName
     *
     * @return array
     */
    public static function splitParts($fullEmailAddressIncludingName)
    {
        $fullEmailAddressIncludingName .= " ";
        $sPattern = '/([\w\s\'\"]+[\s]+)?(<)?(([\w-\.]+)@((?:[\w]+\.)+)([a-zA-Z]{2,4}))?(>)?/';
        preg_match($sPattern, $fullEmailAddressIncludingName, $aMatch);
        $name = (isset($aMatch[1])) ? $aMatch[1] : null;
        $email = (isset($aMatch[3])) ? $aMatch[3] : null;
        return array('name' => $name ? trim($name) : null, 'email_address' => $email ? trim($email) : null);
    }

    /**
     * Email address array builder with name and email_address key
     *
     * @param $emailAddresses
     *
     * @return array
     */
    public static function mailAddressArrayBuilder($emailAddresses)
    {
        $result = [];

        if (sizeof($emailAddresses) > 0) {
            foreach ($emailAddresses as $item) {
                $splitPart = self::splitParts($item);
                if (!empty($splitPart['email_address'])) {
                    $email['email_address'] = $splitPart['email_address'];

                    if (!empty($splitPart['name'])) {
                        $email['name'] = $splitPart['name'];
                    }

                    $result[] = $email;
                    unset($splitPart, $email);
                }
            }
        }

        return $result;
    }
}