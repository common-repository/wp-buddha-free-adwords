<?php defined('ABSPATH') or die('No script kiddies please!'); ?>
<style>
#adword-form label {
	margin-top: 8px;
	float: left;
	margin-bottom: 3px;
	font-weight: bold;
}
#adword-form input[type=text], #adword-form input[type=search], #adword-form input[type=radio], #adword-form input[type=tel], #adword-form input[type=time], #adword-form input[type=url], #adword-form input[type=week], input[type=password], #adword-form input[type=checkbox], #adword-form input[type=color], #adword-form input[type=date], #adword-form input[type=datetime], #adword-form input[type=datetime-local], #adword-form input[type=email], #adword-form input[type=month], #adword-form input[type=number], #adword-form select, #adword-form textarea {
	padding: 10px;
}
#adword-form textarea {
	width: 100%;
	height: auto !important;
	cursor: not-allowed;
	display: block;
	box-sizing: border-box;
	width: 100%;
	height: 2.4375rem;
	padding: 0.5rem;
	border: 1px solid #cacaca;
	margin: 0 0 1rem;
	font-family: inherit;
	font-size: 1rem;
	color: #0a0a0a;
	background-color: #f1f1f1;
	box-shadow: inset 0 1px 2px rgba(10,10,10,0.1);
	border-radius: 3px;
	transition: box-shadow 0.5s, border-color 0.25s ease-in-out;
	-webkit-appearance: none;
}
</style>
<?php

wp_nonce_field('adword_suggestions_nonce', 'adword_suggestions_nonce');



global $wpdb;

$table = $wpdb->prefix.'adwords_data';



$data = $wpdb->get_row("SELECT * FROM $table WHERE post_id = '".$post->ID."' limit 1");



$permalink = get_permalink($post->ID);

?>

<div style="width:100%; display:table;" id="adword-form">
  <div style=""> <a href="https://wp-buddha.com?utm_source=wpbfadw&utm_medium=plugin&utm_campaign=wpbuddhafreeadwordsplugin&utm_content=freeplugin" target="_blank"><img src="<?php echo BUDDHA_ADS_BASE_URL; ?>/assets/images/logo-budhha.png" height="150x"/></a>
    <hr style="margin-top: 30px;margin-bottom: 10px;margin-left: -12px;margin-right: -12px;">
    <h2 style="font-size: 20px;padding-left: 0;"><strong>Create Ad</strong></h2>
    <br>
    <br>
    <table style="width:100%;">
      <tbody>
        <tr>
          <td><label>Headline 1 *</label></td>
          <td><input type="text" name="buddha-ads-title" id="buddha-ads-title" value="<?php echo $data->title; ?>" style="width:100%;" placeholder="Up to 30 characters" maxlength="30" /></td>
        </tr>
        <tr>
          <td><label>Headline 2 *</label></td>
          <td><input type="text" name="buddha-ads-sub-title" id="buddha-ads-sub-title" style="width:100%;" value="<?php echo $data->sub_title; ?>" placeholder="Up to 30 characters" maxlength="30" /></td>
        </tr>
        <tr>
          <td><label>Headline 3</label></td>
          <td><input type="text" name="buddha-ads-titlethree" id="buddha-ads-title" value="<?php echo $data->title_three; ?>" style="width:100%;" placeholder="Up to 30 characters" maxlength="30" /></td>
        </tr>
        <tr>
          <td><label>Description 1 *</label></td>
          <td><input type="text" name="buddha-ads-desc" id="buddha-ads-desc" style="width:100%;" value="<?php echo $data->description; ?>" placeholder="Up to 80 characters" maxlength="80" /></td>
        </tr>
        <tr>
          <td><label>Description 2</label></td>
          <td><input type="text" name="buddha-ads-desctwo" id="buddha-ads-desc" style="width:100%;" value="<?php echo $data->description_two; ?>" placeholder="Up to 80 characters" maxlength="80" /></td>
        </tr>
      </tbody>
    </table>
  </div>
  <hr style="margin-top: 30px;margin-bottom: 10px;margin-left: -12px;margin-right: -12px;">
  <div>
    <h2 style="font-size: 20px;padding-left: 0;"><strong>Custom UTM (optional)</strong></h2>
    <br>
    <br>
    <table style="width:100%;">
      <tbody>
        <tr>
          <td><label>URL</label></td>
          <td><input type="text" disabled="disabled" name="buddha-ads-url" id="buddha-ads-url" style="width:100%;" value="<?php echo $permalink; ?>" /></td>
        </tr>
        <tr>
          <td><label>Source</label></td>
          <td><input type="text" name="buddha-ads-source" id="buddha-ads-source" style="width:100%;" value="<?php echo $data->source; ?>" /></td>
        </tr>
        <tr>
          <td><label>Medium</label></td>
          <td><input type="text" name="buddha-ads-medium" id="buddha-ads-medium" style="width:100%;" value="<?php echo $data->medium; ?>" /></td>
        </tr>
        <tr>
          <td><label>Name</label></td>
          <td><input type="text" name="buddha-ads-name" id="buddha-ads-name" style="width:100%;" value="<?php echo $data->name; ?>" /></td>
        </tr>
        <tr>
          <td><label>Content</label></td>
          <td><input type="text" name="buddha-ads-content" id="buddha-ads-content" style="width:100%;" value="<?php echo $data->content; ?>" /></td>
        </tr>
        <tr>
          <td></td>
          <td></td>
        </tr>
      </tbody>
    </table>
    <label>* Fields are required</label>
  </div>
  <div style="clear:both;"></div>
  <?php

	if(!empty($data->title) and !empty($data->sub_title) and !empty($data->description))

	{

		$utm_url = $permalink;



		if(!empty($data->source))

		{

			$utm_url = add_query_arg(array('utm_source'=>$data->source), $utm_url);

		}



		if(!empty($data->medium))

		{

			$utm_url = add_query_arg(array('utm_medium'=>$data->medium), $utm_url);

		}



		if(!empty($data->name))

		{

			$utm_url = add_query_arg(array('utm_campaign'=>$data->name), $utm_url);

		}



		if(!empty($data->content))

		{

			$utm_url = add_query_arg(array('utm_content'=>$data->content), $utm_url);

		}

		?>
  <table style="width:100%;">
    <tbody>
      <tr>
        <td><label>Copy URL</label></td>
        <td><textarea id="adwords-url" style="background-color: #fff;" readonly="readonly" rows=""><?php echo $utm_url; ?></textarea>
          <a id="publish_buddha" class="publish_button">Publish ad</a>
          <a id="adwords-url-copy" onclick="Buddha_calling_copy()">Click to Copy</a></td>
      </tr>
    </tbody>
  </table>
  <?php

	}

	?>
</div>

<!-- The Modal -->

<div id="publish_ad" class="buddha_modal"> 
  
  <!-- Modal content -->
  
  <div class="buddha_modal-content"> <span class="buddha_closeup">&times;</span>
    <div class="model_header"> <a href="https://wp-buddha.com" target="_blank"><img src="<?php echo BUDDHA_ADS_BASE_URL; ?>/assets/images/logo-budhha.png" height="150x"/></a> </div>
    <div class="model_content">
      <p>Upgrade to Adwords Campaigner to Publish your ad</p>
      <a href="https://wp-buddha.com" class="upgrade" target="_blank">Upgrage Now</a> </div>
    <div class="model_footer">Powerd by : <a href="https://wp-buddha.com/" target="_blank">https://wp-buddha.com</a></div>
  </div>
</div>
<style type="text/css">

.model_header img {

	width: 50%;

}

a.upgrade {

	display: inline-block;

	text-decoration: none;

	background: #ffc1ce;

	color: #fff;

	font-size: 18px;

	width: 26%;

	padding: 13px 0 18px 0;

}

.model_header {

	text-align: center;

}

.model_content, .model_footer {

	text-align: center;

}

.model_content p {

	font-size: 20px;

	color: #222;

}

.model_footer {

	color: #222;

	margin-top: 35px;

}

.model_footer a {

	color: #ffc1ce;

}

#publish_buddha.publish_button {
	float: left;
	background: #ddd;
	color: #777;
	cursor: not-allowed;
	width: 20%;
	display: inline-block;
	padding: 15px 10px;
	font-size: 18px;
	border: none;
	font-weight: 400;
	text-align: center;
	text-decoration: none;
}

	#adword-form tr td label {

	    font-size: 20px;

	    font-weight: lighter;

	    vertical-align: middle;

	}

	#adword-form tr td {

	    vertical-align: top;

	    padding: 8px 0;

	}

	#adword-form tr td:first-child {

	    width: 20%;

	}

	#adword-form tr {

		margin-bottom: 10px;

	}

	

	#adwords-url {

	    width:100% !important; 

	    font-size:15px !important;

	}

#adwords-url-copy {
	width: 20%;
	display: inline-block;
	float: right;
	padding: 15px 10px;
	color: #fff;
	background: #ffc1ce !important;
	font-size: 18px;
	border: none;
	cursor: pointer;
	font-weight: 400;
	text-align: center;
}

.buddha_modal {
	display: none;
	position: fixed;
	z-index: 9999;
	padding-top: 100px;
	left: 0;
	top: 0;
	width: 100%;
	height: 100%;
	overflow: auto;
	background-color: rgb(0,0,0);
	background-color: rgba(0,0,0,0.6);
}



/* Modal Content */

.buddha_modal-content {
	background-color: #fefefe;
	margin: auto;
	padding: 30px 20px;
	border: 1px solid #888;
	width: 45%;
	position: relative;
	top: 10%;
	right: 5%;
}


/* The Close Button */

.buddha_closeup {

	color: #FFC1CE;

	float: right;

	font-size: 30px;

	font-weight: bold;

}



.buddha_closeup:hover,

.buddha_closeup:focus {

  color: #000;

  text-decoration: none;

  cursor: pointer;

}

</style>
<script>

function Buddha_calling_copy() {

  var copyText = document.getElementById("adwords-url");

  copyText.select();

  document.execCommand("copy");

  alert("Url Copied To Clipboard :)");

}

// Get the buddha_modal

var buddha_modal = document.getElementById('publish_ad');



// Get the button that opens the buddha_modal

var btn = document.getElementById("publish_buddha");



// Get the <span> element that buddha_closeups the buddha_modal

var span = document.getElementsByClassName("buddha_closeup")[0];



// When the user clicks the button, open the buddha_modal 

btn.onclick = function() {

  buddha_modal.style.display = "block";

}



// When the user clicks on <span> (x), buddha_closeup the buddha_modal

span.onclick = function() {

  buddha_modal.style.display = "none";

}



// When the user clicks anywhere outside of the buddha_modal, buddha_closeup it

window.onclick = function(event) {

  if (event.target == buddha_modal) {

    buddha_modal.style.display = "none";

  }

}

</script>