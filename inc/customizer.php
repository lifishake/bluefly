<?php
/**
 * bluefly Theme Customizer
 *
 * @package bluefly
 */


function bluefly_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->remove_control( 'header_textcolor' );
    $wp_customize->remove_control( 'display_header_text' );
    $wp_customize->remove_section( 'background_image' );

    //Extra titles
    class bluefly_Titles extends WP_Customize_Control {
        public $type = 'titles';
        public $label = '';
        public function render_content() {
        ?>
            <h3 style="padding: 10px; border: 1px solid #DF7B7B; color: #DF7B7B;"><?php echo esc_html( $this->label ); ?></h3>
        <?php
        }
    }

    class bluefly_Theme_Info extends WP_Customize_Control {
        public $type = 'info';
        public function render_content() {
        }
    }

    //___General___//
    $wp_customize->add_section(
        'bluefly_general',
        array(
            'title' => __('基本', 'bluefly'),
            'priority' => 9,
        )
    );
    //Favicon Upload
    $wp_customize->add_setting(
        'site_favicon',
        array(
            'default-image' => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Image_Control(
            $wp_customize,
            'site_favicon',
            array(
               'label'          => __( '自定义地址栏图标', 'bluefly' ),
               'type'           => 'image',
               'section'        => 'bluefly_general',
               'settings'       => 'site_favicon',
               'priority' => 10,
            )
        )
    );
    //___Header___//
    $wp_customize->add_section(
        'bluefly_header',
        array(
            'title' => __('头部', 'bluefly'),
            'priority' => 10,
        )
    );
    //Logo Upload
    $wp_customize->add_setting(
        'site_logo',
        array(
            'default-image' => '',
            'sanitize_callback' => 'esc_url_raw',

        )
    );
    $wp_customize->add_control(
        new WP_Customize_Image_Control(
            $wp_customize,
            'site_logo',
            array(
               'label'          => __( '上传logo', 'bluefly' ),
               'type'           => 'image',
               'section'        => 'bluefly_header',
               'settings'       => 'site_logo',
               'priority'       => 11,
            )
        )
    );
    //Logo size
    $wp_customize->add_setting(
        'logo_size',
        array(
            'sanitize_callback' => 'absint',
            'default'           => '200',
            'transport'         => 'postMessage'
        )       
    );
    $wp_customize->add_control( 'logo_size', array(
        'type'        => 'number',
        'priority'    => 12,
        'section'     => 'bluefly_header',
        'label'       => __('Logo大小', 'bluefly'),
        'description' => __('logo最大像素，默认200px', 'bluefly'),
        'input_attrs' => array(
            'min'   => 50,
            'max'   => 600,
            'step'  => 5,
            'style' => 'margin-bottom: 15px; padding: 15px;',
        ),
    ) );
    //Logo style
    $wp_customize->add_setting(
        'logo_style',
        array(
            'default'           => 'hide-title',
            'sanitize_callback' => 'bluefly_sanitize_logo_style',
        )
    );
    $wp_customize->add_control(
        'logo_style',
        array(
            'type'          => 'radio',
            'label'         => __('Logo style', 'bluefly'),
            'description'   => __('只有设置logo时此选项才有用', 'bluefly'),
            'section'       => 'bluefly_header',
            'priority'      => 13,
            'choices'       => array(
                'hide-title'  => __( '只有logo', 'bluefly' ),
                'show-title'  => __( 'Logo和标题同时显示', 'bluefly' ),
            ),
        )
    );
    //Padding
    $wp_customize->add_setting(
        'branding_padding',
        array(
            'sanitize_callback' => 'absint',
            'default'           => '150',
        )       
    );
    $wp_customize->add_control( 'branding_padding', array(
        'type'        => 'number',
        'priority'    => 14,
        'section'     => 'bluefly_header',
        'label'       => __('留白', 'bluefly'),
        'description' => __('上下留白高度。 默认: 150px', 'bluefly'),
        'input_attrs' => array(
            'min'   => 20,
            'max'   => 350,
            'step'  => 5,
            'style' => 'padding: 15px;',
        ),
    ) );
    //Padding
    $wp_customize->add_setting(
        'branding_padding_1024',
        array(
            'sanitize_callback' => 'absint',
            'default'           => '100',
        )       
    );
    $wp_customize->add_control( 'branding_padding_1024', array(
        'type'        => 'number',
        'priority'    => 15,
        'section'     => 'bluefly_header',
        'label'       => __('留出空白的大小（小于1024px的设备）', 'bluefly'),
        'description' => __('上下留白高度。 默认: 100px', 'bluefly'),
        'input_attrs' => array(
            'min'   => 20,
            'max'   => 350,
            'step'  => 5,
            'style' => 'padding: 15px;',
        ),
    ) );



    //___Blog options___//
    $wp_customize->add_section(
        'blog_options',
        array(
            'title' => __('Blog options', 'bluefly'),
            'priority' => 13,
        )
    );
 
    //Hide meta
    $wp_customize->add_setting(
      'meta_index',
      array(
        'sanitize_callback' => 'bluefly_sanitize_checkbox',
        'default' => 0,     
      )   
    );
    $wp_customize->add_control(
      'meta_index',
      array(
        'type' => 'checkbox',
        'label' => __('Hide date/author/archives on index?', 'bluefly'),
        'section' => 'blog_options',
        'priority' => 11,
      )
    );
    $wp_customize->add_setting(
      'meta_singles',
      array(
        'sanitize_callback' => 'bluefly_sanitize_checkbox',
        'default' => 0,     
      )   
    );
    $wp_customize->add_control(
      'meta_singles',
      array(
        'type' => 'checkbox',
        'label' => __('隐藏 date/author/archives on single posts?', 'bluefly'),
        'section' => 'blog_options',
        'priority' => 12,
      )
    );
    $wp_customize->add_setting(
      'read_more',
      array(
        'sanitize_callback' => 'bluefly_sanitize_checkbox',
        'default' => 0,     
      )   
    );
    $wp_customize->add_control(
      'read_more',
      array(
        'type' => 'checkbox',
        'label' => __('隐藏read more button?', 'bluefly'),
        'section' => 'blog_options',
        'priority' => 13,
      )
    );
    //Index images
    $wp_customize->add_setting(
        'index_feat_image',
        array(
            'sanitize_callback' => 'bluefly_sanitize_checkbox',
        )       
    );
    $wp_customize->add_control(
        'index_feat_image',
        array(
            'type' => 'checkbox',
            'label' => __('列表中隐藏特色图片？', 'bluefly'),
            'section' => 'blog_options',
            'priority' => 15,
        )
    );
    //Post images
    $wp_customize->add_setting(
        'post_feat_image',
        array(
            'sanitize_callback' => 'bluefly_sanitize_checkbox',
        )       
    );
    $wp_customize->add_control(
        'post_feat_image',
        array(
            'type' => 'checkbox',
            'label' => __('单独文章中隐藏特色图片?', 'bluefly'),
            'section' => 'blog_options',
            'priority' => 16,
        )
    );


    //___Fonts___//
    $wp_customize->add_section(
        'bluefly_fonts',
        array(
            'title' => __('Fonts', 'bluefly'),
            'priority' => 15,
            'description' => __('You can use any Google Fonts you want for the heading and/or body. See the fonts here: google.com/fonts. See the documentation if you need help with this: flyfreemedia.com/documentation/bluefly', 'bluefly'),
        )
    );
    //Body fonts title
    $wp_customize->add_setting('bluefly_options[titles]', array(
            'type' => 'titles_control',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'esc_attr',
        )
    );
    $wp_customize->add_control( new bluefly_Titles( $wp_customize, 'body_fonts', array(
        'label' => __('Body fonts', 'bluefly'),
        'section' => 'bluefly_fonts',
        'settings' => 'bluefly_options[titles]',
        'priority' => 10
        ) )
    );     
    // Site title
    $wp_customize->add_setting(
        'site_title_size',
        array(
            'sanitize_callback' => 'absint',
            'default'           => '82',
            'transport'         => 'postMessage'
        )       
    );
    $wp_customize->add_control( 'site_title_size', array(
        'type'        => 'number',
        'priority'    => 17,
        'section'     => 'bluefly_fonts',
        'label'       => __('网站标题', 'bluefly'),
        'input_attrs' => array(
            'min'   => 10,
            'max'   => 90,
            'step'  => 1,
            'style' => 'margin-bottom: 15px; padding: 10px;',
        ),
    ) ); 
    // Site description
    $wp_customize->add_setting(
        'site_desc_size',
        array(
            'sanitize_callback' => 'absint',
            'default'           => '18',
            'transport'         => 'postMessage'
        )       
    );
    $wp_customize->add_control( 'site_desc_size', array(
        'type'        => 'number',
        'priority'    => 17,
        'section'     => 'bluefly_fonts',
        'label'       => __('副标题', 'bluefly'),
        'input_attrs' => array(
            'min'   => 10,
            'max'   => 50,
            'step'  => 1,
            'style' => 'margin-bottom: 15px; padding: 10px;',
        ),
    ) );         
    //H1 size
    $wp_customize->add_setting(
        'h1_size',
        array(
            'sanitize_callback' => 'absint',
            'default'           => '36',
            'transport'         => 'postMessage'
        )       
    );
    $wp_customize->add_control( 'h1_size', array(
        'type'        => 'number',
        'priority'    => 17,
        'section'     => 'bluefly_fonts',
        'label'       => __('H1字体大小', 'bluefly'),
        'input_attrs' => array(
            'min'   => 10,
            'max'   => 60,
            'step'  => 1,
            'style' => 'margin-bottom: 15px; padding: 10px;',
        ),
    ) );
    //H2 size
    $wp_customize->add_setting(
        'h2_size',
        array(
            'sanitize_callback' => 'absint',
            'default'           => '30',
            'transport'         => 'postMessage'
        )       
    );
    $wp_customize->add_control( 'h2_size', array(
        'type'        => 'number',
        'priority'    => 18,
        'section'     => 'bluefly_fonts',
        'label'       => __('H2字体大小', 'bluefly'),
        'input_attrs' => array(
            'min'   => 10,
            'max'   => 60,
            'step'  => 1,
            'style' => 'margin-bottom: 15px; padding: 10px;',
        ),
    ) );
    //H3 size
    $wp_customize->add_setting(
        'h3_size',
        array(
            'sanitize_callback' => 'absint',
            'default'           => '24',
            'transport'         => 'postMessage'
        )       
    );
    $wp_customize->add_control( 'h3_size', array(
        'type'        => 'number',
        'priority'    => 19,
        'section'     => 'bluefly_fonts',
        'label'       => __('H3字体大小', 'bluefly'),
        'input_attrs' => array(
            'min'   => 10,
            'max'   => 60,
            'step'  => 1,
            'style' => 'margin-bottom: 15px; padding: 10px;',
        ),
    ) );
    //H4 size
    $wp_customize->add_setting(
        'h4_size',
        array(
            'sanitize_callback' => 'absint',
            'default'           => '18',
            'transport'         => 'postMessage'
        )       
    );
    $wp_customize->add_control( 'h4_size', array(
        'type'        => 'number',
        'priority'    => 20,
        'section'     => 'bluefly_fonts',
        'label'       => __('H4字体大小', 'bluefly'),
        'input_attrs' => array(
            'min'   => 10,
            'max'   => 60,
            'step'  => 1,
            'style' => 'margin-bottom: 15px; padding: 10px;',
        ),
    ) );
    //H5 size
    $wp_customize->add_setting(
        'h5_size',
        array(
            'sanitize_callback' => 'absint',
            'default'           => '14',
            'transport'         => 'postMessage'
        )       
    );
    $wp_customize->add_control( 'h5_size', array(
        'type'        => 'number',
        'priority'    => 21,
        'section'     => 'bluefly_fonts',
        'label'       => __('H5字体大小', 'bluefly'),
        'input_attrs' => array(
            'min'   => 10,
            'max'   => 60,
            'step'  => 1,
            'style' => 'margin-bottom: 15px; padding: 10px;',
        ),
    ) );
    //H6 size
    $wp_customize->add_setting(
        'h6_size',
        array(
            'sanitize_callback' => 'absint',
            'default'           => '12',
            'transport'         => 'postMessage'
        )       
    );
    $wp_customize->add_control( 'h6_size', array(
        'type'        => 'number',
        'priority'    => 22,
        'section'     => 'bluefly_fonts',
        'label'       => __('H6字体大小', 'bluefly'),
        'input_attrs' => array(
            'min'   => 10,
            'max'   => 60,
            'step'  => 1,
            'style' => 'margin-bottom: 15px; padding: 10px;',
        ),
    ) );
    //Body
    $wp_customize->add_setting(
        'body_size',
        array(
            'sanitize_callback' => 'absint',
            'default'           => '16',
            'transport'         => 'postMessage'
        )       
    );
    $wp_customize->add_control( 'body_size', array(
        'type'        => 'number',
        'priority'    => 23,
        'section'     => 'bluefly_fonts',
        'label'       => __('正文字体大小', 'bluefly'),
        'input_attrs' => array(
            'min'   => 10,
            'max'   => 24,
            'step'  => 1,
            'style' => 'margin-bottom: 15px; padding: 10px;',
        ),
    ) );

    //___Colors___//
    //Primary color
    $wp_customize->add_setting(
        'primary_color',
        array(
            'default'           => '#23B6B6',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'primary_color',
            array(
                'label'         => __('主色调', 'bluefly'),
                'section'       => 'colors',
                'settings'      => 'primary_color',
                'priority'      => 12
            )
        )
    );
    //Site title
    $wp_customize->add_setting(
        'site_title_color',
        array(
            'default'           => '#f9f9f9',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage'
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'site_title_color',
            array(
                'label' => __('网站标题', 'bluefly'),
                'section' => 'colors',
                'settings' => 'site_title_color',
                'priority' => 13
            )
        )
    );
    //Site desc
    $wp_customize->add_setting(
        'site_desc_color',
        array(
            'default'           => '#dddddd',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage'
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'site_desc_color',
            array(
                'label' => __('副标题', 'bluefly'),
                'section' => 'colors',
                'priority' => 14
            )
        )
    );
    //Body
    $wp_customize->add_setting(
        'body_text_color',
        array(
            'default'           => '#50545C',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage'
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'body_text_color',
            array(
                'label' => __('正文文字', 'bluefly'),
                'section' => 'colors',
                'settings' => 'body_text_color',
                'priority' => 15
            )
        )
    );
    //Entry titles
    $wp_customize->add_setting(
        'entry_titles',
        array(
            'default'           => '#000',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage'
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'entry_titles',
            array(
                'label' => __('各级标题', 'bluefly'),
                'section' => 'colors',
                'priority' => 16
            )
        )
    );  
    //Entry meta
    $wp_customize->add_setting(
        'entry_meta',
        array(
            'default'           => '#9d9d9d',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage'
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'entry_meta',
            array(
                'label' => __('附加信息', 'bluefly'),
                'section' => 'colors',
                'priority' => 17
            )
        )
    );
    //Sidebar
    $wp_customize->add_setting(
        'sidebar_bg',
        array(
            'default'           => '#17191B',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage'
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'sidebar_bg',
            array(
                'label' => __('侧栏背景', 'bluefly'),
                'section' => 'colors',
                'priority' => 18
            )
        )
    );
    //Sidebar color
    $wp_customize->add_setting(
        'sidebar_color',
        array(
            'default'           => '#f9f9f9',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage'
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'sidebar_color',
            array(
                'label' => __('侧栏文字', 'bluefly'),
                'section' => 'colors',
                'priority' => 19
            )
        )
    );

    //Footer
    $wp_customize->add_setting(
        'footer_background',
        array(
            'default'           => '#17191B',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'footer_background',
            array(
                'label' => __('页脚', 'bluefly'),
                'section' => 'colors',
                'priority' => 20
            )
        )
    ); 

    //___Theme info___//
    $wp_customize->add_section(
        'bluefly_extensions',
        array(
            'title' => __('Theme extensions', 'bluefly'),
            'priority' => 99,
            'description' => __('Extensions for bluefly can be found ', 'bluefly') . '<a target="_blank" href="http://flyfreemedia.com/bluefly/extensions">' . __('here', 'bluefly') . '</a>',
        )
    );
    $wp_customize->add_setting('bluefly_theme_ext', array(
            'type'              => 'info_control',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'esc_attr',            
        )
    );
    $wp_customize->add_control( new bluefly_Theme_Info( $wp_customize, 'extensions', array(
        'section' => 'bluefly_extensions',
        'settings' => 'bluefly_theme_ext',
        'priority' => 10
        ) )
    );             

}
add_action( 'customize_register', 'bluefly_customize_register' );

/**
 * Sanitize
 */
//Text
function bluefly_sanitize_text( $input ) {
    return wp_kses_post( force_balance_tags( $input ) );
}
// Logo style
function bluefly_sanitize_logo_style( $input ) {
    $valid = array(
                'hide-title'  => __( '只有 logo', 'bluefly' ),
                'show-title'  => __( 'Logo 和标题', 'bluefly' ),
            );
    if ( array_key_exists( $input, $valid ) ) {
        return $input;
    } else {
        return '';
    }
}
//Checkboxes
function bluefly_sanitize_checkbox( $input ) {
    if ( $input == 1 ) {
        return 1;
    } else {
        return '';
    }
}
/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function bluefly_customize_preview_js() {
	wp_enqueue_script( 'bluefly_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20130508', true );
}
add_action( 'customize_preview_init', 'bluefly_customize_preview_js' );
