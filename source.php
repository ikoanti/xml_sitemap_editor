<?php
/*
Plugin Name: XML Sitemap Editor
Plugin URI: https://www.irakli.life
Description: Edit XML sitemaps from WP admin.
Version: 1.0
Author: Irakli Antidze
Author URI: https://www.irakli.life
*/

// Add submenu page to the settings menu
add_action('admin_menu', 'xmlsitemap_menu');
function xmlsitemap_menu() {
    add_options_page('XML Sitemap Editor', 'XML Sitemap Editor', 'manage_options', 'xmlsitemap_editor', 'xmlsitemap_editor');
}

// Display the XML sitemap editor page
function xmlsitemap_editor() {
    ?>
    <div class="wrap">
        <h1>XML Sitemap Editor</h1>
        <?php
        if(isset($_POST['xml_content'])) {
            $xml_content = stripslashes($_POST['xml_content']);
            $file = ABSPATH . 'sitemap.xml';
            $fh = fopen($file, 'w') or die("Can't open file");
            fwrite($fh, $xml_content);
            fclose($fh);
            echo '<div class="updated"><p>XML sitemap updated successfully!</p></div>';
        }
        ?>
        <form method="post" action="">
            <p>Enter your XML sitemap content:</p>
            <textarea name="xml_content" rows="20" cols="100"><?php echo file_get_contents(ABSPATH . 'sitemap.xml'); ?></textarea>
            <p><input type="submit" value="Save Changes" class="button button-primary"></p>
        </form>
    </div>
    <?php
}
