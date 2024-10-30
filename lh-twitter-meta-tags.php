<?php
/**
 * Plugin Name: LH Twitter Meta Tags
 * Plugin URI: https://lhero.org/portfolio/lh-twitter-meta-tags/
 * Description: Adds twitter meta using post format logic
 * Author: Peter Shaw
 * Author URI: https://shawfactor.com
 * Version: 1.21
 * Text Domain: lh_twitter_meta_tags
 * Domain Path: /languages
*/

if (!class_exists('LH_twitter_meta_cards_plugin')) {

class LH_twitter_meta_cards_plugin {
    
    private static $instance;
    
    static function return_plugin_namespace(){

        return 'lh_twitter_meta_tags';

    }
    
    static function return_site_opt_name(){

        return 'lh_twitter_meta_tags-site_opt';

    }
    
    static function return_creator_user_meta_name(){

        return 'lh_twitter_meta_tags-creator_user_meta';

    }
    

    static function truncate_string($string,$min) {
    
        $text = strip_shortcodes(trim(strip_tags($string)));
    
        if(strlen($text)>$min) {
            
        $blank = strpos($text,' ');
        
            if($blank) {
                
                # limit plus last word
                $extra = strpos(substr($text,$min),' ');
                $max = $min+$extra;
                $r = substr($text,0,$max);
                if(strlen($text)>=$max) $r=trim($r,'.').'...';
                
            } else {
                
                # if there are no spaces
                $r = substr($text,0,$min).'...';
                
            }
            
        } else {
            
        # if original length is lower than limit
        $r = $text;
        
        }

        $r =  str_replace(array("\r", "\n"), '', trim(preg_replace('/\s\s+/', ' ', $r)));
        return $r;
        
    }



    static function text_content($var){
    
        $content = self::truncate_string(strip_tags(do_shortcode($var->post_content)), "140");
        
        $excerpt = self::truncate_string($var->post_excerpt, "140");
        
        return $content;
    
    }


    static function add_meta_tags_gallery(){

        $the_post_object = get_post();
        
        include ('partials/gallery.php');

    }

    static function add_meta_tags_standard(){
        
        include ('partials/standard.php');

    }

    static function add_meta_tags_image(){
    
        self::add_meta_tags_standard();
    
    }

    static function add_meta_tags_aside(){
    
        self::add_meta_tags_standard();
    
    }

    static function add_meta_tags_link(){
    
        self::add_meta_tags_standard();
    
    }

    static function add_meta_tags_quote(){
    
        self::add_meta_tags_standard();
    
    }
    
    static function add_meta_tags_status(){
    
        self::add_meta_tags_standard();
    
    }
    
    static function add_meta_tags_video(){
    
        self::add_meta_tags_standard();
    
    }
    
    static function add_meta_tags_audio(){
    
        self::add_meta_tags_standard();
    
    }
    
    static function add_meta_tags_chat(){
    
        self::add_meta_tags_standard();
    
    }


    public function add_new_image_sizes_to_wp() {
    
        if ( function_exists( 'add_image_size' ) ) { 
        
            add_image_size( 'lh_twitter_meta_tags-thumbnail', 1200, 900 ); 
        
        }
    
    }


    public function add_twitter_meta_to_head() {

        echo "\n\n<!-- begin LH Twitter meta tags output -->\n";

        if (is_singular() && get_the_ID()){
    
            $creator = get_the_author_meta(self::return_creator_user_meta_name());
    
            if (!empty($creator)){

                ?><meta property="twitter:creator" content="<?php echo $creator; ?>"/><?php 

            }

            $format = get_post_format();

            if (empty($format)){ $format = "standard";  }
            
            $func = "add_meta_tags_".$format;
            
            if (method_exists(__CLASS__, $func)) {
                
                self::$func();
                
            }


        } else {

            ?>
            <meta name="twitter:card" content="summary" />
            <meta name="twitter:title" content="<?php echo bloginfo('name'); ?>" />
            <meta name="twitter:description" content="<?php echo esc_attr(self::truncate_string(get_bloginfo('description'), "140")); ?>" />
            <?php

        }

        echo "<!-- end LH Twitter meta tags output -->\n\n";

    }

    public function validate_options( $input ){ 
        
        return $input;
        
    }

    public function input_callback($args) {  // Textbox Callback
    
        ?><input type="text" id="<?php echo self::return_site_opt_name(); ?>" name="<?php echo self::return_site_opt_name(); ?>" value="<?php echo get_option(self::return_site_opt_name());  ?>" size="25" /><?php
    
    }

    public function reading_setting_callback($arguments){
        
        
        
    }


    public function add_settings_section() {  
        
    
        
        add_settings_section(  
            self::return_site_opt_name().'-section', // Section ID 
            __('Twitter Meta Data', self::return_plugin_namespace()), // Section Title
            array($this, 'reading_setting_callback'), // Callback
            'reading' // What Page?  This makes the section show up on the General Settings Page
        );
    
        add_settings_field( // Option 1
            self::return_site_opt_name(), // Option ID
            __('Twitter Site Name', self::return_plugin_namespace()), // Label
            array($this, 'input_callback'), // !important - This is where the args go!
            'reading', // Page it will be displayed (General Settings)
            self::return_site_opt_name().'-section', // Name of our section
            array( // The $args
                self::return_site_opt_name() // Should match Option ID
            )  
        ); 
    
        register_setting('reading',self::return_site_opt_name(), array($this, 'validate_options'));
        
    }


    public function extra_user_profile_field( $user ) {
    
        ?>
        <table class="form-table">
        <tr>
        <th><label for="<?php echo self::return_creator_user_meta_name(); ?>"><?php _e('Twitter Creator Handle', self::return_plugin_namespace()); ?></label></th>
        <td><input type="text" name="<?php echo self::return_creator_user_meta_name(); ?>" id="<?php echo self::return_creator_user_meta_name(); ?>" value="<?php echo esc_attr( get_the_author_meta(self::return_creator_user_meta_name(), $user->ID) ); ?>" class="regular-text" /></td>
        </tr>
        </table>
        <?php
    
    }

    public function save_extra_user_profile_field( $user_id ) {
    
        $saved = false;
    
        if (current_user_can( 'edit_user', $user_id ) and isset($_POST[self::return_creator_user_meta_name()]) and !empty(trim($_POST[self::return_creator_user_meta_name()]))) {
          
            update_user_meta( $user_id, self::return_creator_user_meta_name(), sanitize_text_field(trim($_POST[self::return_creator_user_meta_name()])));
            $saved = true;
        
        } else {
       
            delete_user_meta( $user_id, self::return_creator_user_meta_name()); 
            $saved = true;
          
        }
      
      return $saved;
      
    }

    public function plugin_init(){
        
        //load translations
        load_plugin_textdomain( self::return_plugin_namespace(), false, basename( dirname( __FILE__ ) ) . '/languages' );
        
        add_action( 'init', array($this,"add_new_image_sizes_to_wp"));
        add_action('wp_head', array($this,"add_twitter_meta_to_head"));

        //add a settings section under reading to manage sitewide twitter meta tags
        add_action('admin_init', array($this,'add_settings_section'));  


        //add a section on the user profile area to add the creator meta tag
        add_action( 'show_user_profile', array($this,"extra_user_profile_field"),10,1);
        add_action( 'edit_user_profile', array($this,"extra_user_profile_field"),10,1);
        add_action( 'personal_options_update', array($this,"save_extra_user_profile_field"));
        add_action( 'edit_user_profile_update', array($this,"save_extra_user_profile_field"));
        add_action( 'user_register', array($this,"save_extra_user_profile_field"));
        
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
        
        //run our hooks on plugins loaded to as we may need checks       
        add_action( 'plugins_loaded', array($this,'plugin_init'));
    
    }


}

$lh_twitter_meta_cards = LH_twitter_meta_cards_plugin::get_instance();

}

?>