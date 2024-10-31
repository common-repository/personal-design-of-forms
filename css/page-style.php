<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Вихід, якщо доступ до нього прямий
}
// ---------------- Функція додавання параметру кольору у стилі сторінки -------------- //

function custom_form_enqueue_styles() {

// ------ !!! Якщо немає підключення файлу стилів плагіну, то не виведеться додатковий стиль !!! ------------ //
//  Підключається основний файл стилів плагіну перед виводом блоку стилю з ідентифікатором стилю - "custom-form"
//  Такий файл вже підключений у головному файлі плагіну - custom-form.php, тому тут пыдключення закоментоване.
/* 
  wp_enqueue_style(
    'custom-form', // Уникальный идентификатор стиля
    plugins_url( 'css/custom-form.css', __FILE__ ), // URL к файлу стилей
    array(), // Зависимости
    '1.0.1' // Версия стилей
  );
*/

  $custom_select_fone_color = get_option( 'custom-select-fone-color', '#ffffff' );
  $custom_select_border_color = get_option( 'custom-select-border-color', 'transparent' );
  $custom_select_border = (get_option( 'custom-select-border', '' )) ? get_option( 'custom-select-border', '' )."px" : "";
  $custom_select_padding = get_option( 'custom-select-padding', '' );
  $custom_select_text_color = get_option( 'custom-select-text-color', '' );
  if(!$custom_select_text_color) { $custom_select_text_color = "inherit"; }
  $custom_select_fone_active_color = get_option( 'custom-select-fone-active-color', '' );
  $custom_select_text_hover_color = get_option( 'custom-select-text-hover-color', '' );
  $custom_select_fone_hover_color = get_option( 'custom-select-fone-hover-color', '' );
  $custom_select_class = get_option( 'custom-select-class', '' );
  $custom_select_css = get_option( 'custom-select-css', '' );
	
// --------------------------------------------------------------

  $custom_styles = "
  .trigger {
	background: {$custom_select_fone_color}; 
	border: {$custom_select_border} solid {$custom_select_border_color};
	padding: {$custom_select_padding};
	color: {$custom_select_text_color};
	}
	.activetrigger {
	background: {$custom_select_fone_color}; 
	border: {$custom_select_border} solid {$custom_select_border_color};
	padding: {$custom_select_padding};
	color: {$custom_select_text_color};
	}
	.dropcontainer ul li {
	padding: {$custom_select_padding};
	}
	.dropcontainer ul {
	border: {$custom_select_border} solid {$custom_select_border_color};
	border-top: none;
	margin-top: -{$custom_select_border};
	}
	.dropcontainer a {
	color: {$custom_select_text_color};
	}
	.trigger:hover { /*	color: #777; background: #f5f5f5; */ 
	}
	.activetrigger:hover {
	background: {$custom_select_fone_active_color} !important;
/*	color: {$custom_select_text_hover_color}; */
	}
    .dropcontainer ul li:hover {
	background: {$custom_select_fone_hover_color} !important;
	outline: none;
	}
    .dropcontainer ul li:hover a {
	color: {$custom_select_text_hover_color};
	}
	.activetrigger:active {
	background: {$custom_select_fone_active_color} !important;
	}
	{$custom_select_css}
  ";
	
// --------------------------------------------------------------

  wp_add_inline_style( 'custom-form', $custom_styles ); //Вставляємо у код сторінки персональні стилі та налаштування плагіну 

}
add_action( 'wp_enqueue_scripts', 'custom_form_enqueue_styles' );
