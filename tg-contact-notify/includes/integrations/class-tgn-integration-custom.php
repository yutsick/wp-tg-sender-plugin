<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Довільна HTML-форма через admin-ajax.
 *
 * Додайте до форми:
 *   <input type="hidden" name="action"      value="tgn_custom_form">
 *   <input type="hidden" name="tgn_form_id" value="my-form">   ← довільний slug
 */
add_action( 'wp_ajax_tgn_custom_form',        'tgn_handle_custom_form' );
add_action( 'wp_ajax_nopriv_tgn_custom_form', 'tgn_handle_custom_form' );

function tgn_handle_custom_form(): void {
    $skip    = [ 'action', 'nonce', '_wp_http_referer', 'tgn_form_id' ];
    $form_id = sanitize_text_field( $_POST['tgn_form_id'] ?? 'custom' );
    $fields  = [];

    foreach ( $_POST as $key => $value ) {
        if ( in_array( $key, $skip ) ) continue;
        $fields[ ucfirst( str_replace( ['-', '_'], ' ', sanitize_text_field( $key ) ) ) ] =
            sanitize_text_field( is_array( $value ) ? implode( ', ', $value ) : $value );
    }

    $fallback = ucfirst( str_replace( ['-', '_'], ' ', $form_id ) );
    $name     = TGN_Form_Labels::get( 'custom_' . $form_id, $fallback );

    TGN_Settings::get_telegram()->send( TGN_Telegram::format_message( $name, $fields ) );

    wp_send_json_success( [ 'message' => 'Форму надіслано!' ] );
}
