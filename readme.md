# TG Contact Notify

WordPress-плагін, який надсилає дані з контактних форм у Telegram.

---

## Вимоги

- WordPress 6.0+
- PHP 8.0+

## Встановлення

1. Скопіюйте папку `tg-contact-notify/` у `/wp-content/plugins/`
2. Активуйте плагін у **Адмінка → Плагіни**
3. Перейдіть у **Налаштування → TG Contact Notify**
---------або встановіть через адмін-панель------------

## Налаштування

**1. Створіть бота** — напишіть [@BotFather](https://t.me/BotFather), команда `/newbot`, скопіюйте токен.

**2. Отримайте Chat ID** — напишіть [@userinfobot](https://t.me/userinfobot). Для каналу/групи — додайте бота як адміна, надішліть повідомлення, Chat ID з'явиться у відповіді `getUpdates`.

**3. Введіть дані** у налаштуваннях плагіна та натисніть **«Тестове повідомлення»**.

> 💡 Рекомендується використовувати **приватний канал**: додайте бота адміном, а всіх менеджерів — підписниками. Так не треба змінювати налаштування при ротації команди.

## Підтримувані форми

| Плагін | Підтримка |
|---|---|
| Contact Form 7 | ✅ |
| WPForms | ✅ |
| Gravity Forms | ✅ |
| Elementor Pro Forms | ✅ |
| Ninja Forms | ✅ |
| Довільна HTML-форма | ✅ via AJAX |

## Кастомні назви форм

У розділі **«Назви форм»** можна задати зручну назву для кожної форми — вона з'явиться в заголовку Telegram-повідомлення замість технічної назви.

## Довільна HTML-форма

Додайте до вашої `<form>`:

```html
<input type="hidden" name="action"      value="tgn_custom_form">
<input type="hidden" name="tgn_form_id" value="my-form">
```

`action` форми має вказувати на `<?php echo admin_url('admin-ajax.php'); ?>`.

## Структура плагіна

```
tg-contact-notify/
├── tg-contact-notify.php
└── includes/
    ├── class-tgn-telegram.php
    ├── class-tgn-settings.php
    ├── class-tgn-form-labels.php
    ├── class-tgn-admin.php
    ├── class-tgn-loader.php
    ├── views/
    │   └── admin-page.php
    └── integrations/
        ├── class-tgn-integration-cf7.php
        ├── class-tgn-integration-wpforms.php
        ├── class-tgn-integration-gravity.php
        ├── class-tgn-integration-elementor.php
        ├── class-tgn-integration-ninja.php
        └── class-tgn-integration-custom.php
```