<?php
add_action( 'after_setup_theme', 'vd_custom_velocitychild_theme_setup', 9 );
function vd_custom_velocitychild_theme_setup() {
    if (class_exists('Kirki')):

        // Kirki::add_panel('panel_adsense', [
        //     'priority'    => 10,
        //     'title'       => esc_html__('Iklan', 'justg'),
        //     'description' => esc_html__('', 'justg'),
        // ]);
        // Kirki::add_section('iklan_kiri', [
        //     'panel'    => 'panel_adsense',
        //     'title'    => __('Iklan Kiri', 'justg'),
        //     'priority' => 10,
        // ]);
        new \Kirki\Panel(
            'panel_adsense',
            [
                'priority'    => 10,
                'title'       => esc_html__( 'Iklan', 'velocity' ),
                'description' => esc_html__( 'Pengaturan Iklan.', 'velocity' ),
            ]
        );
            new \Kirki\Section(
                'section_iklan_kiri',
                [
                    'title'       => esc_html__( 'Iklan Kiri', 'velocity' ),
                    'description' => esc_html__( '', 'velocity' ),
                    'panel'       => 'panel_adsense',
                    'priority'    => 160,
                ]
            );
                new \Kirki\Field\Image(
                    [
                        'settings'    => 'image_iklan_kiri',
                        'label'       => esc_html__( 'Gambar', 'velocity' ),
                        'description' => esc_html__( '', 'velocity' ),
                        'section'     => 'section_iklan_kiri',
                        'default'     => '',
                        'choices'     => [
                            'save_as' => 'array',
                        ],
                    ]
                );
                new \Kirki\Field\URL(
                    [
                        'settings'      => 'url_iklan_kiri',
                        'label'         => esc_html__( 'URL', 'velocity' ),
                        'description'   => esc_html__( 'URL ketika Gambar diklik', 'velocity' ),
                        'section'       => 'section_iklan_kiri',
                        'default'       => '',
                        'priority'      => 10,
                    ]
                );
            new \Kirki\Section(
                'section_iklan_kanan',
                [
                    'title'       => esc_html__( 'Iklan Kanan', 'velocity' ),
                    'description' => esc_html__( '', 'velocity' ),
                    'panel'       => 'panel_adsense',
                    'priority'    => 160,
                ]
            );
                new \Kirki\Field\Image(
                    [
                        'settings'    => 'image_iklan_kanan',
                        'label'       => esc_html__( 'Gambar', 'velocity' ),
                        'description' => esc_html__( '', 'velocity' ),
                        'section'     => 'section_iklan_kanan',
                        'default'     => '',
                        'choices'     => [
                            'save_as' => 'array',
                        ],
                    ]
                );
                new \Kirki\Field\URL(
                    [
                        'settings'      => 'url_iklan_kanan',
                        'label'         => esc_html__( 'URL', 'velocity' ),
                        'description'   => esc_html__( 'URL ketika Gambar diklik', 'velocity' ),
                        'section'       => 'section_iklan_kanan',
                        'default'       => '',
                        'priority'      => 10,
                    ]
                );

    endif;

}

function floating_iklan_customvd(){
    $container_width = get_theme_mod('container_width');

    ///left
    $getlefturl = get_theme_mod('url_iklan_kiri');
    $getleftimg = get_theme_mod('image_iklan_kiri');
    $urlleftimg = $getleftimg&&is_array($getleftimg)?$getleftimg['url']:'';
    if($urlleftimg){
        echo '<div id="iklanKiri" class="float-iklan float-iklan-kiri collapse show" data-container="'.$container_width.'">';
            echo '<span class="close-iklan badge badge-light" data-toggle="collapse" data-target="#iklanKiri">close</span>';
            echo $getlefturl?'<a href="'.$getlefturl.'" target="_blank">':'';
            echo '<img src="'.$urlleftimg.'" loading="lazy" class="img-fluid"/>';
            echo $getleftimg?'</a>':'';
        echo '</div>';
    }

    ///Right
    $getrighturl = get_theme_mod('url_iklan_kanan');
    $getrightimg = get_theme_mod('image_iklan_kanan');
    $urlrightimg = $getrightimg&&is_array($getrightimg)?$getrightimg['url']:'';
    if($urlrightimg){
        echo '<div id="iklanKanan" class="float-iklan float-iklan-kanan collapse show" data-container="'.$container_width.'">';
            echo '<span class="close-iklan badge badge-light" data-toggle="collapse" data-target="#iklanKanan">close</span>';
            echo $getrighturl?'<a href="'.$getrighturl.'" target="_blank">':'';
            echo '<img src="'.$urlrightimg.'" loading="lazy" class="img-fluid"/>';
            echo $getrighturl?'</a>':'';
        echo '</div>';
    }
}
add_action('wp_footer','floating_iklan_customvd');