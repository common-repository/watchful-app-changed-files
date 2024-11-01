<?php

namespace Watchful\App\ChangedFiles;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Init {
	public static function activation() {

		if (  get_option( 'watchful_app_settings_changed_files' ) ) {
		    return;
        }

        $settings = [];

        for ($i = 1; $i <= Constants::$TOTAL_FILES; $i++)
        {
            $settings['watchful_file_'.$i]  = array('file_path' => '', 'm_date' => '', 'hash' => '' , 'error' => false);
        }

        update_option('watchful_app_settings_changed_files', $settings);

		add_option( 'watchful_changed_files_do_activation_redirect', true );
	}

	public static function uninstall() {
		delete_option( 'watchful_app_settings_changed_files' );
	}

    public static function admin_init() {
        if ( ! get_option( 'watchful_changed_files_do_activation_redirect', false ) ) {
            return;
        }
        delete_option( 'watchful_changed_files_do_activation_redirect' );

        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        wp_safe_redirect(
            add_query_arg(
                array(
                    'page'     => 'watchful-app-changed-files-settings',
                    'activate' => '1',
                ),
                admin_url( 'options-general.php' )
            )
        );
        exit;
    }
}
