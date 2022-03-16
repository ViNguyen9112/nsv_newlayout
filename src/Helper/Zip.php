<?php

namespace App\Helper;

use ZipArchive;

class Zip
{
    public static function createZip($files = [], $destination = '', $overwrite = true)
    {
        if (file_exists($destination) && !$overwrite) {
            return false;
        }
        $valid_files = [];
        if (is_array($files)) {
            foreach ($files as $file) {
                if (file_exists($file)) {
                    $file = str_replace(WWW_ROOT, '', $file);
                    $valid_files[] = $file;
                }
            }
        }

        if (count($valid_files)) {
            $zip = new ZipArchive();
            if (file_exists($destination)) {
                $zip->open($destination, ZIPARCHIVE::OVERWRITE);
            }
            else {
                $zip->open($destination, ZIPARCHIVE::CREATE);
            }

            foreach ($valid_files as $file) {
                $zip->addFile($file, $file);
            }

            $zip->close();

            return file_exists($destination);
        }
        else {
            return false;
        }
    }

    public static function extractTo($zip_file, $pathto, $files = null) {
        $zip = new ZipArchive;
        $res = $zip->open($zip_file);
        if ($res === TRUE) {
            $zip->extractTo($pathto, $files);
            $zip->close();
            return true;
        } else {
            return false;
        }
    }
}
