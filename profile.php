<?php include 'includes/index.inc.php'; ?>
<?php //'<pre>' . print_r($_SESSION) . '</pre>'; ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.7.2/css/all.min.css"  />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="css/style.css">
	<title>PHP JS | profile</title>
    </head>
    <body>
	<!-- Logout option -->
        <form class="logout-btn">
            <?php
                if(isset($_SESSION["username"])){
                    echo '<button type="submit" name="logout"> Logout </button>';
                }
            ?>
        </form>

	<!-- Showcase -->
	<div class="showcase">
		<h1 class="heading"> PHP JS CURD OOP | <span style="color: crimson;">profile</span> </h1>
		<p class="connection-error"> <?php echo $conn_error; ?></p>
	</div>
	<div class="profile">
		<div class="profile-info">
			<!-- Profle picture -->
			<div class="image">
				<i id="change-image-btn" class="fas fa-cog"></i>
			</div>
			<!-- username, fullname and email -->
			<div class="info">
				<div class="info-inner">
					<h3 class="full-name"></h3><i id="change-name-btn" class="fas fa-cog"></i><br>
					<h5 class="username"></h5><br>
					<h5 class="email"></h5>
				</div>
			</div>
		</div>
	</div>
        <div class="my-timeline">
            <h1>My photos</h1>
            <div class="upload-section">
                <h3>Drag and Drop</h3>
            </div>
            <div class="photos">
                <div class="photo">
                    <!-- drag and drop section overlay -->
                </div>
            </div>
        </div>

	<!-- Scripts -->
	<script type="text/javascript" src="javaScript/app.js"></script>
	<script type="text/javascript" src="javaScript/profile.js"></script>
        <script type="text/javascript" src="javaScript/dragDrop.js"></script>
    </body>
</html>
