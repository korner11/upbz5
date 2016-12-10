<?php

$srch=$db->real_escape_string($_POST['search']);
$stmt = $db->stmt_init();
$sql = "SELECT id,title FROM articles WHERE title LIKE CONCAT('%',?,'%') OR content LIKE CONCAT('%',?,'%')";
$stmt = $db->prepare($sql);
if (  false === $stmt  ) {
 die('prepare() failed: ' . htmlspecialchars($db->error));
}
$rc = $stmt->bind_param("ss",$srch,$srch);
if ( false===$rc ) {
  // again execute() is useless if you can't bind the parameters. Bail out somehow.
  die('bind_param() failed: ' . htmlspecialchars($stmt->error));
}

$rc = $stmt->execute();
if ( false===$rc ) {
  // again execute() is useless if you can't bind the parameters. Bail out somehow.
  die('execute() failed: ' . htmlspecialchars($stmt->error));
}
?>
<h1> Výsledky vyhľadavania: <?=$_POST['search']?></h1>

<div>
    <?php
    //var_dump($stmt);
	$stmt->bind_result($id, $title);
	//var_dump($stmt);
    while($stmt->fetch()){
//	echo "Id: {$id}, title: {$title}";
    //while($data = $search->fetch()){
        echo 'Článok: <a href="./index.php?id={$id}">{$title}</a><br />';
    }
    ?>
</div>
