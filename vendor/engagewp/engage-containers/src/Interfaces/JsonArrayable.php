<?php


namespace engagewp\interop\Interfaces;

use engagewp\EngageContainers\Interfaces\Arrayable;

/**
 * Interface JsonArrayable
 *
 * Interface that all objects that covnert to an array that is then used to convert to JSON MUST Impliment
 */
interface JsonArrayable extends Arrayable, \JsonSerializable
{

}
