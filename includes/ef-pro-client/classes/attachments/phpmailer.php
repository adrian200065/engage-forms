<?php


namespace engagewp\engageforms\pro\attachments;


/**
 * Class phpmailer
 * @package engagewp\engageforms\pro\attachments
 */
class phpmailer extends \PHPMailer
{

	/** @inheritdoc */
	public function attachAll($disposition_type, $boundary)
	{
		return parent::attachAll($disposition_type, $boundary);
	}
}
