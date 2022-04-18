<?php

session_start();

//ログインしていない場合、login.phpを表示
if (empty($_SESSION['user_id'])) 
{
  header('Location: login.php');
  exit;
}

require_once('db.php');
require_once('functions.php');

/**
 * @param String $tweet_textarea
 * つぶやき投稿を行う。
 */
function newTweet($tweet_textarea)
{
  // 汎用ログインチェック処理をルータに作る。早期リターンで
  createTweet($tweet_textarea, $_SESSION['user_id']);
}

function newTweetReply($tweet_textarea)
{
  createTweetReply($tweet_textarea, $_SESSION['user_id'], $_POST['reply_id']);
}
/**
 * ログアウト処理を行う。
 */
function logout()
{
  $_SESSION = [];
  $msg = 'ログアウトしました。';
}


if ($_POST) 
{ /* POST Requests */
    if (isset($_POST['logout'])) 
    { //ログアウト処理
      logout();
      header("Location: login.php");
    } 
    else if (isset($_POST['tweet_textarea'])& isset($_POST['reply_id'])) 
    { //reply投稿処理
      newTweetReply($_POST['tweet_textarea']);
      header("Location: index.php");
    } 
    else if (isset($_POST['tweet_textarea'])) 
    { //投稿処理
      newTweet($_POST['tweet_textarea']);
      header("Location: index.php");
    }
}

$tweets = getTweets();
$tweet_count = count($tweets);
if(isset($_GET['n']))
{
  $n = $_GET['n'];
}
if(isset($_GET['notfavorite']))
{
  deleteFavorite($_SESSION['user_id'], $_GET['notfavorite']);
  header("Location: index.php");
}
if(isset($_GET['favorite']))
{ 
  createFavorite($_SESSION['user_id'], $_GET['favorite']);
  header("Location: index.php");
}
if(isset($_GET['notretweet']))
{ 
  deleteRetweet($_SESSION['user_id'], $_GET['notretweet']);
  header("Location: index.php");
}
if(isset($_GET['retweet']))
{ 
  createRetweet(NULL, $_SESSION['user_id'], $_GET['retweet']);
  header("Location: index.php");
}


?>

<!DOCTYPE html>
<html lang="ja">

<?php require_once('head.php'); ?>

<body>
  <div class="container">
    <h1 class="my-5">新規投稿</h1>
    <div class="card mb-3">
      <div class="card-body">
        <form method="POST">
          <textarea class="form-control" type=textarea name="tweet_textarea" ?><?php if(isset($n)){echo ' '.$n.' ';} ?></textarea>
          <input type=hidden name="reply_id" value="<?php ini_set('display_errors', 0); echo $_GET['reply']; ?>">
          <br>
          <input class="btn btn-primary" type=submit value="投稿">
        </form>
      </div>
    </div>
    <h1 class="my-5">コメント一覧</h1>
    <?php 
    foreach ($tweets as $t)
    {
      $retweet = 0;
      $t_name = $t['name'];
      if($t['retweet_post_id'] !== NULL)
      {
        $t = getRetweet($t['user_id'], $t['retweet_post_id']);
        $t = $t[0];
        $retweet = 1;
      }
      if($t['text'] !== NULL)
      { ?>
        <div class="card mb-3">
          <div class="card-body">
            <?php
            if($retweet === 1)
            {
              $us = getUserName($t['user_id']);
              $us = $us[0];
              echo '<p>'.$us['name'].'さんがリツイートしました。</p>';
            } ?>
            <p class="card-title"><b><?= "{$t['id']}" ?></b> <?= $t_name ?> <small><?= "{$t['updated_at']}" ?></small></p>
            <p class="card-text"><?= "{$t['text']}" ?></p>
            <a href="index.php?reply=<?= "{$t['id']}" ?>&n=Re: @<?= $t_name ?>">[返信する]</a>
              <?php
              if($t['reply_id'] !== 0)
              { ?>
                <a href="view.php?id=<?= "{$t['reply_id']}" ?>">[返信元のメッセージ]</a>
              <?php
              } ?>
              <p><br>
              <?php
              if(getFavorite($_SESSION['user_id'], $t['id']))
              { ?>
                <a id=<?= "{$t['id']}" ?> href="index.php?notfavorite=<?= "{$t['id']}" ?>#<?= "{$t['id']}" ?>">
                <img class="favorite-image" src='/images/heart-solid-red.svg'></a>
              <?php
              }
              else 
              { ?>
                <a id=<?= "{$t['id']}" ?> href="index.php?favorite=<?= "{$t['id']}" ?>#<?= "{$t['id']}" ?>">  
                <img class="favorite-image" src='/images/heart-solid-gray.svg'></a>
              <?php
              }
              $sum = getFavoritCount($t['id']);
              if($sum[0][0] !== 0)
              {
                echo $sum[0][0];
              }
              if(getMyRetweet($_SESSION['user_id'], $t['id']))
              { ?>
                &emsp;
                <a href="index.php?notretweet=<?= "{$t['id']}" ?>">
                <img class="retweet-image" src='/images/retweet-solid-blue.svg'></a>
              <?php
              }
              else
              { ?>
                &emsp;
                <a href="index.php?retweet=<?= "{$t['id']}" ?>">
                <img class="retweet-image" src='/images/retweet-solid-gray.svg'></a>
              <?php
              }
              $sum = getRetweetCount($t['id']);
              if($sum[0][0] !== 0)
              {
                echo $sum[0][0];
              } ?>
            </div>
          </div>
      <?php 
      }
    } ?>
    <form method="POST">
      <input type="hidden" name="logout" value="dummy">
      <button class="btn btn-primary">ログアウト</button>
    </form>
    <br>
  </div>
</body>

</html>
