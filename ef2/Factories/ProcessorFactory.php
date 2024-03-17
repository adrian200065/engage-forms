<?php


namespace engagewp\engageforms\ef2\Factories;


interface ProcessorFactory
{
    /**
     * Factory for processor data
     *
     * @since 1.8.10
     *
     * Designed to be used in callback for processors
     *
     * @param array $config Saved settings for processor
     * @param array $form Saved settings for this form
     * @param array $fields Processor settings field configuration
     *
     * @return \Engage_Forms_Processor_Get_Data
     */
    public function dataFactory(array $config,array $form,array $fields);
}