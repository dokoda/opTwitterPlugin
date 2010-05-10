<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * MemberConfigBlogUrlForm form.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Masato Nagasawa <nagasawa@tejimaya.net>
 */
class MemberConfigTwitterCfgForm extends MemberConfigForm
{
	protected $category = 'TwitterCfg';

	public function setMemberConfigWidget($name) {
$f = fopen('/tmp/test.log','a');
fwrite($f,'test301:'.$name.PHP_EOL);
fclose($f);
		$result = parent::setMemberConfigWidget($name);

		if ($name === 'conkey') {
			$this->widgetSchema['conkey']->setAttributes(array('size' => 69));

			$this->mergePostValidator(
				new sfValidatorString(array('required'=>false))
			);
		}

		return $result;
	}
/*
  public function validate($validator, $value)
  {
    if ($value['conkey'] !== "")
    {
      $root = opBlogPlugin::getFeedByUrl($value['conkey']);
      if (!$root)
      {
        $error = new sfValidatorError($validator, 'URL is invalid.');
        throw new sfValidatorErrorSchema($validator, array('conkey' => $error));
      }
    }
    return $value;
  }
*/
}
