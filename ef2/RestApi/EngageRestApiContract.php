<?php


namespace engagewp\engageforms\ef2\RestApi;


interface EngageRestApiContract
{
    /**
     * Get the namespace for Engage Forms REST API
     *
     * @since 1.8.0
     *
     * @return string
     */
    public function getNamespace();

    /**
     * Initialize the endpoints
     *
     * @since 1.8.0
     *
     * @return $this
     *
     */
    public function initEndpoints();
}