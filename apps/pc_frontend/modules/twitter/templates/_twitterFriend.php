<?php
if (count($blogList))
{
  include_parts(
    'BlogListBox',
    'tweetFriend_'.$gadget->getId(),
    array(
      'title' => __('Friends newest tweet'),
      'list' => $blogList,
      'showName' => true,
      'moreInfo' => 'twitter/friend'
    )
  );
}
