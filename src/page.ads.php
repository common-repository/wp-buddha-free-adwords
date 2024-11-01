<?php defined('ABSPATH') or die('No script kiddies please!'); ?>

<div class="wrap">



	<h1 class="wp-heading-inline">WP Buddha Free Adwords Plugin</h1>



	<?php



	global $wpdb;



	$table = $wpdb->prefix.'adwords_data';







	if(isset($_GET['action']) and $_GET['action'] == 'view-suggestion' and !empty($_GET['id']))



	{



		$result = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE id = %d LIMIT 1", $_GET['id']));



		



		$post = get_post($result->post_id);



		$permalink = get_permalink($post->ID);



		?>



		<div style="width:100%; display:block;">



			<div style="width:49%; float:left;">







				<div style="width:100%;">



					<label><strong>Headline 1</strong></label>



					<p><?php echo $result->title; ?></p>



				</div>



		



				<div style="width:100%;">



					<label><strong>Headline 2</strong></label>



					<p><?php echo $result->sub_title; ?></p>



				</div>







				<div style="width:100%;">



					<label><strong>Headline 3</strong></label>



					<p><?php echo $result->title_three; ?></p>



				</div>



		



				<div style="width:100%;">



					<label><strong>Description 1</strong></label>



					<p><?php echo $result->description; ?></p>



				</div>







				<div style="width:100%;">



					<label><strong>Description 2</strong></label>



					<p><?php echo $result->description_two; ?></p>



				</div>



			</div>



			



			<div style="width:49%; float:right;">







				<div style="width:100%;">



					<label><strong>URL</strong></label>



					<p><?php echo $permalink; ?></p>



				</div>







				<div style="width:100%;">



					<label><strong>Source</strong></label>



					<p><?php echo $result->source; ?></p>



				</div>



		



				<div style="width:100%;">



					<label><strong>Medium</strong></label>



					<p><?php echo $result->medium; ?></p>



				</div>



		



				<div style="width:100%;">



					<label><strong>Name</strong></label>



					<p><?php echo $result->name; ?></p>



				</div>



		



				<div style="width:100%;">



					<label><strong>Content</strong></label>



					<p><?php echo $result->content; ?></p>



				</div>



			</div>



			



			<div style="clear:both;"></div>



		



			<?php



			if(!empty($result->title) and !empty($result->sub_title) and !empty($result->description))



			{



				$utm_url =  $permalink;







				if(!empty($result->source))



				{



					$utm_url = add_query_arg(array('utm_source'=>$result->source), $utm_url);



				}



		



				if(!empty($result->medium))



				{



					$utm_url = add_query_arg(array('utm_medium'=>$result->medium), $utm_url);



				}



		



				if(!empty($result->name))



				{



					$utm_url = add_query_arg(array('utm_campaign'=>$result->name), $utm_url);



				}



		



				if(!empty($result->content))



				{



					$utm_url = add_query_arg(array('utm_content'=>$result->content), $utm_url);



				}







				//$utm_url = add_query_arg(array('utm_source'=>$result->source, 'utm_medium'=>$result->medium, 'utm_campaign'=>$result->name, 'utm_content'=>$result->content), $permalink);



				?>



				<div style="margin-top:20px; display:block;     position: relative;">



					<label style="



    width: 100%;



    float: left;



    display: block;



    margin-bottom: 15px;



"><b>Copy URL</b></label>



					<textarea id="adwords-url" readonly="readonly" rows=""><?php echo $utm_url; ?></textarea>



					    <!--<div id="slideSource">text copied to clipboard</div>-->



					<button id="adwords-url-copy" onclick="Buddha_calling_copy()">Click to Copy</button>



				</div>



				<?php



			}



			?>



		</div>







		<?php



	}



	else



	{



		?>



		<table class="wp-list-table widefat fixed striped pages">



			<thead>



				<tr>



					<th scope="col" class="manage-column column-title"><span>Post/Page</span></th>



					<th scope="col" class="manage-column column-title"><span>Headline</span></th>



					<th scope="col" class="manage-column column-title"><span>Headline 2</span></th>



					<th scope="col" class="manage-column column-author"><span>Action</span></th>



					<th scope="col" class="manage-column column-date"><span>Modify Date</span></th>



				</tr>



			</thead>



			<tbody id="the-list">



				<?php



				if(isset($_GET["paged"])){$page  = $_GET["paged"];}else{$page=1;};



				$limit = 20;



				$start_from = ($page-1) * $limit;



				



				$total_records = $wpdb->get_var("SELECT COUNT(id) FROM $table");



				$total_pages = ceil($total_records / $limit);



	



				$page_params = array();



				foreach($_GET as $ind=>$param)



				{



					if($ind != 'paged')



					{



						$page_params[] = $ind.'='.$param;



					}



				}



				



				$page_url = admin_url('admin.php?'.implode('&', $page_params).'&');



				$buddhapagination = $this->buddhapagination($total_records,$limit,$page, $page_url);



				



				$suggestions = $wpdb->get_results("SELECT * FROM $table ORDER BY id DESC LIMIT $start_from, $limit");



				



				if(!empty($suggestions) and is_array($suggestions))



				{



					foreach($suggestions as $suggestion)



					{



						$post = get_post($suggestion->post_id);



						?>



						<tr>



							<td class="manage-column column-title"><span><?php echo $post->post_title; ?></span></td>



							<td class="manage-column column-title"><span><?php echo $suggestion->title; ?></span></td>



							<td class="manage-column column-title"><span><?php echo $suggestion->sub_title; ?></span></td>



							<td class="manage-column column-author">



								<a href="<?php echo admin_url('admin.php?page=WPBuddha-Adwords&action=view-suggestion&id='.$suggestion->id) ?>"><span class="dashicons dashicons-visibility"></span></a>



								&nbsp;&nbsp;



								<a onclick="return confirm('Are you sure?');" href="<?php echo admin_url('admin.php?page=adwords-suggestions&action=remove_suggestion&id='.$suggestion->id.'&nonce='.wp_create_nonce('remove-suggestion-'.$suggestion->id)) ?>">



									<span class="dashicons dashicons-trash"></span>



								</a>



							</td>



							<td class="manage-column column-date">



								<span><abbr title="<?php echo date('Y/m/d h:i:s A', strtotime($suggestion->date)); ?>"><?php echo date('Y/m/d', strtotime($suggestion->date)); ?></abbr></span>



							</td>



						</tr>



						<?php



					}



				}



				else



				{



					?>



					<td class="manage-column column-title" colspan="5" style="text-align:center;"><span>No Data Found!</span></td>



					<?php



				}



				?>



			</tbody>



			<tfoot>



				<tr>



					<th scope="col" class="manage-column column-title"><span>Post/Page</span></th>



					<th scope="col" class="manage-column column-title"><span>Headline</span></th>



					<th scope="col" class="manage-column column-title"><span>Headline 2</span></th>



					<th scope="col" class="manage-column column-author"><span>Action</span></th>



					<th scope="col" class="manage-column column-date"><span>Modify Date</span></th>



				</tr>



			</tfoot>



		</table>



		



		<?php



		echo $buddhapagination;



	}



	?>



</div>







<style>



ul.buddhapagination {



    text-align:right;



    color:#829994;



}



ul.buddhapagination li {



    display:inline;



    padding:0 3px;



}



ul.buddhapagination a {



    color:#0d7963;



    display:inline-block;



    padding:5px 10px;



    border:1px solid #cde0dc;



    text-decoration:none;



}



ul.buddhapagination a:hover, 



ul.buddhapagination a.current {



    background:#0d7963;



    color:#fff; 



}



	#adwords-url {



	    width:75% !important; 



	    font-size:15px !important;



	    	    float:left;







	}



	#adwords-url-copy {



    width: 15%;



    display: inline-block;



    float: left;



    margin-left: 2%;



    padding: 12px 10px;



    color: #fff;



    background: #ffc1ce!important;



    font-size: 18px;



    border: none;



    cursor: pointer;



    font-weight: 500;







	    



	}







#slideSource {



    opacity: 0;



    transition: opacity 1s;



    display: none;



    width: 20%;



    position: absolute;



    top: -30px;



    right: 5.5%;



    text-align: center;



    color: #fff;



    font-size: 16px;



    background: #ffc1ce!important;



    padding: 15px 0;



    text-transform: capitalize;



}







#slideSource.fade {



  opacity: 1;



   display:block;



}







</style>



















<script>



function Buddha_calling_copy() {



  var copyText = document.getElementById("adwords-url");



    var slideSource = document.getElementById('slideSource');



 



  copyText.select();



document.execCommand("copy");



alert("Url Copied To Clipboard :)")



/*



document.getElementById('adwords-url-copy').onclick = function () {



  slideSource.classList.toggle('fade');



}



*/



}



















slideSource



</script>