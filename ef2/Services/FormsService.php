<?php


namespace engagewp\engageforms\ef2\Services;


use engagewp\engageforms\ef2\EngageFormsV2Contract;
use engagewp\engageforms\ef2\Forms\Collection;

/**
 * Class FormsService
 *
 * Service provider for forms. Abstracts over v1 forms API.
 */
class FormsService extends Service
{


    /** @inheritDoc */
    public function isSingleton()
    {
        return true;
    }

    /** @inheritDoc */
    public function register(EngageFormsV2Contract $container)
    {
        $collection = new Collection();
        $forms = \Engage_Forms_Forms::get_forms(true, false);
        if (!empty($forms)) {
            foreach ($forms as $form) {
                if (isset($form['ID'])) {
                    $collection->addForm($form);
                }
            }
        }
        return $collection;

    }

}