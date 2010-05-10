<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */
class opTwitterPlugin {
	static function getFeedByUrl($url) {
		if (is_null($url)) return false;
		$old = umask(0);
		$feed = new SimplePie();
		$feed->set_cache_duration(60);
		$dir = sfConfig::get('sf_app_cache_dir').'/plugins';
		if (!file_exists($dir)) {
			if (!@mkdir($dir, 0777, true)) {
				throw new Exception(sprintf('Could not create directory "%s"', $dir));
			}
		}
		$dir .= '/opTwitterPlugin';
		if (!file_exists($dir)) {
			if (!@mkdir($dir, 0777, true)) {
				throw new Exception(sprintf('Could not create directory "%s"', $dir));
			}
		}
		umask($old);
		$feed->set_cache_location($dir);
		$feed->set_feed_url($url);
		if(!@$feed->init()) return false;
		$feed->handle_content_type();

		return $feed;
	}

	static function getBlogListByMemberId($member_id, &$list) {
		$member = Doctrine::getTable('Member')->find($member_id);
		if (!$member || !$member->getIsActive()) return;

		$user_id			= $member->getConfig('twitter_user_id');
		$screen_name	= $member->getConfig('twitter_screen_name');
		if (!$user_id) return;
		$url = 'http://twitter.com/statuses/user_timeline/'.$user_id.'.rss';
		$feed = self::getFeedByUrl($url);
		if (!$feed) return;

		foreach ($feed->get_items() as $item) {
			$list[] = self::setBlog(
				strtotime(@$item->get_date()),
				@$item->get_title(),
				@$item->get_link(),
				$member->getName(),
				$user_id
			);
		}
	}

	static function setBlog($date,$title,$link,$name,$user_id) {
		$screen_name = '';
		if (($pos = strpos($title,':')) !== false) {
			$screen_name = substr($title,0,$pos);
			$title = substr($title,$pos + 1);
		}

		return array(
			'date'							=> $date,
			'title'							=> htmlspecialchars_decode($title),
			'link_to_external'	=> $link,
			'screen_name'				=> $screen_name,
			'name'							=> $name,
			'user_id'						=> $user_id
		);
	}

	static function sortBlogList(&$list, $size = 20) {
		foreach ($list as $aKey => $a) {
			$pickKey = $aKey;
			for ($bKey = $aKey + 1; $bKey < count($list); $bKey++) {
				if ($list[$bKey]['date'] > $list[$pickKey]['date']) {
					$pickKey = $bKey;
				}
			}
			if ($aKey != $pickKey) {
				$list[$aKey] = $list[$pickKey];
				$list[$pickKey] = $a;
			}
		}
		return array_splice($list, 0, $size);
	}

	static function limitBlogTitle(&$list) {
		foreach($list as &$res) {
			$res['title'] = mb_strcut($res['title'], 0, 30);
		}
	}

	static function getBlogListOfAllMember($size = 20, $limitTitle = false) {
		$memberList = Doctrine::getTable('Member')->createQuery()->execute();

		$list = array();
		foreach ($memberList as $member) {
			self::getBlogListByMemberId($member->getId(), $list);
		}

		$list = self::sortBlogList($list, $size);
		if ($limitTitle) self::limitBlogTitle($list);

		return $list;
	}

	static function getBlogListOfFriends($member_id, $size = 20, $limitTitle = false) {
		$member = Doctrine::getTable('Member')->find($member_id);
		$friendList = $member->getFriends();

		$list = array();
		foreach ($friendList as $friend) {
			self::getBlogListByMemberId($friend->getId(), $list);
		}
		$list = self::sortBlogList($list, $size);

		if ($limitTitle) self::limitBlogTitle($list);

		return $list;
	}

	static function getBlogListOfMember($member_id, $size = 20, $limitTitle = false)
	{
		$list = array();
		self::getBlogListByMemberId($member_id, $list);
		$list = self::sortBlogList($list, $size);
		if ($limitTitle) self::limitBlogTitle($list);

		return $list;
	}

}
