<?php
class opTwitterPluginTwitterActions extends sfActions
{
	private $twitter;
	private function init() {
		static $ini = false;
		if ($ini) return 0;
		$consumer_key = $this->getSnsConfig('op_twitter_plugin_consumer_key');
		$consumer_secret = $this->getSnsConfig('op_twitter_plugin_consumer_secret');		if (!$consumer_key || !$consumer_secret) return -1;

		$this->twitter = new lib_twitter($consumer_key,$consumer_secret);
		$ini = true;
		return 1;
	}

	public function executeGetRequestToken(sfWebRequest $request) {
		if ($this->init() < 0) {
			$this->getUser()->setFlash('notice', 'Twitter認証システムエラー');
			return sfView::ALERT;
		}

//		$ret = $this->twitter->rate_limit_status();
//$f = fopen('/tmp/test.log','a');
//fwrite($f,'rate_limit_status:'.print_r($ret,true).PHP_EOL);
//fclose($f);
/*
(
    [remaining_hits] => 145
    [hourly_limit] => 150
    [reset_time_in_seconds] => 1271956522
    [reset_time] => Thu Apr 22 17:15:22 +0000 2010
)
*/

		$callback_url = 'http://h-bigvalley.dokoda.jp/twitter/getAccessToken';
		$this->twitter->getRequestToken($callback_url);
	}
	public function executeGetAccessToken(sfWebRequest $request) {
		if ($this->init() < 0) {
			$this->getUser()->setFlash('notice', 'Twitter認証システムエラー');
			return sfView::ALERT;
		}
		$oauth_token		= $this->getRequestParameter('oauth_token');
		$oauth_verifier	= $this->getRequestParameter('oauth_verifier');
		$t = $this->twitter->getAccessToken($oauth_token,$oauth_verifier);

		$oauth_token				= isset($t['oauth_token']) ? $t['oauth_token'] : '';
		$oauth_token_secret	= isset($t['oauth_token_secret']) ? $t['oauth_token_secret'] : '';
		$user_id						= isset($t['user_id']) ? $t['user_id'] : '';
		$screen_name				= isset($t['screen_name']) ? $t['screen_name'] : '';

		$id = $this->getUser()->getMemberId();
		$member = Doctrine::getTable('Member')->find($id);

		$member->setConfig('twitter_oauth_token'				,$oauth_token);
		$member->setConfig('twitter_oauth_token_secret'	,$oauth_token_secret);
		$member->setConfig('twitter_user_id'						,$user_id);
		$member->setConfig('twitter_screen_name'				,$screen_name);
		$member->save();

		if ($oauth_token && $oauth_token_secret && $user_id && $screen_name) {
			$this->getUser()->setFlash('notice', 'Twitter認証成功しました');
			$this->redirect('@twitter_config');
		} else {
			$this->getUser()->setFlash('notice', 'Twitter認証失敗しました');
			$this->redirect('@twitter_config');
		}

//$f = fopen('/tmp/test.log','a');
//fwrite($f,'executeGetAccessToken blog_url:'.$blog_url.PHP_EOL);
//fclose($f);
//return;
//$f = fopen('/tmp/test.log','a');
//fwrite($f,'executeGetAccessToken blog_url:'.$blog_url.PHP_EOL);
//fwrite($f,'executeGetAccessToken token:'.print_r($t,true).PHP_EOL);
//fclose($f);
	}

	public function executeTweet(sfWebRequest $request) {
		if ($this->init() < 0) {
			header('Content-type: application/json');
			echo json_encode(array('result'=> '-1'));
			return;
		}

		$id = $this->getUser()->getMemberId();
		$member = Doctrine::getTable('Member')->find($id);
		$oauth_token				= $member->getConfig('twitter_oauth_token');
		$oauth_token_secret	= $member->getConfig('twitter_oauth_token_secret');
//$f = fopen('/tmp/test.log','a');
//fwrite($f,'oauth_token '.$oauth_token.PHP_EOL);
//fwrite($f,'oauth_token_secret '.$oauth_token_secret.PHP_EOL);
//fclose($f);

		if (!$oauth_token || !$oauth_token) {
			header('Content-type: application/json');
			echo json_encode(array('result'=> '-2'));
			return;
		}
		$this->twitter->setToken($oauth_token,$oauth_token_secret);

		$msg = file_get_contents('php://input');

		$hash_tag = $this->getSnsConfig('op_twitter_plugin_hash_tag');
		if ($hash_tag) $msg .= ' '.$hash_tag;
		$url = $this->getSnsConfig('op_twitter_plugin_url');
		if ($url) $msg .= ' '.$url;

		$ret = $this->twitter->update($msg);
/*
(
    [coordinates] => 
    [favorited] => 
    [created_at] => Mon Apr 26 07:00:31 +0000 2010
    [in_reply_to_status_id] => 
    [geo] => 
    [in_reply_to_user_id] => 
    [place] => 
    [source] => <a href="http://h-bigvalley.dokoda.jp/" rel="nofollow">八戸ビッグバレーSNS</a>
    [contributors] => 
    [in_reply_to_screen_name] => 
    [user] => Array
        (
            [created_at] => Tue Nov 18 12:18:58 +0000 2008
            [profile_sidebar_fill_color] => F3F3F3
            [followers_count] => 569
            [description] => まるごとRSS等を運営
            [statuses_count] => 1333
            [following] => 
            [profile_background_image_url] => http://s.twimg.com/a/1272044617/images/themes/theme7/bg.gif
            [friends_count] => 548
            [profile_sidebar_border_color] => DFDFDF
            [contributors_enabled] => 
            [notifications] => 
            [url] => http://dokoda.jp/
            [profile_background_tile] => 
            [favourites_count] => 9
            [profile_background_color] => EBEBEB
            [location] => 青森県八戸市
            [geo_enabled] => 
            [profile_text_color] => 333333
            [protected] => 
            [verified] => 
            [profile_image_url] => http://a3.twimg.com/profile_images/75216217/327946_2865348867_normal.jpg
            [name] => 菅原@どこだ
            [profile_link_color] => 990000
            [id] => 17462972
            [lang] => ja
            [time_zone] => Tokyo
            [utc_offset] => 32400
            [screen_name] => dokoda
        )

    [id] => 12869316045
    [truncated] => 
    [text] => OpenPne作業中 #8valley
)
*/
		if (isset($ret['id']) && $ret['id']) {
			$profile = array('result'=> '0');
		} else {
			$profile = array('result'=> '-3');
		}
		header('Content-type: application/json');
		echo json_encode($profile);
	}


	private function getSnsConfig($name) {
		$config = Doctrine::getTable('SnsConfig')->retrieveByName($name);
		if (!$config) return '';
		return $config->getValue();
	}

	public function executeConfig(sfWebRequest $request) {
		$id = $this->getUser()->getMemberId();
		$member = Doctrine::getTable('Member')->find($id);
		$oauth_token				= $member->getConfig('twitter_oauth_token');
		$oauth_token_secret	= $member->getConfig('twitter_oauth_token_secret');
		$user_id						= $member->getConfig('twitter_user_id');
		$screen_name				= $member->getConfig('twitter_screen_name');

		if ($oauth_token && $oauth_token_secret && $user_id && $screen_name) {
			$this->token = 1;
			$this->oauth_token = $oauth_token;
			$this->oauth_token_secret = $oauth_token_secret;
			$this->user_id = $user_id;
			$this->screen_name = $screen_name;
		} else {
			$this->token = 0;
		}
//		$this->blogList = opTwitterPlugin::getBlogListOfFriends($id);
//		$this->pager = Doctrine::getTable('VoteQuestion')->getListPager($request->getParameter('page'));
	}

	public function executeIndex(sfWebRequest $request) {
	}

	public function executeReset(sfWebRequest $request)
	{
		$id = $this->getUser()->getMemberId();
		$member = Doctrine::getTable('Member')->find($id);
		$member->setConfig('twitter_oauth_token'				,'');
		$member->setConfig('twitter_oauth_token_secret'	,'');
		$member->setConfig('twitter_user_id'						,'');
		$member->setConfig('twitter_screen_name'				,'');
		$member->save();

		$this->getUser()->setFlash('notice', 'Twitter認証リセットしました');
		$this->redirect('@twitter_config');
	}

	public function executeFriend($request)
	{
		$this->blogList = opTwitterPlugin::getBlogListOfFriends(
			$this->getUser()->getMemberId(),
			sfConfig::get('app_twitter_action_size')
		);
		if (!count($this->blogList)) return sfView::ALERT;
	}

	public function executeAll($request)
	{
		$this->blogList = opTwitterPlugin::getBlogListOfAllMember(
			$this->getUser()->getMemberId(),
			sfConfig::get('app_twitter_action_size')
		);
		if (!count($this->blogList)) return sfView::ALERT;
	}







/*
	public function executeEdit(sfWebRequest $request)
	{
		// モデルオブジェクトをルートから取得
		$object = $this->getRoute()->getObject();
		// もし、作成者でない場合は404画面に飛ばす
		$this->forward404Unless($this->getUser()->getMemberId() == $object->getMemberId());
		$this->form = new VoteQuestionForm($object);
	}

	public function executeUpdate(sfWebRequest $request)
	{
		$object = $this->getRoute()->getObject();
		$this->forward404Unless($this->getUser()->getMemberId() == $object->getMemberId());
		$this->form = new VoteQuestionForm($object);
		if ($this->form->bindAndSave($request->getParameter('vote_question')))
		{
		  $this->getUser()->setFlash('notice', '編集しました');
		  $this->redirect('@vote_list');
		}
		$this->setTemplate('edit');
	}
*/
}

class lib_twitter {
	public $err = 0;
	public $msg = '';
	public $debug = false;
	private $oauth;

	public function __construct($consumer_key,$consumer_secret) {
		try {
			$this->oauth = new OAuth(	$consumer_key,
																$consumer_secret,
																OAUTH_SIG_METHOD_HMACSHA1,
																OAUTH_AUTH_TYPE_URI);
		} catch (OAuthException $e) {
			return false;
		}
	}
//	public function __destruct() {
//	}

	public function getRequestToken($callback_url ='') {
		$url = $this->getRequestTokenUrl($callback_url);
		if ($url) {
			header('Location: '.$url);
			exit;
		}
		return false;
	}

	private function getRequestTokenUrl($callback_url = '') {
		$request_token_url = 'http://twitter.com/oauth/request_token';
		$authurl = 'http://twitter.com/oauth/authorize?oauth_token=';
		try {
			$ret = $this->oauth->getRequestToken($request_token_url,$callback_url);
			return $authurl.$ret['oauth_token'];
		} catch (OAuthException $e) {
			return false;
		}
	}

	public function getAccessToken($oauth_token,$oauth_verifier) {
		$access_token_url = 'http://twitter.com/oauth/access_token';
		try {
			$this->oauth->setToken($oauth_token,'');
			return $this->oauth->getAccessToken($access_token_url,$verifier_token);
		} catch (OAuthException $e) {
			return false;
		}
	}

	public function setToken($oauth_token,$oauth_token_secret) {
		try {
			return $this->oauth->setToken($oauth_token,$oauth_token_secret);
		} catch (OAuthException $e) {
			return false;
//			var_dump($e);
		}
	}

	public function home_timeline($limit=200) {
		$url = 'http://api.twitter.com/1/statuses/home_timeline.json?'.http_build_query(array('count'=>$limit));
		try {
			$this->oauth->fetch($url);
			return json_decode($this->oauth->getLastResponse(),true);
		} catch (OAuthException $e) {
			return false;
//			var_dump($e);
		}
	}

	public function update($msg) {
//		$url = 'https://twitter.com/statuses/update.json';
		$url = 'http://twitter.com/statuses/update.json';
		$extra_parameters = array('status'=>$msg);
		try {
			$this->oauth->fetch($url,
													$extra_parameters,
													OAUTH_HTTP_METHOD_POST);
			return json_decode($this->oauth->getLastResponse(),true);
		} catch (OAuthException $e) {
//			var_dump($e);
			return false;
		}
	}

	public function verify_credentials() {
		$url = 'https://twitter.com/account/verify_credentials.json';
		try {
			$this->oauth->fetch($url); 
			return json_decode($this->oauth->getLastResponse(),true);
		} catch (OAuthException $e) {
			return false;
		}
	}

	public function rate_limit_status() {
		$url = 'http://twitter.com/account/rate_limit_status.json';
		try {
			$this->oauth->fetch($url); 
			return json_decode($this->oauth->getLastResponse(),true);
		} catch (OAuthException $e) {
			return false;
		}
	}

}
