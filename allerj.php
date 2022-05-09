<?php

include 'search_replace.php';

if ( isset( $_POST['test'] ) && $_POST['test'] && $_POST['namespace'] != '' )
{

	$ext = 'php,css,js,txt,md';
	$old_package = 'j_aller';
	$old_namespace = 'allerj';
	$old_title = "AllerJ";
	
	$new_package = $_POST['package'];
	$new_namespace = $_POST['namespace'];
	$new_title = $_POST['title'];
	
	$base = 'dashSpeedPress';
	$destination_dir = $new_namespace; 
	$source_dir = $base;   


	if (recursive_files_copy($source_dir, $destination_dir)) {
	
		$dir = $new_namespace;

		if ( !isset( $_POST['recursive'] ) ) $_POST['recursive'] = 0;
		if ( !isset( $_POST['whole'] ) ) $_POST['whole'] = 0;
		if ( !isset( $_POST['case'] ) ) $_POST['case'] = 0;
		if ( !isset( $_POST['regexp'] ) ) $_POST['regexp'] = 0;

	// FOR Title
		$test = new search_replace( $dir,
									$old_title,
									$new_title,
									$ext,
									$_POST['regexp'],
									$_POST['recursive'],
									$_POST['whole'],
									$_POST['case'] );
		$result = $test->get_results();
	
	// FOR Namespace
		$test = new search_replace( $dir,
									$old_namespace,
									$new_namespace,
									$ext,
									$_POST['regexp'],
									$_POST['recursive'],
									$_POST['whole'],
									$_POST['case'] );
		$result = $test->get_results();
	
	// FOR Package
		$test = new search_replace( $dir,
									$old_package,
									$new_package,
									$ext,
									$_POST['regexp'],
									$_POST['recursive'],
									$_POST['whole'],
									$_POST['case'] );
		$result = $test->get_results();


	// Make and download ZIP file
		$zip_file = $new_namespace.'.zip';
		$rootPath = realpath($dir);
		$zip = new ZipArchive();
		$zip->open($zip_file, ZipArchive::CREATE | ZipArchive::OVERWRITE);
		
		$files = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator($rootPath),
			RecursiveIteratorIterator::LEAVES_ONLY
		);
		
		foreach ($files as $name => $file)
		{
			if (!$file->isDir())
			{
				$filePath = $file->getRealPath();
				$relativePath = substr($filePath, strlen($rootPath) + 1);
				$zip->addFile($filePath, $relativePath);
			}
		}
		
		$zip->close();
		
		// header('Content-Description: File Transfer');
		// header('Content-Type: application/octet-stream');
		// header('Content-Disposition: attachment; filename='.basename($zip_file));
		// header('Content-Transfer-Encoding: binary');
		// header('Expires: 0');
		// header('Cache-Control: must-revalidate');
		// header('Pragma: public');
		// header('Content-Length: ' . filesize($zip_file));
		// readfile($zip_file);
		
	}


}


function recursive_files_copy($source_dir, $destination_dir) 
{ 
  $dir = opendir($source_dir);  
  @mkdir($destination_dir);  
  while($file = readdir($dir)) 
  {
	if(($file != '.') && ($file != '..')) 
	{  
	  if(is_dir($source_dir.'/'.$file))  
	  {  
		recursive_files_copy($source_dir.'/'.$file, $destination_dir.'/'.$file); 
	  }  
	  else 
	  {  
		copy($source_dir.'/'.$file, $destination_dir.'/'.$file);  
	  }  
	}  
  }  
  closedir($dir); 
  return true;
}  


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>dashSpeedPress() Theme Generation</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<style type="text/css">
<!--
body
{
	min-width: 800px;
	margin: 0 3%;
	background: #fff;
	color: #000;
	font-family: verdana,arial,sans-serif;
	font-size: 75%;
}
#container
{
	background: #efefef;
	border: 1px solid #ccc;
	-moz-border-radius: 5px;
	border-radius: 5px;
	padding: 5px;
	clear: both;
}
#header
{
	margin-top: 0;
	margin-bottom: 20px;
	padding: 10px;
	-moz-border-radius: 6px;
	border-bottom-right-radius: 6px;
	border-bottom-left-radius: 6px;
	box-shadow: 0px 3px 1px #ccc;
	background: #383838;
	/* gecko based browsers */
	background: -moz-linear-gradient(top, #444, #000);
	/* webkit based browsers */
	background: -webkit-gradient(linear, left top, left bottom, from(#444), to(#000));
	color: #fff; /* text colour (black) */
	height: auto; /* gradient uses the full height of the element */
}
#form_table
{
	width: 100%;
	border-collapse: seperate;
	border-spacing: 8px;
}
#form_table td
{
	-moz-border-radius: 3px;
	border-radius: 3px;
	background: #ddd;
	color: #000;
	padding: 5px;
}
input
{
	padding: 3px;
}
.align_elem
{
	text-align: left;
}
.align_ctrl
{
	text-align: center;
}
-->
</style>
</head>
<body>
	<div id="header">
		<h1 style="font-style:oblique;padding-left:20px;">dashSpeedPress() Theme Generation</h1>
	</div>
	<div id="container">
		<?php
			if($_POST){
				?>
				<center>
				<h1>Theme for <?= $new_title; ?> Ready</h1>
				<h5><a href="<?= $new_namespace; ?>.zip">Download <?= $new_namespace; ?>.zip Now</a></h5>
				</center>
				<br /><br /><br /><br />
				<?php
			}
		?>
		<form action="dash.php" method="post">
			<input type="hidden" name="test" value="1" />
			<table id="form_table">
				<tr>
					<th class="align_elem" colspan="2">Use this to build a custom branded theme for a client.<br /><br />
					</th>
				</tr>
				<tr>
					<th class="align_elem" colspan="2">Client Title</th>
				</tr>
				<tr>
					<td class="align_elem"><input type="text" name="title" /></td>
					<td class="align_elem" style="width:100%;">This should be something like "Datz Restaurant" it replaces "dashSpeedPress()" in base theme.</td>
				</tr>
				<tr>
					<th class="align_elem" colspan="2">Package</th>
				</tr>
				<tr>
					<td class="align_elem"><input type="text" name="package" /></td>
					<td class="align_elem">This is an underscore version of title "Datz_Restaurant" it replaces "dash_Speed_Press" in base theme.</td>
				</tr>
				<tr>
					<th class="align_elem" colspan="2">Namespace</th>
				</tr>
				<tr>
					<td class="align_elem"><input type="text" name="namespace" /></td>
					<td class="align_elem">This should be short, unique, all lowercase, oneword, namespace like "datzr" it replaces "dashsp"</td>
				</tr>
				<tr>
					<td colspan="2" class="align_ctrl"><input type="submit" value="Replace" style="padding:5px 20px;" />&nbsp;&nbsp;&nbsp;<input type="reset"style="padding:5px 16px;" value="Clear Form" /></td>
				</tr>
				<tr>
					<th class="align_elem" colspan="2" style="padding:10px;">
					<?php

					if ( isset( $_POST['test'] ) && $_POST['test'] && $_POST['namespace'] != '' )
					{
						echo "Search string: <span style=\"font-weight:normal\">{$_POST['needle']}</span><br />";
						echo "Replacement string: <span style=\"font-weight:normal\">{$_POST['replace']}</span><br />";
						echo "<p>Files searched: {$result[0]}<br />";
						echo "Files modified: {$result[1]}</p>";

						echo "<p style=\"height:100px;overflow:auto;background:#fff;padding:8px;border:1px solid #ccc;\">";

						if ( $result[1] )
						{
							foreach ( $result[2] as $files )
								echo $files . ' instance(s)<br />';
						}

						echo '</p>';
					}
					else
					{
						echo "<p>Files searched:<br />";
						echo "Files modified:</p>";
					}

					?>
					</th>
				</tr>
			</table>
		</form>
	</div>
</body>
</html>
