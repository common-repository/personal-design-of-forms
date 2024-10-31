<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Вихід, якщо доступ до нього прямий
}
$solass_base_plugin_url = "solass-wp-settings/solass-wp-settings.php";
$main_page_slug = 'solass-wp-settings';
$hookname = ""; 
//add_action('init', 'sett_hookname', 2);		// Ставимо приоритет для виконання функції



// --------------------- Реєструємо сторінку налаштувань плагіна ----------------------------- //

function custom_form_options_page() {
global $solass_base_plugin_url, $main_page_slug, $menu, $hookname, $solass_base_plugin, $plugin_dir_url, $plugins_name;
global $GLOBALS;

if ( isset( $GLOBALS['admin_page_hooks'][$main_page_slug] ) ) { $hookname = "ok"; }

if ( $hookname && $solass_base_plugin_url ) { $solass_base_plugin_url = $solass_base_plugin_url; } else { $solass_base_plugin_url = "no_menu_SOLASS"; }
if (isset($value->response[$solass_base_plugin_url])) { /* проверяем есть ли файл главного плагина SOLASS и включаем что-то */ }

require_once( ABSPATH . 'wp-admin/includes/plugin.php' ); // Смотрим файл плагинов
if (is_plugin_active($solass_base_plugin_url) && $hookname) { // Проверяем включен ли главный плагин SOLASS и есть ли у него меню
// Добавляем страницу плагина в меню основного плагина SOLASS
 add_menu_page( 
	esc_html__( 'Параметри дизайну форм вводу', 'custom-form'), // Заголовок страницы
    esc_html__( 'Дизайн форм', 'custom-form'), // Название пункта меню
    'manage_options', // Разрешение на просмотр страницы... publish_pages - редакторам, publish_posts - авторам 
    'custom-form', // Уникальный идентификатор страницы
    'custom_form_admin_options' // Функция отображения страницы
	); 

 add_submenu_page( $main_page_slug,	esc_html__( 'Параметри дизайну форм вводу', 'custom-form'), esc_html__( 'Дизайн форм', 'custom-form'), 'manage_options', 'custom-form', 'custom_form_admin_options', 1 ); 
 remove_menu_page( 'custom-form' ); // Видаляємо головне меню плагіну залишаючи підменю в основному роздіді SOLASSWPSel
} else {
 add_options_page( 
    esc_html__( 'Параметри дизайну форм вводу', 'custom-form'), // Заголовок страницы
    esc_html__( 'Дизайн форм', 'custom-form'), // Название пункта меню
    'manage_options', // Разрешение на просмотр страницы
    'custom-form', // Уникальный идентификатор страницы
    'custom_form_admin_options' // Функция отображения страницы
  ); 
}
}

add_action( 'admin_menu', 'custom_form_options_page' );


// ------------- Код виводу сторінки налаштувань ------------------ //

function custom_form_admin_options() {  
	global $solass_base_plugin_url, $main_page_slug, $menu, $plugin_name, $mce_css, $plugins_name;

	//Стили визуального редактора подключаемые на странице
//	$mce_css = "/wp-admin/load-styles.php, ". SOLASS_WP_PLUGIN_URL . 'admin/admin_style.css'; //get_template_directory_uri().'/styles.php'
	/* Підключення до візуального редактору стилі схожі на візуал адмін частини, та стилю адмінки плагіна */
//	add_action('mce_css', 'my_plugin_add_editor_style');
?>
  <div class="wrap <?php echo esc_attr( dirname(plugin_basename(__FILE__),2)." ".$plugins_name['solass-wp-settings'] ); ?> custom-form" style="margin-right: 8%">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
	  <?php //print_r($menu); ?>

	<br><?php custom_form_submenu_zakladka_admin_menu(); ?><br>


    <form method="post" action="options.php">
      <?php
        settings_fields( 'custom-form-settings' );
        do_settings_sections( 'custom-form-settings' );
      ?>
      <table class="form-table" border=0>
        <tr>
          <th scope="row"><label for="select-on"><?php echo esc_html__( 'Включити персональні стилі', 'custom-form'); ?></label></th>
          <td>
			  <label for="select-on"><input id="select-on" name="select-on" type="checkbox" <?php if(esc_attr( get_option( 'select-on', '' ) )) echo 'checked="checked"'; ?>> <span style="text-transform: uppercase"><?php echo esc_html__( 'TAK', 'custom-form'); ?></span><br>
			  </label>
		  </td>
          <th scope="row" style="<?php if(!esc_attr( get_option( 'select-on', '' ) )) echo 'opacity: .3;'; ?>"><label for="custom-select-class"><?php echo esc_html__( 'Включити тільки для CLASSу', 'custom-form'); ?></label></th>
          <td style="<?php if(esc_attr( !get_option( 'select-on', '' ) )) echo 'opacity: .3;'; ?>">
			  <input type="text" name="custom-select-class" id="custom-select-class" value="<?php echo esc_attr( get_option( 'custom-select-class', '' ) ); ?>" 
					 <?php if(!esc_attr( get_option( 'select-on', '' ) )) echo 'readonly'; ?> />
		  </td>
		  <td colspan=4 style="<?php if(!esc_attr( get_option( 'select-on', '' ) )) echo 'opacity: .3;'; ?>">
			  <small><?php echo esc_html__( 'Вкажіть CSS-класи випадаючої форми для якої потрібно включити інивідуальне оформлення.', 'custom-form');?> <br>
					 <?php echo esc_html__( 'Якщо форму залишити пустою то індивідуальне оформленння буде використане для всіх випадаючих форм.', 'custom-form'); ?> </small>
		  </td>
        </tr>
         <tr>
          <td colspan=8><hr></td>
        </tr>
         <tr>
          <td style=" padding: 0 0px;" colspan=8><h3 style="/*color: #717b82;*/ padding: 0 0px;"><?php echo esc_html__( 'Налаштування випадаючої форми (SELECT)', 'custom-form' ); ?></h3></td>
        </tr>
         <tr>
          <th scope="row" class="align-right"><label for="custom-select-fone-color"><?php echo esc_html__( 'Колір фону:', 'custom-form'); ?></label></th>
          <td><input type="text" class="colorPicker" name="custom-select-fone-color" value="<?php echo esc_attr( get_option( 'custom-select-fone-color', '#ffffff' ) ); ?>" data-default-color="#ffffff" /></td>
          <th scope="row" class="align-right"><label for="custom-select-border-color"><?php echo esc_html__( 'Колір окантовки:', 'custom-form'); ?></label></th>
          <td><input type="text" class="colorPicker" name="custom-select-border-color" value="<?php echo esc_attr( get_option( 'custom-select-border-color', '' ) ); ?>" data-default-color="" /></td>
          <th scope="row" class="align-right"><label for="custom-select-border"><?php echo esc_html__( 'Розмір окантовки:', 'custom-form'); ?></label></th>
          <td><div style="display: block ruby;">
			  <input style="width: 70%" type="number" name="custom-select-border" id="custom-select-border" value="<?php echo esc_attr( get_option( 'custom-select-border', '0' ) ); ?>" /> px
		  </div></td>
          <th scope="row" class="align-right"><label for="custom-select-padding"><?php echo esc_html__( 'Відступи - padding:', 'custom-form'); ?></label></th>
          <td><input type="text" name="custom-select-padding" id="custom-select-padding" value="<?php echo esc_attr( get_option( 'custom-select-padding', '' ) ); ?>" /></td>
        </tr>
         <tr>
          <th scope="row" class="align-right"><label for="custom-select-text-color"><?php echo esc_html__( 'Колір тексту:', 'custom-form'); ?></label></th>
          <td><input type="text" class="colorPicker" name="custom-select-text-color" value="<?php echo esc_attr( get_option( 'custom-select-text-color', '' ) ); ?>" data-default-color="" /></td>
          <th scope="row" class="align-right"><label for="custom-select-fone-active-color"><?php echo esc_html__( 'Колір фону активного меню:', 'custom-form'); ?></label></th>
          <td><input type="text" class="colorPicker" name="custom-select-fone-active-color" value="<?php echo esc_attr( get_option( 'custom-select-fone-active-color', '' ) ); ?>" data-default-color="" /></td>
          <th scope="row" class="align-right"><label for="custom-select-text-hover-color"><?php echo esc_html__( 'Колір тексту при наведенні:', 'custom-form'); ?></label></th>
          <td><input type="text" class="colorPicker" name="custom-select-text-hover-color" value="<?php echo esc_attr( get_option( 'custom-select-text-hover-color', '' ) ); ?>" data-default-color="" /></td>
         <th scope="row" class="align-right"><label for="custom-select-fone-hover-color"><?php echo esc_html__( 'Колір фону при наведенні:', 'custom-form'); ?></label></th>
          <td><input type="text" class="colorPicker" name="custom-select-fone-hover-color" value="<?php echo esc_attr( get_option( 'custom-select-fone-hover-color', '' ) ); ?>" data-default-color="" /></td>
        </tr>
         <tr>
          <th scope="row" class="align-right" style="vertical-align: top;"><label for="custom-select-form-class"><?php echo esc_html__( 'Перелік классів для форми:', 'custom-form'); ?></label></th>
          <td colspan=3 style="vertical-align: top;">
			  <input type="text" name="custom-select-form-class" id="custom-select-form-class" value="<?php echo esc_attr( get_option( 'custom-select-form-class', '' ) ); ?>" style="width: 90%" />
			  <div style="margin: 15px 10% 0 0"><small><?php echo esc_html__( 'Вказаний вами перелік класів буде назначений для сформованої/трансформованої випадаючої форми. В подальшому ви зможете керувати та налаштовувати зовнішній вигляд форми за допомогою цих класів.', 'custom-form'); ?> </small></div>
		  </td>
          <th scope="row" class="align-right" style="vertical-align: top;"><label for="сustom-select-css"><?php echo esc_html__( 'Стилі елементів форми:', 'custom-form'); ?></label></th>
          <td colspan=3 style="vertical-align: top;">
			  <textarea rows="7" name="custom-select-css" id="custom-select-css" aria-describedby="editor-keyboard-trap-help-1 editor-keyboard-trap-help-2 editor-keyboard-trap-help-3 editor-keyboard-trap-help-4" style="width: 90%"><?php echo esc_textarea( get_option('custom-select-css') ); ?></textarea>
		</td>
        </tr>
      </table>
<?php if($onnn): ?>
<table class="form-table" border=0>
<tr><td colspan=2><hr></td></tr>
<tr>
<th scope="row" class="align-right"><label for=""><?php echo esc_html__( 'Заміна тексту у підвалі', 'solass-wp'); ?> <br><?php echo esc_html__( 'адмін-сторінки', 'solass-wp'); ?></label></th> 
<td class="vertical-top" style="/*width: 50%*/">
<?php 
	$editor_css = '<style>.solass_wp_admin_footer_text { background: #f0f0f1; color: #3c434a; /*font-family: inherit;*/ font-size: 14px; line-height: 1.3; } </style>';
	$settings = array( 'editor_css' => $editor_css, 'editor_height' => 100, 'teeny' => true, 'dfw' => true, 'quicktags' => false, 'media_buttons' => false );
	wp_editor( esc_textarea( get_option( 'solass_wp_admin_footer_text', '' ) ), 'solass_wp_admin_footer_text', $settings ); 
 ?>
</td>
</tr>
</table>
<?php endif; ?>
      <?php submit_button(); ?>
    </form>
  </div>

<!-- Підключаємо форму вибору кольору -->
<script type="text/javascript">
   jQuery(document).ready(function() {
      jQuery('.colorPicker').wpColorPicker();
   });
</script>
<style>	</style>
<?php 
// -------------------- Включение/замена textarea на редактор CSS ------------------------- //

	function custom_form_options_page_editor($textarea_id) {

    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    // Объявляем переменную с настройками плагина
    $options = get_option( $textarea_id );
    // Подключаем редактор CSS
    wp_enqueue_code_editor( array( 'type' => 'text/css' ) ); //
    // Выводим форму настроек плагина
                $css = isset( $options ) ? $options : '';
                $settings = array(
                    'textarea_name' => $textarea_id,
                    'textarea_rows' => 7,
                    'editor_class' => $textarea_id,
//					'tinymce' => false,
	                'codemirror' => array(
                    'mode' => 'css', //'htmlmixed'
                    'lineNumbers' => true,
                    'autoCloseBrackets' => true,
                    'matchBrackets' => true,
                    'indentWithTabs' => true,
                    'smartIndent' => true,
                    'extraKeys' => array(
                    'Ctrl-Space' => 'autocomplete',
                    ),
                ),
                );
// wp_editor( $css, $textarea_id, $settings ); // Створює редактор у місті запуску функції

// Вказуємо скрипт по запуску заміни текстового редактора який підключається в головному файлі плагіну
// (необхідно вказати ID змінюваної TEXTAREA)
	$custom_form_admin_script = "
	<script>
		/* Запуск текстового редактора кода */
		jQuery(document).ready(function($) {
        var textarea = document.getElementById('".$textarea_id."');
        var editorSettings = wp.codeEditor.defaultSettings ? _.clone(wp.codeEditor.defaultSettings) : {};
        var editor = wp.codeEditor.initialize(textarea, editorSettings);
    	});
	</script> ";
	// Додаємо скрипт у кінець сторінки адмінки після файлу admin/admin_js.js (ідентифікатор "admin-script") 
	wp_add_inline_script( 'admin-script', $custom_form_admin_script ); 

}
	custom_form_options_page_editor('custom-select-css');
}

// ------------- Реєстрація параметрів для сторінки налаштувань --------------- //

function custom_form_register_settings() { 
  // Регистрируем опцию для цвета текста в выпадающей форме
  register_setting(
    'custom-form-settings', // Название группы настроек
    'select-on', // Название опции
    array(
      'default' => false, // Значение по умолчанию
    )
  );
  register_setting(
    'custom-form-settings', // Название группы настроек
    'custom-select-class', // Название опции
    array(
      'type' => 'string', // Тип данных
      'default' => '', // Значение по умолчанию
    )
  );
  register_setting(
    'custom-form-settings', // Название группы настроек
    'custom-select-fone-color', // Название опции
    array(
      'type' => 'string', // Тип данных
      'default' => '#ffffff', // Значение по умолчанию
      'sanitize_callback' => 'sanitize_hex_color', // Функция очистки данных
    )
  );
  register_setting(
    'custom-form-settings', // Название группы настроек
    'custom-select-border-color', // Название опции
    array(
      'type' => 'string', // Тип данных
      'default' => '', // Значение по умолчанию
      'sanitize_callback' => 'sanitize_hex_color', // Функция очистки данных
    )
  );
  register_setting(
    'custom-form-settings', // Название группы настроек
    'custom-select-text-color', // Название опции
    array(
      'type' => 'string', // Тип данных
      'default' => '', // Значение по умолчанию
      'sanitize_callback' => 'sanitize_hex_color', // Функция очистки данных
    )
  );
  register_setting(
    'custom-form-settings', // Название группы настроек
    'custom-select-fone-active-color', // Название опции
    array(
      'type' => 'string', // Тип данных
      'default' => '', // Значение по умолчанию
      'sanitize_callback' => 'sanitize_hex_color', // Функция очистки данных
    )
  );
  register_setting(
    'custom-form-settings', // Название группы настроек
    'custom-select-text-hover-color', // Название опции
    array(
      'type' => 'string', // Тип данных
      'default' => '', // Значение по умолчанию
      'sanitize_callback' => 'sanitize_hex_color', // Функция очистки данных
    )
  );
  register_setting(
    'custom-form-settings', // Название группы настроек
    'custom-select-fone-hover-color', // Название опции
    array(
      'type' => 'string', // Тип данных
      'default' => '', // Значение по умолчанию
      'sanitize_callback' => 'sanitize_hex_color', // Функция очистки данных
    )
  );
  register_setting(
    'custom-form-settings', // Название группы настроек
    'custom-select-border', // Название опции
    array(
      'type' => 'number', // Тип данных
      'default' => '', // Значение по умолчанию
    )
  );
  register_setting(
    'custom-form-settings', // Название группы настроек
    'custom-select-padding', // Название опции
    array(
      'type' => 'string', // Тип данных
      'default' => '', // Значение по умолчанию
    )
  );
  register_setting(
    'custom-form-settings', // Название группы настроек
    'custom-select-class', // Название опции
    array(
      'type' => 'string', // Тип данных
      'default' => '', // Значение по умолчанию
    )
  );
  register_setting(
    'custom-form-settings', // Название группы настроек
    'custom-select-form-class', // Название опции
    array(
      'type' => 'string', // Тип данных
      'default' => '', // Значение по умолчанию
    )
  );
  register_setting(
    'custom-form-settings', // Название группы настроек
    'custom-select-css', // Название опции
    array(
      'type' => 'string', // Тип данных
      'default' => '', // Значение по умолчанию
    )
  );
}
add_action( 'admin_init', 'custom_form_register_settings' ); 
