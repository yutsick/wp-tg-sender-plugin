<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Завантажує всі інтеграції та запускає адмін.
 */
class TGN_Loader {

    public static function init(): void {
        TGN_Admin::init();
        self::load_integrations();
    }

    private static function load_integrations(): void {
        $integrations = [
            'cf7'       => 'class-tgn-integration-cf7.php',
            'wpforms'   => 'class-tgn-integration-wpforms.php',
            'gravity'   => 'class-tgn-integration-gravity.php',
            'elementor' => 'class-tgn-integration-elementor.php',
            'ninja'     => 'class-tgn-integration-ninja.php',
            'custom'    => 'class-tgn-integration-custom.php',
        ];

        foreach ( $integrations as $file ) {
            require_once TGN_PATH . 'includes/integrations/' . $file;
        }
    }
}
