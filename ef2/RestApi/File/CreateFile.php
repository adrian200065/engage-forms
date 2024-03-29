<?php


namespace engagewp\engageforms\ef2\RestApi\File;


use engagewp\engageforms\ef2\Exception;
use engagewp\engageforms\ef2\Fields\FieldTypes\FileFieldType;
use engagewp\engageforms\ef2\Fields\Handlers\Cf1FileUploader;
use engagewp\engageforms\ef2\Fields\Handlers\FileUpload;
use engagewp\engageforms\ef2\Transients\Cf1TransientsApi;
use engagewp\engageforms\ef2\Fields\Handlers\UploaderContract;

class CreateFile extends File
{

    /** @inheritdoc */
    protected function getArgs()
    {
        return [

            'methods' => 'POST',
            'callback' => [$this, 'createItem'],
            'permission_callback' => [$this, 'permissionsCallback' ],
            'args' => [
                'hashes' => [
                    'description' => __('MD5 has of files to upload, should have same order as files arg', 'engage-forms')
                ],
                'verify' => [
                    'type' => 'string',
                    'description' => __('Verification token (nonce) for form', 'engage-forms'),
                    'required' => true,
                ],
                'formId' => [
                    'type' => 'string',
                    'description' => __('ID for form field belongs to', 'engage-forms'),
                    'required' => true,
                ],
                'fieldId' => [
                    'type' => 'string',
                    'description' => __('ID for field files are submitted to', 'engage-forms'),
                    'required' => true,
                ],
                'control' => [
                    'type' => 'string',
                    'description' => __('Unique control string for field', 'engage-forms'),
                    'required' => true,
                ]
            ]
        ];
    }

    /**
     * Permissions check for file field uploads
     *
     * @since 1.8.0
     *
     * @param \WP_REST_Request $request Request object
     *
     * @return bool
     */
    public function permissionsCallback(\WP_REST_Request $request ){
        $form =  \Engage_Forms_Forms::get_form( $request->get_param( 'formId' ) );
        if( FileFieldType::getCf1Identifier() !== \Engage_Forms_Field_Util::get_type( $request->get_param( 'fieldId' ), $form ) ){
            return false;
        }
        return \Engage_Forms_Render_Nonce::verify_nonce(
            $request->get_param( 'verify'),
            $request->get_param( 'formId' )
        );
    }

    /**
     * Upload file for ef2 file field
     *
     * @since 1.8.0
     *
     * @param \WP_REST_Request $request
     * @return \WP_REST_Response
     */

    /**
     * @param \WP_REST_Request $request
     * @return mixed|null|\WP_REST_Response
     * @throws \Exception
     */
    public function createItem(\WP_REST_Request $request)
    {

        $files = $request->get_file_params();
        if( isset( $files[ 'file'] ) ){
            $files = [$files['file']];
        }
        $formId = $request->get_param('formId');
        $this->setFormById($formId);
        $fieldId = $request->get_param('fieldId');
        $field = \Engage_Forms_Field_Util::get_field($fieldId,$this->getForm());
        $uploader = new Cf1FileUploader();
        if( is_wp_error( $uploader ) ){
            /** @var \WP_Error $uploader */
            $e = Exception::fromWpError($uploader);
            return $e->toResponse();
        }
        $hashes = $request->get_param( 'hashes');
        $controlCode = $request->get_param( 'control'  );
        $transientApi = new Cf1TransientsApi();
        $handler = new FileUpload(
            $field,
            $this->getForm(),
            new Cf1FileUploader()
        );
        try{
            if( is_string($hashes ) ){
                $hashes = [$hashes];
            }

            $uploads = $handler->processFiles($files,$hashes);
            $transdata = is_array( $transientApi->getTransient( $controlCode ) )
                ? $transientApi->getTransient( $controlCode )
                : [];

            $transientApi->setTransient( $controlCode, array_merge(
                $transdata,
                $uploads
            ), DAY_IN_SECONDS );
        }catch( Exception $e ){
            return $e->toResponse();
        }

        if( is_wp_error( $controlCode ) ){
            $e = Exception::fromWpError($controlCode);
            return $e->toResponse();

        }

        $response = rest_ensure_response([
            'control' => $controlCode,
        ]);
        $response->set_status(201);

        return $response;
    }



}
