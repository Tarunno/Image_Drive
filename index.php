<?php include 'includes/index.inc.php'; ?>
<?php //'<pre>' . print_r($_SESSION) . '</pre>'; ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.7.2/css/all.min.css"  />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="css/style.css">
        <title>PHP JS | curd</title>
    </head>
	<body>
        <!-- Logout and Profile option -->
        <div class="profile-btn">
            <?php
                if(isset($_SESSION["username"])){
                    echo '<a href="profile.php"> <button> Profile </button> </a>';
                }
            ?>
        </div>
        <form class="logout-btn">
            <?php
                if(isset($_SESSION["username"])){
                    echo '<button type="submit" name="logout"> Logout </button>';
                }
            ?>
        </form>

        <!-- Showcase -->
		<div class="showcase">
			<h1 class="heading"> PHP JS CURD OOP | <span style="color: crimson;">signin</span> </h1>
			<p class="connection-error"> <?php echo $conn_error; ?></p>
		</div>

        <!--- Signin and Signup -->
		<div class="signin-signup-section">
			<p class="signin-msg"></p>
			<div class="forms-section">

                <!-- Signin -->
				<form class="signin">
					<fieldset>
						<legend>Sign in</legend>
						<input type="text" name="username" placeholder="Username" required> <br>
						<input type="password" name="password" placeholder="Password" required> <br>
                        <input type="text" class="varification_code" name="varification_code" placeholder="Varification code" value=0>
						<button type="submit" name="signin-submit"> Signin </button>
					</fieldset>
				</form>

                <!-- Signup -->
				<form class="signup" enctype="multipart/form-data">
					<fieldset>
						<legend>Sign up</legend>
						<input type="text" name="username" placeholder="Username" required> <br>
						<input type="email" name="email" placeholder="Email" required> <br>
						<input type="password" name="password" placeholder="Password" required> <br>
						<input type="password" name="re-password" placeholder="Re-enter password" required> <br>
						<button type="submit" name="signup-submit"> Signup </button>
					</fieldset>
				</form>
			</div>
		</div>
        
        <!-- Scripts -->
		<script type="text/javascript" src="javaScript/app.js"></script>
	</body>
</html>
