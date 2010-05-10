<?php

include_parts(
  'BlogListPage',
  'tweetFriend',
  array(
    'title' => __('Newest tweet'),
    'list' => $sf_data->getRaw('blogList'),
    'showName' => true
  )
);
