<?php


namespace engagewp\engageforms\ef2\RestApi\File;


use engagewp\engageforms\ef2\RestApi\Endpoint;

abstract class File extends Endpoint
{

    const URI = 'file';
    /** @inheritdoc */
    protected function getUri()
    {
        return self::URI;
    }
}