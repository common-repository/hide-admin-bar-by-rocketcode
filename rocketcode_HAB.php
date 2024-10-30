<?php
/*
Plugin Name: Hide Admin Bar by RocketCode
Plugin URI: https://wordpress.org/plugins/hide-admin-bar-by-rocketcode
Description: Hide the Admin Bar from the front-end of your website.
Version: 1.1
Author: RocketCode
Author URI: https://rocketcode.com.br
*/

function rocketcode_hab_options_validate($input) {
    $valid = array();
    $valid['subscriber'] = (isset($input['subscriber']) && !empty($input['subscriber'])) ? 1 : 0;
    $valid['contributor'] = (isset($input['contributor']) && !empty($input['contributor'])) ? 1 : 0;
    $valid['author'] = (isset($input['author']) && !empty($input['author'])) ? 1 : 0;
    $valid['editor'] = (isset($input['editor']) && !empty($input['editor'])) ? 1 : 0;
    $valid['administrator'] = (isset($input['administrator']) && !empty($input['administrator'])) ? 1 : 0;
    return $valid;
}

function rocketcode_hab() {
	$options = get_option('rocketcode_hab_options');
	$current_user = wp_get_current_user();
	if(!empty($current_user->roles)) {
		$user_role = $current_user->roles[0];
		if ((isset($options[$user_role]) && $options[$user_role])) {
			show_admin_bar(true);
		} else {
			show_admin_bar(false);
		}
	}
}

add_action('init', 'rocketcode_hab');


function rocketcode_hab_settings_init() {
    register_setting( 'rocketcode_hab', 'rocketcode_hab_options', 'rocketcode_hab_options_validate' );

    add_settings_section(
        'rocketcode_hab_section',
        __( 'Settings', 'rocketcode_hab' ),
        'rocketcode_hab_header',
        'rocketcode_hab'
    );

    add_settings_field(
        'rocketcode_hab_id',
        __( 'Show only for', 'rocketcode_hab' ),
        'rocketcode_hab_options',
        'rocketcode_hab',
        'rocketcode_hab_section'
    );


    add_settings_section(
        'rocketcode_hab_footer',
        __('', 'rocketcode_hab'),
        'rocketcode_hab_footer',
        'rocketcode_hab'
    );
}


function rocketcode_hab_add_settings_link($links)
{
    $settings_link = '<a href="' . admin_url('options-general.php?page=rocketcode_hab') . '">' . __('Settings', 'rocketcode_hab') . '</a>';
    array_push($links, $settings_link);
    return $links;
}

add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'rocketcode_hab_add_settings_link');


function rocketcode_hab_settings_page()
{
    add_options_page('Hide Admin Bar by RocketCode', 'Hide Admin Bar - RocketCode', 'manage_options', 'rocketcode_hab', 'rocketcode_hab_settings_page_html');
}

add_action('admin_menu', 'rocketcode_hab_settings_page');


function rocketcode_hab_settings_page_html()
{
    if (!current_user_can('manage_options')) { return; }

    if (isset($_GET['settings-updated'])) {
        add_settings_error('rocketcode_hab_messages', 'rocketcode_hab_message', __('Settings Saved', 'rocketcode_hab'), 'updated');
    }

    settings_errors('rocketcode_hab_messages');

    $rocketcode_hab_id = get_option('rocketcode_hab_id');
?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form action="options.php" method="post">
            <?php
            settings_fields('rocketcode_hab');
            do_settings_sections('rocketcode_hab');
            submit_button('Save Settings');
            ?>
        </form>
    </div>
<?php
}


add_action('admin_init', 'rocketcode_hab_settings_init');


function rocketcode_hab_header() {
    ?>
         <p style="text-align: left;">This plugin hides the frontend admin bar of your website. Except for those selected below.</p>
    <?php 
}

function rocketcode_hab_options() {
    $options = get_option( 'rocketcode_hab_options' );
    ?>
    <div style="display: flex; gap: 10px; flex-direction: column;">
        <label for="rocketcode_hab_id[subscriber]">
            <input type="checkbox" name="rocketcode_hab_options[subscriber]" id="rocketcode_hab_id[subscriber]" value="1" <?php checked(1, $options['subscriber']); ?> />
            <?php _e('Subscriber', 'rocketcode_hab'); ?>
        </label>
        <label for="rocketcode_hab_id[contributor]">
            <input type="checkbox" name="rocketcode_hab_options[contributor]" id="rocketcode_hab_id[contributor]" value="1" <?php checked(1, $options['contributor']); ?> />
            <?php _e('Contributor', 'rocketcode_hab'); ?>
        </label>
        <label for="rocketcode_hab_id[author]">
            <input type="checkbox" name="rocketcode_hab_options[author]" id="rocketcode_hab_id[author]" value="1" <?php checked(1, $options['author']); ?> />
            <?php _e('Author', 'rocketcode_hab'); ?>
        </label>
        <label for="rocketcode_hab_id[editor]">
            <input type="checkbox" name="rocketcode_hab_options[editor]" id="rocketcode_hab_id[editor]" value="1" <?php checked(1, $options['editor']); ?> />
            <?php _e('Editor', 'rocketcode_hab'); ?>
        </label>
        <label for="rocketcode_hab_id[administrator]">
            <input type="checkbox" name="rocketcode_hab_options[administrator]" id="rocketcode_hab_id[administrator]" value="1" <?php checked(1, $options['administrator']); ?> />
            <?php _e('Administrator', 'rocketcode_hab'); ?>
        </label>
    </div>
    <?php
}



function rocketcode_hab_footer() {
?>
    <p style="text-align: left;">Thank you for use Hide Admin Bar by <a href="https://rocketcode.com.br" target="_blank">RocketCode.</a></p>
<?php 
}
?>