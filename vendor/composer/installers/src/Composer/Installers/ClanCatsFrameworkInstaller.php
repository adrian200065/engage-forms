<?php
namespace Composer\Installers;

class ClanCatsFrameworkInstaller extends BaseInstaller
{
	protected $locations = array(
		'ship'      => 'CEF/orbit/{$name}/',
		'theme'     => 'CEF/app/themes/{$name}/',
	);
}