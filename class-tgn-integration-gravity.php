<?php
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'gform_after_submission', function ( $entry, $form ) {
    $fields = [];
    foreach ( $form['fields'] as $field ) {
        $value = rgar( $entry, (string) $field->id );
        if ( $value !== '' && $value !== null ) {
            $fields[ $field->label ] = $value;
        }
    }

    $name = TGN_Form_Labels::get( 'gf_' . $form['id'], $form['title'] );
    TGN_Settings::get_telegram()->send( TGN_Telegram::format_message( $name, $fields ) );
}, 10, 2 );
