<?php
add_action( 'init', 'create_modown_blog_item' );
function create_modown_blog_item() {
    register_post_type( 'blog',
        array(
            'labels' => array(
                'name' => _MBT('blog_name')?_MBT('blog_name'):'博客',
                'singular_name' => 'blog',
                'add_new' => '发布'.(_MBT('blog_name')?_MBT('blog_name'):'博客'),
                'add_new_item' => '发布新'.(_MBT('blog_name')?_MBT('blog_name'):'博客'),
                'edit' => '编辑',
                'edit_item' => '编辑'.(_MBT('blog_name')?_MBT('blog_name'):'博客'),
                'new_item' => '新'.(_MBT('blog_name')?_MBT('blog_name'):'博客'),
                'view' => '查看',
                'view_item' => '查看'.(_MBT('blog_name')?_MBT('blog_name'):'博客'),
                'search_items' => '搜索'.(_MBT('blog_name')?_MBT('blog_name'):'博客'),
                'not_found' => '暂无'.(_MBT('blog_name')?_MBT('blog_name'):'博客'),
                'not_found_in_trash' => '垃圾箱里暂无'.(_MBT('blog_name')?_MBT('blog_name'):'博客'),
                'parent' => '父'.(_MBT('blog_name')?_MBT('blog_name'):'博客')
            ),
 			'rewrite' => array('slug' => 'blog','with_front' => false),
            'public' => true,
			'show_in_nav_menus' => true,
            'menu_position' => 9,
            'supports' => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments' ),
            'menu_icon' => 'dashicons-flag',
            'has_archive' => true,
            'capability_type' => 'blog',
            'capabilities' => array(
                'publish_posts' => 'publish_blogs',
                'edit_posts' => 'edit_blogs',
                'edit_others_posts' => 'edit_others_blogs',
                'read_private_posts' => 'read_private_blogs',
                'edit_post' => 'edit_blog',
                'delete_post' => 'delete_blog',
                'read_post' => 'read_blog',
            ),
        )
    );


    $post_texonomys = _MBT('post_taxonomy');
    if($post_texonomys){
        $post_texonomys = explode('|', $post_texonomys);
        foreach ($post_texonomys as $post_texonomy) {
            $post_texonomy = explode(',', $post_texonomy);
            $labels = array(
                'name' => _x( $post_texonomy[0], 'taxonomy general name' ),
                'singular_name' => _x( $post_texonomy[0], 'taxonomy singular name' ),
                'search_items' =>  __( '按'.$post_texonomy[0].'搜索' ),
                'all_items' => __( '所有'.$post_texonomy[0] ),
                'parent_item' => __( '上级'.$post_texonomy[0] ),
                'parent_item_colon' => __( '上级'.$post_texonomy[0].':' ),
                'edit_item' => __( '编辑'.$post_texonomy[0] ),
                'update_item' => __( '更新'.$post_texonomy[0] ),
                'add_new_item' => __( '新增'.$post_texonomy[0] ),
                'new_item_name' => __( '新'.$post_texonomy[0] ),
            );

            register_taxonomy($post_texonomy[1],'post',array(
                'hierarchical' => true,
                'show_in_rest' => true,
                'labels' => $labels
            ));
        }
    }
}

function add_modown_capability(){
    $administrator = get_role('administrator');
    $administrator->add_cap('publish_blogs');
    $administrator->add_cap('edit_blogs');
    $administrator->add_cap('edit_others_blogs');
    $administrator->add_cap('read_private_blogs');
    $administrator->add_cap('read_blog');
    $administrator->add_cap('edit_blog');
    $administrator->add_cap('delete_blog');

    $editor = get_role('editor');
    $editor->add_cap('publish_blogs');
    $editor->add_cap('edit_blogs');
    $editor->add_cap('edit_others_blogs');
    $editor->add_cap('read_private_blogs');
    $editor->add_cap('read_blog');
    $editor->add_cap('edit_blog');
    $editor->add_cap('delete_blog');
}
add_action('admin_init', 'add_modown_capability');