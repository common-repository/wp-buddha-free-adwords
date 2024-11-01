<?php

defined('ABSPATH') or die('No script kiddies please!');



class wpbuddha_publishad{

	public function __construct()

	{

		if(is_admin())

		{

			add_action('admin_notices', array($this, '_admin_notice'), 1);

			add_action('admin_menu',array($this,'_initMenu'));

			register_activation_hook(BUDDHA_ADS_FILE, array($this, '_buddhacreatetable'));

			add_action('add_meta_boxes', array($this, '_buddha_ads_meta_box'));

			add_action('save_post', array($this, 'buddha_save_adword_suggestions'));

		}

		

		add_action('init', array($this, 'buddha_handle_forms'));

	}



	public function _buddhacreatetable()

	{

		global $wpdb;

		

		$Table_name = $wpdb->prefix . 'adwords_data';

		if( !$wpdb->get_var( "SHOW TABLES LIKE $Table_name" ) ) {

			$sql = "CREATE TABLE $Table_name

			(

				`id` int(11) NOT NULL AUTO_INCREMENT,

				`post_id` int(11) NOT NULL,

				`title` text NOT NULL,

				`sub_title` text NOT NULL,

				`description` text NOT NULL,

				`source` text NOT NULL,

				`medium` text NOT NULL,

				`name` text NOT NULL,

				`content` text NOT NULL,

				`date` timestamp,

				 PRIMARY KEY (`id`)

			);";	

			include_once(ABSPATH . 'wp-admin/includes/upgrade.php');

			dbDelta($sql) or die('table error');

		}

		if( !$wpdb->get_var( "SHOW COLUMNS FROM $Table_name LIKE title_three" ) ) {

			$table = "ALTER TABLE $Table_name ADD COLUMN title_three text NOT NULL AFTER sub_title";

			$wpdb->query($table);

		}

		if( !$wpdb->get_var( "SHOW COLUMNS FROM $Table_name LIKE description_two" ) ) {

			$table = "ALTER TABLE $Table_name ADD COLUMN description_two text NOT NULL AFTER description";

			$wpdb->query($table);

		}

	}

	

	public function buddha_handle_forms()

	{

		global $wpdb;

		$table = $wpdb->prefix.'adwords_data';

		

		if(isset($_GET['action']) and $_GET['action'] == 'remove_suggestion' and !empty($_GET['id']))

		{
			if ( !wp_verify_nonce( $_GET['nonce'], 'remove-suggestion-'.$_GET['id'] ) ){return;}
			
			$wpdb->query($wpdb->prepare("DELETE FROM $table WHERE id = %d LIMIT 1", $_GET['id']));



			if($wpdb->last_error !== '')

			{

				update_option('buddha_ads_notice', 'An error occurred, please try again later!');

			}

			else

			{

				update_option('buddha_ads_notice', 'WP Buddha Free Adwords Plugin deleted successfully!');

			}

			

			wp_safe_redirect($_SERVER['HTTP_REFERER']);

			die;

		}

	}



	public function buddha_save_adword_suggestions($post_id)

	{

		global $wpdb;

		$table = $wpdb->prefix.'adwords_data';



		if(!isset($_POST['adword_suggestions_nonce']))

		{

			return;

		}

		if(!wp_verify_nonce($_POST['adword_suggestions_nonce'], 'adword_suggestions_nonce'))

		{

        	return;

    	}

		if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)

		{

        	return;

    	}

		if(defined('DOING_AJAX') && DOING_AJAX) 

		{

			return;

		}

		if(false !== wp_is_post_revision($post_id))

		{

			return;

		}



		if(isset($_POST['post_type']) && 'page' == $_POST['post_type'])

		{

			if(!current_user_can('edit_page', $post_id))

			{

				return;

			}

		}

		else

		{

			if (!current_user_can('edit_post', $post_id))

			{

				return;

			}

		}

		if(!empty($_POST['buddha-ads-url'])){

		$url = sanitize_text_field($_POST['buddha-ads-url']);

		}

		if(!empty($_POST['buddha-ads-title'])){

		$title = sanitize_text_field($_POST['buddha-ads-title']);

		}

		if(!empty($_POST['buddha-ads-sub-title'])){

		$sub_title =sanitize_text_field($_POST['buddha-ads-sub-title']);	

			}

		if(!empty($_POST['buddha-ads-desc'])){

		$desc = sanitize_text_field($_POST['buddha-ads-desc']);

		}

		

		if(!empty($_POST['buddha-ads-source'])){

		$source = sanitize_text_field($_POST['buddha-ads-source']);

		}

		if(!empty($_POST['buddha-ads-medium'])){

		$medium = sanitize_text_field($_POST['buddha-ads-medium']);

		}

		if(!empty($_POST['buddha-ads-name'])){

		$name = sanitize_text_field($_POST['buddha-ads-name']);

		}

		if(!empty($_POST['buddha-ads-content'])){

		$content = sanitize_text_field($_POST['buddha-ads-content']);

		}

		if(!empty($_POST['buddha-ads-titlethree'])){

		$titlethree = sanitize_text_field($_POST['buddha-ads-titlethree']);

		}

		

		if(!empty($_POST['buddha-ads-desctwo'])){

		$desctwo = sanitize_text_field($_POST['buddha-ads-desctwo']);

		}

		$row = $wpdb->get_var("SELECT id FROM $table WHERE post_id = '".$post_id."' limit 1");

		

		if(!empty($row))

		{

			$wpdb->query($wpdb->prepare("UPDATE $table SET post_id=%d, title=%s, sub_title=%s, title_three=%s, description=%s, description_two=%s, source=%s, medium=%s, name=%s, content=%s WHERE id = %d limit 1", $post_id, $title, $sub_title, $titlethree, $desc, $desctwo, $source, $medium, $name, $content, $row));

		}

		else

		{

			$wpdb->query($wpdb->prepare("INSERT INTO $table (post_id, title, sub_title, title_three, description, description_two, source, medium, name, content) VALUES (%d, %s, %s, %s, %s, %s, %s, %s, %s, %s)", $post_id, $title, $sub_title, $titlethree, $desc, $desctwo, $source, $medium, $name, $content));

		}

		

		//print_r($wpdb->last_error);

		//die;

	}

	

	public function _buddha_ads_meta_box()

	{

		add_meta_box(

			'adword-suggestions',

			__( 'Adword Suggestions', 'ads' ),

			array($this, 'buddha_ads_metabox_callback'),

			array('post', 'page')

		);

	}

	

	public function buddha_ads_metabox_callback($post)

	{

		include_once(BUDDHA_ADS_BASE_DIR.'/src/ads.form.php');

	}

		

	public function _initMenu()

	{

		add_menu_page('WPBuddha Adwords', 'WPBuddha Adwords', 'administrator', 'WPBuddha-Adwords', array($this,'_buddha_ads_page'));

	}



	public function _buddha_ads_page()

	{

		include_once(BUDDHA_ADS_BASE_DIR.'/src/page.ads.php');

	}

	

	public function _admin_notice()

	{

		$notice = get_option('buddha_ads_notice');

		if(!empty($notice) and $notice !== false)

		{

			echo '<div class="updated" id="message">

			   <p>'.$notice.'</p>

			</div>';



			delete_option('buddha_ads_notice');

		}

	}



	public function buddhapagination($total,$per_page=10,$page=1,$url){

		$adjacents = "2"; 

		  

		$prevlabel = "&lsaquo; Prev";

		$nextlabel = "Next &rsaquo;";

		$lastlabel = "Last &rsaquo;&rsaquo;";

		  

		$page = ($page == 0 ? 1 : $page);  

		$start = ($page - 1) * $per_page;                               

		  

		$prev = $page - 1;                          

		$next = $page + 1;

		  

		$lastpage = ceil($total/$per_page);

		  

		$lpm1 = $lastpage - 1; // //last page minus 1

		  

		$buddhapagination = "";

		if($lastpage > 1){   

			$buddhapagination .= "<ul class='buddhapagination'>";

			$buddhapagination .= "<li class='page_info'>Page {$page} of {$lastpage}</li>";

				  

				if ($page > 1) $buddhapagination.= "<li><a href='{$url}paged={$prev}'>{$prevlabel}</a></li>";

				  

			if ($lastpage < 7 + ($adjacents * 2)){   

				for ($counter = 1; $counter <= $lastpage; $counter++){

					if ($counter == $page)

						$buddhapagination.= "<li><a class='current'>{$counter}</a></li>";

					else

						$buddhapagination.= "<li><a href='{$url}paged={$counter}'>{$counter}</a></li>";                    

				}

			  

			} elseif($lastpage > 5 + ($adjacents * 2)){

				  

				if($page < 1 + ($adjacents * 2)) {

					  

					for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++){

						if ($counter == $page)

							$buddhapagination.= "<li><a class='current'>{$counter}</a></li>";

						else

							$buddhapagination.= "<li><a href='{$url}paged={$counter}'>{$counter}</a></li>";                    

					}

					$buddhapagination.= "<li class='dot'>...</li>";

					$buddhapagination.= "<li><a href='{$url}paged={$lpm1}'>{$lpm1}</a></li>";

					$buddhapagination.= "<li><a href='{$url}paged={$lastpage}'>{$lastpage}</a></li>";  

						  

				} elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {

					  

					$buddhapagination.= "<li><a href='{$url}paged=1'>1</a></li>";

					$buddhapagination.= "<li><a href='{$url}paged=2'>2</a></li>";

					$buddhapagination.= "<li class='dot'>...</li>";

					for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {

						if ($counter == $page)

							$buddhapagination.= "<li><a class='current'>{$counter}</a></li>";

						else

							$buddhapagination.= "<li><a href='{$url}paged={$counter}'>{$counter}</a></li>";                    

					}

					$buddhapagination.= "<li class='dot'>..</li>";

					$buddhapagination.= "<li><a href='{$url}paged={$lpm1}'>{$lpm1}</a></li>";

					$buddhapagination.= "<li><a href='{$url}paged={$lastpage}'>{$lastpage}</a></li>";      

					  

				} else {

					  

					$buddhapagination.= "<li><a href='{$url}paged=1'>1</a></li>";

					$buddhapagination.= "<li><a href='{$url}paged=2'>2</a></li>";

					$buddhapagination.= "<li class='dot'>..</li>";

					for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {

						if ($counter == $page)

							$buddhapagination.= "<li><a class='current'>{$counter}</a></li>";

						else

							$buddhapagination.= "<li><a href='{$url}paged={$counter}'>{$counter}</a></li>";                    

					}

				}

			}

			  

				if ($page < $counter - 1) {

					$buddhapagination.= "<li><a href='{$url}paged={$next}'>{$nextlabel}</a></li>";

					$buddhapagination.= "<li><a href='{$url}paged=$lastpage'>{$lastlabel}</a></li>";

				}

			  

			$buddhapagination.= "</ul>";        

		}

		  

		return $buddhapagination;

	}

}

?>