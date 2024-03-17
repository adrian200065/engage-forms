<?php


namespace engagewp\engageforms\ef2\Fields\Handlers;


use engagewp\engageforms\ef2\EngageFormsV2Contract;

abstract class FieldHandler implements FieldHandlerContract
{
    /**
     * EF2 Container
     *
     * @since 1.8.0
     *
     * @var EngageFormsV2Contract
     */
    protected $container;


    /**
     * FieldHandler constructor.
     *
     * @since 1.8.0
     *
     * @param EngageFormsV2Contract $container
     */
    public function __construct(EngageFormsV2Contract $container)
    {
        $this->container = $container;
    }
}