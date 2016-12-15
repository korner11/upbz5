<?php
include 'csrf.class.php';
 
$csrf = new csrf();
	
	// Generate Token Id and Valid
	$token_id = $csrf->get_token_id();
	$token_value = $csrf->get_token($token_id);
 
	// Generate Random Form Names
	$form_names = $csrf->form_names(array('user', 'password'), false);

function verify_login($name,$pass){	
				
			global $db;
			$name=trim($db->real_escape_string($name));
			$pass=hash("sha512",trim($db->real_escape_string($pass)));
			$stmt = $db->stmt_init();
			$sql = "SELECT id,name,password FROM admins WHERE name=? AND password=? LIMIT 1";
			$stmt = $db->prepare($sql);
			if (  false === $stmt  ) {
			 die('prepare() failed: ' . htmlspecialchars($db->error));
			}
			$rc = $stmt->bind_param("ss",$name,$pass);
			if ( false===$rc ) {
			  // again execute() is useless if you can't bind the parameters. Bail out somehow.
			  die('bind_param() failed: ' . htmlspecialchars($stmt->error));
			}
			$rc = $stmt->execute();
			if ( false===$rc ) {
			  // again execute() is useless if you can't bind the parameters. Bail out somehow.
			  die('execute() failed: ' . htmlspecialchars($stmt->error));
			}
			$data = $stmt->get_result()->fetch_object();
			

			if(!empty($data)){
				$_SESSION['id']  = $data->id;
				$_SESSION['name'] = $data->name;
				$_SESSION['session_id'] = session_id();
				return true;
			}else{
				return false;
			}
       
}

if(@$_POST['logIN']){
	if(isset($_POST[$form_names['user']], $_POST[$form_names['password']])) {
		
        // Check if token id and token value are valid.
        if($csrf->check_valid('post')) {
                // Get the Form Variables.
                $user = $_POST[$form_names['user']];
                 $password = $_POST[$form_names['password']];
				 if(verify_login($user,$password)) {
					header('LOCATION: index.php');
				}else{
						$error = "Wrong name or password!! Pls try it again!!";
				}
		}
        // Regenerate a new random value for the form.
        $form_names = $csrf->form_names(array('user', 'password'), true);
	}
   
}
?>
<?if(!isLogin()){?>
<div style="width:20%;">
    <?=@$error?>
    <form method="post" name="login">
        <label>Meno</label>
        <input name="<?= $form_names['user']; ?>"  type="text" placeholder="LamaCoder" autofocus />
        <label>Heslo</label>
        <input name="<?= $form_names['password']; ?>"  type="password" placeholder="********" />
		<input type="hidden" name="<?= $token_id; ?>" value="<?= $token_value; ?>" />
        <br />
        <button class="button" name="logIN" value="1">Prihlasiť</button>
    </form>
</div>
<?}else{?>
    <div style="width:20%;">
        <?=@$error?>
        <a href="./?page=logout.php"><button class="button">Odhlásiť sa</button></a>
    </div>
<?}?>
