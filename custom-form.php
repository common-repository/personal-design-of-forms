<?php
/*
Plugin Name: SOLASS Personal design of forms
Plugin URI: https://solass.com.ua/custom-form/
Description: Цей плагін замінює стандартні поля вибору на власно оформлені.
Version: 1.0.1
Author: Design Studio SOLASS
Author URI: https://solass.com.ua/
License: GPL2
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Вихід, якщо доступ до нього прямий
}
// --------------------- Глобальні параметри плагіну -------------------------- //
$plugin_version = '1.0.1';											// для використання на сторінках адміністратора
$plugin_file = plugin_basename(__FILE__);							// файл плагіна для довідки
define( 'CASTOM_FORM_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );	// визначте абсолютний шлях плагіна для включень
define( 'CASTOM_FORM_PLUGIN_URL', plugin_dir_url( __FILE__ ) );		// визначте URL-адресу плагіна для використання в черзі
$plugin_name = dirname(plugin_basename(__FILE__));					// Визначаємо ім'я плагіну
$plugins_name['custom-form'] = $plugin_name;

// --------------------- Головні параметри налаштування плагіну -------------------------- //
$custom_form_select_on = get_option('select-on', ''); 	  			// параметр увімкнення свого оформлення випадаючої форми
$custom_form_select_class = get_option('custom-select-class', '');  // параметр классу випадаючої форми
$custom_form_select_form_class = get_option('custom-select-form-class', '');  // перелік классів які мають бути у перероблених формах

$custom_form_select_class = "
/* Запускаємо код створення персонального виду випадаючою форми */ 
replaceSelectWithCustomSelect('".$custom_form_select_class."', '".$custom_form_select_form_class."'); ";

// -------------- Підключаємо файли стилів та скриптів плагіну --------------- //
function custom_form_enqueue_scripts() {
global $custom_form_select_on, $custom_form_select_class;
// Підключаємо стилі
  wp_enqueue_style( dirname(plugin_basename(__FILE__)), CASTOM_FORM_PLUGIN_URL . 'css/custom-form.css' ); 
// Підключаємо скрипти
if ( !empty($custom_form_select_on) && $custom_form_select_on === 'on' ) {
  wp_enqueue_script( dirname(plugin_basename(__FILE__)), CASTOM_FORM_PLUGIN_URL . 'js/custom-form.js', array( 'jquery' ), '1.0', true );

  wp_add_inline_script( dirname(plugin_basename(__FILE__)), $custom_form_select_class ); // Додаємо вставку скрипта у код сторінки після завантаженого файлу.
}
}
add_action( 'wp_enqueue_scripts', 'custom_form_enqueue_scripts' );

// -------------------- Підключення модулів плагіну ------------------------------ //
include( CASTOM_FORM_PLUGIN_PATH . 'admin/admin-page.php' );			// підключаємо файл виводу сторінки налаштувань плагіну
include( CASTOM_FORM_PLUGIN_PATH . 'css/page-style.php' );				// підключаємо файл виводу стилів в коді сторінок сайту
include( CASTOM_FORM_PLUGIN_PATH . 'functions/functions.php' );				// підключаємо файл функцій


// -------------------- Підключення модулів плагіну в залежності від налаштувань плагіну ------------- //
if ( !empty($custom_form_options['vasha_var']) && $custom_form_options['vasha_var'] === 'on' ) {
// Код підключень або налаштувань в залежності від параметрів плагіну

}

// --------------------- Додаємо файл стилів CSS та файл скриптів ДО АДМІНКИ ПЛАГІНУ WordPress ------------------//
$custom_form_admin_script = "
<script>
</script> ";
function castom_form_admin_style() {
global $custom_form_admin_script;
      wp_enqueue_script( 'wp-codemirror' ); // Загрузить библиотеку wp-codemirror
	  wp_enqueue_style('admin-styles', CASTOM_FORM_PLUGIN_URL . 'admin/admin_style.css'); 
	  wp_enqueue_script( 'admin-script', CASTOM_FORM_PLUGIN_URL . 'admin/admin_js.js', array( 'jquery', 'wp-theme-plugin-editor' ), '1.0.0',  true ); 

	  wp_add_inline_script( 'admin-script', $custom_form_admin_script ); // Додаємо скрипт у кінець сторінки адмінки
}
//if(file_exists(CASTOM_FORM_PLUGIN_URL . 'admin/admin_style.css')) {  }
add_action('admin_enqueue_scripts', 'castom_form_admin_style');


// Функція реєстрації файлів перекладів плагіну
if (!function_exists('custom_form_load_textdomain')) {
function custom_form_load_textdomain() {
	global $plugin_name, $plugins_name;
    load_plugin_textdomain(dirname(plugin_basename(__FILE__)), false, dirname(plugin_basename(__FILE__)) . '/languages/');
}
}
add_action('plugins_loaded', 'custom_form_load_textdomain');

// Підключаємо візуальну форму вибору кольору
wp_enqueue_style( 'wp-color-picker' );
wp_enqueue_script( 'wp-color-picker' );

// Прописуємо потрібні посилання у списку назв плагінів
if (!function_exists('custom_form_plugin_settings_link')) {
function custom_form_plugin_settings_link($links) {
	global $plugin_name, $plugins_name;
	$this_plugin_name = dirname(plugin_basename(__FILE__));
    $settings_link = '<a style="font-weight: bold;" href="'. esc_url( admin_url( "admin.php?page={$this_plugin_name}" ) ) . '">' . esc_html__( 'Налаштування', 'custom-form' ) . '</a>';
    array_unshift($links, $settings_link);
    return $links;
}
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'custom_form_plugin_settings_link');
