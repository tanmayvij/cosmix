<?php 

include '../config.php';
page_protect();
$link = mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);
$err = array();
$msg = array();

if(isset($_POST['doUpdate']) == 'Update')  
{


$rs_pwd = mysqli_query($link,"select pwd from users where id='$_SESSION[user_id]'");
list($old) = mysqli_fetch_row($rs_pwd);
$old_salt = substr($old,0,9);

	if($old === PwdHash($_POST['pwd_old'],$old_salt))
	{
	$newsha1 = PwdHash($_POST['pwd_new']);
	mysqli_query($link,"update users set pwd='$newsha1' where id='$_SESSION[user_id]'");
	$msg[] = "Password successfully updated.";
	} else
	{
	 $err[] = "Your old password is invalid";
	}

}

if(isset($_POST['doSave']) == 'Save')  
{
foreach($_POST as $key => $value) {
	$data[$key] = filter($value);
}


mysqli_query($link,"UPDATE users SET
			`full_name` = '$data[name]',
			`address` = '$data[address]',
			`tel` = '$data[tel]',
			`fax` = '$data[fax]',
			`country` = '$data[country]',
			`website` = '$data[web]'
			 WHERE id='$_SESSION[user_id]'
			") or die(mysqli_error($link));

$msg[] = "Profile successfully saved";
 }
 
$rs_settings = mysqli_query($link,"select * from users where id='$_SESSION[user_id]'"); 
?>
<!DOCTYPE html>
<html>
	<head>
		<link rel='stylesheet' href='/static/css/module.css'>
	</head>
	<body>
	<h2>My Profile</h2>

        <?php	
	if(!empty($err))  {
	   echo "<div class=\"msg\">";
	  foreach ($err as $e) {
	    echo "* Error - $e <br>";
	    }
	  echo "</div>";	
	   }
	   if(!empty($msg))  {
	    echo "<div class=\"msg\">" . $msg[0] . "</div>";

	   }
	  ?>
     
	  <?php while ($row_settings = mysqli_fetch_array($rs_settings)) {?>
      <form action="" method="post" name="myform" class="profile_form">
        <table width="90%" border="0" align="center" cellpadding="3" cellspacing="3" class="forms">
          <tr> 
            <td width="27%">Your Name</td>
			<td width="73%"><input name="name" type="text" id="name"  class="required" value="<? echo $row_settings['full_name']; ?>" size="50"></td>
          </tr>
          <tr> 
            <td width="27%">Address</td> 
              <td width="73%">
			 <input name="address" class="required" id="address" value="<? echo $row_settings['address']; ?>" type="text">
            </td>
          </tr>
          <tr> 
            <td>Country</td>
            <td><input name="country" type="text" id="country" value="<? echo $row_settings['country']; ?>" ></td>
          </tr>
          <tr> 
            <td width="27%">Phone</td>
            <td width="73%"><input name="tel" type="text" id="tel" class="required" value="<? echo $row_settings['tel']; ?>"></td>
          </tr>
          <tr> 
            <td>Fax</td>
            <td><input name="fax" type="text" id="fax" value="<? echo $row_settings['fax']; ?>"></td>
          </tr>
          <tr> 
            <td>Website</td>
            <td><input name="web" type="text" id="web" class="optional defaultInvalid url" value="<? echo $row_settings['website']; ?>"></td>
          </tr>
          <tr> 
            <td>User Name</td>
            <td><input name="user_name" type="text" id="web2" value="<? echo $row_settings['user_name']; ?>" disabled></td>
          </tr>
          <tr> 
            <td>Email</td>
            <td><input name="user_email" type="text" id="web3"  value="<? echo $row_settings['user_email']; ?>" disabled></td>
          </tr>
        </table>
        <p align="center"> 
          <input name="doSave" type="submit" id="doSave" value="Save">
        </p>
      </form>
	  <?php } ?>
      <h3>Change Password</h3>
      <p>Please confirm your old password and choose a new one.</p>
      <form name="pform" class="profile_form" method="post" action="">
        <table>
          <tr> 
            <td width="27%">Old Password</td>
            <td width="73%"><input name="pwd_old" type="password" class="required password"  id="pwd_old"></td>
          </tr>
          <tr> 
            <td width="27%">New Password</td>
            <td width="73%"><input name="pwd_new" type="password" id="pwd_new" class="required password"  ></td>
          </tr>
        </table>
        <p align="center"> 
          <input name="doUpdate" type="submit" id="doUpdate" value="Update">
        </p>
      </form>
	   
  </tr>
</table>
<br><br>
</body>
</html>
