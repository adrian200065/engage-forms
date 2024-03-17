<?php


namespace engagewp\engageforms\ef2\Services;


use engagewp\engageforms\ef2\EngageFormsV2Contract;
use engagewp\engageforms\ef2\Factories\Processor;

class ProcessorService extends Service
{


    /** @inheritDoc */
    public function isSingleton()
    {
       return true;
    }

    /** @inheritDoc */
    public function register(EngageFormsV2Contract $container)
    {
        return new Processor();
    }
}