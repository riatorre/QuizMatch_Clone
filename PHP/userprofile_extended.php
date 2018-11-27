<?php
/* userprofile_extended.php
 * If they are not signed in, they are redirected to the login page.
 * This prevents the user from using the back button of their browser
 * to return here after they had already signed out.
 */
session_start();
if(!isset($_SESSION["loggedin"])||$_SESSION["loggedin"] !== true){
	header("location: login.php");
	exit;
}
require_once "quiz_DB_access_functions.php";
if(!isset($profile)){
	$sql = "SELECT * FROM users WHERE id =".$_SESSION["id"];
	$profile_entry = mysqli_query($link, $sql);
	$profile = mysqli_fetch_assoc($profile_entry);
}
$current_username = $profile["username"];
$current_email = $profile["email"];
$current_desc = $profile["bio"];
$current_gender = $profile["gender"];

$quizzes_created = getMyQuizzes($link);

$transfer_quizzes_created = json_encode($quizzes_created);
?>
<!-- 
This is the homepage for QuizMatch. It contains links to the login page and
the sign up page.
Hovering over the card QuizMatch will produce one of many random anecdotes.
-->

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<title>QuizMatch: Profile Extended</title>
		<link href= "stupid.css" type = "text/css" rel = "stylesheet"/>
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<style>
			body, html
			{
				height:100%;
				background-color: #f2f2f2;
			}
			div.contentRoundBorders
			{
				border-radius:15px;
				padding:1%;
				background:white;
				margin-bottom:1%;
				margin-top:1%;
				box-shadow: 0 0 3px rgba(0,0,0,0.5);
			}
			div.buttonSide
			{
				display:flex;
				justify-content: space-between;
				padding: 0% 3% 0% 5%;
			}
			div.editProfileRight
			{
				display:flex;
				justify-content: flex-end;
				padding-right: 3%;
			}
			div.profileBlock
			{
				text-align: center;
				margin:4%;
			}
			#avatar
			{
				background-image: url('images/default-user2.png');
				width: 300px;
				height: 300px;
				background-size: cover;
				background-position: center;
			}
		</style>
		
	</head>
	<body>
		<div class = "content">
			<div class = "container">
				<div class = "fill">
					<div class = "buttonSide">
					<a href="userprofile.php" class="btn large pink rounded"><tt>Home&#x1F3E0;</tt></a>
					<a href="quiz_home.php" class="btn large pink rounded"><tt>Quizzes!&#10004;</tt></a>
					</div>
				</div>
			</div>
			
			<div class = "container center">
				<div class = "quarter white rounded" style = "min-width:325px">
					<div class = "profileBlock">
						<div id="avatar"></div>
						<h3>
							<b><?php echo htmlspecialchars($current_username); ?></b><br>
							<b><?php echo htmlspecialchars($current_email); ?></b><br>
							<b><?php echo htmlspecialchars($current_gender); ?></b>
						</h3>
					</div>
				</div>
				<div class = "twothirds" style = "margin-left:1%;">
					<div class = "contentRoundBorders">
						<h6>
							Bio:
						</h6>
						<p>
							<?php echo htmlspecialchars($current_desc); ?>
						</p>
					</div>
					
					<div class = "contentRoundBorders">
						<h6>
							Quizzes Created:
							<div class = "contentRoundBorders">
								<span id="my_quizzes"></span>
							</div>
						</h6>
					</div>
					
					<div class = "contentRoundBorders">
						<h6>
							Quizzes Taken:
							<div class = "contentRoundBorders">
								<p>No Quizzes Have Been Taken Yet! And I Don't Work Yet Either!</p>
							</div>
						</h6>
					</div>
				</div>
			</div>
			<div class = "editProfileRight">
				<a href="edit_profile.php" class="btn large pink rounded"><tt>Edit Profile&#9998;</tt></a> 	
			</div>
		</div>
		<script src="config.js"></script>
		<script>
			var my_quizzes = [];
			my_quizzes = <?= $transfer_quizzes_created?>;
			createQuizzesList(my_quizzes, "my_quizzes");
			
			function createQuizzesList(quizzes, destination){
				var num_quizzes = quizzes.length;
				var list = document.createElement("DIV");
				if(num_quizzes != 0){
					for(var i = 0; i<num_quizzes; i++){
						list.appendChild(createQuizBtn(quizzes, i));
					}
				}else{
					list.innerHTML = "<p>You have not created any quizzes yet.</p>";
				}
				document.getElementById(destination).appendChild(list);
			}
		</script>
	</body>
</html>