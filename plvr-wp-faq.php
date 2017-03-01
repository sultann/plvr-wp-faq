<?php

/**
 * Plugin Name: PLVR WP FAQ Lite
 * Plugin URI: http://wordpress.org/plugins/plvr-wp-faq/
 * Description: Adds FAQ System on any WordPress powered site. The plugin is based on custom post type to handle FAQ items. The plugin comes with FAQ category support.
 * Author: PluginEver
 * Version: 1.0
 * Author URI: https://pluginever.com/
 */

if(!class_exists('PLVR_WP_FAQ')):
class PLVR_WP_FAQ
{
    /**
     * PLVR_WP_FAQ constructor.
     */
    public function __construct()
    {

        $this->define_constants();
        $this->hooks();
        $this->includes();
    }


    public function define_constants(){
        if (!defined('PLVR_WPFAQ_PLUGIN_DIR')) {
            define('PLVR_WPFAQ_PLUGIN_DIR', plugin_dir_path(__FILE__));
        }

        if (!defined('PLVR_WPFAQ_PLUGIN_URL')) {
            define('PLVR_WPFAQ_PLUGIN_URL', plugin_dir_url(__FILE__));
        }
    }


    public function hooks(){
        add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array( __CLASS__, 'plugin_action_links' ) );

        add_action('init', array($this, 'register_shortcode'), 0);
        add_action('wp_enqueue_scripts', array($this, 'register_scripts'), 10);
    }

    public function  includes(){
        require_once PLVR_WPFAQ_PLUGIN_DIR.'inc/plvr-wp-faq-cpt.php';
    }


    /**
     * @param $links
     *
     * @return array
     */
    public static function plugin_action_links($links){
        $action_links = array(
            'Documentation' => '<a target="_blank" href="https://www.pluginever.com/docs/wp-faq-plugin-documentation/" title="' . esc_attr( __( 'View Plugin Documentation', 'plvrwpfaq' ) ) . '">' . __( 'Documentation', 'plvrwpfaq' ) . '</a>',
        );

        return array_merge( $action_links, $links );
    }


    public function register_scripts()
    {
        wp_enqueue_style('plvr-wp-faq', plugins_url('/css/style.min.css', __FILE__));
        wp_register_script('plvr-wp-faq', plugins_url('/js/script.min.js', __FILE__), array('jquery'), NULL, false);
        wp_enqueue_script('plvr-wp-faq');
    }



    public function register_shortcode()
    {
        add_shortcode('plvr_faq', array($this, 'plvr_faq_shortcode_callback'));
    }


    public function plvr_faq_shortcode_callback($atts){
        $categories = $has_border =  $order = $orderby = $has_gap =  $theme = $title = $output = '';

        extract(shortcode_atts(array(
            'categories' => '',
            'has_border' => true,
            'order' => 'DSC',
            'orderby' => 'title',
            'has_gap' => true,
            'title' => '',
        ), $atts));

        $faq_query = new WP_Query(array(
            'post_type' => 'plvr_faq',
            'orderby' => $orderby,
            'order' => $order,
            'posts_per_page' => -1,
            'faq_cat' => $categories,
        ));


        $classes = array();

        if($has_border !== false){
            $classes[] =  'has-border';
        }else{
            $classes[] =  'no-border';
        }

        if($has_gap !== false){
            $classes[] =  'has-gap';
        }


        ob_start();
        ?>
        <?php if($title != ''): ?>
            <h2 class="plvr-faq-category-title"><?php echo $title; ?></h2>
        <?php endif;?>
        <ul class="plvr-faq-list basic-version is-icon-left <?php echo implode(' ', $classes);?>">
            <?php if ($faq_query->have_posts()) : while ($faq_query->have_posts()) : $faq_query->the_post(); ?>
                <?php include 'templates/content-loop.php'; ?>
            <?php endwhile; ?>
            <?php else: ?>
             <?php //no posts found ?>
            <?php endif; ?>

        </ul>
        <?php
        wp_reset_postdata();  // Reset
        $output = ob_get_contents();

        ob_get_clean();

        return $output;



    }

}
endif;
new PLVR_WP_FAQ();
