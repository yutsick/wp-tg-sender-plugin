<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Зберігає та повертає глобальні налаштування плагіна (токен, chat_id).
 */
class TGN_Settings {

    public static function get_token(): string {
        return (string) get_option( 'tgn_bot_token', '' );
    }

    public static function get_chat_id(): string {
        return (string) get_option( 'tgn_chat_id', '' );
    }

    public static function get_telegram(): TGN_Telegram {
        return new TGN_Telegram( self::get_token(), self::get_chat_id() );
    }

    public static function register(): void {
        register_setting( 'tgn_settings_group', 'tgn_bot_token' );
        register_setting( 'tgn_settings_group', 'tgn_chat_id' );
    }
}
