<?php

namespace Watchful\App\ChangedFiles;

if (!defined('ABSPATH')) {
    exit;
}

class FileChecker

{
    public function check_file($file)
    {
        $absolutePath = ABSPATH . $file;
        if ($absolutePath !== ABSPATH && !file_exists($absolutePath)) {
            return array(
                'file_path' => null,
                'size' => null,
                'm_date' => null,
                'hash' => null,
                'error' => true
            );
        }

        $fp = fopen($absolutePath, 'r');
        $fstat = fstat($fp);
        fclose($fp);
        $checksum = md5_file($absolutePath);

        return array(
            'file_path' => sanitize_textarea_field($file),
            'size' => $fstat['size'],
            'm_date' => $fstat['mtime'],
            'hash' => $checksum,
            'error' => false,
        );
    }
}
