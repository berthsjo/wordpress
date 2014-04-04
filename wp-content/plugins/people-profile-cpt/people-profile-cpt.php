<?php
/*/
Plugin Name: People Profile CPT
Plugin URI: http://wordpress.org/plugins/people-profile-cpt/
Description: Creates "People" custom post type.
Version: 1.1
Author: WP Dev Shed
Author URI: http://wpdevshed.com/
License: GNU General Public License v2.0
License URI: http://www.gnu.org/licenses/gpl-2.0.html
/*/

/******************************************************************
 * Register the "People" custom post type
 ******************************************************************/

function wpds_people_profile_cpt() {
	
	$labels = array(
		'name'               => _x( 'People', 'post type general name', 'wpds-people-cpt' ),
		'singular_name'      => _x( 'People', 'post type singular name', 'wpds-people-cpt' ),
		'add_new'            => _x( 'Add New', 'book', 'wpds-people-cpt' ),
		'add_new_item'       => __( 'Add New People', 'wpds-people-cpt' ),
		'edit_item'          => __( 'Edit People', 'wpds-people-cpt' ),
		'new_item'           => __( 'New People', 'wpds-people-cpt' ),
		'all_items'          => __( 'All People', 'wpds-people-cpt' ),
		'view_item'          => __( 'View People', 'wpds-people-cpt' ),
		'search_items'       => __( 'Search People', 'wpds-people-cpt' ),
		'not_found'          => __( 'No People found', 'wpds-people-cpt' ),
		'not_found_in_trash' => __( 'No People found in the Trash', 'wpds-people-cpt' ), 
		'parent_item_colon'  => '',
		'menu_name'          => 'People'
	);
	
	$args = array(
		'labels'        => $labels,
		'description'   => __('Holds our people and people specific data', 'wpds-people-cpt'),
		'public'        => true,
		'menu_position' => 5,
		'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments' ),
		'has_archive'   => false,
		
	);
	register_post_type( 'people', $args );	
}
add_action( 'init', 'wpds_people_profile_cpt' );



/* Flush your rewrite rules */
function wpds_people_cpt_flush_rewrite_rules() {
	global $pagenow;
	if ( 'themes.php' == $pagenow && isset( $_GET['activated'] ) )
		flush_rewrite_rules();
}
/* Flush rewrite rules for custom post types. */
add_action( 'load-themes.php', 'wpds_people_cpt_flush_rewrite_rules' );



/* Custom Taxonomies for Menu Item */
function wpds_people_cpt_taxonomies() {
	$labels = array(
		'name'              => _x( 'People Categories', 'taxonomy general name', 'wpds-people-cpt' ),
		'singular_name'     => _x( 'People Category', 'taxonomy singular name', 'wpds-people-cpt' ),
		'search_items'      => __( 'Search People Categories', 'wpds-people-cpt' ),
		'all_items'         => __( 'All People Categories', 'wpds-people-cpt' ),
		'parent_item'       => __( 'Parent People Category', 'wpds-people-cpt' ),
		'parent_item_colon' => __( 'Parent People Category:', 'wpds-people-cpt' ),
		'edit_item'         => __( 'Edit People Category', 'wpds-people-cpt' ), 
		'update_item'       => __( 'Update People Category', 'wpds-people-cpt' ),
		'add_new_item'      => __( 'Add New People Category', 'wpds-people-cpt' ),
		'new_item_name'     => __( 'New People Category', 'wpds-people-cpt' ),
		'menu_name'         => __( 'People Categories', 'wpds-people-cpt' ),
	);
	$args = array(
		'labels' => $labels,
		'hierarchical' => true,
	);
	register_taxonomy( 'people_category', 'people', $args );
}
add_action( 'init', 'wpds_people_cpt_taxonomies', 0 );


/* Job Title Meta Box */

function wpds_people_cpt_job_title() {
    add_meta_box( 
        'people_job_title_box',
        __( 'Job Title', 'wpds-people-cpt' ),
        'wpds_people_cpt_job_title_content',
        'people',
        'side',
        'high'
    );
}
add_action( 'add_meta_boxes', 'wpds_people_cpt_job_title' );

function wpds_people_cpt_job_title_content( $people_job_title ) {

	$wpds_people_title = get_post_meta( $people_job_title->ID, 'job_title', true );
	echo '<input type="hidden" name="wpds_people_noncename" id="wpds_people_noncename" value="' . wp_create_nonce( 'wpdevshed-nonce' ) . '" />';
	echo '<label for="people_job_title">' . __('Enter the Job Title', 'wpds-people-cpt') . '</label>';
	echo '<input id=people_job_title" name="people_job_title" style="width: 100%" type="text" value="'.$wpds_people_title.'" />';
}


/* Phone Number Meta Box */

function wpds_people_cpt_phone_number() {
    add_meta_box( 
        'people_phone_number_box',
        __( 'Phone Number', 'wpds-people-cpt' ),
        'wpds_people_cpt_phone_number_content',
        'people',
        'side',
        'high'
    );
}
add_action( 'add_meta_boxes', 'wpds_people_cpt_phone_number' );

function wpds_people_cpt_phone_number_content( $people_phone_number) {

	$wpds_people_phone = get_post_meta( $people_phone_number->ID, 'phone_number', true );
	echo '<label for="people_phone_number">' . __('Enter the Phone Number', 'wpds-people-cpt') . '</label>';
	echo '<input id="people_phone_number" name="people_phone_number" style="width: 100%" type="text" value="'.$wpds_people_phone.'" />';
}



/* LinkedIn Meta Box */

function wpds_people_cpt_linkedin() {
    add_meta_box( 
        'people_linkedin_box',
        __( 'LinkedIn', 'wpds-people-cpt' ),
        'wpds_people_cpt_linkedin_content',
        'people',
        'side',
        'high'
    );
}
add_action( 'add_meta_boxes', 'wpds_people_cpt_linkedin' );

function wpds_people_cpt_linkedin_content( $people_linkedin ) {

	$wpds_people_linkedin = get_post_meta( $people_linkedin->ID, 'linkedin', true );
	echo '<label for="people_linkedin">' . __('Enter the LinkedIn Profile URL', 'wpds-people-cpt') . '</label>';
	echo '<input id="people_linkedin" name="people_linkedin" style="width: 100%" type="text" value="'.$wpds_people_linkedin.'" />';
}



/* Twitter Meta Box */

function wpds_people_cpt_twitter() {
    add_meta_box( 
        'people_twitter_box',
        __( 'Twitter', 'wpds-people-cpt' ),
        'wpds_people_cpt_twitter_content',
        'people',
        'side',
        'high'
    );
}
add_action( 'add_meta_boxes', 'wpds_people_cpt_twitter' );

function wpds_people_cpt_twitter_content( $people_twitter ) {

	$wpds_people_twitter = get_post_meta( $people_twitter->ID, 'twitter', true );
	echo '<label for="people_twitter">' . __('Enter the Twitter Profile URL', 'wpds-people-cpt') . '</label>';
	echo '<input id="people_twitter" name="people_twitter" style="width: 100%" type="text" value="'.$wpds_people_twitter.'" />';
}



/* Facebook Meta Box */

function wpds_people_cpt_facebook() {
    add_meta_box( 
        'people_facebook_box',
        __( 'Facebook', 'wpds-people-cpt' ),
        'wpds_people_cpt_facebook_content',
        'people',
        'side',
        'high'
    );
}
add_action( 'add_meta_boxes', 'wpds_people_cpt_facebook' );

function wpds_people_cpt_facebook_content( $people_facebook ) {

	$wpds_people_facebook = get_post_meta( $people_facebook->ID, 'facebook', true );
	echo '<label for="people_facebook">' . __('Enter the Facebook Profile URL', 'wpds-people-cpt') . '</label>';
	echo '<input id="people_facebook" name="people_facebook" style="width: 100%" type="text" value="'.$wpds_people_facebook.'" />';
}



/* Google+ Meta Box */

function wpds_people_cpt_googleplus() {
    add_meta_box( 
        'people_googleplus_box',
        __( 'Google+', 'wpds-people-cpt' ),
        'wpds_people_cpt_googleplus_content',
        'people',
        'side',
        'high'
    );
}
add_action( 'add_meta_boxes', 'wpds_people_cpt_googleplus' );

function wpds_people_cpt_googleplus_content( $people_googleplus ) {

	$wpds_peole_googleplus = get_post_meta( $people_googleplus->ID, 'googleplus', true );
	echo '<label for="people_googleplus">' . __('Enter the Google+ Profile URL', 'wpds-people-cpt') . '</label>';
	echo '<input id="people_googleplus" name="people_googleplus" style="width: 100%" type="text" value="'.$wpds_peole_googleplus.'" />';
}


/* Email Meta Box */

function wpds_people_cpt_email() {
    add_meta_box( 
        'people_email_box',
        __( 'Email', 'wpds-people-cpt' ),
        'wpds_people_cpt_email_content',
        'people',
        'side',
        'high'
    );
}
add_action( 'add_meta_boxes', 'wpds_people_cpt_email' );

function wpds_people_cpt_email_content( $people_email ) {

	$wpds_people_email = get_post_meta( $people_email->ID, 'email', true );
	echo '<label for="people_email">' . __('Enter the Email Address', 'wpds-people-cpt') . '</label>';
	echo '<input id="people_email" name="people_email" style="width: 100%" type="text" value="'.$wpds_people_email.'" />';
}




/* Avvo Meta Box */

function wpds_people_cpt_avvo() {
    add_meta_box( 
        'people_avvo_box',
        __( 'Avvo', 'wpds-people-cpt' ),
        'wpds_people_cpt_avvo_content',
        'people',
        'side',
        'high'
    );
}
add_action( 'add_meta_boxes', 'wpds_people_cpt_avvo' );

function wpds_people_cpt_avvo_content( $people_avvo ) {

	$wpds_people_avvo = get_post_meta( $people_avvo->ID, 'avvo', true );
	echo '<label for="people_avvo">' . __('Enter the Avvo Profile URL', 'wpds-people-cpt') . '</label>';
	echo '<input id="people_avvo" name="people_avvo" style="width: 100%" type="text" value="'.$wpds_people_avvo.'" />';
}


/* Save */

function wpds_people_cpt_meta_save($post_id) {

	// If it is our form has not been submitted, so we dont want to do anything
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
        return;
    }
	
	// verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times
    if (isset($_POST['wpds_people_noncename'])){
        if ( !wp_verify_nonce( $_POST['wpds_people_noncename'], 'wpdevshed-nonce' ) )
            return;
    }else{return;}
	
	
	// Check permissions
	 if ( 'page' == $_POST['post_type'] ||  'post' == $_POST['post_type']) {
        if ( !current_user_can( 'edit_page', $post_id ) || !current_user_can( 'edit_post', $post_id ))
          return $post_id;
      } 
	
	
	// Update meta values
	update_post_meta( $post_id, 'job_title', $_POST['people_job_title'] );
	update_post_meta( $post_id, 'phone_number', $_POST['people_phone_number'] );
	update_post_meta( $post_id, 'linkedin', $_POST['people_linkedin'] );
	update_post_meta( $post_id, 'twitter', $_POST['people_twitter'] );
	update_post_meta( $post_id, 'facebook', $_POST['people_facebook'] );
	update_post_meta( $post_id, 'googleplus', $_POST['people_googleplus'] );
	update_post_meta( $post_id, 'email', $_POST['people_email'] );
	update_post_meta( $post_id, 'avvo', $_POST['people_avvo'] );
}
add_action( 'save_post', 'wpds_people_cpt_meta_save' );


?>