<?php
/**
 * @package   Essential_Grid
 * @author    ThemePunch <info@themepunch.com>
 * @link      http://www.themepunch.com/essential/
 * @copyright 2022 ThemePunch
 */

if( !defined( 'ABSPATH') ) exit();

class Essential_Grid_Rightclick_Addon
{
	protected $_handle = 'esg-rightclick-addon';

	public function __construct()
	{
	}

	public function get_handle()
	{
		return $this->_handle;
	}

	/**
	 * addon is missing if original rightclick option still in database
	 *
	 * @return bool
	 */
	public function is_missing()
	{
		$esg_addons = Essential_Grid_Addons::instance();
		$addons = $esg_addons->get_addons_list();
		$options = get_option('tp_eg_rightclick', array());

		if (empty($options) || !is_array($options) || !isset($options['rightclick-enabled']) || $options['rightclick-enabled'] !== 'true') return false;
		
		if (empty($addons[$this->_handle]) || !$addons[$this->_handle]->installed || !$addons[$this->_handle]->active) return true;

		return false;
	}
}
