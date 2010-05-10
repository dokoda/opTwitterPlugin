<?php if ($token) { ?>

<?php
op_include_box('twitter_cfg_create_box','',array(
	'title' => 'Twitter認証済'
));
?>
<p>
<a href="http://twitter.com/<?php echo $screen_name ?>" target="_blank"><?php echo $screen_name ?> on Twitter</a><br/>
<!--
oauth_token:<?php echo $oauth_token ?><br/>
oauth_token_secret:<?php echo $oauth_token_secret ?><br/>
-->
認証済ツイッターID:<?php echo $screen_name ?><br/>
認証済ユーザーID:<?php echo $user_id ?><br/>
<a href="http://twitter.com/statuses/user_timeline/<?php echo $user_id ?>.rss" target="_blank">RSS</a><br/>
</p>


<?php

op_include_box('twitter_cfg_create_box','自分のツイートを表示します。',array(
	'title' => 'マイツイート',
	'moreInfo' => array(link_to(__('Show Profile'), 'member/profile'))
));



?>

<?php } else { ?>

<?php
op_include_box('twitter_cfg_create_box','Twitterとの連携を確認します。',array(
	'title' => 'Twitter認証設定',
	'moreInfo' => array(link_to('Twitter認証設定', '@twitter_getRequestToken'))
));
?>

<?php } ?>

<?php
op_include_box('twitter_cfg_create_box','マイフレンドのツイートを表示します。',array(
	'title' => 'マイフレンドツイート',
	'moreInfo' => array(link_to('マイフレンドツイート', '@twitter_friend'))
));
?>

<?php
if ($token) {
	op_include_box('twitter_cfg_create_box','Twitterとの連携を解除します。',array(
		'title' => 'Twitter認証リセット',
		'moreInfo' => array(link_to('Twitter認証リセット', '@twitter_reset'))
	));
}
?>


