<?php
include 'csrf.class.php';
$csrf = new csrf();
// Generate Token Id and Valid
$token_id = $csrf->get_token_id();
$token_value = $csrf->get_token($token_id);
// Generate Random Form Names
$form_names = $csrf->form_names(array('nameMail', 'email','msg'), false);
//<input type="hidden" name="action" value="submit">


if(isset($_POST['sendMail'])) {
	if(isset($_POST[$form_names['nameMail']], $_POST[$form_names['email']],$_POST[$form_names['msg']])) {
        // Check if token id and token value are valid.
        if($csrf->check_valid('post')) {
                // Get the Form Variables.
                $name = $_POST[$form_names['nameMail']];
                $email = $_POST[$form_names['email']];
				 $msg = $_POST[$form_names['msg']];
				if (($name=="")||($email=="")||($msg==""))
					{
					echo "All fields are required, please fill <a href=\"\">the form</a> again.";
					}
				else{        
					$from="From: $name<$email>\r\nReturn-path: $email";
					$subject="Message sent using your contact form";
					mail("youremail@yoursite.com", $subject, $message, $from);
					echo "Email sent!";
					}
				}  
                // Form Function Goes Here
    }
        // Regenerate a new random value for the form.
        $form_names = $csrf->form_names(array('user', 'password'), true);
}
?>
    <form   method="POST" enctype="multipart/form-data">
    Your name:<br>
    <input name="<?= $form_names['nameMail']; ?>"type="text" value="" size="30"/><br>
    Your email:<br>
    <input name="<?= $form_names['email']; ?>" type="text" value="" size="30"/><br>
    Your message:<br>
    <textarea name="<?= $form_names['msg']; ?>"rows="7" cols="30"></textarea><br>
	<input type="hidden" name="<?= $token_id; ?>" value="<?= $token_value; ?>" />
    <input type="submit" name="sendMail" value="Send email"/>
    </form>

