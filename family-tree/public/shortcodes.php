<?php
function family_tree_shortcode() {
    ob_start();
    include plugin_dir_path(__FILE__) . '../templates/tree-template.php';
    return ob_get_clean();
}
add_shortcode('family_tree', 'family_tree_shortcode');