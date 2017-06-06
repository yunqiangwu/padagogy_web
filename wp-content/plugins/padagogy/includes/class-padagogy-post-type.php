<?php

/**
 * Class P_PadagogyPostType 实现Padagogy文章的注册和编辑
 */

require_once 'AppMsgService.php';

class P_PadagogyPostType{

    // 在控制台中视图上显示的文案 ,如按钮的文章, 提示内容等
    var $labels;
    var $permissions;
    var $post_type_options;
    var $post_taxonomy_options;
    var $taxonomy_labels;



	function __construct() {

		$this->PadagogyPostType();
//        exit($this->appMsgService->getAppMsgListByName());

	}

	// 注册函数,调用此函数完成注册
	function register(){
        register_post_type( 'padagogy', $this->post_type_options );
        register_taxonomy('app_classification','padagogy',$this->post_taxonomy_options);
        add_filter('template_include',function ($template) {
            if( get_post_type() == 'padagogy' || get_queried_object()->taxonomy == 'app_classification' ||  get_queried_object()->post_type == 'padagogy'){
                if(is_single()){
                    return PADAGOGY_FILE_PATH.'/views/single.php';
                }
                if(is_archive()){
                    return PADAGOGY_FILE_PATH.'/views/archive.php';
                }
            }
           return $template;
        });
        add_filter('comments_template',function ($template) {
            if( get_post_type() == 'padagogy' || get_queried_object()->taxonomy == 'app_classification' ||  get_queried_object()->post_type == 'padagogy'){
                    return PADAGOGY_FILE_PATH.'/views/comments.php';
            }
            return $template;
        });

    }

    function set_permissions(){
    }
	
	function PadagogyPostType() {



        $this->labels = array(
            'name' => 'Padagogy APP',
            'singular_name' => 'APP',
            'name_admin_bar'     => '应用',
            'all_items'          =>  'Padagogy管理' ,
            'menu_name' => 'Padagogy',
            'add_new' => '添加应用',
            'add_new_item' => '发布新应用',
            'edit_item' => '编辑应用',
            'new_item' => '新应用',
            'view_item' => '查看应用',
            'search_items' => '搜索应用',
            'not_found' =>  '你还没用填加应用',
            'not_found_in_trash' => '回收站中没有应用',
            'parent_item_colon' => ''
        );

        $this->permissions = array(
            'edit_wiki'=>true,
            'edit_wiki_page'=>true,
            'edit_wiki_pages'=>true,
            'edit_others_wiki_pages'=>true,
            'publish_wiki_pages'=>true,
            'delete_wiki_page'=>true,
            'delete_others_wiki_pages'=>false
        );

        $this->post_type_options = array(
            'label'=> 'Padagogy Page',
            'labels'=>$this->labels,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'has_archive' => true,
            'description'=>'Padagogy',
            'public'=>true,
            'capability_type'=>'post',
            'supports' => array('title','editor','author','thumbnail','excerpt','comments','revisions','custom-fields','page-attributes'),
            'hierarchical' => false,
            'rewrite' => array('slug' => 'padagogy', 'with_front' => true)
        );

        $this->taxonomy_labels = array(
            'name'              => _x( 'APP领域分类', 'taxonomy 名称' ),
            'singular_name'     => _x( '领域分类', 'taxonomy 单数名称' ),
            'search_items'      => __( '搜索APP分类' ),
            'all_items'         => __( '所有APP分类' ),
            'parent_item'       => __( '该APP分类的上级分类' ),
            'parent_item_colon' => __( '该APP分类的上级分类：' ),
            'edit_item'         => __( '编辑APP分类' ),
            'update_item'       => __( '更新APP分类' ),
            'add_new_item'      => __( '添加新的APP分类' ),
            'new_item_name'     => __( '新APP分类' ),
            'menu_name'         => __( '领域分类' ),
        );

        $this->post_taxonomy_options = array(
            'labels' => $this->taxonomy_labels,
            'public'            => true,
            'show_in_nav_menus' => true,
            'hierarchical' => true, //控制自定义分类法的格式，如果值是false，则将分类（category）转化成标签（tags）
            'show_ui'           => true,
            'query_var'         => true,
            'rewrite'           => true,
            'show_admin_column' => true
        );

        // 将注册函数添加到系统的初始化事件中去,即在系统完成初始化的过程中完整 对象的注册
        add_action('init', array($this,'register') );
        add_action('init', array($this,'set_permissions') );
	}

}