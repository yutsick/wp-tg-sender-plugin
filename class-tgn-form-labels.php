<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Керує кастомними назвами форм.
 * Ключ: унікальний ID форми (напр. "cf7_12"), значення: кастомна назва.
 */
class TGN_Form_Labels {

    /**
     * Повернути назву форми: кастомна якщо є, інакше — fallback.
     */
    public static function get( string $form_id, string $fallback ): string {
        $labels = get_option( 'tgn_form_labels', [] );
        $custom = $labels[ $form_id ] ?? '';
        return $custom !== '' ? $custom : $fallback;
    }

    /**
     * Зберегти масив [ form_id => label ] в БД.
     */
    public static function save( array $raw ): void {
        $labels = [];
        foreach ( $raw as $entry ) {
            $id    = sanitize_text_field( trim( $entry['form_id'] ?? '' ) );
            $label = sanitize_text_field( trim( $entry['label']   ?? '' ) );
            if ( $id !== '' && $label !== '' ) {
                $labels[ $id ] = $label;
            }
        }
        update_option( 'tgn_form_labels', $labels );
    }

    /**
     * Повернути всі збережені назви [ form_id => label ].
     */
    public static function all(): array {
        return (array) get_option( 'tgn_form_labels', [] );
    }

    /**
     * Зібрати всі форми з активних плагінів.
     * Повертає масив [ ['id' => ..., 'plugin' => ..., 'name' => ...], ... ]
     */
    public static function detect_forms(): array {
        $forms = [];

        // Contact Form 7
        if ( post_type_exists( 'wpcf7_contact_form' ) ) {
            $items = get_posts( [ 'post_type' => 'wpcf7_contact_form', 'numberposts' => -1, 'post_status' => 'publish' ] );
            foreach ( $items as $f ) {
                $forms[] = [ 'id' => 'cf7_' . $f->ID, 'plugin' => 'CF7', 'name' => $f->post_title ];
            }
        }

        // WPForms
        if ( function_exists( 'wpforms' ) ) {
            $items = wpforms()->form->get( '', [ 'fields' => 'ID, post_title' ] );
            if ( $items ) foreach ( $items as $f ) {
                $forms[] = [ 'id' => 'wpf_' . $f->ID, 'plugin' => 'WPForms', 'name' => $f->post_title ];
            }
        }

        // Gravity Forms
        if ( class_exists( 'GFAPI' ) ) {
            foreach ( GFAPI::get_forms() as $f ) {
                $forms[] = [ 'id' => 'gf_' . $f['id'], 'plugin' => 'Gravity Forms', 'name' => $f['title'] ];
            }
        }

        // Ninja Forms
        if ( class_exists( 'Ninja_Forms' ) ) {
            foreach ( Ninja_Forms()->form()->get_forms() as $f ) {
                $forms[] = [ 'id' => 'nf_' . $f->get_id(), 'plugin' => 'Ninja Forms', 'name' => $f->get_setting( 'title' ) ];
            }
        }

        return $forms;
    }
}
