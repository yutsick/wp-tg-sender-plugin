<?php
/**
 * Plugin Name: TG Contact Notify
 * Description: Надсилає дані контактних форм у Telegram.
 * Version:     2.0.0
 * Author:      Taras Yurchyshyn
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'TGN_VERSION', '2.0.0' );
define( 'TGN_PATH', plugin_dir_path( __FILE__ ) );

require_once TGN_PATH . 'includes/class-tgn-telegram.php';
require_once TGN_PATH . 'includes/class-tgn-settings.php';
require_once TGN_PATH . 'includes/class-tgn-form-labels.php';
require_once TGN_PATH . 'includes/class-tgn-admin.php';
require_once TGN_PATH . 'includes/class-tgn-loader.php';

TGN_Loader::init();
