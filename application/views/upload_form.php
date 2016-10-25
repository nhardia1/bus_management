<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>upload zip</title>

</head>
<body>

<form enctype="multipart/form-data" action="<?php echo base_url().'index.php/sync/upload_database';?>" method="post" >
<input type="file" name="upload_database">

<input type="submit" value="upload_database">

</form>

<!--
<form enctype="multipart/form-data" action="<?php //echo base_url().'index.php/sync_webservises/upload_images';?>" method="post" >
<input type="file" name="upload_images">

<input type="submit" value="upload_images">

</form>



<form enctype="multipart/form-data" action="<?php //echo base_url().'index.php/sync_webservises/upload_videos';?>" method="post" >
<input type="file" name="upload_videos">

<input type="submit" value="uploadPostVideos">

</form>
-->

</form>


</body>
</html>

