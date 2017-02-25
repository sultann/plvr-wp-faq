<?php

if(!class_exists('PLVR_WP_FAQ_CPT')):
class PLVR_WP_FAQ_CPT{

    /**
     * PLVR_WP_FAQ_CPT constructor.
     */
    public function __construct() {
        add_action('init', array($this, 'register_faq_post'),0);
        add_action('init', array($this, 'register_faq_taxonomy'),0);
        add_filter( "manage_edit-faq_cat_columns", array($this, 'custom_column_header'), 10);
        add_action( "manage_faq_cat_custom_column", array($this, 'custom_column_content'), 10, 3);
    }

    function register_faq_post() {
        $labels = array(
            'name'               => _x( 'FAQs', 'post type general name', 'plvrwpfaq' ),
            'singular_name'      => _x( 'FAQ', 'post type singular name', 'plvrwpfaq' ),
            'menu_name'          => _x( 'FAQs', 'admin menu', 'plvrwpfaq' ),
            'name_admin_bar'     => _x( 'FAQ', 'add new on admin bar', 'plvrwpfaq' ),
            'add_new'            => _x( 'Add New', 'book', 'plvrwpfaq' ),
            'add_new_item'       => __( 'Add New FAQ', 'plvrwpfaq' ),
            'new_item'           => __( 'New FAQ', 'plvrwpfaq' ),
            'edit_item'          => __( 'Edit FAQ', 'plvrwpfaq' ),
            'view_item'          => __( 'View FAQ', 'plvrwpfaq' ),
            'all_items'          => __( 'All FAQs', 'plvrwpfaq' ),
            'search_items'       => __( 'Search FAQs', 'plvrwpfaq' ),
            'parent_item_colon'  => __( 'Parent FAQs:', 'plvrwpfaq' ),
            'not_found'          => __( 'No FAQ found.', 'plvrwpfaq' ),
            'not_found_in_trash' => __( 'No FAQ found in Trash.', 'plvrwpfaq' )
        );

        $args = array(
            'labels'             => $labels,
            'description'        => __( 'Description.', 'plvrwpfaq' ),
            'public'             => false,
            'publicly_queryable' => false,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'taxonomies'          => array( 'faq_cat' ),
            'query_var'          => true,
            'menu_icon'           => 'dashicons-editor-help',
            'rewrite'            => array( 'slug' => 'faq' ),
            'capability_type'    => 'post',
            'has_archive'        => false,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => array( 'title', 'editor')
        );

        register_post_type( 'plvr_faq', $args );
    }


    public function register_faq_taxonomy(){
        $labels = array(
            'name'              => _x( 'Categories', 'taxonomy general name', 'plvrwpfaq' ),
            'singular_name'     => _x( 'Category', 'taxonomy singular name', 'plvrwpfaq' ),
            'search_items'      => __( 'Search Categories', 'plvrwpfaq' ),
            'all_items'         => __( 'All Categories', 'plvrwpfaq' ),
            'parent_item'       => __( 'Parent Category', 'plvrwpfaq' ),
            'parent_item_colon' => __( 'Parent Category:', 'plvrwpfaq' ),
            'edit_item'         => __( 'Edit Category', 'plvrwpfaq' ),
            'update_item'       => __( 'Update Category', 'plvrwpfaq' ),
            'add_new_item'      => __( 'Add New Category', 'plvrwpfaq' ),
            'new_item_name'     => __( 'New Category Name', 'plvrwpfaq' ),
            'menu_name'         => __( 'Category', 'plvrwpfaq' ),
        );

        $args = array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
        );

        register_taxonomy( 'faq_cat', array( 'plvr_faq' ), $args );
    }
    function custom_column_header( $columns ){
        unset($columns['description']);
        $columns['short_code'] = 'Shortcode';
        return $columns;
    }




    function custom_column_content( $value, $column_name, $tax_id ){
        $tax = get_term( $tax_id, 'faq_cat' );
        echo '[plvr_faq categories="'.$tax->slug.'"]';
    }
}
endif;
new PLVR_WP_FAQ_CPT();
