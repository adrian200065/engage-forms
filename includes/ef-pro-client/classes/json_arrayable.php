<?php


namespace engagewp\engageforms\pro;

use engagewp\engageforms\pro\interfaces\arrayable;


/**
 * Class json_arrayable
 *
 * A bass class that can be arrayed or JSON serialized
 *
 *
 * @package engagewp\engageforms\pro
 */
abstract class json_arrayable implements \JsonSerializable, arrayable
{

	/**
	 * @inheritdoc
	 * @since 0.0.1
	 */
	public function jsonSerialize()
	{
		return $this->toArray();
	}

}
