<?php


namespace engagewp\engageforms\ef2\Fields\Handlers;
use engagewp\engageforms\ef2\Fields\Handlers\UploaderContract;


class Cf1FileUploader implements UploaderContract
{

    /** @inheritdoc */
    public function upload($file, array $args = array())
    {
       return \Engage_Forms_Files::upload($file,$args);
    }

	/** @inheritdoc */
	public function addFilter($fieldId, $formId, $private,$transientId= null )
    {
        \Engage_Forms_Files::add_upload_filter($fieldId,$formId,$private,$transientId);
    }
	/** @inheritdoc */
    public function removeFilter()
    {
       \Engage_Forms_Files::remove_upload_filter();
    }

	/** @inheritdoc */
    public function scheduleFileDelete($fieldId,$formId,$file)
	{
		return \Engage_Forms_Files::schedule_delete($fieldId,$formId,$file);
	}

	/** @inheritdoc */
	public function isFileTooLarge(array $field, $filePath)
	{
		return \Engage_Forms_Files::is_file_too_large($field,$filePath);
	}
}