<?php
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'ninja_forms_after_submission', function ( $form_data ) {
    $fields     = [];
    $skip_types = [ 'submit', 'html', 'hr', 'recaptcha' ];

    foreach ( $form_data['fields'] as $f ) {
        if ( in_array( $f['settings']['type'] ?? '', $skip_types ) ) continue;
        $fields[ $f['settings']['label'] ?? 'Поле ' . $f['id'] ] = $f['value'] ?? '';
    }

    $name = TGN_Form_Labels::get( 'nf_' . ( $form_data['form_id'] ?? 0 ), $form_data['settings']['title'] ?? 'Ninja Form' );
    TGN_Settings::get_telegram()->send( TGN_Telegram::format_message( $name, $fields ) );
} );
