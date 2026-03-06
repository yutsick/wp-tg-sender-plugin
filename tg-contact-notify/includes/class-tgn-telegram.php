<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Відповідає виключно за відправку повідомлень через Telegram Bot API.
 */
class TGN_Telegram {

    private string $token;
    private string $chat_id;

    public function __construct( string $token, string $chat_id ) {
        $this->token   = $token;
        $this->chat_id = $chat_id;
    }

    /**
     * Надіслати текстове повідомлення (Markdown).
     */
    public function send( string $text ): bool|\WP_Error {
        if ( empty( $this->token ) || empty( $this->chat_id ) ) {
            return new \WP_Error( 'tgn_config', 'Не вказано Bot Token або Chat ID.' );
        }

        $response = wp_remote_post(
            "https://api.telegram.org/bot{$this->token}/sendMessage",
            [
                'timeout' => 15,
                'body'    => [
                    'chat_id'                  => $this->chat_id,
                    'text'                     => $text,
                    'parse_mode'               => 'Markdown',
                    'disable_web_page_preview' => true,
                ],
            ]
        );

        if ( is_wp_error( $response ) ) {
            error_log( 'TGN Telegram error: ' . $response->get_error_message() );
            return $response;
        }

        $body = json_decode( wp_remote_retrieve_body( $response ), true );

        if ( empty( $body['ok'] ) ) {
            $msg = $body['description'] ?? 'Unknown Telegram API error';
            error_log( 'TGN Telegram API error: ' . $msg );
            return new \WP_Error( 'tgn_api', $msg );
        }

        return true;
    }

    /**
     * Відформатувати масив полів форми у Markdown-повідомлення.
     */
    public static function format_message( string $form_name, array $fields ): string {
        $lines = [
            "📬 *Нова заявка — {$form_name}*",
            "🌐 Сайт: " . get_bloginfo( 'name' ),
            "🔗 Сторінка: " . home_url( $_SERVER['REQUEST_URI'] ?? '/' ),
            "🕐 Час: " . current_time( 'd.m.Y H:i' ),
            "───────────────────",
        ];

        foreach ( $fields as $label => $value ) {
            $value = trim( is_array( $value ) ? implode( ', ', $value ) : (string) $value );
            if ( $value !== '' ) {
                $lines[] = "*{$label}:* {$value}";
            }
        }

        return implode( "\n", $lines );
    }
}
