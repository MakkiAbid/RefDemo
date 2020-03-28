<?php 

	
	include "config.php";
	if(empty($_SESSION['user'])){
		header("Location: index.php");
		exit();
	}

	include "include/header.php";


	$video_id = $_GET['v'];
	$video = mysqli_fetch_assoc(mysqli_query($conn, "SELECT video from videos where id = '$video_id'"))['video'];
?>



<div>
	<video id="player" playsinline controls>
	    <source src="./uploads/<?= $video ?>" type="video/mp4">
	</video>
</div>	

<script>
    const player = new Plyr('#player');
</script>


<?php include "include/footer.php"; ?>