<?php

namespace Watchful\App\ChangedFiles;

use Watchful\App\Alert as WatchfulAlert;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (interface_exists('Watchful\App\Alert')) {
    class Alert implements WatchfulAlert
    {

        protected $file_path;

        public function __construct($file_path)
        {
            $this->file_path = $file_path;
        }

        public function getMessage()
        {
            return 'We detected a new file changed on the website: <br /><br /> ' . $this->file_path;
        }

        public function getLevel()
        {
            return self::LEVEL_INFO;
        }

        public function getParameter1()
        {
            return null;
        }

        public function getParameter2()
        {
            return null;
        }

        public function getParameter3()
        {
            return null;
        }
    }
}
