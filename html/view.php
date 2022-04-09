<?php
require_once('db.php');
require_once('functions.php');

/* 返信課題はここからのコードを修正しましょう。 */
$tw = getTweet($_GET['id']);
$tw = $tw[0];
//print_r($tw);
$us = getusername($tw['user_id']);
$us = $us[0];
/* 返信課題はここまでのコードを修正しましょう。 */
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
        <!-- 返信課題はここからのコードを修正しましょう。 -->
          <p><b><?= $tw['id'] ?></b> <small><?= $us['name'] ?> <?= $tw['updated_at'] ?></small></p>
          <p><?= $tw['text'] ?></p>
         <!--返信課題はここまでのコードを修正しましょう。 -->
      </div>
    </div>
    <br>
  </div>
</body>

</html>
