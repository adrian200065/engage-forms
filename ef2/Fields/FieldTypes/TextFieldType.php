<?php


namespace engagewp\engageforms\ef2\Fields\FieldTypes;


use engagewp\engageforms\ef2\Fields\FieldType;

class TextFieldType extends FieldType
{

    /** @inheritdoc */
    public static function getType()
    {
        return 'text';
    }
    /** @inheritdoc */
    public static function getCf1Identifier()
    {
        return 'ef2_text';
    }

	/** @inheritdoc */
    public static function getCategory ()
	{
		return __( 'Basic', 'engage-forms' );
	}

	/** @inheritdoc */
	public static function getDescription ()
	{
		return __( 'Text Field With Super Powers', 'engage-forms' );
	}

	/** @inheritdoc */
	public static function getName ()
	{
		__( 'Text Field (EF2)', 'engage-forms' );
	}
}
