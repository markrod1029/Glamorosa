<?php include_once('includes/header.php'); ?>

<body class="cbp-spmenu-push">
	<div class="main-content">

		<!-- main content start-->
		<div style="background-color: #F1F1F1; height:800px;">
			<div class="main-page login-page ">
				<h3 class="title1">SignIn Page</h3>
				<div class="widget-shadow">
					<div class="login-top">
						<h4>Welcome back to BPMS AdminPanel ! </h4>
					</div>
					<div class="login-body">
						<form role="form" method="post" action="">
							<p style="font-size:16px; color:red" align="center"> <?php //if($msg){ echo $msg; }  
																					?> </p>
							<input type="text" class="user" name="username" placeholder="Username" required="true">
							<input type="password" name="password" class="lock" placeholder="Password" required="true">
							<input type="submit" name="login" value="Sign In">
							<div class="forgot-grid">

								<div class="forgot">
									<a href="../index.php">Back to Home</a>
								</div>
								<div class="clearfix"> </div>
							</div>
							<div class="forgot-grid">

								<div class="forgot">
									<a href="forgot-password.php">forgot password?</a>
								</div>
								<div class="clearfix"> </div>
							</div>
						</form>
					</div>
				</div>


			</div>
		</div>

	</div>
	<!-- Classie -->

	<?php include_once('includes/footer.php'); ?>