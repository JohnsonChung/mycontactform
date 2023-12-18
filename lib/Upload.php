<?php

namespace JQuest;

use JQuest\Models\Enquiry;

/**
 * @author Peter Chung <touhonoob@gmail.com>
 * @date Jul 29, 2015
 */
class Upload
{

    public static function check(Enquiry $enquiry, $returnFilepath = false)
    {
        $response = $enquiry->response()->first();
        if (!$response || !$response->upload_filename || strlen($response->upload_filename) < 1) {
            return false;
        }

        $filename = $enquiry->response->upload_filename;
        $filepath = static::getRealBase() . "/$filename";
        $filepath = realpath($filepath);

        if (!is_file($filepath)) {
            return false;
        }

        if (strpos($filepath, static::getRealBase()) !== 0) {
            return false;
        }

        if ($returnFilepath) {
            return $filepath;
        } else {
            return $filename;
        }
    }

    public static function getRealBase()
    {
        return realpath(DIR . '/assets/upload/');
    }
}
