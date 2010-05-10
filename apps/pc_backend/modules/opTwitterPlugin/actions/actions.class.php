<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opTwitterPlugin actions.
 *
 * @package    OpenPNE
 * @subpackage opTwitterPlugin
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 9301 2008-05-27 01:08:46Z dwhittle $
 */
class opTwitterPluginActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfWebRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
		$this->form = new TwitterAdminCfg();
		if ($request->isMethod(sfWebRequest::POST)) {
			$this->form->bind($request->getParameter('consumer'));
			if ($this->form->isValid()) {
				$this->form->save();
				$this->redirect('opTwitterPlugin/index');
			}
		}
		return sfView::SUCCESS;
//    $this->forward('default', 'module');
  }
}
