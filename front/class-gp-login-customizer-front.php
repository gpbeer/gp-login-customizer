<?php
/**
 * Plugin Name: Gp Login Customizer
 * Description: Change default login URL, Title, Styles, Logo, etc. Go to : Appearance -> Themes -> Customize -> Login page
 * Version: 1.0.1
 * Author: German Pichardo
 * Author URI: http://www.german-pichardo.com
 * Text Domain: custom-login-settings
 */
// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    die;
}
if (!class_exists(' GpLoginCustomizerFront')) {
    class GpLoginCustomizerFront
    {
        public static $text_domain = 'gp-login-customizer';

        public function __construct()
        {
            add_action('login_headerurl', [$this, 'logo_url']);
            add_action('login_headertitle', [$this, 'logo_title']);
            add_action('login_errors', [$this, 'error_message']);
            add_action('login_head', [$this, 'login_head']);
        }

        // Change default url link wordpress.org from logo
        public static function logo_url()
        {
            return home_url();
        }

        // Change default logo title attribute "Powered by Wordpress"
        public static function logo_title()
        {
            return get_option('blogname');
        }

        // For security reasons it's better to insert a generic message instead of precising "Invalid username" or "Invalid password".
        public static function error_message()
        {
            return get_theme_mod('setting_error_message', __('ERROR: Incorrect login details.', self::$text_domain));
        }

        public function login_head()
        {
            $this->logo_mod_style();
            $this->login_mod_style();
            $this->login_overwrite_style();
            $this->login_mod_additional_style();
        }

        // Change default WP logo image
        public static function logo_mod_style()
        {
            $logo_image = get_theme_mod('setting_logo_image');

            if ($logo_image && !empty($logo_image)) {
                $logo_image_size = getimagesize($logo_image);
                $logo_image_width = $logo_image_size[0];
                $logo_image_height = $logo_image_size[1];

                $is_ratio_69 = $logo_image_width > $logo_image_height;

                $logo_background_size = $is_ratio_69 ? '60%% auto' : (is_array($logo_image_size) ? ' auto 80%%' : 'contain');
                $logo_padding_top = $is_ratio_69 ? '56.25%%' : '75%%';
                $logo_container_width = is_array($logo_image_size) ? '100%%' : '60%%'; ?>

                <style type="text/css">
                    <?php self::generate_css('body.login h1 a', 'background', '', 'url("' . $logo_image . '") center center no-repeat'); ?>
                    <?php self::generate_css('body.login h1 a', 'background-size', '', $logo_background_size); ?>
                    <?php self::generate_css('body.login h1 a', 'background-position', '', 'center 80%%'); ?>
                    <?php self::generate_css('body.login h1 a', 'width', '', $logo_container_width); ?>
                    <?php self::generate_css('body.login h1 a', 'height', '', '100%%'); ?>
                    <?php self::generate_css('body.login h1 a', 'white-space', '', 'nowrap'); ?>
                    <?php self::generate_css('body.login h1 a', 'font-size', '', '0px'); ?>
                    <?php self::generate_css('body.login h1 a', 'line-height', '', '0px'); ?>
                    /**/
                    <?php self::generate_css('body.login h1 a:before', 'padding-top', '', $logo_padding_top); ?>
                    <?php self::generate_css('body.login h1 a:before', 'content', '', '""'); ?>
                    <?php self::generate_css('body.login h1 a:before', 'display', '', 'block'); ?>
                </style>

            <?php }
        }

        public static function login_mod_style()
        { ?>
            <style id="login_mod_style" type="text/css">

                <?php
                    $login_background_image = get_theme_mod('setting_login_body_background_image');
                    if ($login_background_image && !empty($login_background_image)) {
                        self::generate_css('body.login', 'background', '', 'url("' . $login_background_image . '") center center no-repeat');
                        self::generate_css('body.login', 'background-size', '', 'cover');
                    }
                ?>

                <?php self::generate_css('body.login', 'background-color', 'setting_login_body_background', '#e8e8e7'); ?>
                <?php self::generate_css('body.login', 'color', 'setting_form_label_color', '#514f4c'); ?>

                <?php self::generate_css('body.login label', 'color', 'setting_form_label_color', '#514f4c'); ?>

                <?php self::generate_css('body.login form .input', 'border-color', 'setting_form_input_border_color', '#e3e5e8'); ?>
                <?php self::generate_css('body.login form .input', 'color', 'setting_form_label_color', '#514f4c'); ?>
                <?php self::generate_css('body.login form .input', 'border-width', 'setting_form_input_border_width', '2px'); ?>

                <?php self::generate_css('.wp-core-ui .button-primary', 'background-color', 'setting_form_primary_color', '#293550','',' !important'); ?>
                <?php self::generate_css('.wp-core-ui .button-primary', 'border-color', 'setting_form_primary_color', '#293550','',' !important'); ?>
                <?php self::generate_css('.wp-core-ui .button-primary', 'color', 'setting_form_button_text_color', '#ffffff'); ?>

                <?php self::generate_css('body.login .message, body.login #login_error, body.login input[type=checkbox]:checked, input[type="checkbox"]:focus', 'border-color', 'setting_form_secondary_color', '#ffcc4d'); ?>
                <?php self::generate_css('body.login input[type=checkbox]:checked:before', 'color', 'setting_form_secondary_color', '#ffcc4d'); ?>
                <?php self::generate_css('.login #nav a, .login #backtoblog a', 'color', 'setting_form_link_color', '#72777c'); ?>

            </style>
        <?php }

        // Add custom css styles : external css or inline css to overwrite default form styles
        public static function login_mod_additional_style()
        {
            if (!empty(get_theme_mod('setting_additional_css'))) { ?>

                <style id="login_additional_css" type="text/css">
                    /*Start Additional CSS*/
                    <?php print get_theme_mod( 'setting_additional_css','' ); ?>
                    /*End Additional CSS*/
                </style>

            <?php }
        }

        public static function login_overwrite_style()
        { ?>
            <style id="login_overwrite_style" type="text/css">
                /*Overwrite style*/
                body.login form {
                    padding: 40px 30px;
                }

                body.login label {
                    font-weight: 700;
                    font-size:   0.9em;
                }

                body.login input[type="text"] {
                    -webkit-border-radius: 0;
                }

                body.login form .input,
                body.login .login input[type=text] {
                    height:             46px;
                    padding:            6px 15px;
                    margin-top:         10px;
                    font-size:          14px;
                    line-height:        1.5;
                    -webkit-box-shadow: none;
                    -moz-box-shadow:    none;
                    box-shadow:         none;
                    font-weight:        normal;
                }

                .wp-core-ui .button-primary,
                .wp-core-ui .button-primary:hover,
                .wp-core-ui .button-primary:focus,
                .wp-core-ui .button-primary:active {
                    text-shadow:        none;
                    -webkit-box-shadow: none;
                    -moz-box-shadow:    none;
                    box-shadow:         none;
                }

                input[type="text"]:focus,
                input[type="email"]:focus,
                input[type="search"]:focus,
                input[type="checkbox"]:focus {
                    -webkit-box-shadow: none;
                    -moz-box-shadow:    none;
                    box-shadow:         none;
                }

            </style>
        <?php }

        public static function generate_css($selector, $style, $mod_name, $fallback_value, $prefix = '', $postfix = '', $echo = true)
        {
            $return = '';
            $mod = get_theme_mod($mod_name, $fallback_value);
            if (!empty($mod)) {
                $return = sprintf('%s { %s:%s; }',
                    $selector,
                    $style,
                    $prefix . $mod . $postfix
                );
                if ($echo) {
                    echo $return;
                }
            }

            return $return;
        }

    }

}

$gp_login_customizer_front = new GpLoginCustomizerFront();