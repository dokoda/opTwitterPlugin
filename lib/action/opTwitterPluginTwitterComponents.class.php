<?php

class opTwitterPluginTwitterComponents extends sfComponents
{
	public function executeTwitterFriend(sfWebRequest $request) {
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

		$this->blogList = opTwitterPlugin::getBlogListOfFriends($id);

	}
	public function executeTwitterUser(sfWebRequest $request) {
		$id = 0;
		if ($request->hasParameter('id')) $id = $request->getParameter('id');
		if (!$id) $id = $this->getUser()->getMemberId();
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
			$this->blogList = opTwitterPlugin::getBlogListOfMember($id);
		} else {
			$this->token = 0;
		}
	}
	public function executeTwitterPost(sfWebRequest $request) {
	}
}
