<?php
function verify_login(){
    global $db;
	$name=trim($db->real_escape_string($_POST['name']));
	$pass=hash("sha512",trim($db->real_escape_string($_POST['pass'])));
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

    //$data = $sql->fetch_array();
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

//echo hash("sha512","student");

if(@$_POST['logIN']){
    if(verify_login()) {
        header('LOCATION: index.php');
    }else{
        $error = "Wrong name or password!! Pls try it again!!";
    }
}
?>
<?if(!isLogin()){?>
<div style="width:20%;">
    <?=@$error?>
    <form method="post" name="login">
        <label>Meno</label>
        <input name="name" value="" type="text" placeholder="LamaCoder" autofocus />
        <label>Heslo</label>
        <input name="pass" value="" type="password" placeholder="********" />
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
