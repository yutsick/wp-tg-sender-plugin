<?php
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'elementor_pro/forms/new_record', function ( $record, $handler ) {
    $fields = [];
    foreach ( $record->get( 'fields' ) as $f ) {
        $fields[ $f['title'] ?: $f['id'] ] = $f['value'];
    }

    $form_id  = $record->get_form_settings( 'id' ) ?: 'elementor';
    $fallback = $record->get_form_settings( 'form_name' ) ?: 'Elementor Form';
    $name     = TGN_Form_Labels::get( 'el_' . $form_id, $fallback );

    TGN_Settings::get_telegram()->send( TGN_Telegram::format_message( $name, $fields ) );
}, 10, 2 );
