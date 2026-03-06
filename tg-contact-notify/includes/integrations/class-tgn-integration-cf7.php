<?php
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'wpcf7_mail_sent', function ( $cf7 ) {
    $submission = WPCF7_Submission::get_instance();
    if ( ! $submission ) return;

    $fields = [];
    foreach ( $submission->get_posted_data() as $key => $value ) {
        if ( str_starts_with( $key, '_' ) ) continue;
        $fields[ ucfirst( str_replace( ['-', '_'], ' ', $key ) ) ] =
            is_array( $value ) ? implode( ', ', $value ) : $value;
    }

    $name = TGN_Form_Labels::get( 'cf7_' . $cf7->id(), $cf7->title() );
    TGN_Settings::get_telegram()->send( TGN_Telegram::format_message( $name, $fields ) );
} );
