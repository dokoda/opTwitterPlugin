<?php

include_parts(
  'BlogListPage',
  'tweetIndex',
  array(
    'title' => __('Friends Newest tweet'),
    'list' => $sf_data->getRaw('blogList'),
    'showName' => true
  )
);
