<?php
/**
 * @package   Essential_Grid
 * @author    ThemePunch <info@themepunch.com>
 * @link      http://www.themepunch.com/essential/
 * @copyright 2022 ThemePunch
 */

if( !defined( 'ABSPATH') ) exit();

class Essential_Grid_Mediafilters_Addon
{
	protected $_handle = 'esg-mediafilters-addon';

	public function __construct()
	{
	}

	public function get_handle()
	{
		return $this->_handle;
	}

	public function get_options()
	{
		return get_option($this->_handle . '_options', array());
	}

	/**
	 * addon is missing if it is used in grids and not installed or activated
	 *
	 * @return bool
	 */
	public function is_missing()
	{
		$esg_addons = Essential_Grid_Addons::instance();
		$addons = $esg_addons->get_addons_list();
		$options = $this->get_options();

		if (!empty($addons[$this->_handle]) && $addons[$this->_handle]->installed && $addons[$this->_handle]->active) return false;

		//get grids and check mediafilters status
		$grids = new Essential_Grid();
		$arrGrids = $grids->get_essential_grids(false, false);
		foreach ($arrGrids as $grid) {
			if (isset($grid->params['addons'][$this->_handle])) return true;
		}

		return false;
	}
}
