<?php
function create_mabpro_certificate_post_type() {
    register_post_type('mabpro_certificate',
        array(
            'labels' => array(
                'name' => __('Certificates'),
                'singular_name' => __('Certificate')
            ),
            'public' => false,
            'has_archive' => false,
            'show_ui' => true,
            'supports' => array('title', 'custom-fields'),
        )
    );
}
add_action('init', 'create_mabpro_certificate_post_type');
