<?php

namespace Watchful\App\ChangedFiles;

if (!defined('ABSPATH')) {
    exit;
}

class Settings
{
    private $options;

    private $file_checker;

    public function __construct()
    {
        $this->file_checker = new FileChecker();
    }

    public function init()
    {
        add_action('admin_menu', array($this, 'add_plugin_page'));
        add_action('admin_init', array($this, 'page_init'));
        add_action('wp', array($this, 'watchful_page_posted'));
    }

    public function add_plugin_page()
    {
        add_options_page(
            'Watchful App: Changed Files Settings',
            'Watchful App: Changed Files',
            'manage_options',
            'watchful-app-changed-files-settings',
            array($this, 'create_admin_page')
        );
    }

    public function create_admin_page()
    {
        $this->options = get_option('watchful_app_settings_changed_files');

        $activation_message = isset($_GET['activate']) ? sanitize_text_field($_GET['activate']) : '';
        ?>
        <div class="wrap">

            <?php
            if ( $activation_message ) {
                $this->print_activation_successful();
            }
            ?>

            <h2>Watchful App: Changed Files</h2>

            <br/><br/>
            <form method="post" action="options.php">
                <?php
                settings_fields('watchful_app_changed_files_settings_group');

                do_settings_sections('watchful-app-changed-files-settings');

                printf('<hr /> ');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    public function page_init()
    {
        register_setting(
            'watchful_app_changed_files_settings_group',
            'watchful_app_settings_changed_files', // Option name.
            array($this, 'sanitize')
        );

        add_settings_section(
            'watchful_app_changed_files_section', // ID.
            'Watchful App: Changed Files - Settings', // Title.
            array($this, 'print_section_info'), // Callback.
            'watchful-app-changed-files-settings' // Page.
        );

        for ($i = 1; $i <= Constants::$TOTAL_FILES; $i++) {
            $file_id = 'watchful_file_' . $i;
            add_settings_field(
                $file_id, // ID.
                'File path ' . $i, // Title.
                array($this, 'watchful_file_callback'), // Callback.
                'watchful-app-changed-files-settings', // Page.
                'watchful_app_changed_files_section', // Section.
                $file_id
            );
        }
    }

    public function sanitize($input)
    {
        $new_input = array();

        for ($i = 1; $i <= Constants::$TOTAL_FILES; $i++) {
            $id = 'watchful_file_' . $i;
            if (!empty($input[$id])) {
                $new_input[$id] = $this->file_checker->check_file($input[$id]);
            }
        }
        return $new_input;
    }

    public function print_section_info()
    {
        printf('Please enter below the path of the files you would like to be monitored by Watchful. The paths should be relative to your website root folder: <b>' . get_home_path() . ' </b><hr />', 'watchfulChangedFiles');
    }

    public function watchful_file_callback($file_id)
    {
        printf(
            '<input type="text" id="'.$file_id.'" name="watchful_app_settings_changed_files['.$file_id.']" class="regular-text full" style="width: 800px" value="%s" />',
            isset($this->options[$file_id]['file_path']) ? esc_attr($this->options[$file_id]['file_path']) : ''
        );

        printf('<small style="color: #ff0000">%s</small>', (isset($this->options[$file_id]['error']) && $this->options[$file_id]['error']) ? 'Provided file path does not exists' : '');
    }

    protected function print_activation_successful()
    {
        ?>
        <div class="updated notice">
            <p>The plugin has been activated</p>
        </div>
        <?php
    }

    public function watchful_page_posted()
    {

        $request_method = !empty($_SERVER['REQUEST_METHOD']) ? sanitize_text_field(wp_unslash($_SERVER['REQUEST_METHOD'])) : '';
        $current_page = !empty($_GET['page']) ? sanitize_text_field(wp_unslash($_GET['page'])) : '';

        if ('POST' !== strtoupper($request_method) && 'watchful-app-changed-files-settings' !== $current_page) {
            return;
        }

        $settings = get_option('watchful_app_settings_changed_files');

        for ($i = 1; $i <= Constants::$TOTAL_FILES; $i++) {
            $id = 'watchful_file_' . $i;
            $file_path = !empty($_POST[$id]) ? sanitize_text_field($_POST[$id]) : '';
            $settings[$id] = array('file_path' => $file_path);
        }

        update_option('watchful_app_settings_changed_files', $settings);
    }
}
