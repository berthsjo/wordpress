	<?php	
	if(function_exists('current_user_can'))
	if(!current_user_can('manage_options')) {
	die('Access Denied');
}	
if(!function_exists('current_user_can')){
	die('Access Denied');
}

function showslider() 
  {
	  
  global $wpdb;
  
  if(isset($_POST['search_events_by_title']))
$_POST['search_events_by_title']=esc_html(stripslashes($_POST['search_events_by_title']));
if(isset($_POST['asc_or_desc']))
$_POST['asc_or_desc']=esc_js($_POST['asc_or_desc']);
if(isset($_POST['order_by']))
$_POST['order_by']=esc_js($_POST['order_by']);
  $where='';
  	$sort["custom_style"] ="manage-column column-autor sortable desc";
	$sort["default_style"]="manage-column column-autor sortable desc";
	$sort["sortid_by"]='id';
	$sort["1_or_2"]=1;
	$order='';
	
	if(isset($_POST['page_number']))
	{
			
			if($_POST['asc_or_desc'])
			{
				$sort["sortid_by"]=$_POST['order_by'];
				if($_POST['asc_or_desc']==1)
				{
					$sort["custom_style"]="manage-column column-title sorted asc";
					$sort["1_or_2"]="2";
					$order="ORDER BY ".$sort["sortid_by"]." ASC";
				}
				else
				{
					$sort["custom_style"]="manage-column column-title sorted desc";
					$sort["1_or_2"]="1";
					$order="ORDER BY ".$sort["sortid_by"]." DESC";
				}
			}
	if($_POST['page_number'])
		{
			$limit=($_POST['page_number']-1)*20; 
		}
		else
		{
			$limit=0;
		}
	}
	else
		{
			$limit=0;
		}
	if(isset($_POST['search_events_by_title'])){
		$search_tag=esc_html(stripslashes($_POST['search_events_by_title']));
		}
		
		else
		{
		$search_tag="";
		}		
		
	 if(isset($_GET["catid"])){
	    $cat_id=$_GET["catid"];	
		}
       else
	   {
       if(isset($_POST['cat_search'])){
		$cat_id=$_POST['cat_search'];
		}else{
		
		$cat_id=0;}
       }
     
 if ( $search_tag ) {
		$where= " WHERE name LIKE '%".$search_tag."%' ";
	}
if($where){
	  if($cat_id){
	  $where.=" AND sl_width=" .$cat_id;
	  }
	
	}
	else{
	if($cat_id){
	  $where.=" WHERE sl_width=" .$cat_id;
	  }
	
	}
	
	 $cat_row_query="SELECT id,name FROM ".$wpdb->prefix."huge_itslider_sliders WHERE sl_width=0";
	$cat_row=$wpdb->get_results($cat_row_query);
	
	// get the total number of records
	$query = "SELECT COUNT(*) FROM ".$wpdb->prefix."huge_itslider_sliders". $where;
	
	$total = $wpdb->get_var($query);
	$pageNav['total'] =$total;
	$pageNav['limit'] =	 $limit/20+1;
	
	if($cat_id){
	$query ="SELECT  a.* ,  COUNT(b.id) AS count, g.par_name AS par_name FROM ".$wpdb->prefix."huge_itslider_sliders  AS a LEFT JOIN ".$wpdb->prefix."huge_itslider_sliders AS b ON a.id = b.sl_width LEFT JOIN (SELECT  ".$wpdb->prefix."huge_itslider_sliders.ordering as ordering,".$wpdb->prefix."huge_itslider_sliders.id AS id, COUNT( ".$wpdb->prefix."huge_itslider_images.slider_id ) AS prod_count
FROM ".$wpdb->prefix."huge_itslider_images, ".$wpdb->prefix."huge_itslider_sliders
WHERE ".$wpdb->prefix."huge_itslider_images.slider_id = ".$wpdb->prefix."huge_itslider_sliders.id
GROUP BY ".$wpdb->prefix."huge_itslider_images.slider_id) AS c ON c.id = a.id LEFT JOIN
(SELECT ".$wpdb->prefix."huge_itslider_sliders.name AS par_name,".$wpdb->prefix."huge_itslider_sliders.id FROM ".$wpdb->prefix."huge_itslider_sliders) AS g
 ON a.sl_width=g.id WHERE  a.name LIKE '%".$search_tag."%' group by a.id ". $order ." "." LIMIT ".$limit.",20" ; 

	 }
	 else{
	 $query ="SELECT  a.* ,  COUNT(b.id) AS count, g.par_name AS par_name FROM ".$wpdb->prefix."huge_itslider_sliders  AS a LEFT JOIN ".$wpdb->prefix."huge_itslider_sliders AS b ON a.id = b.sl_width LEFT JOIN (SELECT  ".$wpdb->prefix."huge_itslider_sliders.ordering as ordering,".$wpdb->prefix."huge_itslider_sliders.id AS id, COUNT( ".$wpdb->prefix."huge_itslider_images.slider_id ) AS prod_count
FROM ".$wpdb->prefix."huge_itslider_images, ".$wpdb->prefix."huge_itslider_sliders
WHERE ".$wpdb->prefix."huge_itslider_images.slider_id = ".$wpdb->prefix."huge_itslider_sliders.id
GROUP BY ".$wpdb->prefix."huge_itslider_images.slider_id) AS c ON c.id = a.id LEFT JOIN
(SELECT ".$wpdb->prefix."huge_itslider_sliders.name AS par_name,".$wpdb->prefix."huge_itslider_sliders.id FROM ".$wpdb->prefix."huge_itslider_sliders) AS g
 ON a.sl_width=g.id WHERE a.name LIKE '%".$search_tag."%'  group by a.id ". $order ." "." LIMIT ".$limit.",20" ; 
}

$rows = $wpdb->get_results($query);
 global $glob_ordering_in_cat;
if(isset($sort["sortid_by"]))
{
	if($sort["sortid_by"]=='ordering'){
	if($_POST['asc_or_desc']==1){
		$glob_ordering_in_cat=" ORDER BY ordering ASC";
	}
	else{
		$glob_ordering_in_cat=" ORDER BY ordering DESC";
	}
	}
}
$rows=open_cat_in_tree($rows);
	$query ="SELECT  ".$wpdb->prefix."huge_itslider_sliders.ordering,".$wpdb->prefix."huge_itslider_sliders.id, COUNT( ".$wpdb->prefix."huge_itslider_images.slider_id ) AS prod_count
FROM ".$wpdb->prefix."huge_itslider_images, ".$wpdb->prefix."huge_itslider_sliders
WHERE ".$wpdb->prefix."huge_itslider_images.slider_id = ".$wpdb->prefix."huge_itslider_sliders.id
GROUP BY ".$wpdb->prefix."huge_itslider_images.slider_id " ;
	$prod_rows = $wpdb->get_results($query);
		
foreach($rows as $row)
{
	foreach($prod_rows as $row_1)
	{
		if ($row->id == $row_1->id)
		{
			$row->ordering = $row_1->ordering;
		$row->prod_count = $row_1->prod_count;
	}
		}
	
	}
	
	$cat_row=open_cat_in_tree($cat_row);
		html_showsliders( $rows, $pageNav,$sort,$cat_row);
  }

function open_cat_in_tree($catt,$tree_problem='',$hihiih=1){

global $wpdb;
global $glob_ordering_in_cat;
static $trr_cat=array();
if(!isset($search_tag))
$search_tag='';
if($hihiih)
$trr_cat=array();
foreach($catt as $local_cat){
	$local_cat->name=$tree_problem.$local_cat->name;
	array_push($trr_cat,$local_cat);
	$new_cat_query=	"SELECT  a.* ,  COUNT(b.id) AS count, g.par_name AS par_name FROM ".$wpdb->prefix."huge_itslider_sliders  AS a LEFT JOIN ".$wpdb->prefix."huge_itslider_sliders AS b ON a.id = b.sl_width LEFT JOIN (SELECT  ".$wpdb->prefix."huge_itslider_sliders.ordering as ordering,".$wpdb->prefix."huge_itslider_sliders.id AS id, COUNT( ".$wpdb->prefix."huge_itslider_images.slider_id ) AS prod_count
FROM ".$wpdb->prefix."huge_itslider_images, ".$wpdb->prefix."huge_itslider_sliders
WHERE ".$wpdb->prefix."huge_itslider_images.slider_id = ".$wpdb->prefix."huge_itslider_sliders.id
GROUP BY ".$wpdb->prefix."huge_itslider_images.slider_id) AS c ON c.id = a.id LEFT JOIN
(SELECT ".$wpdb->prefix."huge_itslider_sliders.name AS par_name,".$wpdb->prefix."huge_itslider_sliders.id FROM ".$wpdb->prefix."huge_itslider_sliders) AS g
 ON a.sl_width=g.id WHERE a.name LIKE '%".$search_tag."%' AND a.sl_width=".$local_cat->id." group by a.id  ".$glob_ordering_in_cat; 
 $new_cat=$wpdb->get_results($new_cat_query);
 open_cat_in_tree($new_cat,$tree_problem. "— ",0);
}
return $trr_cat;

}

function editslider($id)
  {
	  
	  global $wpdb;
	  
	     if($_GET["removeslide"] != ''){
	

	  $wpdb->query("DELETE FROM ".$wpdb->prefix."huge_itslider_images  WHERE id = ".$_GET["removeslide"]." ");


	
	   }

	   $query=$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."huge_itslider_sliders WHERE id=%d",$id);
	   $row=$wpdb->get_row($query);
	   if(!isset($row->slider_list_effects_s))
	   return 'id not found';
       $images=explode(";;;",$row->slider_list_effects_s);
	   $par=explode('	',$row->param);
	   $count_ord=count($images);
	   $cat_row=$wpdb->get_results("SELECT * FROM ".$wpdb->prefix."huge_itslider_sliders WHERE id!=" .$id." and sl_width=0");
       $cat_row=open_cat_in_tree($cat_row);
	   	  $query="SELECT name,ordering FROM ".$wpdb->prefix."huge_itslider_sliders WHERE sl_width=".$row->sl_width."  ORDER BY `ordering` ";
	   $ord_elem=$wpdb->get_results($query);
	   
	    $query=$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."huge_itslider_images where slider_id = '".$row->id."' order by id ASC  ",$id);
			   $rowim=$wpdb->get_results($query);
			   
			   if($_GET["addslide"] == 1){
	
$table_name = $wpdb->prefix . "huge_itslider_images";
    $sql_2 = "
INSERT INTO 

`" . $table_name . "` ( `name`, `slider_id`, `description`, `image_url`, `sl_url`, `ordering`, `published`, `published_in_sl_width`) VALUES
( '', '".$row->id."', '', '', '', 'par_TV', 2, '1' )";

    $wpdb->query($sql_huge_itslider_images);
	

      $wpdb->query($sql_2);
	
	   }
	
	   
	   $query=$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."huge_itslider_sliders order by id ASC",$id);
			   $rowsld=$wpdb->get_results($query);
			  
			    $query = "SELECT *  from " . $wpdb->prefix . "huge_itslider_params ";

    $rowspar = $wpdb->get_results($query);

    $paramssld = array();
    foreach ($rowspar as $rowpar) {
        $key = $rowpar->name;
        $value = $rowpar->value;
        $paramssld[$key] = $value;
    }
	
	
	 
	
	   	   
    Html_editslider($ord_elem, $count_ord, $images, $row, $cat_row, $rowim, $rowsld, $paramssld);
  }
  
function add_slider()
{
	global $wpdb;
	
	$query="SELECT name,ordering FROM ".$wpdb->prefix."huge_itslider_sliders WHERE sl_width=0 ORDER BY `ordering`";
	$ord_elem=$wpdb->get_results($query); ///////ordering elements list
	$cat_row=$wpdb->get_results("SELECT * FROM ".$wpdb->prefix."huge_itslider_sliders where sl_width=0");
	$cat_row=open_cat_in_tree($cat_row);
	
	$table_name = $wpdb->prefix . "huge_itslider_sliders";
    $sql_2 = "
INSERT INTO 

`" . $table_name . "` ( `name`, `sl_height`, `sl_width`, `pause_on_hover`, `slider_list_effects_s`, `description`, `param`, `ordering`, `published`) VALUES
( 'New slider', '500', '300', 'true', '2', '2900', '1000', '1', '1')";

    $wpdb->query($sql_huge_itslider_sliders);

      $wpdb->query($sql_2);

   $query=$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."huge_itslider_sliders order by id ASC",$id);
			   $rowsldcc=$wpdb->get_results($query);
			   $last_key = key( array_slice( $rowsldcc, -1, 1, TRUE ) );
			   
			   
	foreach($rowsldcc as $key=>$rowsldccs){
		if($last_key == $key){
			header('Location: admin.php?page=sliders_huge_it_slider&id='.$rowsldccs->id.'&task=apply');
		}
	}
	
	html_add_slider($ord_elem, $cat_row);
	
}

function removeslider($id)
{

	global $wpdb;
	 $sql_remov_tag=$wpdb->prepare("DELETE FROM ".$wpdb->prefix."huge_itslider_sliders WHERE id=%d",$id);
 if(!$wpdb->query($sql_remov_tag))
 {
	  ?>
	  <div id="message" class="error"><p>slider Not Deleted</p></div>
      <?php
	 
 }
 else{
 ?>
 <div class="updated"><p><strong><?php _e('Item Deleted.' ); ?></strong></p></div>
 <?php
 }
    $row=$wpdb->get_results($wpdb->prepare( 'UPDATE '.$wpdb->prefix.'huge_itslider_sliders SET sl_width="0"   WHERE sl_width=%d',$id));
	$rows=$wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'huge_itslider_sliders  ORDER BY `ordering` ASC ');
	
	$count_of_rows=count($rows);
	$ordering_values=array();
	$ordering_ids=array();
	for($i=0;$i<$count_of_rows;$i++)
	{		
	
		$ordering_ids[$i]=$rows[$i]->id;
		if(isset($_POST["ordering"]))
		$ordering_values[$i]=$i+1+$_POST["ordering"];
		else
		$ordering_values[$i]=$i+1;
	}

		for($i=0;$i<$count_of_rows;$i++)
	{	
			$wpdb->update($wpdb->prefix.'huge_itslider_sliders', 
			  array('ordering'      =>$ordering_values[$i]), 
              array('id'			=>$ordering_ids[$i]),
			  array('%s'),
			  array( '%s' )
			  );
	}

}

function apply_cat($id)
{	
		 global $wpdb;
		 if(!is_numeric($id)){
			 echo 'insert numerc id';
		 	return '';
		 }
		 if(!(isset($_POST['sl_width']) && isset($_POST["name"]) ))
		 {
			 return '';
		 }
		 $cat_row=$wpdb->get_results($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."huge_itslider_sliders WHERE id!=%d ",$_GET['id']));
		 $corent_ord=$wpdb->get_var($wpdb->prepare('SELECT `ordering` FROM '.$wpdb->prefix.'huge_itslider_sliders WHERE id=%d AND sl_width=%d',$id,$_POST['sl_width']));
		 $max_ord=$wpdb->get_var('SELECT MAX(ordering) FROM '.$wpdb->prefix.'huge_itslider_sliders');
	 
            $query=$wpdb->prepare("SELECT sl_width FROM ".$wpdb->prefix."huge_itslider_sliders WHERE id=%d",$id);
	        $id_bef=$wpdb->get_var($query);
      

	$script_cat = preg_replace('#<script(.*?)>(.*?)</script>#is', '', stripslashes($_POST["content"]));
	$savedd=$wpdb->update($wpdb->prefix.'huge_itslider_sliders', array(
					'name'   				 => esc_js($_POST["name"]),
					'sl_width'   				 => $_POST["sl_width"],
					'sl_height'   				 => $_POST['sl_height'],
					'pause_on_hover'   				 => $_POST['pause_on_hover'],
					'slider_list_effects_s'     => $_POST['slider_effects_list'],
					'description'				=> $_POST['sl_pausetime'],
					'param'  				 =>$_POST["sl_changespeed"],
					'ordering' 				 => '1',
					'published'				 =>'1',
              ), 
              array('id'=>$id),
			  array( 
			    '%s',
				'%d',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%d',	
				'%d', )
			  );

		
	$query=$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."huge_itslider_sliders WHERE id=%d",$id);
	   $row=$wpdb->get_row($query);

			    $query=$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."huge_itslider_images where slider_id = '".$row->id."' order by id ASC",$id);
			   $rowim=$wpdb->get_results($query);
			   
			   foreach ($rowim as $key=>$rowimages){
			
$wpdb->query("UPDATE ".$wpdb->prefix."huge_itslider_images SET  sl_url = '".$_POST["sl_url".$rowimages->id.""]."' WHERE ID = ".$rowimages->id." ");
$wpdb->query("UPDATE ".$wpdb->prefix."huge_itslider_images SET  name = '".$_POST["titleimage".$rowimages->id.""]."'  WHERE ID = ".$rowimages->id." ");
$wpdb->query("UPDATE ".$wpdb->prefix."huge_itslider_images SET  description = '".$_POST["im_description".$rowimages->id.""]."'  WHERE ID = ".$rowimages->id." ");
$wpdb->query("UPDATE ".$wpdb->prefix."huge_itslider_images SET  image_url = '".$_POST["imagess".$rowimages->id.""]."'  WHERE ID = ".$rowimages->id." ");
}

if (isset($_POST['params'])) {
      $params = $_POST['params'];
      foreach ($params as $key => $value) {
          $wpdb->update($wpdb->prefix . 'huge_itslider_params',
              array('value' => $value),
              array('name' => $key),
              array('%s')
          );
      }
     
    }
	
				   if($_POST["imagess"] != ''){
	
$table_name = $wpdb->prefix . "huge_itslider_images";
    $sql_2 = "
INSERT INTO 

`" . $table_name . "` ( `name`, `slider_id`, `description`, `image_url`, `sl_url`, `ordering`, `published`, `published_in_sl_width`) VALUES
( '', '".$row->id."', '', '".$_POST["imagess"]."', '', 'par_TV', 2, '1' )";

    $wpdb->query($sql_huge_itslider_images);
	

      $wpdb->query($sql_2);
	
	   }
	

	?>
	<div class="updated"><p><strong><?php _e('Item Saved'); ?></strong></p></div>
	<?php
	
    return true;
	
}

?>