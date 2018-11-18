<?php
/* config.php
 * Configuration file in which can be used across
 * various PHP files to connect to Database.
 * Allows for simpler edits if Database details change.
 * Also includes a variable for Password Length for
 * any files that require a new password to be entered.
 */
 
/* ---------------------------------------------------------
 * NOTE:
 * Login and Signup work under the assumption that there
 * exists a table as follows:
 * CREATE TABLE users (
     id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
     username VARCHAR(x) NOT NULL UNIQUE,
     email VARCHAR(y) NOT NULL UNIQUE,
     password VARCHAR(z) NOT NULL,
     created_at DATETIME DEFAULT CURRENT_TIMESTAMP
 *);
 * where x, y, and z are max lengths of strings(?)
 * ---------------------------------------------------------
 */ 
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'QuizMatch');

$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
if($link === false)die("ERROR: Unable to connect. " . mysqli_connect_error());

$min_username_len = 6;

$min_pw_len = 5;

$traits[0] = "SANGUINE";
$traits[1] = "PHLEGMATIC";
$traits[2] = "CHOLERIC";
$traits[3] = "MELANCHOLIC";
$catagories = array(array("",""),array($traits[0], $traits[1]), array($traits[0], $traits[2]), array($traits[0], $traits[3]),
								 array($traits[1], $traits[0]), array($traits[1], $traits[2]), array($traits[1], $traits[3]),
								 array($traits[2], $traits[0]), array($traits[2], $traits[1]), array($traits[2], $traits[3]),
								 array($traits[3], $traits[0]), array($traits[3], $traits[1]), array($traits[3], $traits[2]));

?>
<!DOCTYPE html>
<html>
		<script>
			// Variables
			
			// Variables for managing Quiz Creation/Edit
			var min_Quiz_Name_Length = 3;
			var min_num_Ans = 2; // Minimum number of answers that a question must have
			var max_num_Ans = 5; // Maximum number of answers that a question may have
			
			// Functions
			
			// Functions for navigation
			function confirmLeave(msg, path){
				if(confirm(msg)){
					location.href = path;
				}
			}
			
			// Functions for managing Tab pages
			function showTab(n) {
			  // This function will display the specified tab of the form ...
			  var x = document.getElementsByClassName("tab");
			  x[n].style.display = "block";
			  // ... and fix the Previous/Next buttons:
			  if (n == 0) {
				document.getElementById("prevBtn").style.display = "none";
			  } else {
				document.getElementById("prevBtn").style.display = "inline";
			  }
			  if (n == (x.length - 1)) {
				if(document.getElementById("quizForm")!=null){
					document.getElementById("nextBtn").innerHTML = "Submit";
				}else{
					document.getElementById("nextBtn").style.display = "none";
				}
			  } else {
				document.getElementById("nextBtn").style.display = "inline";
				document.getElementById("nextBtn").innerHTML = "Next";
			  }
			  // ... and run a function that displays the correct step indicator:
			  fixStepIndicator(n)
			}
			
			function switch_to_Finished_Tab(n){
				var x = document.getElementsByClassName("tab");
				var y = document.getElementsByClassName("step");
				if(n >= currentTab || n < 0) return false;
				y[currentTab].className += " finish"
				y[currentTab].setAttribute("onclick", "switch_to_Finished_Tab("+currentTab+")");
				x[currentTab].style.display = "none";
				currentTab = n;
				y[currentTab].className = y[currentTab].className.replace(" finish", "");
				showTab(currentTab);
			}

			function nextPrev(n) {
			  // This function will figure out which tab to display
			  var x = document.getElementsByClassName("tab");
			  var y = document.getElementsByClassName("step");
			  // Exit the function if any field in the current tab is invalid:
			  if (n == 1 && !validateForm()) return false;
			  // Hide the current tab:
			  x[currentTab].style.display = "none";
			  // Increase or decrease the current tab by 1:
			  if(n == -1) y[currentTab].className += " finish";
			  currentTab = currentTab + n;
			  y[currentTab].className = y[currentTab].className.replace(" finish", "");
			  // if you have reached the end of the form... :
			  if (currentTab >= x.length && document.getElementByID("quizForm")!=null) {
				//...the form gets submitted:
				document.getElementById("quizForm").submit();
				return false;
			  }
			  // Otherwise, display the correct tab:
			  showTab(currentTab);
			}

			function validateForm() {
			  // This function deals with validation of the form fields
			  var x, y, i, valid = true;
			  x = document.getElementsByClassName("tab");
			  y = x[currentTab].getElementsByTagName("input");
			  // A loop that checks every input field in the current tab:
			  for (i = 0; i < y.length; i++) {
				// If a field is empty...
				if (y[i].value == "") {
				  // add an "invalid" class to the field:
				  y[i].className += " invalid";
				  // and set the current valid status to false:
				  valid = false;
				}
			  }
			  // If the valid status is true, mark the step as finished and valid:
			  if (valid) {
				document.getElementsByClassName("step")[currentTab].className += " finish";
				document.getElementsByClassName("step")[currentTab].setAttribute("onclick","switch_to_Finished_Tab("+currentTab+")");
			  }
			  return valid; // return the valid status
			}

			function fixStepIndicator(n) {
			  // This function removes the "active" class of all steps...
			  var i, x = document.getElementsByClassName("step");
			  for (i = 0; i < x.length; i++) {
				x[i].className = x[i].className.replace(" active", "");
			  }
			  //... and adds the "active" class to the current step:
			  x[n].className += " active";
			}
		</script>
</html>