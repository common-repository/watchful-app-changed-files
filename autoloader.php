<?php

function watchful_app_changed_files_class_loader( $class_name ) {
	$class_name  = ltrim( $class_name, '\\' );
	$file_name   = '';
	$namespace   = '';
	$last_ns_pos = strripos( $class_name, '\\' );
	if ( ! empty( $last_ns_pos ) ) {
		$namespace  = substr( $class_name, 0, $last_ns_pos );
		$class_name = substr( $class_name, $last_ns_pos + 1 );
	}

	$namespace = str_replace( 'Watchful\App\ChangedFiles', 'watchful-app-changed-files' . DIRECTORY_SEPARATOR . 'lib', $namespace );
	$namespace = str_replace( 'Watchful\App', 'watchful' . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'App', $namespace );
	$namespace = str_replace( '\\', DIRECTORY_SEPARATOR, $namespace );

	$file_name .= WP_PLUGIN_DIR
	. DIRECTORY_SEPARATOR . $namespace
	. DIRECTORY_SEPARATOR
	. $class_name
	. '.php';


    if ( file_exists( $file_name ) ) {
		require_once $file_name;
	}

}
