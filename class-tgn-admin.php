<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Вся логіка адмін-панелі: меню, рендер сторінки, обробка форм.
 */
class TGN_Admin {

    public static function init(): void {
        add_action( 'admin_menu',  [ __CLASS__, 'register_menu' ] );
        add_action( 'admin_init',  [ __CLASS__, 'register_settings' ] );
        add_action( 'admin_post_tgn_save_form_labels', [ __CLASS__, 'handle_save_labels' ] );
    }

    public static function register_menu(): void {
        add_options_page(
            'TG Contact Notify',
            'TG Contact Notify',
            'manage_options',
            'tgn-settings',
            [ __CLASS__, 'render_page' ]
        );
    }

    public static function register_settings(): void {
        TGN_Settings::register();
    }

    public static function handle_save_labels(): void {
        check_admin_referer( 'tgn_save_form_labels' );
        if ( ! current_user_can( 'manage_options' ) ) wp_die( 'Недостатньо прав' );

        TGN_Form_Labels::save( $_POST['tgn_form_labels'] ?? [] );

        wp_redirect( admin_url( 'options-general.php?page=tgn-settings&saved=1' ) );
        exit;
    }

    public static function render_page(): void {
        $saved       = isset( $_GET['saved'] );
        $all_forms   = TGN_Form_Labels::detect_forms();
        $form_labels = TGN_Form_Labels::all();
        $auto_ids    = array_column( $all_forms, 'id' );
        $manual_idx  = count( $all_forms );

        // Тест надсилання
        $test_result = null;
        if ( isset( $_POST['tgn_test'] ) && check_admin_referer( 'tgn_test_send' ) ) {
            $test_result = TGN_Settings::get_telegram()->send(
                "✅ *Тест успішний!*\n\nПлагін TG Contact Notify працює коректно.\nСайт: " . get_bloginfo( 'url' )
            );
        }

        include TGN_PATH . 'includes/views/admin-page.php';
    }
}
