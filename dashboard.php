<?php
	include "config.php";
	if(empty($_SESSION['user'])){
		header("Location: index.php");
		exit();
	}

	include "include/header.php";

	$video = null;
	$errors = array('video' => '');

	 if(isset($_POST['uploadbtn']))	{

	 	if(!empty($_FILES['video']['name'])){
            //check video
        $target_dir = "./uploads/";
        $target_file = $target_dir . basename($_FILES["video"]["name"]);
        $uploadOk = 1;
        $videoFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        // Check file size
        if ($_FILES["video"]["size"] > 50000000) {
            $errors['video'] = "Sorry, your file is too large.";
            $uploadOk = 0;
        }
        // Allow certain file formats
        if($videoFileType != "mp4" && $videoFileType != "mkv" && $videoFileType != "3gp") {
            $errors['video'] = "Sorry, only mp4, mkv & 3gp files are allowed.";
            $uploadOk = 0;
        }
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 1){
            $newname = md5(uniqid())."_".time().".".$videoFileType;
            if (move_uploaded_file($_FILES["video"]["tmp_name"], $target_dir.$newname)) {
                $video = $newname;
                $userid=  $_SESSION['user']['id'];
                mysqli_query($conn,"INSERT INTO videos (user_id,video) VALUES ('$userid','$video')");
                mysqli_query($conn, "UPDATE users SET balance = balance + 10 WHERE  id = ".$_SESSION['user']['id']);
                header("Location: dashboard.php");
            } else {
                $errors['video'] = "Sorry, there was an error uploading your file.";
            }
        }
        }else{
            $errors['video'] = "Field Cannot be empty!";

        }


	 }//main if ends here

?>


	<!-- Refral Link Card -->
	<div class="d-flex flex-justify-center">
		<div class="card col-sm-8 offset-2">
			<div class="card-header d-flex flex-justify-between">
				<h3>Your Referal Link</h3>
				<h3>Balance: $<?= number_format(mysqli_fetch_assoc(mysqli_query($conn, "SELECT balance from users where id = ".$_SESSION['user']['id']))['balance']) ?></h3>
			</div>
			<div class="card-content d-flex flex-justify-between flex-align-center py-4">
				<a target="_blank" href="<?= WEBSITE_URI."signup.php?ref_token=".$_SESSION['user']['token'] ?>"><?= WEBSITE_URI."signup.php?ref_token=".$_SESSION['user']['token'] ?></a>

				<a href="logout.php" class="button drop-shadow alert rounded">Logout</a>
			</div>
		</div>
	</div>



<!-- Upload CARD -->
<div class="card col-sm-8 offset-2">
	<div class="card-header">
		<h3>Upload Video</h3>
	</div>
		<form enctype="multipart/form-data" method="POST">
			<div class="card-content py-4">
				<input name="video" type="file" dir="ltr" data-role="file">
					<?php if(!empty($errors['video'])): ?>
						<p class="remark alert"><?= $errors['video']; ?></p>
	              	<?php endif; ?>					
			</div>
			<div class="card-footer">
				<button name="uploadbtn" type="submit" class="button primary rounded col-sm-12">Upload</button>
			</div>
		</form>
</div>



 <!-- Refered Users CARD -->
<div class="card mt-5 col-sm-8 offset-2">
	<div class="card-header">
		<h4>Your Refered users</h4>
	</div>
		<div class="card-content">
			<table class="table">
				<thead>
					<tr>
						<th>Email</th>
						<th>Balance</th>
					</tr>
				</thead>
				<tbody class="row-hover">
					<?php
						$id = $_SESSION['user']['id'];
						$results = mysqli_query($conn, "SELECT * FROM users where ref_id = '$id'");
						while ($user = mysqli_fetch_assoc($results)):
					?>
					<tr>
						<td><?= $user['email'] ?></td>
						<td>$<?= number_format($user['balance']) ?></td>
					</tr>
				<?php endwhile; ?>
				</tbody>
			</table>
		</div>
</div>


<!-- Your Videos -->
<div class="card col-sm-8 offset-2">
	<div class="card-header">
		<h3>Your Videos</h3>
	</div>
	<div class="card-content">
		<table class="table">
			<thead>
				<tr>
					<th>Name</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				$id = $_SESSION['user']['id'];
				$videos = mysqli_query($conn, "SELECT * from videos WHERE user_id = '$id'" ); 
				while($video = mysqli_fetch_assoc($videos)): ?>
					<tr>
						<td><?= $video['video']; ?></td>
						<td><a class="button primary rounded" href="video.php?v=<?= $video['id']; ?>">View</a></td>
					</tr>
				<?php endwhile; ?>
			</tbody>
		</table>
	</div>
</div>

<?php include "include/footer.php" ?>