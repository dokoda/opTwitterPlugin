<div id="<?php echo $id ?>" class="dparts homeRecentList">
<div class="parts">

<div class="partsHeading">
<h3><?php echo $options['title'] ?></h3>
</div>

<div class="block">

<style type="text/css">

ol.statuses {
font-size:14px;
list-style:none outside none;
}

ol.statuses li.status, ol.statuses li.direct_message {
border-bottom:1px solid #EEEEEE;
line-height:16px;
padding:10px 0 8px;
position:relative;
margin-left:0px;
}

ol.statuses > li.last-on-page, ol.statuses > li.last-on-refresh {
border-bottom:1px solid #CCCCCC !important;
}

.thumb  {
display:block;
height:50px;
left:0;
margin:0 10px 0 0;
overflow:hidden;
position:absolute;
width:50px;
z-index:10;
}

span.status-body {
margin-left:56px;
display:block;
min-height:48px;
overflow:visible;
}

ol.statuses span.status-body span.status-content {
overflow:hidden;
}

ol.statuses .actions {
border-width:0;
line-height:1.25em;
position:absolute;
right:10px;
top:8px;
}


a.screen-name {
text-decoration:none;
}

.meta  {
color:#999999;
display:block;
font-size:11px;
}

.entry-meta {
margin-top:2px;
}

a.entry-date {
text-decoration:none;
color:#999999;
}
</style>

<ol id="timeline" class="statuses">

<?php foreach($options->getRaw('list') as $v): ?>
<li id="status_00000000000" class="hentry u-<?php echo $v['screen_name'] ?> status">
	<span class="thumb vcard author">
		<a class="tweet-url profile-pic url" href="http://twitter.com/<?php echo $v['screen_name'] ?>">

<?php
//http://blog.livedoor.jp/dankogai/archives/51331768.html
?>
			<img class="photo fn" width="48" height="48" src="http://api.dan.co.jp/twicon/<?php echo $v['screen_name'] ?>/normal" alt="<?php echo $v['screen_name'] ?>" onerror="this.src='/images/dummy.gif'"/>
		</a>
	</span>
	<span class="status-body">
		<span class="status-content">
			<strong>
				<a href="http://twitter.com/<?php echo $v['screen_name'] ?>" class="tweet-url screen-name" target="_blank"><?php echo $v['screen_name'] ?></a>
			</strong>
			<span class="actions"><div>
					<a id="status_star_12707835694" class="fav-action non-fav" title="ツイートをお気に入りに登録">&nbsp;&nbsp;</a>
			</div></span>
			<span class="entry-content"><?php echo $v['title'] ?></span>
		</span>
		<span class="meta entry-meta" data="{}">
			<a class="entry-date" rel="bookmark" href="<?php echo $v['link_to_external'] ?>" target="_blank">
				<span class="published timestamp" data="{time:'<?php echo $v['date'] ?>'}"><?php echo op_format_date($v['date'], 'f') ?><!-- 3分前 --></span>
			</a>
			<!-- <span>webから</span><a href="">〇〇宛</a> -->
		</span>
		<ul class="actions-hover">
<!--
			<li>
			<span class="reply">
				<span class="reply-icon icon"></span>
				<a href="/?status=@<?php echo $v['screen_name'] ?>&in_reply_to_status_id=12707835694&in_reply_to=<?php echo $v['screen_name'] ?>" title="<?php echo $v['screen_name'] ?>に返事">返信</a>
			</span>
			</li>
			<li>
				<span class="retweet-link">
					<span class="retweet-icon icon"></span>
					<a title="リツイート" href="#">リツイート</a>
				</span>
			</li>
-->
		</ul>
	</span>
</li>
<?php endforeach; ?>
</ol>




<!--
<ul class="articleList">
<?php foreach($options->getRaw('list') as $v): ?>
<li>
<span class="date"><?php echo date( __('m/d'), $v['date']) ?></span>
<?php image_tag('articleList_maker.gif', array('alf' => '')) ?> 
<?php
echo '<a href="' . $v['link_to_external'] . '" target="_blank">' . $v['title'] . '</a>';
?>
<?php if ($options['showName']): ?>
(<?php echo $v['name'] ?>)
<?php endif ?>
</li>
<?php endforeach; ?>
</ul>

<?php if (isset($options['moreInfo'])): ?>
<div class="moreInfo"><ul class="moreInfo"><li>
<?php echo link_to(__('More info'), $options['moreInfo']) ?>
</li></ul></div>
<?php endif; ?>
-->


</div>

</div></div>

