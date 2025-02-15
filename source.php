<?php
/*
Plugin Name: XML Sitemap Editor
Plugin URI: https://www.irakli.life
Description: Edit XML sitemaps from WP admin.
Version: 1.1
Author: Irakli Antidze
Author URI: https://www.irakli.life
*/

// Add submenu to WP admin settings menu
add_action('admin_menu', 'xmlsitemap_menu');
function xmlsitemap_menu() {
    add_options_page('XML Sitemap Editor', 'XML Sitemap Editor', 'manage_options', 'xmlsitemap_editor', 'xmlsitemap_editor_page');
}

// Display the XML sitemap editor page
function xmlsitemap_editor_page() {
    // Get the XML sitemap content safely
    $file_path = ABSPATH . 'sitemap.xml';
    $xml_content = is_readable($file_path) ? file_get_contents($file_path) : '';

    ?>
    <div class="wrap">
        <h1>XML Sitemap Editor</h1>
        
        <?php if (isset($_POST['xml_content'])): ?>
            <?php 
            if (!isset($_POST['xml_sitemap_nonce']) || !wp_verify_nonce($_POST['xml_sitemap_nonce'], 'save_xml_sitemap')) {
                die('<div class="error"><p>Security check failed.</p></div>');
            }

            if (!current_user_can('manage_options')) {
                wp_die(__('Unauthorized access.', 'xml-sitemap-editor'));
            }

            // Sanitize and save content
            $xml_content = sanitize_textarea_field($_POST['xml_content']);

            if (is_writable($file_path)) {
                file_put_contents($file_path, $xml_content);
                echo '<div class="updated"><p>XML sitemap updated successfully!</p></div>';
            } else {
                echo '<div class="error"><p>Error: Cannot write to sitemap.xml.</p></div>';
            }
            ?>
        <?php endif; ?>

        <form method="post" action="">
            <?php wp_nonce_field('save_xml_sitemap', 'xml_sitemap_nonce'); ?>
            <p>Enter your XML sitemap content:</p>
            <textarea name="xml_content" rows="20" cols="100"><?php echo esc_textarea($xml_content); ?></textarea>
            <p><input type="submit" value="Save Changes" class="button button-primary"></p>
        </form>
    </div>
    <?php
}
