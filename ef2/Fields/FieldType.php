<?php


namespace engagewp\engageforms\ef2\Fields;


abstract class FieldType implements FieldTypeContract
{

	/** @inheritdoc */
	public static function toArray ()
	{
		return [
			'ef2' => TRUE,
			'field' => static::getName(),
			'description' => static::getDescription(),
			'category' => static::getCategory(),
			'setup' => static::getSetup(),
			'icon' => static ::getIcon(),
		];
	}

	/** @inheritdoc */
	public static function getSetup ()
	{
		return [
			'template' => 'fields/' . static::getCf1Identifier() . '/config.php',
			'preview' => 'fields/' . static::getCf1Identifier() . '/preview.php',
		];
	}

	/** @inheritdoc */
	public static function getIcon()
	{
		return engage_forms_get_v2_container()->getCoreUrl() . 'assets/build/images/field.png';
	}
}
