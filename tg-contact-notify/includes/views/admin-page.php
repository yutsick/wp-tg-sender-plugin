<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="wrap">
<h1>🤖 TG Contact Notify — Налаштування</h1>

<?php if ( $saved ) : ?>
    <div class="notice notice-success is-dismissible"><p>✅ Налаштування збережено!</p></div>
<?php endif; ?>

<?php if ( isset( $test_result ) ) : ?>
    <?php if ( is_wp_error( $test_result ) ) : ?>
        <div class="notice notice-error"><p>❌ <?php echo esc_html( $test_result->get_error_message() ); ?></p></div>
    <?php else : ?>
        <div class="notice notice-success"><p>✅ Тестове повідомлення надіслано!</p></div>
    <?php endif; ?>
<?php endif; ?>

<!-- ── Підключення ─────────────────────────────────────────────────── -->
<h2>🔌 Підключення</h2>
<form method="post" action="options.php">
    <?php settings_fields( 'tgn_settings_group' ); ?>
    <table class="form-table">
        <tr>
            <th><label for="tgn_bot_token">Bot Token</label></th>
            <td>
                <input type="text" id="tgn_bot_token" name="tgn_bot_token"
                       value="<?php echo esc_attr( TGN_Settings::get_token() ); ?>"
                       class="regular-text" placeholder="123456789:ABCdef..." />
                <p class="description">Отримайте токен у <a href="https://t.me/BotFather" target="_blank">@BotFather</a></p>
            </td>
        </tr>
        <tr>
            <th><label for="tgn_chat_id">Chat ID</label></th>
            <td>
                <input type="text" id="tgn_chat_id" name="tgn_chat_id"
                       value="<?php echo esc_attr( TGN_Settings::get_chat_id() ); ?>"
                       class="regular-text" placeholder="-100123456789 або 123456789" />
                <p class="description">
                    Особистий ID, група або канал.<br>
                    Дізнатись свій ID: <a href="https://t.me/userinfobot" target="_blank">@userinfobot</a>
                </p>
            </td>
        </tr>
    </table>
    <?php submit_button( 'Зберегти підключення' ); ?>
</form>

<hr>

<!-- ── Назви форм ─────────────────────────────────────────────────── -->
<h2>📋 Назви форм у повідомленнях</h2>
<p>Задайте зручну назву — саме вона з'явиться в заголовку Telegram-повідомлення.<br>
   Якщо поле порожнє — використовується оригінальна назва форми.</p>

<form method="post" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>">
    <?php wp_nonce_field( 'tgn_save_form_labels' ); ?>
    <input type="hidden" name="action" value="tgn_save_form_labels" />

    <table class="wp-list-table widefat fixed striped" id="tgn-table">
        <thead>
            <tr>
                <th style="width:110px">Плагін</th>
                <th>Оригінальна назва</th>
                <th style="width:120px">ID форми</th>
                <th>✏️ Назва в Telegram</th>
                <th style="width:36px"></th>
            </tr>
        </thead>
        <tbody id="tgn-body">

        <?php foreach ( $all_forms as $i => $form ) :
            $saved_label = $form_labels[ $form['id'] ] ?? ''; ?>
            <tr>
                <td><span class="tgn-pill"><?php echo esc_html( $form['plugin'] ); ?></span></td>
                <td><?php echo esc_html( $form['name'] ); ?></td>
                <td>
                    <code><?php echo esc_html( $form['id'] ); ?></code>
                    <input type="hidden" name="tgn_form_labels[<?php echo $i; ?>][form_id]"
                           value="<?php echo esc_attr( $form['id'] ); ?>" />
                </td>
                <td>
                    <input type="text" name="tgn_form_labels[<?php echo $i; ?>][label]"
                           value="<?php echo esc_attr( $saved_label ); ?>"
                           class="widefat" placeholder="<?php echo esc_attr( $form['name'] ); ?>" />
                </td>
                <td></td>
            </tr>
        <?php endforeach; ?>

        <?php foreach ( $form_labels as $fid => $flabel ) :
            if ( in_array( $fid, $auto_ids, true ) ) continue; ?>
            <tr class="tgn-manual">
                <td><span class="tgn-pill tgn-pill-gray">Вручну</span></td>
                <td>—</td>
                <td>
                    <input type="text" name="tgn_form_labels[<?php echo $manual_idx; ?>][form_id]"
                           value="<?php echo esc_attr( $fid ); ?>"
                           class="widefat" placeholder="cf7_12" />
                </td>
                <td>
                    <input type="text" name="tgn_form_labels[<?php echo $manual_idx; ?>][label]"
                           value="<?php echo esc_attr( $flabel ); ?>"
                           class="widefat" placeholder="Назва в Telegram" />
                </td>
                <td><button type="button" class="button-link tgn-del" title="Видалити">✕</button></td>
            </tr>
            <?php $manual_idx++; ?>
        <?php endforeach; ?>

        </tbody>
    </table>

    <p style="margin-top:10px">
        <button type="button" class="button" id="tgn-add">＋ Додати вручну</button>
        &nbsp;&nbsp;
        <?php submit_button( 'Зберегти назви', 'primary', 'submit', false ); ?>
    </p>
</form>

<?php if ( empty( $all_forms ) ) : ?>
    <div class="notice notice-info">
        <p>ℹ️ Форми не виявлено автоматично. Активуйте CF7 / WPForms / Gravity Forms / Ninja Forms або додайте форми вручну.</p>
    </div>
<?php endif; ?>

<hr>

<!-- ── Тест ───────────────────────────────────────────────────────── -->
<h2>🧪 Тест відправки</h2>
<form method="post">
    <?php wp_nonce_field( 'tgn_test_send' ); ?>
    <input type="hidden" name="tgn_test" value="1" />
    <?php submit_button( 'Надіслати тестове повідомлення', 'secondary' ); ?>
</form>

</div><!-- .wrap -->

<style>
.tgn-pill { display:inline-block; padding:2px 8px; border-radius:10px; font-size:11px; font-weight:600; background:#2271b1; color:#fff; }
.tgn-pill-gray { background:#8a8a8a; }
.tgn-del { color:#b32d2e !important; font-size:15px; line-height:1; }
#tgn-table input[type="text"] { margin:0; }
</style>

<script>
(function () {
    var idx = <?php echo (int) $manual_idx; ?>;

    function addDeleteHandler( btn ) {
        btn.addEventListener( 'click', function () {
            btn.closest( 'tr' ).remove();
        } );
    }

    document.getElementById( 'tgn-add' ).addEventListener( 'click', function () {
        var tr = document.createElement( 'tr' );
        tr.className = 'tgn-manual';
        tr.innerHTML =
            '<td><span class="tgn-pill tgn-pill-gray">Вручну</span></td>' +
            '<td>—</td>' +
            '<td><input type="text" name="tgn_form_labels[' + idx + '][form_id]" class="widefat" placeholder="cf7_12" /></td>' +
            '<td><input type="text" name="tgn_form_labels[' + idx + '][label]"   class="widefat" placeholder="Назва в Telegram" /></td>' +
            '<td><button type="button" class="button-link tgn-del" title="Видалити">✕</button></td>';
        addDeleteHandler( tr.querySelector( '.tgn-del' ) );
        document.getElementById( 'tgn-body' ).appendChild( tr );
        idx++;
    } );

    document.querySelectorAll( '.tgn-del' ).forEach( addDeleteHandler );
}());
</script>
