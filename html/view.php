<?php
require_once('db.php');
require_once('functions.php');

$tw = getTweet($_GET['id']);
$tw = $tw[0];
$us = getUserName($tw['user_id']);
$us = $us[0];
?>

<!DOCTYPE html>
<html lang="ja">

<?php require_once('head.php'); ?>

<body>
  <div class="container">
    <h1 class="my-5">投稿表示</h1>
    
    <p><a href="index.php">&lt;&lt; 掲示板に戻る</a></p>
    
    <div class="card mb-3">
      <div class="card-body">
          <p><b><?= $tw['id'] ?></b> <small><?= $us['name'] ?> <?= $tw['updated_at'] ?></small></p>
          <p><?= $tw['text'] ?></p>
      </div>
    </div>
    <br>
  </div>
</body>

</html>
