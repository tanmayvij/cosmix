<?php 
include '../config.php';
page_protect();
$link = mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);
if(!checkAdmin()) {
header("Location: /user_dashboard.php");
exit();
}

$page_limit = 10; 


$host  = $_SERVER['HTTP_HOST'];
$host_upper = strtoupper($host);
$login_path = preg_replace('admin','',dirname($_SERVER['PHP_SELF']));
$path   = rtrim($login_path, '/\\');

foreach($_GET as $key => $value) {
	$get[$key] = filter($value);
}

foreach($_POST as $key => $value) {
	$post[$key] = filter($value);
}

if(isset($post['doBan']) == 'Ban') {

if(!empty($_POST['u'])) {
	foreach ($_POST['u'] as $uid) {
		$id = filter($uid);
		mysqli_query($link,"update users set banned='1' where id='$id' and `user_name` <> 'admin'");
	}
 }
 $ret = $_SERVER['PHP_SELF'] . '?'.$_POST['query_str'];;
 
 header("Location: $ret");
 exit();
}

if(isset($_POST['doUnban']) == 'Unban') {

if(!empty($_POST['u'])) {
	foreach ($_POST['u'] as $uid) {
		$id = filter($uid);
		mysqli_query($link,"update users set banned='0' where id='$id'");
	}
 }
 $ret = $_SERVER['PHP_SELF'] . '?'.$_POST['query_str'];;
 
 header("Location: $ret");
 exit();
}

if(isset($_POST['doDelete']) == 'Delete') {

if(!empty($_POST['u'])) {
	foreach ($_POST['u'] as $uid) {
		$id = filter($uid);
		mysqli_query($link,"delete from users where id='$id' and `user_name` <> 'admin'");
	}
 }
 $ret = $_SERVER['PHP_SELF'] . '?'.$_POST['query_str'];;
 
 header("Location: $ret");
 exit();
}

if(isset($_POST['doApprove']) == 'Approve') {

if(!empty($_POST['u'])) {
	foreach ($_POST['u'] as $uid) {
		$id = filter($uid);
		mysqli_query($link,"update users set approved='1' where id='$id'");
	}
 }
 
 $ret = $_SERVER['PHP_SELF'] . '?'.$_POST['query_str'];	 
 header("Location: $ret");
 exit();
}

$rs_all = mysqli_query($link,"select count(*) as total_all from users") or die(mysqli_error($link));
$rs_active = mysqli_query($link,"select count(*) as total_active from users where approved='1'") or die(mysqli_error($link));
$rs_total_pending = mysqli_query($link,"select count(*) as tot from users where approved='0'");						   

list($total_pending) = mysqli_fetch_row($rs_total_pending);
list($all) = mysqli_fetch_row($rs_all);
list($active) = mysqli_fetch_row($rs_active);


?>
<!DOCTYPE html>
<html>
	<head>
		<link rel='stylesheet' href='/static/css/module.css'>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	</head>
	<body><h2>Manage Users</h2>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>

    <td width="74%" valign="top" style="padding: 10px;">
      <table width="100%" border="0" cellpadding="5" cellspacing="0" class="myaccount">
        <tr>
          <td>Total users: <?php echo $all;?></td>
          <td>Active users: <?php echo $active; ?></td>
          <td>Pending users: <?php echo $total_pending; ?></td>
        </tr>
      </table>
      <p><?php 
	  if(!empty($msg)) {
	  echo $msg[0];
	  }
	  ?></p>
	  <div align="center">
		<div style="border: 1px solid #000; border-radius:20px; width: 60%;">
		  <table>
			<tr>
			  <td><form name="form1" method="get" action="">
				  <p align="center">
					<input name="q" placeholder="Search username or email..." type="text" id="q" size="40">
					<br>
					</p>
				 
				  <p align="center"> 
					<input name="doSearch" type="submit" id="doSearch2" value="Search">
				  </p>
				  </form></td>
			</tr>
		  </table>
		 </div>
		</div>
      <p>
        <?php if (isset($get['doSearch']) == 'Search') {
	 
	  $sql = "select * from users where `user_email` = '$_REQUEST[q]' or `user_name`='$_REQUEST[q]' ";

	  $rs_total = mysqli_query($link,$sql) or die(mysqli_error($link));
	  $total = mysqli_num_rows($rs_total);
	  
	  if (!isset($_GET['page']) )
		{ $start=0; } else
		{ $start = ($_GET['page'] - 1) * $page_limit; }
	  
	  $rs_results = mysqli_query($link,$sql . " limit $start,$page_limit") or die(mysqli_error($link));
	  $total_pages = ceil($total/$page_limit);
	  
	  ?>
     
   
      <p align="right"> 
        <?php 
	  
		if ($total > $page_limit)
		{
		echo "<div><strong>Pages:</strong> ";
		$i = 0;
		while ($i < $page_limit)
		{
		
		
		$page_no = $i+1;
		$qstr = preg_replace("&page=[0-9]+","",$_SERVER['QUERY_STRING']);
		echo "<a href=\"?$qstr&page=$page_no\">$page_no</a> ";
		$i++;
		}
		echo "</div>";
		}  ?>
		</p>
		<form name "searchform" action="" method="post">
        <table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">
          <tr bgcolor="#FF80AA"> 
            <td width="4%"><strong>ID</strong></td>
            <td> <strong>Date</strong></td>
            <td><div align="center"><strong>User Name</strong></div></td>
            <td width="24%"><strong>Email</strong></td>
            <td width="10%"><strong>Approval</strong></td>
            <td width="10%"> <strong>Banned</strong></td>
            <td width="25%">&nbsp;</td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td width="10%">&nbsp;</td>
            <td width="17%"><div align="center"></div></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <?php while ($rrows = mysqli_fetch_array($rs_results)) {?>
          <tr> 
            <td><input name="u[]" type="checkbox" value="<?php echo $rrows['id']; ?>" id="u[]"></td>
            <td><?php echo $rrows['date']; ?></td>
            <td> <div align="center"><?php echo $rrows['user_name'];?></div></td>
            <td><?php echo $rrows['user_email']; ?></td>
            <td> <span id="approve<?php echo $rrows['id']; ?>"> 
              <?php if(!$rrows['approved']) { echo "Pending"; } else {echo "Active"; }?>
              </span> </td>
            <td><span id="ban<?php echo $rrows['id']; ?>"> 
              <?php if(!$rrows['banned']) { echo "no"; } else {echo "yes"; }?>
              </span> </td>
            <td> <font size="2"><a href="javascript:void(0);" onclick='$.get("user_control_scripts.php",{ cmd: "approve", id: "<?php echo $rrows['id']; ?>" } ,function(data){ $("#approve<?php echo $rrows['id']; ?>").html(data); });'>Approve</a> 
              <a href="javascript:void(0);" onclick='$.get("user_control_scripts.php",{ cmd: "ban", id: "<?php echo $rrows['id']; ?>" } ,function(data){ $("#ban<?php echo $rrows['id']; ?>").html(data); });'>Ban</a> 
              <a href="javascript:void(0);" onclick='$.get("user_control_scripts.php",{ cmd: "unban", id: "<?php echo $rrows['id']; ?>" } ,function(data){ $("#ban<?php echo $rrows['id']; ?>").html(data); });'>Unban</a> 
              <a href="javascript:void(0);" onclick='$("#edit<?php echo $rrows['id'];?>").show("slow");'>Edit</a> 
              </font> </td>
          </tr>
          <tr> 
            <td colspan="7">
			
			<div style="display:none;font: normal 11px arial; padding:10px; background: #FFFFFF" id="edit<?php echo $rrows['id']; ?>">
			<input type="hidden" name="id<?php echo $rrows['id']; ?>" id="id<?php echo $rrows['id']; ?>" value="<?php echo $rrows['id']; ?>">
			User ID:<br>
			<input name="user_name<?php echo $rrows['id']; ?>" id="user_name<?php echo $rrows['id']; ?>" type="text" size="10" value="<?php echo $rrows['user_name']; ?>" ><br>
			Email Address:<br>
			<input id="user_email<?php echo $rrows['id']; ?>" name="user_email<?php echo $rrows['id']; ?>" type="text" size="20" value="<?php echo $rrows['user_email']; ?>" ><br>
			Level:<br>
			<input id="user_level<?php echo $rrows['id']; ?>" name="user_level<?php echo $rrows['id']; ?>" type="text" size="5" value="<?php echo $rrows['user_level']; ?>" ><br>
			Password:<br>
			<input id="pass<?php echo $rrows['id']; ?>" name="pass<?php echo $rrows['id']; ?>" type="password" size="20" value="" placeholder="Leave blank for default" >
			<br><input name="doSave" type="button" id="doSave" value="Save" 
			onclick='$.get("user_control_scripts.php",{ cmd: "edit", pass:$("input#pass<?php echo $rrows['id']; ?>").val(),user_level:$("input#user_level<?php echo $rrows['id']; ?>").val(),user_email:$("input#user_email<?php echo $rrows['id']; ?>").val(),user_name: $("input#user_name<?php echo $rrows['id']; ?>").val(),id: $("input#id<?php echo $rrows['id']; ?>").val() } ,function(data){ $("#msg<?php echo $rrows['id']; ?>").html(data); });'> 
			<a  onclick='$("#edit<?php echo $rrows['id'];?>").hide();' href="javascript:void(0);">close</a>
		  <div style="color:red" id="msg<?php echo $rrows['id']; ?>" name="msg<?php echo $rrows['id']; ?>"></div>
		  </div>
		  
		  </td>
          </tr>
          <?php } ?>
        </table>
	    <p><br>
          <input name="doApprove" type="submit" id="doApprove" value="Approve">
          <input name="doBan" type="submit" id="doBan" value="Ban">
          <input name="doUnban" type="submit" id="doUnban" value="Unban">
          <input name="doDelete" type="submit" id="doDelete" value="Delete">
          <input name="query_str" type="hidden" id="query_str" value="<?php echo $_SERVER['QUERY_STRING']; ?>">
          </p>
        
      </form>
	  
	  <?php } ?>
      &nbsp;</p>
	  <?php
	  if(isset($_POST['doSubmit']) == 'Create')
{
$rs_dup = mysqli_query($link,"select count(*) as total from users where user_name='$post[user_name]' OR user_email='$post[user_email]'") or die(mysqli_error($link));
list($dups) = mysqli_fetch_row($rs_dup);

if($dups > 0) {
	die("The user name or email already exists in the system");
	}


  $pwd = $post['pwd'];	
  $hash = PwdHash($post['pwd']); 
 
mysqli_query($link,"INSERT INTO users (`full_name`, `user_name`,`user_email`,`pwd`,`approved`,`date`,`user_level`)
			 VALUES ('$post[full_name]','$post[user_name]','$post[user_email]','$hash','1',now(),'$post[user_level]')
			 ") or die(mysqli_error($link)); 




echo "<div class=\"msg\">User registered successfully.</div>"; 
}

	  ?>
	  
      <h2>Create New User</h2>
	  <div align="center">	
		<form name="form1" method="post" action="" class="profile_form">
              
			  <input name="full_name" type="text" id="user_name" placeholder="Name" required><br>
              <input name="user_name" type="text" id="user_name" placeholder="User ID" required><br>
              <input name="user_email" type="text" id="user_email" placeholder="Email" required><br>
			  <input name="pwd" type="password" id="pwd" placeholder="Password" required><br> 
             <span style="font-size: 20px;">User Level:</span>&nbsp;<select name="user_level" id="user_level" required>
                  <option value="1">User</option>
                  <option value="5">Admin</option>
                </select><br>
				             
                <input name="doSubmit" type="submit" id="doSubmit" value="Create">
            
            </form>
     </div>
        
      
  </tr>
</table>
</body>
</html>
