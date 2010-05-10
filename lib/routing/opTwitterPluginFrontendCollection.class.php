<?php

class opTwitterPluginFrontendRouteCollection extends sfRouteCollection
{
  public function __construct(array $options)
  {
    parent::__construct($options);
 
    $this->routes = array(
      'twitter_getRequestToken' => new sfRequestRoute(
        '/twitter/getRequestToken',
        array('module' => 'twitter', 'action' => 'getRequestToken'),
        array('sf_method' => array('get'))
      ),
      'twitter_getAccessToken' => new sfRequestRoute(
        '/twitter/getAccessToken',
        array('module' => 'twitter', 'action' => 'getAccessToken'),
        array('sf_method' => array('get'))
      ),
      'twitter_list' => new sfRequestRoute(
        '/twitter',
        array('module' => 'twitter', 'action' => 'index'),
        array('sf_method' => array('get'))
      ),
      'twitter_reset' => new sfRequestRoute(
        '/twitter/reset',
        array('module' => 'twitter', 'action' => 'reset'),
        array('sf_method' => array('get'))
      ),
      'twitter_friend' => new sfRequestRoute(
        '/twitter/friend',
        array('module' => 'twitter', 'action' => 'friend'),
        array('sf_method' => array('get'))
      ),
      'twitter_all' => new sfRequestRoute(
        '/twitter/all',
        array('module' => 'twitter', 'action' => 'all'),
        array('sf_method' => array('get'))
      ),
      'twitter_config' => new sfRequestRoute(
        '/twitter/config',
        array('module' => 'twitter', 'action' => 'config'),
        array('sf_method' => array('get'))
      ),
      'twitter_tweet' => new sfRequestRoute(
        '/twitter/tweet',
        array('module' => 'twitter', 'action' => 'tweet'),
        array('sf_method' => array('post'))
      ),
      // no default
      'twitter_nodefaults' => new sfRoute(
        '/twitter/*',
        array('module' => 'default', 'action' => 'error')
      ),
    );
  }
}
