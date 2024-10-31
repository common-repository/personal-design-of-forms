<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Вихід, якщо доступ до нього прямий
}
/* Функція додавання до візуального редактору у адмінці персональних стилів */
if (!function_exists('custom_form_add_editor_style')) { 
function custom_form_add_editor_style($mce_css) {
global $mce_css;
if ( ! empty( $mce_css ) ) $mce_css .= ',';
$mce_css .= $mce_css; //get_template_directory_uri() . '/dynamic-css.php';
return $mce_css;
}
}

/* Функція виводу підменю розділу */
if (!function_exists('custom_form_submenu_zakladka_admin_menu')) { 
function custom_form_submenu_zakladka_admin_menu() {
    global $submenu, $main_page_slug; 
	$current_page = sanitize_key($_GET['page']);
    $current_page = isset($current_page) ? $current_page : '';
    $links = array();

    if (isset($submenu[$main_page_slug])) {
        foreach ($submenu[$main_page_slug] as $key => $item) {
            $url = admin_url( 'admin.php?page=' . $item[2] ); $name = $item[0]; $slug = $item[2];
            // Проверяем, есть ли уже такой пункт в массиве
            if (!array_key_exists($slug, $links)) {
                $active = ($slug === $current_page) ? 'nav-tab-active' : '';
                $links[$slug] = '<a class="nav-tab ' . esc_attr($active) . '" href="' . esc_url( $url) . '">' . esc_html($name) . '</a>';
            }
        }
        echo '<div class="nav-tab-wrapper">';
        echo implode('', $links);
        echo '</div>';
    }
}
}
