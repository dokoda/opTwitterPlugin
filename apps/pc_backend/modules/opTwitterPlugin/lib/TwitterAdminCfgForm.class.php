<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * APIAllowIPConfig form.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class TwitterAdminCfg extends sfForm {
	protected $configs = array(
		'consumer_key'		=> 'op_twitter_plugin_consumer_key',
		'consumer_secret'	=> 'op_twitter_plugin_consumer_secret',
		'hash_tag'				=> 'op_twitter_plugin_hash_tag',
		'url'							=> 'op_twitter_plugin_url'
	);

	public function configure()
	{
		$this->setWidgets(array(
			'consumer_key'		=> new sfWidgetFormInput(),
			'consumer_secret'	=> new sfWidgetFormInput(),
			'hash_tag'				=> new sfWidgetFormInput(),
			'url'							=> new sfWidgetFormInput()
		));
		$this->setValidators(array(
			'consumer_key'		=> new sfValidatorString(array('required'=>false)),
			'consumer_secret'	=> new sfValidatorString(array('required'=>false)),
			'hash_tag'				=> new sfValidatorString(array('required'=>false)),
			'url'							=> new sfValidatorString(array('required'=>false))
		));

		foreach ($this->configs as $k => $v) {
			$config = Doctrine::getTable('SnsConfig')->retrieveByName($v);
			if ($config) {
				$this->getWidgetSchema()->setDefault($k, $config->getValue());
			}
		}

		$this->getWidgetSchema()->setNameFormat('consumer[%s]');
	}

	public function save() {
		foreach ($this->getValues() as $k => $v) {
			if (!isset($this->configs[$k])) continue;

			$config = Doctrine::getTable('SnsConfig')->retrieveByName($this->configs[$k]);
			if (!$config) {
				$config = new SnsConfig();
				$config->setName($this->configs[$k]);
			}
			$config->setValue($v);
			$config->save();
		}
	}
}
