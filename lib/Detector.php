<?php

namespace Watchful\App\ChangedFiles;


if (!defined('ABSPATH')) {
    exit;
}

class Detector
{
    private $file_checker;

    public function __construct()
    {
        $this->file_checker = new FileChecker();
    }

    public function run_detector()
    {
        add_filter('watchful_app_alerts', [$this, 'get_watchful_app_changed_files_alerts']);
    }

    public function get_watchful_app_changed_files_alerts($alerts)
    {
        $filesSettings = get_option('watchful_app_settings_changed_files');

        if (!class_exists('Watchful\App\ChangedFiles\Alert')) {
            return $alerts;
        }

        foreach ($filesSettings as $key => $filesSetting) {
            if ($filesSetting['file_path'] === null || $this->file_checker->check_file($filesSetting['file_path'])['hash'] === $filesSetting['hash']) {
                continue;
            }

            $alerts[] = new Alert($filesSetting['file_path']);
            $filesSettings[$key]['hash'] = $this->file_checker->check_file($filesSetting['file_path'])['hash'];
        }

        update_option('watchful_app_settings_changed_files', $filesSettings);

        return $alerts;
    }
}
