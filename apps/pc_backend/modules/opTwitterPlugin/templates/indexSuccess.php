<h2><?php echo __('Twitter認証設定') ?></h2>

<form action="<?php url_for('opTwitterPlugin/index') ?>" method="post">
<table>
<?php echo $form ?>
<tr>
<td colspan="2"><input type="submit" value="<?php echo __('変更') ?>" /></td>
</tr>
</table>
</form>
