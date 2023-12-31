<?php
session_start();

require_once('db_connect.php');

if (isset($_POST['submit'])) {
	if (isset($_POST['email'], $_POST['password']) && !empty($_POST['email']) && !empty($_POST['password'])) {
		$email = trim($_POST['email']);
		$password = trim($_POST['password']);

		if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$sql = "select * from medewerker where email = :email ";
			$handle = $pdo->prepare($sql);
			$params = ['email' => $email];
			$handle->execute($params);
			if ($handle->rowCount() > 0) {
				$getRow = $handle->fetch(PDO::FETCH_ASSOC);
				if (password_verify($password, $getRow['password'])) {
					unset($getRow['password']);
					$_SESSION = $getRow;
					header('location:admin.php');
					exit();
				} else {
					$errors[] = "Wrong Email or Password";
				}
			} else {
				$errors[] = "Wrong Email or Password";
			}
		} else {
			$errors[] = "Email address is not valid";	
		}
	} else {
		$errors[] = "Email and Password are required";	
	}
}
?>

<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="style.css">

<head>
    <title>Medewerkers</title>
</head>

<body>
    <header>
        <div class="banner">
        <div class="navbar">
            <a href="homepage.php"><img src="images/logo.png" class="logo"></a>
            <ul>
                <li><a href="homepage.php">Home</a></li>
                <li><a href="shoppagina.php">Bestel Nu</a></li>
                <li><a href="informatie.php">Informatie</a></li>
                <li><a href="loginsystem/login.php">Login</a></li>
            </ul>
        </div>
    </header>
    <main>
<div class="container-login">
	<div class="row h-100 mt-5 justify-content-center align-items-center">
		<div class="col-md-5 mt-5 pt-2 pb-5 align-self-center border bg-light">
			<h1 class="mx-auto w-25" >Medewerkers</h1>
			<?php 
            if (isset($errors) && count($errors) > 0) {
                foreach ($errors as $error_msg) {
                    echo '<div class="error">' . $error_msg . '</div>';
                }
            }
			?>
			<form method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>">
				<div class="form-group">
					<label for="email">Email:</label>
					<input type="text" name="email" placeholder="Enter Email" class="form-control">
				</div>
				<div class="form-group">
				<label for="email">Password:</label>
					<input type="password" name="password" placeholder="Enter Password" class="form-control">
				</div>

				<button type="submit" name="submit" class="login-button">Submit</button>
			</form>
		</div>
	</div>
</div>

        <div class="bottom-container">
            <div class="row">
                <div class="col">
                    <a href="../homepage.html" style="color:white" class="btn">Terug naar homepage</a>
                </div>
                <div class="col">
                    <a href="#" style="color:white" class="btn">Wachtwoord vergeten?</a>
                </div>
            </div>
        </div>

    </div>
    </main>
    <?php include './components/footer.php'; ?>
</body>

</html>
