<?php


namespace engagewp\engageforms\ef2\Fields\FieldTypes;


use engagewp\engageforms\ef2\Fields\FieldType;

class FileFieldType extends FieldType
{

    /** @inheritdoc */
    public static function getType()
    {
        return 'file';
    }
    /** @inheritdoc */
    public static function getCf1Identifier()
    {
        return 'ef2_file';
    }
	/** @inheritdoc */
	public static function getCategory ()
	{
		return __( 'File', 'engage-forms' );
	}

	/** @inheritdoc */
	public static function getDescription ()
	{
		return __( 'File upload field with more features than standard HTML5 input.', 'engage-forms' );
	}

	/** @inheritdoc */
	public static function getName ()
	{
		return __( 'Advanced File Uploader (2.0)', 'engage-forms' );
	}

	/** @inheritdoc */
	public static function getIcon()
	{
		return engage_forms_get_v2_container()->getCoreUrl() . 'assets/images/cloud-upload.svg';
	}
}
