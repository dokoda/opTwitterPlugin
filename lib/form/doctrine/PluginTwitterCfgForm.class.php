<?php

/**
 * PluginTwitterCfg form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfDoctrineFormPluginTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class PluginTwitterCfgForm extends BaseTwitterCfgForm
{
	public function setup()
	{
		// �e�N���X��setup()�Ăяo��
		parent::setup();

//		// �o���f�[�^��������
//		$this->setValidator('title', new opValidatorString(array('max_length' => 140, 'trim' => true)));
//		$this->setValidator('body', new opValidatorString(array('max_length' => 2147483647, 'trim' => true)));

//		// �I�������w�肷��E�B�W�F�b�g�ǉ�
//		$this->setWidget('option', new sfWidgetFormInput());
//		$this->setValidator('option', new sfValidatorString(array('trim' => true)));
//		$this->widgetSchema->setLabel('option', '�I����');
//		$this->widgetSchema->setHelp('option', '�I�������X�y�[�X��؂�œ��͂��Ă�������');

//		// �ҏW���Ȃ�I�����Ɍ��݂̃f�[�^���f�t�H���g���đ}��
//		if (!$this->isNew())
//		{
//			$options = $this->getObject()->getTwitterCfg()->toKeyValueArray('id', 'body');
//			$this->setDefault('option', implode(' ', $options));
//		}

		// �g���t�B�[���h�w��
		$this->useFields(array('oauth_token', 'oauth_token_secret'));
	}

/*
	protected function doSave($con = null)
	{
		parent::doSave();

		$newOptions = $this->getValue('option');
		$newOptions = preg_split('/[\s�@]+/u', $newOptions, -1, PREG_SPLIT_NO_EMPTY);
		$voteQuestion = $this->getObject();

		// �ߋ��̑I�����̒��o
		$oldOptions = $voteQuestion->getVoteQuestionOptions();
		$oldOptions = $oldOptions->toKeyValueArray('id', 'body');

		// �폜���ꂽ�I�����̒��o
		$deletedOptions = array_diff($oldOptions, $newOptions);
		foreach ($deletedOptions as $id => $body)
		{
		  // �폜
		  $object = Doctrine::getTable('VoteQuestionOption')->find($id);
		  $object->delete();
		}

		// �V�K�̑I����
		$insertOptions = array_diff($newOptions, $oldOptions);
		foreach ($insertOptions as $body)
		{
		  // �ǉ�
		  $object = new VoteQuestionOption();
		  $object->setVoteQuestion($voteQuestion);
		  $object->setBody($body);
		  $object->save();
		}
	}
*/

}
