<?php
/*
* Plugin Name: Gravity Table Entries with Paypal
* Description: Create your WordPress shortcode.
* Version: 1.0
* Author: Neroshin
* Author URI: http://sample.com/
*/



if ( ! defined( 'ABSPATH' ) ) exit; // Exit if this file is accessed directly
// Example 1 : WP Shortcode to display form on any page or post.

// include( plugin_dir_path( __FILE__ ) . 'includes/admin-view.php');
/*  
 define( 'PLUGIN_ROOT_DIR', plugin_dir_path( __FILE__ ) );
include( plugin_dir_path( __FILE__ ) .  'includes/admin-view.php'); */
include_once plugin_dir_path( __FILE__ ) .'include/admin-view.php';
if( !class_exists( 'GravityView_Extension' ) ) {

		if( class_exists('GravityView_Plugin') && is_callable(array('GravityView_Plugin', 'include_extension_framework')) ) {
			GravityView_Plugin::include_extension_framework();
			echo "GravityView_Extension";
			//include( plugin_dir_path( __FILE__ ) .  'includes/admin-view.php'); 
		} else {
			// We prefer to use the one bundled with GravityView, but if it doesn't exist, go here.
			//include_once plugin_dir_path( __FILE__ ) . 'lib/class-gravityview-extension.php';
		}
	}

// echo $my_settings_page->title_callback(); 
function wpdocs_theme_name_scripts() {
wp_enqueue_style( 'style-table', plugins_url('asset/Gravity-table-entries.css', __FILE__) );
wp_enqueue_style( 'style-tablesaw', plugins_url('asset/tablesaw.css', __FILE__)) ;
wp_enqueue_script( 'datatables', '//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js', array( 'jquery' ) );
wp_enqueue_style( 'datatables-style', '//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css' );
wp_enqueue_script( 'script-pagination',plugins_url('asset/js/pagination-fron-end.js', __FILE__), array(), '1.0.0', true ); 
wp_enqueue_script( 'script-data-loadfont','//filamentgroup.github.io/demo-head/loadfont.js', array(), '1.0.0', true ); 
wp_enqueue_script( 'script-data-tablesaw',plugins_url('asset/js/tablesaw.js', __FILE__), array(), '1.0.0', true ); 
wp_enqueue_script( 'script-data-tablesaw-init',plugins_url('asset/js/tablesaw-init.js', __FILE__), array(), '1.0.0', true ); 
wp_localize_script( 'script-pagination', 'postlove', array(
		'ajax_url' => admin_url( 'admin-ajax.php' )
	));
}
add_action( 'wp_enqueue_scripts', 'wpdocs_theme_name_scripts' );


function table_creation(){


global $wpdb;

 $is_empty_title = true;
$forms_id = RGFormsModel::get_forms(1, "title");
$form = GFAPI::get_form(1);
 $current_user = wp_get_current_user();
$forms_get_leads = array(); 
$options = get_option( 'my_option_name' );
 $title_display = explode(",",  $options['title']);
 foreach ( $forms_id as $print_active )
{	
    $lead['payment_status'] = 'Approved';
	$forms_get_leads =  array_merge_recursive( $forms_get_leads,GFFormsModel::get_leads($print_active->id));
	 
} 
    $fields = $form["fields"];
 // echo "<pre>";
 
 // print_r( $fields );
   // echo "</pre>";  
	
$forms_get_leads = array_filter( $forms_get_leads );
$value_recursive = apply_filters( 'hook_filter_recursive', $forms_get_leads );


 /*  
    echo "<pre>";
    print_r( $value_recursive );
  echo "</pre>";     */

usort($value_recursive, "Sortbydate");
/*  print_r($value_recursive);
  echo "</pre>";  */

$table_data .=" <div id='pagination'></div>";
$table_data .="<div class='table-responsive'><table id='gravity-table' class='tablesaw tablesaw-swipe display dataTable' data-tablesaw-mode='swipe' cellspacing='0' width='100%' role='grid' aria-describedby='example_info' style='width: 100%;'>";
$table_data .= "<thead>";
$label = "Entry";
$class_label = "header-" . str_replace(" ", "-", strtolower($label));
$table_data .= "<th  class='sort $class_label title defaultSort' data-sort='sort-$i' scope='col' data-tablesaw-sortable-col data-tablesaw-priority='persist' >$label</th>"; 
$label = "Form";
$class_label = "header-" . str_replace(" ", "-", strtolower($label));
$table_data .= "<th  class='sort $class_label ' data-sort='sort-$i' scope='col' data-tablesaw-sortable-col data-tablesaw-priority='1'>$label</th>"; 
/* $label = "User";
$class_label = "header-" . str_replace(" ", "-", strtolower($label));
$table_data .= "<th  class='sort $class_label' data-sort='sort-$i' scope='col' data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority='2'>$label</th>";  */
$label = "Email";
$class_label = "header-" . str_replace(" ", "-", strtolower($label));
$table_data .= "<th  class='sort $class_label' data-sort='sort-$i' scope='col' data-tablesaw-sortable-col data-tablesaw-priority='2'>$label</th>"; 
$label = "Status";
$class_label = "header-" . str_replace(" ", "-", strtolower($label));
$table_data .= "<th  class='sort $class_label' data-sort='sort-$i' scope='col' data-tablesaw-sortable-col data-tablesaw-priority='3'>$label</th>"; 
$label = "Date Payment";
$class_label = "header-" . str_replace(" ", "-", strtolower($label));
$table_data .= "<th  class='sort $class_label ' data-sort='sort-$i' scope='col' data-tablesaw-sortable-col data-tablesaw-priority='4' style='width: 60px;'>$label</th>"; 
$label = "Amount";
$class_label = "header-" . str_replace(" ", "-", strtolower($label));
$table_data .= "<th  class='sort $class_label' data-sort='sort-$i' scope='col' data-tablesaw-sortable-col data-tablesaw-priority='5'>$label</th>"; 
$label = "Payment";
$class_label = "header-" . str_replace(" ", "-", strtolower($label));
$table_data .= "<th  class='sort $class_label' data-sort='sort-$i' scope='col' data-tablesaw-sortable-col data-tablesaw-priority='6'>$label</th>"; 
$label = "More..";
$class_label = "header-" . str_replace(" ", "-", strtolower($label));
$table_data .= "<th  class='sort $class_label' data-sort='sort-$i' scope='col' data-tablesaw-sortable-col data-tablesaw-priority='7'>$label</th>"; 
$table_data .= "</thead>";
$table_data .= "</tbody>";
if (!is_user_logged_in() ) 
{
	
}
else{
	  foreach ( $value_recursive as $data )   
	  {
		  	$value_recursive = apply_filters( 'hook_RGCurrency', $data->payment_amount );
			 $author_obj = get_user_by('id', $data->created_by);
			$form_title = apply_filters( 'hook_get_the_form_title', $data->form_id );
		    $email = $wpdb->get_results( "SELECT value FROM  `onlinema_rg_lead_detail` WHERE  `lead_id` LIKE  '$data->id' AND  `field_number` LIKE  '31'" );
		    $get_email="";
		    $get_username="";
		   $keyfield_id = "";
			 
			foreach ($data as $keyfield => $valuefield) {
				// echo $msg->date;      // shows 2013-01-28 08:35:59
			//	echo $fields[170]."<br>";
			    if( get_fields($data->form_id ,$keyfield) == "Username" || get_fields($data->form_id ,$keyfield) == "username")
				{
					$keyfield_id= $keyfield;
				}
			
			}
		  $username_nd = $wpdb->get_results( "SELECT value FROM  `onlinema_rg_lead_detail` WHERE  `lead_id` LIKE  '$data->id' AND `field_number` LIKE  '".$keyfield_id."'" );
		   /* 
	    	   echo "<pre>";
			  print_r(  $username_nd);
		  echo "</pre>";   */  
			foreach ($email as $object) 
			{ 
				$get_email = array_key_exists($object->value,$object)?"":$object->value;
			}
			foreach ($username_nd as $object) 
			{ 
				$get_username = array_key_exists($object->value,$object)?"":$object->value;
			}
			//echo $get_username ."==". $current_user->user_login."=".($get_username == $current_user->user_login)."<br>" ;
			if($author_obj->user_login ==  $current_user->user_login || $get_username == $current_user->user_login)
			{
				$table_data .= '<tr>';  
				$table_data .= '<td class="title" onclick="show_more_detail('.$title_display.','.$data->id.','.$data->form_id.',\''.$form_title.'\');"><span ><a >#'.$data->id.'</a></span></td>'; 
				$table_data .= '<td ><span>'.$form_title.'</span></td>';
				//$table_data .= '<td ><span>'.$author_obj->user_login.'</span></td>';
				$table_data .= '<td ><span>'.$get_email.'</span></td>';
				$table_data .= '<td ><span>'.$data->payment_status.'</span></td>';
				$table_data .= '<td ><span>'.$data->payment_date.'</span></td>';
				//$table_data .= '<td ><span>'.$data->transaction_id.'</span></td>';
				$table_data .= '<td ><span>'.$value_recursive.'</span></td>';
				$table_data .= '<td ><span>'.$data->payment_method.'</span></td>'; 
				$table_data .= '<td ><span><a class="more-info" onclick="show_more_detail('.$title_display.','.$data->id.','.$data->form_id.',\''.$form_title.'\');">View</a></span></td>';
				$table_data .= '</tr>';  
			}
			
	  }
	}
$table_data .="</tbody></table></div>"; 
	 $table_data .= apply_filters( 'hook_Modal', "" );
	 



  return $table_data;

}
add_shortcode('gravity_table', 'table_creation');

function value_to_money( $value  ) {
	require_once( GFCommon::get_base_path() . '/currency.php' );
     $currency = new RGCurrency( GFCommon::get_currency() );
      $money = $currency->to_money( $value );
    return $money;
}
add_filter( 'hook_RGCurrency', 'value_to_money', 10, 1 );

function array_filter_recursive( $array , $callback = null ) {
    foreach ($array as $key => & $value) {
        if (is_array($value)) {
            $value = (object)array_filter_recursive($value, $callback);
        }
        else {
            if ( ! is_null($callback)) {
                if ( ! $callback($value)) {
                    unset($array[$key]);
                }
            }
            else {
                if ( ! (bool) $value) {
                    unset($array[$key]);
                }
            }
        }
    }
    unset($value);
    return $array;
}
add_filter( 'hook_filter_recursive', 'array_filter_recursive', 10, 2 );

function get_the_form_title($form_id) {
  $forminfo = RGFormsModel::get_form($form_id);
  $form_title = $forminfo->title;
  return $form_title;
}
add_filter( 'hook_get_the_form_title', 'get_the_form_title', 10, 1);

function Sortbydate($a, $b)
 {
   return $b->id - $a->id;
 }
 
add_filter( 'hook_Modal', 'post_love_display', 99 );
function post_love_display() {
	    $love_text = '<div id="myModal" class="'. esc_attr( "modal" ).'">';
		$love_text .= '<div class="modal-content"> <div class="header-modal"><span class="header-title"></span><span ><a class="close">x</a></span></div><div id="details"></div></div>';
		$love_text .= '</div>';
	return  $love_text;

}
add_action( 'wp_ajax_nopriv_post_love_add_love', 'post_love_add_love' );
add_action( 'wp_ajax_post_love_add_love', 'post_love_add_love' );


function get_fields($form_id,$field_id)
{
	$form = RGFormsModel::get_form_meta($form_id);
	
	 $fields = $form['fields'];
    $form_fields = array(); 
    foreach($fields as $field)
    {
        $form_fields[$field['id']] = $field['label'];
    }
	return $form_fields[$field_id];
}

function post_love_add_love() {
	header("Content-Type: application/json", true);
	$love = $_POST['post_id'];
	$form_id = $_POST['form_id'];
	$entry = array_filter(GFAPI::get_entry( $love ));
    $referrer = gform_get_meta( $love, 'id' );
	
	
	/* $form_fields['value']['repeater'] = 'null';
	$form_fields['value']['Repeater'] = 'null'; */
	
	$form = RGFormsModel::get_form_meta($form_id);
	
	 $fields = $form['fields'];
    $form_fields = array(); 
    foreach($fields as $field)
    {
        $form_fields[$field['id']] = $field['label'];
    }
	//echo $form_fields[128];
	$count = 0;
	foreach($entry as $key=>$value)
	{
		$data = @unserialize($value);
		if ($data !== false) 
		{
			$entry[$key] = unserialize($value);
			//$entry[$key] = "";
			//print_r($entry[$key]);
			 foreach($entry[$key] as $field=>$field_1value)
			{
				
					foreach($field_1value as $field_1=>$field_1val)
					{
						 foreach($field_1val as $keyd=>$valued)
						 {
							/*  echo count($entry[$key]);
							 echo "<pre>";
							 /*  print_r(  $entry[$key][$count++]);
							 echo "</pre>"; 
							   // $entry[$key][$count++] = 
							   print_r( change_key( $entry[$key][1], $field_1, $form_fields[$field_1]));
							  // echo $count++;
							 echo "</pre>"; */
							//print_r($entry[$key][$field][$field_1] ); 
							 $entry[$key][$field][$field_1] = array($form_fields[$field_1] => $valued);
						   
						 }
						
							// unset( $entry[$key] );
					}
					
			} 
			//echo "ok".'<br>';
		} 
		else
		{
			//echo "not ok".'<br>';
		}
	}
	
	$form_fields = get_all_form_fields($form_id, $entry,$form_fields );
	
	/* $form = RGFormsModel::get_form_meta($form_id);
	
	 $fields = $form['fields'];
    $form_fields = array(); 
    foreach($fields as $field)
    {
        $form_fields[$field['id']] = $field['label'];
    }
	echo $form_fields[130]; */
	//    echo "<pre>";
	 //print_r($form_fields); 
	
	// sort($form_fields, SORT_NUMERIC);
	echo json_encode($form_fields);
	// echo "<pre>"; 
	die();
}
function get_all_form_fields($form_id,$entry,$form_fields)
	{
        $form = RGFormsModel::get_form_meta($form_id);
		$repeater = array();
        $fields = array();
		$fiele_value = $entry;
		$count = 0;
        if(is_array($form["fields"]))
		{
			
             foreach($form["fields"] as $field){
                if(isset($field["inputs"]) && is_array($field["inputs"]))
				{
                     foreach($field["inputs"] as $input)
						$fields[$input["id"]] = GFCommon::get_label($field, $input["id"]); 
                }
                else if(!rgar($field, 'displayOnly'))
				{
						$fields[$field["id"] ] = GFCommon::get_label($field);
				}	
			}
			
		}
		//print_r($fiele_value);
		foreach($entry as $key=>$value)
		{
			foreach($fields as $key_fields=>$value_fields)
			{
				if(is_array($value))
				{
				$fiele_value = change_key( $fiele_value, $key,  " Repeater#".$key. "/~/(".$form_fields[$key]. ")");
					//echo "<script>console.log('".$key." = ".$form_fields[$key]."')</script>";
				}
				else if($key == $key_fields)
				{
					/* if($key_fields == "Repeater")
					{
					echo "<script>alert(". $key_fields.") </script>";
					} */
					
					$fiele_value = change_key( $fiele_value, $key, $value_fields);
				}
				else
				{
					/* if($key == '50')
					{ */
						
					//	$fiele_value = change_key( $fiele_value, $key, $key.' '. "Titled ~ ".$form_fields[$key]);
					// }
				}
			}
			
		}
        return array( "Fields" => $fiele_value )  ;
	}
function change_key( $array, $old_key, $new_key)
{
    if( ! array_key_exists( $old_key, $array ) )
        return $array;

    $keys = array_keys( $array );
    $keys[ array_search( $old_key, $keys ) ] = $new_key;

    return array_combine( $keys, $array );
}
function isJson($string) {
 json_decode($string);
 return (json_last_error() == JSON_ERROR_NONE);
}




add_filter( 'plugin_row_meta', 'gravity_view_meta_links', 10, 2 );
function gravity_view_meta_links( $links, $file ) {

	$plugin = plugin_basename(__FILE__);

// create the links
	/* if ( $file == $plugin ) {

		$supportlink = 'https://wordpress.org/support/plugin/tabby-responsive-tabs';
		$donatelink = 'http://cubecolour.co.uk/wp';
		$reviewlink = 'https://wordpress.org/support/view/plugin-reviews/tabby-responsive-tabs?rate=5#postform';
		$twitterlink = 'http://twitter.com/cubecolour';
		$customiselink = 'http://cubecolour.co.uk/tabby-responsive-tabs-customiser';
		$iconstyle = 'style="-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale;"';

		if ( is_plugin_active( 'tabby-responsive-tabs-customiser/tabby-customiser.php' ) ) {
			$customiselink = admin_url( 'options-general.php?page=tabby-settings' );
		}

		return array_merge( $links, array(
			'<a href="' . $supportlink . '"> <span class="dashicons dashicons-lightbulb" ' . $iconstyle . 'title="Tabby Responsive Tabs Support"></span></a>',
			'<a href="' . $twitterlink . '"><span class="dashicons dashicons-twitter" ' . $iconstyle . 'title="Cubecolour on Twitter"></span></a>',
			'<a href="' . $reviewlink . '"><span class="dashicons dashicons-star-filled"' . $iconstyle . 'title="Give a 5 Star Review"></span></a>',
			'<a href="' . $donatelink . '"><span class="dashicons dashicons-heart"' . $iconstyle . 'title="Donate"></span></a>',
			'<a href="' . $customiselink . '"><span class="dashicons dashicons-admin-appearance" ' . $iconstyle . 'title="Tabby Responsive Tabs Customizer"></span></a>'
		) );
	} */

	return $links;
}
if( ! class_exists( 'Smashing_Updater' ) ){
	include_once( plugin_dir_path( __FILE__ ) . 'inlcude/updater.php' );
}
$updater = new Gravity_table_update( __FILE__ );
$updater->set_username( 'rayman813' );
$updater->set_repository( 'smashing-updater-plugin' );
/*
	$updater->authorize( 'abcdefghijk1234567890' ); // Your auth code goes here for private repos
*/
$updater->initialize();/ initialize the 
?>
