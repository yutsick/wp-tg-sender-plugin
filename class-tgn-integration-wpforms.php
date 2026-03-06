<?php
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'wpforms_process_complete', function ( $fields_data, $entry, $form_data ) {
    $fields = [];
    foreach ( $fields_data as $f ) {
        $fields[ $f['name'] ?? 'Поле ' . $f['id'] ] = $f['value'] ?? '';
    }

    $name = TGN_Form_Labels::get( 'wpf_' . $form_data['id'], $form_data['settings']['form_title'] ?? 'WPForms' );
    TGN_Settings::get_telegram()->send( TGN_Telegram::format_message( $name, $fields ) );
}, 10, 3 );
