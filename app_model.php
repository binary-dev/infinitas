<?php
	/**
	* Comment Template.
	*
	* @todo Implement .this needs to be sorted out.
	*
	* Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	*
	* Licensed under The MIT License
	* Redistributions of files must retain the above copyright notice.
	* @filesource
	* @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	* @link http://infinitas-cms.org
	* @package sort
	* @subpackage sort.comments
	* @license http://www.opensource.org/licenses/mit-license.php The MIT License
	* @since 0.5a
	*/

	class AppModel extends Model {
		/**
		* The database configuration to use for the site.
		*/
		var $useDbConfig = 'default';

		//var $tablePrefix = 'core_';

		/**
		* Behaviors to attach to the site.
		*/
		var $actsAs = array(
			'Containable',
			'Libs.Infinitas',
			'Events.Event',
			'Libs.Logable',
			'DebugKit.Timed'

			//'Libs.AutomaticAssociation'
		);

		var $recursive = -1;

		/**
		* error messages in the model
		*/
		var $_errors = array();

		/**
		 * @var string Plugin that the model belongs to.
		 */
		var $plugin = null;

		function __construct($id = false, $table = null, $ds = null) {
			parent::__construct($id, $table, $ds);

			if (isset($this->_schema) && is_array($this->_schema)) {
				if (array_key_exists('locked', $this->_schema)) {
					$this->Behaviors->attach('Libs.Lockable');
				}

				if (array_key_exists('deleted', $this->_schema)) {
					$this->Behaviors->attach('Libs.SoftDeletable');
				}

				if (array_key_exists('slug', $this->_schema)) {
					$this->Behaviors->attach('Libs.Sluggable');
				}

				if (array_key_exists('lft', $this->_schema) && array_key_exists('rght', $this->_schema)) {
					$this->Behaviors->attach('Tree');
				}

				if (array_key_exists('ordering', $this->_schema)) {
					$this->Behaviors->attach('Libs.Sequence');
				}

				if (array_key_exists('rating', $this->_schema)) {
					$this->Behaviors->attach('Libs.Rateable');
				}

				$this->__getPlugin();
			}
		}

		/**
		 *
		 * @return string Name of the model in the form of Plugin.Name. Usefull for polymorphic relations.
		 */
		function modelName() {
			if($this->plugin == null) {
				$this->__getPlugin();
			}

			return $this->plugin == null ? $this->name : $this->plugin . '.' . $this->name;
		}

		private function __getPlugin() {
			$parentName = get_parent_class($this);

			if($parentName !== 'AppModel' && $parentName !== 'Model' && strpos($parentName, 'AppModel') !== false) {
				$this->plugin = str_replace('AppModel', '', $parentName);
			}
		}
	}