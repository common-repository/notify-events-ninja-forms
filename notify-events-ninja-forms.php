<?php
/*
Plugin Name: Notify.Events - Ninja Forms
Plugin URI: https://notify.events/en/source/wordpress
Description: Fast and simplest way to integrate Ninja Forms plugin with more then 30 messengers and platforms including SMS, Voicecall, Facebook messenger, VK, Telegram, Viber, Slack and etc.
Author: Notify.Events
Author URI: https://notify.events/
Version: 1.0.0
License: GPL-2.0
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: notify-events-ninja-forms
Domain Path: /languages/
*/

require_once ABSPATH . 'wp-admin/includes/plugin.php';

use notify_events\modules\ninja_forms\models\NinjaForms;

const WPNE_NJF = 'notify-events-ninja-forms';

spl_autoload_register(function($class) {
    if (stripos($class, 'notify_events\\modules\\ninja_forms\\') !== 0) {
        return;
    }

    $class_file = __DIR__ . '/' . str_replace(['notify_events\\', '\\'], ['', '/'], $class . '.php');

    if (!file_exists($class_file)) {
        return;
    }

    require_once $class_file;
});

register_activation_hook(__FILE__, function() {
    if (!is_plugin_active('notify-events/notify-events.php') and current_user_can('activate_plugins')) {
        wp_die(__('Sorry, but this plugin requires the <a href="https://ru.wordpress.org/plugins/notify-events/" target="_blank">Notify.Events</a> plugin to be installed and active.<br><a href="' . admin_url( 'plugins.php' ) . '">&laquo; Return to Plugins</a>', WPNE_NJF), __('Plugin required', WPNE_NJF));
    }

    if (!is_plugin_active('ninja-forms/ninja-forms.php') and current_user_can('activate_plugins')) {
        wp_die(__('Sorry, but this plugin requires the <a href="https://ru.wordpress.org/plugins/ninja-forms/" target="_blank">Ninja Forms</a> plugin to be installed and active.<br><a href="' . admin_url( 'plugins.php' ) . '">&laquo; Return to Plugins</a>', WPNE_NJF), __('Plugin required', WPNE_NJF));
    }
});

add_action('plugins_loaded', function() {
    if (!is_plugin_active('notify-events/notify-events.php')) {
        deactivate_plugins('notify-events-ninja-forms/notify-events-ninja-forms.php');
        return;
    }

    if (!is_plugin_active('ninja-forms/ninja-forms.php')) {
        deactivate_plugins('notify-events-ninja-forms/notify-events-ninja-forms.php');
        return;
    }
});

add_action('wpne_module_init', function() {
    NinjaForms::register();
});
