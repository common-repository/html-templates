<?php
/*
Plugin Name: HTMLTemplates
Plugin URI: http://www.pirex.com.br/wordpress-plugins/
Description:  Allows you to create small HTML templates to use inside your posts or pages.
Author: LeoGermani
Version: 0.6
Author URI: http://leogermani.pirex.com.br/
*/ 

// mySQL table
$ht_table = $table_prefix."html_templates";
$ht_table_fields = $table_prefix."html_templates_fields";


ht_check_table();

function ht_check_table() {
	global $ht_table,$ht_table_fields ;

	$query=mysql_query("SHOW TABLES");
	
	$contador = 0;

	while ($fetch=mysql_fetch_array($query)) {
		if ($fetch[0] == $ht_table || $fetch[0] == $ht_table_fields) { $contador+=1; }
		
	}
	
	if ($contador < 2) {
		// table does not exists. creating.
		mysql_query("CREATE TABLE `$ht_table` (
			  `ht_ID` int(11) NOT NULL AUTO_INCREMENT,
			  `ht_name` TEXT NOT NULL,
			  `ht_template` LONGTEXT NOT NULL,
			  PRIMARY KEY  (`ht_ID`),
			  UNIQUE KEY `ht_ID` (`ht_ID`)
			) TYPE=MyISAM COMMENT='html templates plugin by LeoGermani'
		");	
		
		mysql_query("CREATE TABLE `$ht_table_fields` (
			  `template_ID` int(11) NOT NULL default '0',
			  `field_name` TEXT NOT NULL
			) TYPE=MyISAM COMMENT='html templates plugin by LeoGermani'
		");	
	}

}

// administration panel

function ht_admin() {
	if (function_exists('add_management_page')) {
		add_management_page('Manage HTML Templates', 'HTML Templates', 8, basename(__FILE__), 'htmlTemplates_admin_page');
	}
}

function htmlTemplates_admin_page() {
	global $ht_table,$ht_table_fields, $table_prefix;

	if (isset($_POST['submit_level'])) {


		echo "<div class=\"updated\"><p><strong>";
		//"
		$ht_name=$_POST['ht_newname'];
		$ht_fields=$_POST['ht_newfields'];
		$ht_template=$_POST['ht_newtemplate'];

		//$newFields = explode(",",$ht_fields);
		//	foreach ($newFields as $campo){
		//		echo $campo." ";
		//		//mysql_query("INSERT INTO $ht_table_fields VALUES($newId, '".trim($campo)."')");
		//	}
		
		$queryCount=mysql_query("SELECT * FROM $ht_table WHERE ht_name = '$ht_name' ");
		$existht=mysql_num_rows($queryCount);		
		
		if ($existht > 0) {
			
			_e('Error: Template Name already Exists','');
		}
		else { 
			mysql_query("INSERT INTO $ht_table (ht_name, ht_template) VALUES('$ht_name', '$ht_template')");
			$newId = mysql_query("SELECT ht_ID FROM $ht_table WHERE ht_name = '$ht_name'");
			$newId=mysql_fetch_array($newId);
			$newFields = explode(",",$ht_fields);
			foreach ($newFields as $campo){
				
				mysql_query("INSERT INTO $ht_table_fields VALUES(".$newId[0].", '".trim($campo)."')");
			}
			_e('Template added!','');
		}

		echo "</strong></p></div>";
	}
	
	
	if (isset($_POST['delete_level'])) {
		if(isset($_POST['ht_delete'])){		

			$ht_to_delete=implode(",",$_POST['ht_delete']);
			mysql_query("DELETE FROM $ht_table WHERE ht_ID IN ($ht_to_delete)");
			mysql_query("DELETE FROM $ht_table_fields WHERE template_ID IN ($ht_to_delete)");
			echo "<div class=\"updated\"><p><strong>";
			//"
			_e('Template(s) deleted!','');
			echo "</strong></p></div>";
		}
	}
	?>



	<div class=wrap>
	  <form name="l2c" method="post">
	    <h2>HTML Templates</h2>

		
							
	<?
	

	//$querystring="SELECT ".$l2c_table.".cat_ID, ".$l2c_table.".level, ".$l2c_category_table.".cat_name as cat_name FROM ".$l2c_table." INNER JOIN ".$l2c_category_table." ON ".$l2c_table.".cat_ID = ".$l2c_category_table.".cat_ID";
	$querystring="SELECT * FROM ".$ht_table;
	$query=mysql_query($querystring);
	$cat_total=mysql_num_rows($query);
	if ($cat_total > 0) {		
		
		while ($fetch=mysql_fetch_array($query)) {

			echo "<input type='checkbox' name='ht_delete[]' value='".$fetch["ht_ID"]."'> <b>".$fetch["ht_name"]."</b><BR>";

		}

			

	}
	?>
	
	<h3>Add New</h3>
	
	<b>Template Name</b><BR>
	<input type="text" name="ht_newname">
	<BR><BR>
	<b>Template Fields</b> (enter fields separeted by commas. example: "title,image,description")<BR>
	<input type="text" name="ht_newfields">
	<BR><BR>
	<b>Your HTML Template</b> Insert dynamic fields between "#". <BR>Example:<BR> <i>#title#&lt;BR&gt;&lt;img src="#image#"&gt;</i><BR>
	You can also use some template tags:<BR>
	#post-id#, #post-title# and #post-permalink# are accepted.<BR>
	<textarea cols="50" name="ht_newtemplate"></textarea>
	
	
	
	
	<BR><BR>
	<div class="submit">
	<input type="submit" name="submit_level" value="<?php _e('Add/Update', '') ?> &raquo;">
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<? if($cat_total>0) { ?>
	<input type="submit" name="delete_level" value="<?php _e('Remove Selected Templates', '') ?> &raquo;">
	<? } ?>
	</div>
	  </form>
	</div>

	<?php

}



//Add the buttons

if(version_compare($wp_version, '2.4', '>=')){
	include_once (dirname (__FILE__)."/plugin/tinymce.php");	
}


// Load the Script for the Button
function insert_htmlTemplate_script() {
	
	global $ht_table,$ht_table_fields, $table_prefix;
	?>

	<script type="text/javascript">
	function htmlTemplate_buttonscript() {  
		
		document.getElementById("ht_select").style.display="block";
	}
	
	function htmlTemplate_select_cancel() {  
		
		document.getElementById("ht_select").style.display="none";
		
	}
	
	function htmlTemplate_select(valor){
	
		document.getElementById("ht_select").style.display="none";
		document.getElementById("ht_template_"+valor).style.display="block";
	
	}
	
	</script>
	<style>
	
	.HT_boxes{
		position: absolute;
		width: 450px;
		top: 200px;
		left: auto;
		background: #14568a;
		color: #ffffff;
		font-size: 1em;
		padding: 10px;
		display:none;
	}
	
	.HT_boxes H1{
		font-size: 1.5em;
		color: #FFF;
		font-weight: bold;
	}
	
	
	</style>
	
	<div id="ht_select" class="HT_boxes">
		
		<h1>Select the template to use:</h1>
		<fieldset name="HT_select_form" id="HT_select_form">
		<select name="ht_templates" onChange="htmlTemplate_select(this.value)">
		<option value="">--Select--</option>
	<?
	/////////////////// Load the templates and Creates de DIVs........./////////////////////////////
	
	// carrega os templates
	
	$arrayTemplates = Array();
	$arrayTemplatesHTML = Array();
	$query_templates="SELECT * FROM ".$ht_table;
	$queryTemplates=mysql_query($query_templates);
		$total=mysql_num_rows($queryTemplates);
		$i_templates=0;
		if ($total > 0) {		
			
			while ($fetch=mysql_fetch_array($queryTemplates)) {
	
				echo '<option value="'.$i_templates.'">'.$fetch["ht_name"].'</option>';
					array_push($arrayTemplates, 0);
					array_push($arrayTemplatesHTML, $fetch["ht_template"]);
					
					$query_fields="SELECT * FROM ".$ht_table_fields." WHERE template_ID = ". $fetch["ht_ID"];
					$queryFields=mysql_query($query_fields);
					
					if(mysql_num_rows($queryFields)>0){
					
						$arrayTemplates[$i_templates] = Array();
						
						while ($field=mysql_fetch_array($queryFields)){
						
							array_push($arrayTemplates[$i_templates], $field["field_name"]);
						
						}
					
					}
					
					
					
					$i_templates+=1;
			}
	
				
	
		}
	
		
		
	?>
		</select>
		<input type="button" value="Cancel" onClick="htmlTemplate_select_cancel();">
		</fieldset>
	</div>	

	<? 
	//print_r($arrayTemplates);
	$i_templates=0;
	foreach ($arrayTemplates as $templates){
		
		echo '<div id="ht_template_'.$i_templates.'" class="HT_boxes">'.Chr(13);
		echo '<h1>Enter the values for the dynamic fields</h1>'.Chr(13);
		echo '<fieldset name="form_'.$i_templates.'" id="form_'.$i_templates.'">'.Chr(13);
		foreach($templates as $campos){
		
			echo "<b>" . $campos . ":<BR>".Chr(13);
			echo '<input type="text" name="field_'.$i_templates.'_'. $campos .'" id="field_'.$i_templates.'_'. $campos .'"><BR><BR>'.Chr(13);
			
		}
		echo '<input type="button" value="Cancel" onClick="ht_replace_'.$i_templates.'(';
		echo "'cancel'";
		echo ')">&nbsp;&nbsp;&nbsp;'.Chr(13);
		echo '<input type="button" value="Send to editor" onClick="ht_replace_'.$i_templates.'()">'.Chr(13);
		echo '</fieldset></div>'.Chr(13).Chr(13);
		$i_templates+=1;
	
	}
	
	echo "<script>".Chr(13);
	
	$i_templates=0;
	foreach ($arrayTemplates as $templates){
		
		echo 'function ht_replace_'.$i_templates.'(c){'.Chr(13);
			
			
			echo 'document.getElementById("ht_template_'.$i_templates.'").style.display="none";'.Chr(13);
			echo 'if (c=="cancel") return;'.Chr(13);
			echo 'var ht_html_'.$i_templates.'="'.str_replace('"','\"',str_replace(Chr(13), '', str_replace(Chr(10), '', $arrayTemplatesHTML[$i_templates]))).'";'; //substiruir quebras de linhas e aspas
			foreach($templates as $campos){
				
				echo 'ht_html_'.$i_templates.'=ht_html_'.$i_templates.'.replace("#'.$campos.'#",document.getElementById("field_'.$i_templates.'_'.$campos.'").value);'.Chr(13);

			}
			
			echo 'tinyMCE.execInstanceCommand("content", "mceInsertContent", false, ht_html_'.$i_templates.');'.Chr(13);
			
		echo '}'.Chr(13);
		$i_templates+=1;
	
	}
	
	
	echo "</script>".Chr(13);
	
	
	?>
	
	
	
	
	<?
}


function HT_replace_id($content){

	$content = str_replace("#post-id#", get_the_ID(), $content);
	$content = str_replace("#post-permalink#", get_permalink(get_the_ID()), $content);
	$content = str_replace("#post-title#", get_the_title(), $content);

	return $content;
	
}



//add_action('simple_edit_form', 'l2c_disable_cats');
//add_action('edit_form_advanced', 'l2c_disable_cats');
//add_action('init', 'htmlTemplate_addbuttons'); 
add_action('admin_menu', 'ht_admin');
add_action('edit_page_form', 'insert_htmlTemplate_script');
add_action('edit_form_advanced', 'insert_htmlTemplate_script');
add_filter('the_content', 'HT_replace_id')
?>
