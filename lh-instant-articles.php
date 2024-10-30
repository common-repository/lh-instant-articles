<?php
/**
 * Plugin Name: LH Instant Articles
 * Plugin URI: http://lhero.org/portfolio/lh-instant-articles/
 * Description: Adds a customised Instant Articles feed to Wordpress
 * Author: Peter Shaw
 * Text Domain: lh_instant_articles
 * Domain Path: /languages
 * Version: 1.20
 * Requires PHP: 5.3
 * Author URI: https://shawfactor.com/
 * License: http://www.gnu.org/copyleft/gpl.html
*/


/*
 * LH Instant Articles - Better Intsant Article handling
 * Copyright 2017  Peter Shaw  (email : pete@localhero.biz)
 *
 * Released under the GPL license
 */


/*
*  LH Instant Articles
*
*  @description:
*/

if (!class_exists('LH_instant_articles_plugin')) {

class LH_instant_articles_plugin {

var $filename;
var $options;
var $namespace = 'lh_instant_articles';
var $thumbnail_name = 'lh_instant_articles-thumbnail_name';
var $approved_types_field_name = 'lh_instant_articles-approved_types_field_name';
var $opt_name = 'lh_instant_articles-options';
var $fb_pages_field_name = 'lh_instant_articles-fb_pages';
var $excluded_post_formats_field_name = 'lh_instant_articles-excluded_post_formats';
var $tracking_code_field_name = 'lh_instant_articles-tracking_code_field_name';
var $advertisement_code_field_name = 'lh_instant_articles-advertisement_code_field_name';
var $footer_message_field_name = 'lh_instant_articles-footer_message';


private static $instance;

static function return_not_empty_html($first,$html,$second){

if (isset($html) and !empty($html)) {

return $first.$html.$second;

}



}


private function curpageurl() {
	$pageURL = 'http';

	if ((isset($_SERVER["HTTPS"])) && ($_SERVER["HTTPS"] == "on")){
		$pageURL .= "s";
}

	$pageURL .= "://";

	if (($_SERVER["SERVER_PORT"] != "80") and ($_SERVER["SERVER_PORT"] != "443")){
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];

	} else {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];

}

	return $pageURL;
}

  
  
private function get_supported_post_types() {

if (is_array($this->options[ $this->approved_types_field_name ])){

$types = $this->options[ $this->approved_types_field_name ];


} else {

$types =  array( 'post' );

}

return $types;


}



private function return_allowed_tags_wp_kses(){

if ( !current_user_can( 'publish_posts' ) ){

return array();

} else {

// Allow iframes and the following attributes

$allowedposttags = array();

	$allowedposttags['a'] = array(
		'href' => true
);

	$allowedposttags['script'] = array(
		'src' => true,
		'async' => true,
		'defer' => true,
);


	$allowedposttags['ins'] = array(
		'class' => true,
		'style' => true,
		'data-ad-client'  => true,
		'data-ad-slot' => true,
		'data-ad-format'  => true,
);

	$allowedposttags['iframe'] = array(
		'width' => true,
		'height' => true,
		'frameborder' => true,
		'name' => true,
		'src' => true,
		'id' => true,
		'class' => true,
	);

	$allowedposttags['figure'] = array(
		'class' => true
);
	return $allowedposttags;

}




}


public function instant_articles_output_xml() {

header('Content-Type: ' . feed_content_type('rss-http') . '; charset=' . get_option('blog_charset'), true);

echo '<?xml version="1.0" encoding="'.get_option('blog_charset').'"?'.'>';
?>
<rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:wfw="http://wellformedweb.org/CommentAPI/"
xmlns:dc="http://purl.org/dc/elements/1.1/"
xmlns:atom="http://www.w3.org/2005/Atom"
xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
>
<channel>
<title><?php wp_title_rss(); ?></title>
<atom:link href="<?php self_link(); ?>" rel="self" type="application/rss+xml" />
<link><?php bloginfo_rss('url') ?></link>
<description><?php bloginfo_rss("description") ?></description>
<lastBuildDate><?php echo mysql2date('D, d M Y H:i:s +0000', get_lastpostmodified('GMT'), false); ?></lastBuildDate>
<language><?php bloginfo_rss( 'language' ); ?></language>
<sy:updatePeriod><?php echo apply_filters( 'rss_update_period', 'hourly' ); ?></sy:updatePeriod>
<sy:updateFrequency><?php echo apply_filters( 'rss_update_frequency', '1' ); ?></sy:updateFrequency>

<?php while( have_posts()) : the_post(); ?>
<item>
<title><?php the_title_rss() ?></title>
<link><?php the_permalink() ?></link>
<description><![CDATA[<?php the_excerpt_rss() ?>]]></description>
<dc:creator><?php the_author() ?></dc:creator>
<pubDate><?php echo get_post_time('c', true); ?></pubDate>
<modDate><?php echo get_post_modified_time('c', true); ?></modDate>
<guid isPermaLink="false"><?php the_permalink(); ?></guid>
<content:encoded><![CDATA[<?php echo lh_instant_articles_output_content(); ?>]]></content:encoded>
</item>
<?php endwhile; ?>
</channel>
</rss><?php

}



public function add_feeds() {

add_feed('lh-instant-articles', array($this,"instant_articles_output_xml"));

}

public function plugin_menu() {
    
   
add_options_page( __('LH Instant Articles', $this->namespace ), __('Instant Articles', $this->namespace ), 'manage_options', $this->filename, array($this,"plugin_options"));

//add_action( 'admin_init', array($this,"register_menu"));

}


public function plugin_options() {

if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}

   
 // See if the user has posted us some information
    // If they did, the nonce will be set

	if( isset($_POST[ $this->namespace."-backend_nonce" ]) && wp_verify_nonce($_POST[ $this->namespace."-backend_nonce" ], $this->namespace."-backend_nonce" )) {





if (isset($_POST[ $this->fb_pages_field_name]) and ($_POST[ $this->fb_pages_field_name] != "")){
$options[ $this->fb_pages_field_name ] = sanitize_text_field($_POST[ $this->fb_pages_field_name ]);
}





//need to add some appropriate validation here

if (is_array($_POST[ $this->approved_types_field_name] )){

$options[ $this->approved_types_field_name ] = $_POST[ $this->approved_types_field_name ];

}







if (isset($_POST[ $this->tracking_code_field_name]) and ($_POST[ $this->tracking_code_field_name] != "")){
$options[ $this->tracking_code_field_name ] = wp_kses(trim($_POST[ $this->tracking_code_field_name ]), $this->return_allowed_tags_wp_kses());

}


if (isset($_POST[ $this->advertisement_code_field_name]) and ($_POST[ $this->advertisement_code_field_name] != "")){
$options[ $this->advertisement_code_field_name ] = wp_kses(trim($_POST[ $this->advertisement_code_field_name ]), $this->return_allowed_tags_wp_kses());
}


if (isset($_POST[ $this->footer_message_field_name]) and ($_POST[ $this->footer_message_field_name] != "")){
$options[ $this->footer_message_field_name ] = wp_kses(trim($_POST[ $this->footer_message_field_name ]), $this->return_allowed_tags_wp_kses());

}



if (update_option( $this->opt_name, $options )){

$this->options = get_option($this->opt_name);

?>
<div class="updated"><p><strong><?php _e('Values saved', $this->namespace ); ?></strong></p></div>
<?php


}


}




include ('partials/option-settings.php');

}

public function modify_wp_query($wp_query) {



if (($wp_query->query_vars['feed'] == 'lh-instant-articles') and $wp_query->is_main_query()  ){

$exclude[] = 'no';


        $post_format_tax_query = array(
            'taxonomy' => 'lh_instant_articles-syndicate',
            'field' => 'slug',
            'terms' => $exclude, // Change this to the formats you want to exclude
            'operator' => 'NOT IN'
        );
        $tax_query = $wp_query->get( 'tax_query' );
        if ( is_array( $tax_query ) ) {
            $tax_query = $tax_query + $post_format_tax_query;
        } else {
            $tax_query = array( $post_format_tax_query );
        }
        $wp_query->set( 'tax_query', $tax_query );
	$wp_query->set( 'orderby', 'modified' );


if (is_array($this->options[ $this->approved_types_field_name ])){

foreach ($this->options[ $this->approved_types_field_name ] as $post_type){

$types[] = $post_type;

}

$wp_query->set( 'post_type', $types );


}


 }

	return $wp_query;

	}


public function create_taxonomies() {

if (is_array($this->options[ $this->approved_types_field_name ])){

$types = $this->options[ $this->approved_types_field_name ];


} else {

$types =  array( 'post' );

}

register_taxonomy(
    'lh_instant_articles-syndicate',
   $types,
    array(
        'hierarchical' => false,
        'show_ui' => false
    )
);


}

public function render_metabox_content( $post, $callback_args ){

$terms = wp_get_post_terms( $post->ID, 'lh_instant_articles-syndicate');

if (isset($terms[0]->name)){
    
    $yes_no = $terms[0]->name;
    
    
} else {
    
    $yes_no = apply_filters( 'lh_instant_articles_default_option', "yes", $post, $callback_args);
    
}

wp_nonce_field( $this->namespace."-syndicate_post-nonce", $this->namespace."-syndicate_post-nonce" );

?>
<select name="lh_instant_articles-syndicate_post" id="lh_instant_articles-syndicate_post">
<option value="yes" <?php if ($yes_no == 'yes') { echo 'selected="selected"';  } ?>>Yes</option>
<option value="no" <?php if ($yes_no == 'no') { echo 'selected="selected"';  } ?>>No</option>
</select>
<?php



}



public function add_meta_boxes($post_type, $post)  {

if (is_array($this->options[ $this->approved_types_field_name ])){

$types = $this->options[ $this->approved_types_field_name ];


} else {

$types =  array( 'post' );

}

if (in_array($post->post_type, $types)) {

add_meta_box($this->namespace."-send_to_ia-div", "Send to Instant Articles", array($this,"render_metabox_content"), $post_type, "side", "low", array());

}

}

public function add_html_meta(){

if (isset($this->options[$this->fb_pages_field_name])){

echo "\n<!-- begin LH Instant Articles meta output -->\n";
echo '<meta property="fb:pages" content="'.$this->options[$this->fb_pages_field_name].'" />';
echo "\n<!-- end LH Instant Articles meta output -->\n";

}



}


public function filter_content($content){

if (!class_exists('lh-html-dom-fixer-class.php')) {


require_once('includes/lh-html-dom-fixer-class.php');


}

$args = array();

// create instance
$lh_html_dom_fixer_instance = new LH_Html_dom_fixer_class($args);


$body = $lh_html_dom_fixer_instance->run_from_html_doc_as_string_return_body_content('<!doctype html><html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body>'.$content.'</body><html>');

if (isset($this->options[$this->tracking_code_field_name])){

$body .= '<figure class="op-tracker">'.$this->options[$this->tracking_code_field_name].'</figure>';

}

return $body;

}


public function filter_final_output($content){


//remove line breaks
return str_replace(array("\n", "\r"), '', $content);

}



public function include_tracking($content){

if ( isset( $this->options[$this->tracking_code_field_name] )){


$content .= '<figure class="op-tracker">'.$this->options[$this->tracking_code_field_name].'</figure>';


}


return content;

}





public function on_activate($network_wide) {

    if ( is_multisite() && isset($network_wide) ) { 

        global $wpdb;

        foreach ($wpdb->get_col("SELECT blog_id FROM $wpdb->blogs") as $blog_id) {
            switch_to_blog($blog_id);
wp_schedule_single_event(time(), 'lh_instant_articles_run');
            restore_current_blog();
        } 

    } else {


$this->run_initial_processes();

}

}

public function run_initial_processes(){

//flush the rewrite rules
flush_rewrite_rules();

$this->create_taxonomies();

wp_insert_term(
  'yes', // the term 
  'lh_instant_articles-syndicate', // the taxonomy
  array(
    'description'=> 'Send to Instant Articles',
    'slug' => 'yes'
  )
);


wp_insert_term(
  'no', // the term 
  'lh_instant_articles-syndicate', // the taxonomy
  array(
    'description'=> 'Do not send to Instant Articles',
    'slug' => 'no'
  )
);

wp_clear_scheduled_hook( 'lh_instant_articles_run' ); 

}

// add a settings link next to deactive / edit
public function add_settings_link( $links, $file ) {

	if( $file == $this->filename ){
		$links[] = '<a href="'. admin_url( 'options-general.php?page=' ).$this->filename.'">Settings</a>';
	}
	return $links;
}

public function lh_instant_articles_meta_filter($meta_content, $post_id){

if (isset( $this->options[$this->advertisement_code_field_name])){

$meta_content .= '<meta property="fb:use_automatic_ad_placement" content="enable=true ad_density=default">';

} 

return $meta_content;

}

public function lh_instant_articles_figure_tag_filter($figure, $post_id){


//$object = get_post($post_id);

//$foo = get_media_embedded_in_content( apply_filters( 'the_content', $object->post_content), array('video', 'iframe' ) );


if (get_the_post_thumbnail_url($post_id, 'full')){


$try = '<img src="'.get_the_post_thumbnail_url($post_id, 'full').'"/>';


}

if (isset($try)){

return $try;



} else {

return $figure;

}
 

}


public function lh_instant_articles_header_filter($header_content, $post_id){

if (isset( $this->options[$this->advertisement_code_field_name])){

$header_content .= '<section class="op-ad-template">'.$this->options[$this->advertisement_code_field_name].'</section>';

} 

return $header_content;

}

public function lh_instant_articles_footer_generic_filter($aside, $post_id){

if (isset( $this->options[$this->footer_message_field_name])){

$aside .= '<aside>'.$this->options[$this->footer_message_field_name].'</aside>';

}

return $aside;


}

public function update_post_syndication( $post_id, $post, $update ) {

if (isset($_POST[$this->namespace.'-syndicate_post-nonce']) and wp_verify_nonce( $_POST[$this->namespace.'-syndicate_post-nonce'], $this->namespace.'-syndicate_post-nonce')){

if (($_POST[$this->namespace."-syndicate_post"] == 'yes') || ($_POST[$this->namespace."-syndicate_post"] == 'no')){

wp_set_post_terms( $post_id, array($_POST[$this->namespace."-syndicate_post"]), 'lh_instant_articles-syndicate');

}

}


}




public function pre_get_posts($query){

global $et_bloom;

if ( is_feed() ){
remove_filter( 'the_content', array($et_bloom, 'display_below_post'), 9999 );
remove_filter( 'the_content', array($et_bloom, 'trigger_bottom_mark'), 9999 );
}


}


public function plugins_loaded(){


load_plugin_textdomain( 'lh_instant_articles', false, basename( dirname( __FILE__ ) ) . '/languages' ); 

}

    /**
     * Gets an instance of our plugin.
     *
     * using the singleton pattern
     */
    public static function get_instance(){
        if (null === self::$instance) {
            self::$instance = new self();
        }
 
        return self::$instance;
    }




public function __construct() {


/* Initialize the plugin */
$this->filename = plugin_basename( __FILE__ );
$this->options = get_option($this->opt_name);

/* Add menu */
add_action('admin_menu', array($this, 'plugin_menu'));

/* Add html fb:page meta tag */
add_action('wp_head', array($this, 'add_html_meta'));

add_action( 'init', array($this,"create_taxonomies"),9); 
add_action('init', array($this,"add_feeds"),11);


add_action( 'pre_get_posts', array($this,"modify_wp_query"));
  

add_action('add_meta_boxes', array($this,"add_meta_boxes"),10,2);

add_filter('lh_instant_articles_meta_filter', array( $this, 'lh_instant_articles_meta_filter' ),10,2);

add_filter('lh_instant_articles_figure_tag_filter', array( $this, 'lh_instant_articles_figure_tag_filter' ),10,2);

add_filter('lh_instant_articles_header_filter', array( $this, 'lh_instant_articles_header_filter' ),10,2);


add_filter('lh_instant_articles_content_filter', array( $this, 'filter_content' ),10,1);
//add_filter('lh_instant_articles_content_filter', array( $this, 'include_tracking' ),20,1);

add_filter('lh_instant_articles_footer_generic_filter', array( $this, 'lh_instant_articles_footer_generic_filter' ),10,2);
add_filter('lh_instant_articles_output_buffer_filter', array( $this, 'filter_final_output' ),10,1);


//Hook to attach processes to cron job
add_action('lh_instant_articles_run', array($this,"run_initial_processes"));

add_filter('plugin_action_links', array($this,"add_settings_link"), 10, 2);

add_action( 'save_post', array($this,"update_post_syndication"),10,3);

//hook to remove bloom opt in crap
add_action( 'pre_get_posts', array($this,"pre_get_posts"));

//various plugins loaded actions, currently only make it semi translation ready
add_action( 'plugins_loaded', array($this,"plugins_loaded"));


}

}

$lh_instant_articles_instance = LH_instant_articles_plugin::get_instance();
register_activation_hook(__FILE__, array($lh_instant_articles_instance, 'on_activate') , 10, 1);

}



/*
*  A pluggable function for outputting the content
*
*  @description:
*/


if ( ! function_exists( 'lh_instant_articles_output_content' ) ) {

function lh_instant_articles_output_content() {
    
    $content = '';

ob_start(); ?>
<!doctype html>
<html lang="<?php echo get_bloginfo("language"); ?>" prefix="op: http://media.facebook.com/op#">
<head>
<?php $url = wp_get_canonical_url(); if (!empty( $url ) ) { echo '<link rel="canonical" href="' . esc_url( $url ) . '" />'; } ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" title="default" href="#">
<title><?php the_title_rss() ?></title>
<?php echo apply_filters( 'lh_instant_articles_meta_filter', '', get_the_ID()); ?>
</head>
<body>
<article>
<header>
<!-- The header image shown inside your article -->
<figure><?php echo apply_filters( 'lh_instant_articles_figure_tag_filter', get_the_author_meta( 'display_name' ), get_the_ID()); ?></figure>
<!-- The title and subtitle shown in your article -->
<?php the_title( '<h1>', '</h1>' ); ?>

<!-- A kicker for your article -->
<?php
  
if (has_category()){
	
$categories = get_the_category();

echo LH_instant_articles_plugin::return_not_empty_html('<h3 class="op-kicker">',$categories[0]->name,'</h3>'); 
  
  }
  
  ?>
<!-- The author of your article -->
<address><?php echo apply_filters( 'lh_instant_articles_address_tag_filter', get_the_author_meta( 'display_name' ), get_the_ID()); ?></address>

      <!-- The published and last modified time stamps -->
       <time class="op-published" dateTime="<?php echo get_post_time('c', true); ?>"><?php the_date(); ?></time>
      <time class="op-modified" dateTime="<?php echo get_post_modified_time('c', true); ?>"><?php the_modified_date(); ?></time>
<?php echo apply_filters( 'lh_instant_articles_header_filter', '', get_the_ID()); ?>
    </header>



<?php echo apply_filters( 'lh_instant_articles_content_filter', apply_filters( 'the_content', get_the_content())); ?>
<footer>
<?php echo LH_instant_articles_plugin::return_not_empty_html('', apply_filters( 'lh_instant_articles_footer_generic_filter', '', get_the_ID()), ''); ?>

<?php echo apply_filters( 'lh_instant_articles_related_articles_filter', '', get_the_ID()); ?>
</footer>
</article></body></html><?php

if (ob_get_contents()){ 

$content .= ob_get_contents();
ob_end_clean();

}

$content = apply_filters( 'lh_instant_articles_output_buffer_filter', $content);

return $content;

}


}

?>