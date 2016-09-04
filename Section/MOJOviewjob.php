<?php
	session_start();
	if(isset($_SESSION["section"])){
		$secCode = $_SESSION["section"];
	}
	else{
	 header("Location:index.php");
	}

	include_once '../config.php';

	function get_jt($j){
		$type = "Private Job";
		switch($j){
			case "BC": $type = "Bus Company"; break;
			case "VM": $type = "Vehicle Maintain"; break;
		}
		return $type;
	}

	if (!isset($_GET['id'])){
    	echo 'No ID was given...';
    	exit;
	}
	else{
		$jobNo = $_GET['id'];

		$sql1 = mysqli_query($conn,"SELECT * FROM jobservce WHERE job_no = '$jobNo'");
		$row1 = mysqli_fetch_array($sql1, MYSQL_ASSOC);
		$jt = get_jt($row1['job_typ']);
		$code = $row1['sec_code'];
		$cDate = $row1['closedDate'];
		$gp = $row1['gatePass'];

		$sql2 = mysqli_query($conn,"SELECT name FROM section WHERE code = '$code'");
		$row2 = mysqli_fetch_array($sql2, MYSQL_ASSOC);
		$sec = $row2['name'];
		$rDate = $row1['rDate'];
		$det = $row1['details'];

		$sql3 = mysqli_query($conn,"SELECT subJob_no FROM subjob WHERE job_no = '$jobNo'");
		$sj = mysqli_num_rows($sql3);

		$sql4 = mysqli_query($conn,"SELECT * FROM vehicle WHERE job_no = '$jobNo'");
		$row4 = mysqli_fetch_array($sql4, MYSQL_ASSOC);
		$vNo= $row4['v_no'];

		$sql5 = mysqli_query($conn,"SELECT * FROM customer WHERE v_no = '$vNo'");
		$row5 = mysqli_fetch_array($sql5, MYSQL_ASSOC);

		$sql6 = mysqli_query($conn,"SELECT gtpass_no FROM account WHERE job_no = '$jobNo'");
		$row6 = mysqli_fetch_array($sql6, MYSQL_ASSOC);
        
        $sql7 = mysqli_query($conn,"SELECT * FROM job WHERE job_no = '$jobNo'");
		$row7 = mysqli_fetch_array($sql7, MYSQL_ASSOC);
        
	}

?>

<!DOCTYPE html>

<html>
<head>

	<title>CGTTI JobInfo</title> 
	<link rel="stylesheet" type="text/css" href="../CSS/jobOffice.css">
	<!--<link rel="stylesheet" type="text/css" href="CSS/index.css">-->
	<link rel="stylesheet" type="text/css" href="../CSS/regForm.css">
	<meta name="viewport" content="width=device-width, initial-scale: 1.0, user-scaleable=no">
	<script>
		
	</script>
 
</head>

<body class="body">
	<?php include 'SHeader.php'; ?>
	<div class="pageArea">
		<div class="titleArea">
			<div class="theme"><h1>Job No. :- <span class="Number"><?php echo $jobNo; ?></span></h1></div>

		</div>
		<div class="form-area" style="background-color:#fff; height:auto;width:43.5%;margin-top:0%;">
			<div class="topBar" style="background-color:rgb(254,217,139);">Job Details</div>
			<dl>
			<div class="list">
				<dt><div class="form-fields1">Job Type</div><div class="form-dots2">:</div></dt>
				<dd><div class="form-inputs1"><?php echo $jt; ?></div></dd>
			</div><br>
			<div class="list">
				<dt><div class="form-fields1">Section</div><div class="form-dots2">:</div></dt>
				<dd><div class="form-inputs1"><?php echo $sec; ?></div></dd>
			</div><br>
			<div class="list">
				<dt><div class="form-fields1">Registered Date</div><div class="form-dots2">:</div></dt>
				<dd><div class="form-inputs1"><?php echo $rDate; ?></div></dd>
			</div><br>
			<div class="list">
				<dt><div class="form-fields1">Description</div><div class="form-dots2">:</div></dt>
				<dd><div class="form-inputs1"><?php echo $det; ?></div></dd>
			</div><br>
			<div class="list">
				<dt><div class="form-fields1">Sub Job No.</div><div class="form-dots2">:</div></dt>
				<dd><div class="form-inputs1">
				<?php echo "<ul>";
						if($sj == 0){
							echo "<li> - </li><br><br>";
						}
						else{
						while($sj>0){
						$row3 = mysqli_fetch_array($sql3, MYSQL_ASSOC);
						echo "<li>".$row3['subJob_no']."</li><br><br>";
                            $sj-=1;
						}}
                    
                    
					echo "</ul>";
					?>
                    
                    <br>
                    
                    
                    </div>
                
                </dd>
                
			</div><br>
			<div class="list">
                
				<dt><div class="form-fields1"><div id = "b1" class="round-button2" style='margin-top: -50%; float:right    ; margin-right:-70%;'>
				<div id = "g1" class="button-action1" style="margin-top:18%"><p>Send sub Job</p></div>
                    </dt>
				
			</div><br>
                
                
			
			</dl>
		</div>
		<div class="form-area" style="background-color:#fff; height:auto; width:38%;margin-top:0%;">
			<div class="topBar" style="background-color:rgb(254,217,139);">Update Job Details</div>
			<dl>
			<form action="MupdateJob.php" method="post">
                
                <div class="info">
				<div class="topics">
					<ul>
						
                        <li>Man Hours :</li><br><br>
                        <li>Machine Hours :</li><br><br>
                        <li>Used Material :</li><br><br>
                        <li>Quantity :</li><br><br>
                        <li>Unite Price :</li><br><br>
					</ul>
				</div>
                
				<div class="ans">
                    <?php 
                    $_SESSION["jNo"] = $jobNo;
                    $_SESSION["oldManHr"] = $row7['man_hrs'];
                    $_SESSION["oldMachHr"] = $row7['mach_hrs'];
                    
                ?>
                    <ul>
                        <li> <input style="width:50%;" type="text" id="manHour" name="manHour"/><?php echo " + ".$row7['man_hrs']." hour."; ?></li><br><br>
                        <li> <input style="width:50%;" type="text" id="machHour" name="machHour"  /><?php echo " + ".$row7['mach_hrs']." hour."; ?></li><br><br>
                        <li><input style="width:50%;" type="text" id="mat" name="mat"  /></li><br><br>
                        <li><input style="width:50%;" type="text" id="quan" name="quan"  /></li><br><br>
                        <li><input style="width:50%;" type="text" id="up" name="up"  /></li><br><br>
                        
                    </ul>
					<div class='butArea' style="margin-left:25%"><button class='btn-circle' id='submit' name='submit' value='Register'><b> Update Job Details </b></button></div>
				</div>
			</div>
              
                 
			 
              
            </form>
			</dl>
		</div>
		<!--<div id = "g1" class="button-action1"><p>Send Message to</p><p>the Job Section</p></div>
		<div id = "b1" class="round-button1">fafaf</div>-->
		<div id = "b2" class="round-button2">fafaf</div>
		<div id = "g2" class="button-action1" style="margin-top:18%"><p>Download Details</p></div><br>
		<script type="text/javascript">
			var event1 = document.getElementById('b1');
			var event2 = document.getElementById('b2');
			event1.onmouseover = function() {
			  document.getElementById('g1').style.display = 'block';
			}
			event1.onmouseout = function() {
			  document.getElementById('g1').style.display = 'none';
			}
			event2.onmouseover = function() {
			  document.getElementById('g2').style.display = 'block';
			}
			event2.onmouseout = function() {
			  document.getElementById('g2').style.display = 'none';
			}
	</script>
	</div>

	<!--Job message box-->
	<div id="jobmsg" class="msgWindow">
		<div class="msgBody">
			<span class="close">x</span>
			<br>
			<form action="MsendSubJob.php" method="post">
			<div class="info">
				<div class="topics" style="width:14%; padding-top:1%;">
					<ul>
                        <li>Job No :</li><br><br><br>       
						<li>From :</li><br><br><br>
                        
                        <li>To :</li><br><br><br>
						<li>Sub Job Description :</li>
					</ul>
				</div>
				<div class="ans" style="padding-left:3%; width:80%;">
					<ul>
                        <li><div class="toMsg"><?php echo $jobNo;?></div></li><br><br><br>
						<li><div class="toMsg"><?php echo $secCode." Section";?></div></li><br><br><br>
                        <div class="ans" style="padding-left:1%; padding-top:1%">
                        
                        <input type="text" id="to" name="to" /><br><br>
                        
						<input type="hidden" name="from" value="<?php echo $secCode;?>">
						</div>
						<textarea style="cursor:auto;" name="subJobDetails" cols="50" rows="5" placeholder="Type your message here"></textarea>
					</ul><br>
				</div>
			</div>
			<button id="submit" type="submit" name="submit" value="Register">Send Sub Job</button>
			</form>
			<?php
				//if(isset($_POST['submit'])){include 'sendMsg.php';}
			?>
		</div>
	</div>
	<!--Job message box-->

	<!--Script to visible msg box-->
	<script>
		var msgBox = document.getElementById('jobmsg');
		var btn1 = document.getElementById("b1");
		var btn2 = document.getElementById("submit");
		var span = document.getElementsByClassName("close")[0];

		btn1.onclick = function() {
			msgBox.style.display = "block";
		}
		btn2.onclick = function() {
			msgBox.style.display = "none";
		}

		// When the user clicks on <span> (x), close the modal
		span.onclick = function() {
			msgBox.style.display = "none";
		}
	</script>		

</body>
</html>
<?php 
//Started at 177
/*<div class="list">
				<dt><div class="form-fields1" style="width:35%;">Customer Name</div><div class="form-dots2">:</div></dt>
				<dd><div class="form-inputs1" style="width:60%;"><?php echo $row5['name']; ?></div></dd>
			</div><br>
			<div class="list">
				<dt><div class="form-fields1" style="width:35%;">Vehicle No.</div><div class="form-dots2">:</div></dt>
				<dd><div class="form-inputs1" style="width:60%;"><?php echo $vNo ?></div></dd>
			</div><br>
			<div class="list">
				<dt><div class="form-fields1" style="width:35%;">Contact No.</div><div class="form-dots2">:</div></dt>
				<dd><div class="form-inputs1" style="width:60%;"><?php echo $row5['contact_no'] ?></div></dd>
			</div><br>
			<div class="list">--------------------------------------------------------------------------------</div>
			<div class="list">
				<dt><div class="form-fields1" style="width:35%;padding-top:1%;">Email</div><div class="form-dots2" style="padding-top:1%;">:</div></dt>
				<dd><div class="form-inputs1" style="width:60%;padding-top:1%;"><?php echo $row5['email'] ?></div></dd>
			</div><br>
			<div class="list">
				<dt><div class="form-fields1" style="width:35%;">Address</div><div class="form-dots2">:</div></dt>
				<dd><div class="form-inputs1" style="width:60%;"><?php echo $row5['address'] ?></div></dd>
			</div><br>
			<div class="list">
				<dt><div class="form-fields1" style="width:35%;">Fuel Type</div><div class="form-dots2">:</div></dt>
				<dd><div class="form-inputs1" style="width:60%;"><?php echo $row4['fuel_typ'] ?></div></dd>
			</div><br>
			<div class="list">
				<dt><div class="form-fields1" style="width:35%;">Engine No.</div><div class="form-dots2">:</div></dt>
				<dd><div class="form-inputs1" style="width:60%;"><?php echo $row4['eng_no'] ?></div></dd>
			</div><br>
			<div class="list">
				<dt><div class="form-fields1" style="width:35%;padding-bottom:2%;">Chassis No.</div><div class="form-dots2">:</div></dt>
				<dd><div class="form-inputs1" style="width:60%;"><?php echo $row4['che_no'] ?></div></dd>
			</div><br> */?>