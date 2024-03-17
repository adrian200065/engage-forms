<?php


namespace engagewp\engageforms\ef2\Factories;

/**
 * Class Processor
 *
 * Factory for Engage Forms processors
 */
class Processor implements ProcessorFactory
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
     * @return \Engage_Forms_Processor_Data
     */
    public function dataFactory(array $config,array $form,array $fields){
        return new \Engage_Forms_Processor_Get_Data($config,$form,$fields);
    }

}