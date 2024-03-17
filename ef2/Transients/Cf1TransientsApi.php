<?php


namespace engagewp\engageforms\ef2\Transients;

use engagewp\engageforms\ef2\Exception;

/**
 * Class Cf1TransientsApi
 *
 * Thin wrapper over EF 1.5+ Transients API wrapper
 *
 * @package engagewp\engageforms\ef2\Transients
 */
class Cf1TransientsApi implements TransientApiContract
{ /**
 * @inheritdoc
 * @since 1.8.0
 */
    public  function getTransient($id){
        return \Engage_Forms_Transient::get_transient($id);
    }
    /**
     * @inheritdoc
     * @since 1.8.0
     */
    public  function setTransient($id, $data, $expires = null)
    {
        return  \Engage_Forms_Transient::set_transient($id,$data,$expires);
    }
    /**
     * @inheritdoc
     * @since 1.8.0
     */
    public  function deleteTransient($id){
        return \Engage_Forms_Transient::delete_transient($id);
    }



}