<?php
/**
 * bluefly 主题自定义页
 *
 * @package bluefly
 */

//PRIMARY #23B6B6
//SECOND #B524B5
//THIRD #B2B223
//优先级稍微比默认调用晚一点儿，这样就能替换掉原来的英文标题和描述了。
add_action('customize_register', 'bluefly_customize_register', 11);

/**
 * 作用: 主题自定义面板的追加和修改
 * 来源: Oblique原版，破袜子修改
 * 参考资料: https://developer.wordpress.org/reference/classes/wp_customize_manager/
 */
function bluefly_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->remove_control( 'header_textcolor' );
    $wp_customize->remove_control( 'display_header_text' );
    $wp_customize->remove_section( 'background_image' );
	$wp_customize->remove_section( 'static_front_page' );

	//下面几行没什么大作用，仅仅为了将自定义菜单标题全部中文化而已。
	$wp_customize->get_panel( 'nav_menus' )->title = '菜单' ;
	$wp_customize->get_panel( 'nav_menus' )->description = '<p>这个面版用来管理导航菜单。你可以创建和变更菜单</p><p>菜单可以显示在主题的指定区域或者<a href="javascript:wp.customize.panel( \'widgets\' ).focus();">小工具</a>栏里&#8220;自定义菜单&#8221;中。</p>' ;
	if ($wp_customize->get_panel( 'widgets' ))
		$wp_customize->get_panel( 'widgets' )->title = '小工具';
	if ($wp_customize->get_panel( 'colors' ))
		$wp_customize->get_section( 'colors' )->title = '颜色';
	if ($wp_customize->get_panel( 'header_image' ))
		$wp_customize->get_section( 'header_image' )->title = '题头图片';
	if ($wp_customize->get_panel( 'title_tagline' ))
		$wp_customize->get_section( 'title_tagline' )->title = '站点标识';
	//汉化完毕
	
    //自定义一个显示控件
    class bluefly_Titles extends WP_Customize_Control {
        public $type = 'titles';
        public $label = '';
		public $description = '';
        public function render_content() {
        ?>
            <h3 style="padding: 10px; border: 1px solid #DF7B7B; color: #DF7B7B;"><?php echo esc_html( $this->label ); ?></h3>
			<p style="font-style: italic;"><?php echo esc_html( $this->description ); ?></p>
        <?php
        }
    }

    //题头选项//
    $wp_customize->add_section(
        'bluefly_header',
        array(
            'title' => '题头选项',
            'priority' => 10,
        )
    );
    //上传LOGO
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
               'label'          => '上传LOGO',
               'type'           => 'image',
               'section'        => 'bluefly_header',
               'settings'       => 'site_logo',
               'priority'       => 11,
            )
        )
    );
    //LOGO大小
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
        'label'       => 'LOGO大小',
        'description' => 'LOGO最大像素，默认200px',
        'input_attrs' => array(
            'min'   => 50,
            'max'   => 600,
            'step'  => 5,
            'style' => 'margin-bottom: 15px; padding: 15px;',
        ),
    ) );
    //LOGO风格
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
            'label'         => 'LOGO风格',
            'description'   => '只有设置logo时此选项才有用',
            'section'       => 'bluefly_header',
            'priority'      => 13,
            'choices'       => array(
                'hide-title'  => '只有LOGO',
                'show-title'  => 'LOGO和标题同时显示',
            ),
        )
    );
    //填充
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
        'label'       => '留白',
        'description' => '上下留白高度。 默认: 150px',
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
        'label'       => '留出空白的大小（小于1024px的设备）',
        'description' => '上下留白高度。 默认: 100px',
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
            'title' => '内容选项',
            'priority' => 10,
        )
    );
 
    $wp_customize->add_setting(
      'infinite_index',
      array(
        'sanitize_callback' => 'bluefly_sanitize_checkbox',
        'default' => 0,     
      )   
    );
	
    $wp_customize->add_control(
      'infinite_index',
      array(
        'type' => 'checkbox',
        'label' => '首页自动无限加载?',
        'section' => 'blog_options',
        'priority' => 11,
      )
    );
	
	$wp_customize->add_setting(
      'infinite_archive',
      array(
        'sanitize_callback' => 'bluefly_sanitize_checkbox',
        'default' => 0,     
      )   
    );
    $wp_customize->add_control(
      'infinite_archive',
      array(
        'type' => 'checkbox',
        'label' => '存档页自动无限加载?',
        'section' => 'blog_options',
        'priority' => 12,
      )
    );
	
    //Index images
    $wp_customize->add_setting(
        'hide_index_feat_image',
        array(
            'sanitize_callback' => 'bluefly_sanitize_checkbox',
        )       
    );
    $wp_customize->add_control(
        'hide_index_feat_image',
        array(
            'type' => 'checkbox',
            'label' => '列表中隐藏特色图片？',
            'section' => 'blog_options',
            'priority' => 13,
        )
    );
    //Post images
    $wp_customize->add_setting(
        'background_feat_image',
        array(
            'sanitize_callback' => 'bluefly_sanitize_checkbox',
        )       
    );
    $wp_customize->add_control(
        'background_feat_image',
        array(
            'type' => 'checkbox',
            'label' => '使用特色图片作为背景?',
            'section' => 'blog_options',
            'priority' => 14,
        )
    );


    //___Fonts___//
    $wp_customize->add_section(
        'bluefly_fonts',
        array(
            'title' => '字体大小',
            'priority' => 10,
            'description' => '设置字体大小',
        )
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
        'priority'    => 11,
        'section'     => 'bluefly_fonts',
        'label'       => '网站主标题',
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
        'priority'    => 12,
        'section'     => 'bluefly_fonts',
        'label'       => '副标题',
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
        'priority'    => 13,
        'section'     => 'bluefly_fonts',
        'label'       => 'H1',
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
        'priority'    => 14,
        'section'     => 'bluefly_fonts',
        'label'       => 'H2',
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
        'priority'    => 15,
        'section'     => 'bluefly_fonts',
        'label'       => 'H3',
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
        'priority'    => 16,
        'section'     => 'bluefly_fonts',
        'label'       => '正文',
        'input_attrs' => array(
            'min'   => 10,
            'max'   => 24,
            'step'  => 1,
            'style' => 'margin-bottom: 15px; padding: 10px;',
        ),
    ) );

    //___Colors___//
    //Primary color
	
    $wp_customize->add_setting('bluefly_options[titles]', array(
            'type' => 'titles_control',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'esc_attr',
        )
    );
    $wp_customize->add_control( new bluefly_Titles( $wp_customize, 'colors_help', array(
        'label' => '重要颜色',
		'description' => '下面的颜色决定主题风格',
        'section' => 'colors',
        'settings' => 'bluefly_options[titles]',
        'priority' => 10
        ) )
    );    
	
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
                'label'         => '主色调',
                'section'       => 'colors',
                'settings'      => 'primary_color',
                'priority'      => 11
            )
        )
    );
	
	//主背景色
    $wp_customize->add_setting(
        'background_color',
        array(
            'default'           => '#E5E5E5',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage'
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'background_color',
            array(
                'label' => '网站背景',
                'section' => 'colors',
                'priority' => 12
            )
        )
    );
	
	//文章背景色
    $wp_customize->add_setting(
        'entry_background_color',
        array(
            'default'           => '#ffffff',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage'
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'entry_background_color',
            array(
                'label' => '文章背景',
                'section' => 'colors',
                'priority' => 13
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
                'label' => '网站标题',
                'section' => 'colors',
                'settings' => 'site_title_color',
                'priority' => 14
            )
        )
    );

    //正文文字
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
                'label' => '正文文字',
                'section' => 'colors',
                'settings' => 'body_text_color',
                'priority' => 15
            )
        )
    );
	
    //条目标题
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
                'label' => '文章标题',
                'section' => 'colors',
                'priority' => 16
            )
        )
    ); 

	//非重要
	$wp_customize->add_setting('bluefly_options[titles]', array(
            'type' => 'titles_control',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'esc_attr',
        )
    );
    $wp_customize->add_control( new bluefly_Titles( $wp_customize, 'colors_help', array(
        'label' => '可计算颜色',
		'description' => '下面的颜色可以通过主要颜色计算得到',
        'section' => 'colors',
        'settings' => 'bluefly_options[titles]',
        'priority' => 17
        ) )
    );  
	
	//通过计算生成颜色
	$wp_customize->add_setting(
      'ignore_calc_color',
      array(
        'sanitize_callback' => 'bluefly_sanitize_checkbox',
        'default' => 0,     
      )   
    );
	
    $wp_customize->add_control(
      'ignore_calc_color',
      array(
        'type' => 'checkbox',
        'label' => '不使用计算生成的颜色?',
        'section' => 'colors',
        'priority' => 18,
      )
    );
	$wp_customize->add_setting(
        'color_phase_steps',
        array(
            'sanitize_callback' => 'absint',
            'default'           => '45',
        )       
    );
    $wp_customize->add_control( 'color_phase_steps', array(
        'type'        => 'number',
        'priority'    => 19,
        'section'     => 'colors',
        'label'       => '近似色角度差',
        'description' => '计算近似色与主色调的角度差,以15的整数倍为最佳。数值越大颜色差异越大。',
        'input_attrs' => array(
            'min'   => 15,
            'max'   => 120,
            'step'  => 15,
            'style' => 'padding: 15px;',
        ),
    ) );
	
    //附加信息
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
                'label' => '附加信息',
                'section' => 'colors',
                'priority' => 20
            )
        )
    );
	
    //侧栏背景
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
                'label' => '侧栏背景',
                'section' => 'colors',
                'priority' => 21
            )
        )
    );
    //侧栏文字
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
                'label' => '侧栏文字',
                'section' => 'colors',
                'priority' => 22
            )
        )
    );

    //底部
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
                'label' => '页脚背景',
                'section' => 'colors',
                'priority' => 23
            )
        )
    );
	
    //网站描述
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
                'label' => '网站副标题',
                'section' => 'colors',
                'priority' => 24
            )
        )
    );	
	
	//第二颜色
    $wp_customize->add_setting(
        'secondary_color',
        array(
            'default'           => '#AF23AF',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage'
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'secondary_color',
            array(
                'label' => '第二颜色',
                'section' => 'colors',
                'priority' => 25
            )
        )
    );	
	
	//第三颜色
    $wp_customize->add_setting(
        'thirdly_color',
        array(
            'default'           => '#B2B223',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage'
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'thirdly_color',
            array(
                'label' => '第三颜色',
                'section' => 'colors',
                'priority' => 26
            )
        )
    );	
}

/**
 * 作用: 设置LOGO风格的回调函数
 * 来源: Oblique
 */
function bluefly_sanitize_logo_style( $input ) {
    $valid = array(
                'hide-title'  => '只有LOGO',
                'show-title'  => 'LOGO和标题',
            );
    if ( array_key_exists( $input, $valid ) ) {
        return $input;
    } else {
        return '';
    }
}

/**
 * 作用: CHECKBOX的回调函数
 * 来源: Oblique
 */
function bluefly_sanitize_checkbox( $input ) {
    if ( $input == 1 ) {
        return 1;
    } else {
        return '';
    }
}
add_action( 'customize_preview_init', 'bluefly_customize_preview_js' );

 /**
 * 作用: 绑定JS处理程序
 * 参考资料: https://developer.wordpress.org/reference/classes/wp_customize_manager/customize_preview_init/
 */
function bluefly_customize_preview_js() {
	wp_enqueue_script( 'bluefly_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20150918', true );
}
