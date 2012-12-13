<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Installer
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

jimport('joomla.base.adapterinstance');

/**
 * Module installer
 *
 * @package     Joomla.Platform
 * @subpackage  Installer
 * @since       11.1
 */
class NewsletterClassExtensionAdapterPlugin extends JAdapterInstance
{
	
	/**
	 * Install function routing
	 *
	 * @var    string
	 * @since 11.1
	 */
	protected $route = 'Install';

	public    $adapterName = 'newsletter_plugin';
	
	/**
	 * @var
	 * @since 11.1
	 */
	protected $manifest = null;

	/**
	 * @var
	 * @since 11.1
	 */
	protected $manifest_script = null;

	/**
	 * Extension name
	 *
	 * @var
	 * @since   11.1
	 */
	protected $name = null;

	/**
	 * @var
	 * @since  11.1
	 */
	protected $element = null;

	/**
	 * @var    string
	 * @since 11.1
	 */
	protected $scriptElement = null;

	protected $_extPath = '';
	
	public function __construct(&$parent, &$db, $options = array()) {
		
		parent::__construct($parent, $db, $options);
		
		$this->_extPath = 
			!empty($options['extPath'])? $options['extPath'] : 
			
			JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 
			'components' . DIRECTORY_SEPARATOR . 
			'com_newsletter' . DIRECTORY_SEPARATOR . 
			'extensions' . DIRECTORY_SEPARATOR . 
			'plugins';
	}
	
	
	/**
	 * Custom install method
	 *
	 * @return  boolean  True on success
	 *
	 * @since   11.1
	 */
	public function install()
	{
		// Get a database connector object
		$db = $this->parent->getDbo();

		// Get the extension manifest object
		$this->manifest = $this->parent->getManifest();

		// Manifest Document Setup Section

		// Set the extensions name
		$name = (string) $this->manifest->name;
		
		$namespace = (string) $this->manifest->namespace;
		if (empty($namespace)) { 
                    $this->parent->abort(JText::sprintf('COM_NEWSLETTER_INSTALLER_ABORT_PLG_INSTALL_NONAMESPACE'));
                    return false;
		}
		
		$folder = explode('.', $namespace);
		$folder = $folder[0];
		
		$name = JFilterInput::getInstance()->clean($name, 'string');
		$this->set('name', $name);

		// Get the component description
		$description = (string) $this->manifest->description;
		if ($description)
		{
			$this->parent->set('message', JText::_($description));
		}
		else
		{
			$this->parent->set('message', '');
		}

		// No client attribute was found so we assume the site as the client
		$cname = 'admin';
		$clientId = 1;

		// Set the installation path
		$element = '';
		if (count($this->manifest->files->children()))
		{
			foreach ($this->manifest->files->children() as $file)
			{
				if ((string) $file->attributes()->plugin)
				{
					$element = (string) $file->attributes()->plugin;
					$this->set('element', $element);

					break;
				}
			}
		}
		if (!empty($element))
		{
			$this->parent->setPath('extension_root', $this->_extPath . DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR . $element);
		}
		else
		{
			$this->parent->abort(JText::sprintf('JLIB_INSTALLER_ABORT_MOD_INSTALL_NOFILE', JText::_('JLIB_INSTALLER_' . $this->route)));

			return false;
		}

		// Check to see if a module by the same name is already installed
		// If it is, then update the table because if the files aren't there
		// we can assume that it was (badly) uninstalled
		// If it isn't, add an entry to extensions
		$query = $db->getQuery(true);
		$query->select($query->qn('extension_id'))->from($query->qn('#__newsletter_extensions'));
		$query
			->where($query->qn('extension') . ' = ' . $query->q($element))
			->where($query->qn('namespace') . ' = ' . $query->q($namespace))
			->where($query->qn('type') . ' = ' . 2);
		$db->setQuery($query);

		try
		{
			$db->Query();
		}
		catch (JException $e)
		{
			// Install failed, roll back changes
			$this->parent
				->abort(JText::sprintf('JLIB_INSTALLER_ABORT_MOD_ROLLBACK', JText::_('JLIB_INSTALLER_' . $this->route), $db->stderr(true)));

			return false;
		}

		$id = $db->loadResult();

		// If the module directory already exists, then we will assume that the
		// module is already installed or another module is using that
		// directory.
		// Check that this is either an issue where its not overwriting or it is
		// set to upgrade anyway

		if (file_exists($this->parent->getPath('extension_root')) && (!$this->parent->getOverwrite() || $this->parent->getUpgrade()))
		{
			// Look for an update function or update tag
			$updateElement = $this->manifest->update;
			// Upgrade manually set or
			// Update function available or
			// Update tag detected
			if ($this->parent->getUpgrade() || ($this->parent->manifestClass && method_exists($this->parent->manifestClass, 'update'))
				|| is_a($updateElement, 'JXMLElement'))
			{
				// Force this one
				$this->parent->setOverwrite(true);
				$this->parent->setUpgrade(true);

				if ($id)
				{
					// If there is a matching extension mark this as an update; semantics really
					$this->route = 'Update';
				}
			}
			elseif (!$this->parent->getOverwrite())
			{
				// Overwrite is set
				// We didn't have overwrite set, find an update function or find an update tag so lets call it safe
				$this->parent
					->abort(
					JText::sprintf(
						'JLIB_INSTALLER_ABORT_MOD_INSTALL_DIRECTORY', JText::_('JLIB_INSTALLER_' . $this->route),
						$this->parent->getPath('extension_root')
					)
				);

				return false;
			}
		}

		// Installer Trigger Loading

		// If there is an manifest class file, let's load it; we'll copy it later (don't have destination yet)
		$this->scriptElement = $this->manifest->scriptfile;
		$manifestScript = (string) $this->manifest->scriptfile;

		if ($manifestScript)
		{
			$manifestScriptFile = $this->parent->getPath('source') . '/' . $manifestScript;

			if (is_file($manifestScriptFile))
			{
				// Load the file
				include_once $manifestScriptFile;
			}

			// Set the class name
			$classname = $element . 'InstallerScript';

			if (class_exists($classname))
			{
				// Create a new instance.
				$this->parent->manifestClass = new $classname($this);
				// And set this so we can copy it later.
				$this->set('manifest_script', $manifestScript);

				// Note: if we don't find the class, don't bother to copy the file.
			}
		}

		// Run preflight if possible (since we know we're not an update)
//		ob_start();
//		ob_implicit_flush(false);
//
//		if ($this->parent->manifestClass && method_exists($this->parent->manifestClass, 'preflight'))
//		{
//			if ($this->parent->manifestClass->preflight($this->route, $this) === false)
//			{
//				// Install failed, rollback changes
//				$this->parent->abort(JText::_('JLIB_INSTALLER_ABORT_MOD_INSTALL_CUSTOM_INSTALL_FAILURE'));
//
//				return false;
//			}
//		}
//
//		// Create msg object; first use here
//		$msg = ob_get_contents();
//		ob_end_clean();

		// Filesystem Processing Section

		// If the module directory does not exist, lets create it
		$created = false;
		if (!file_exists($this->parent->getPath('extension_root')))
		{
			
			if (!$created = JFolder::create($this->parent->getPath('extension_root')))
			{
				$this->parent
					->abort(
					JText::sprintf(
						'JLIB_INSTALLER_ABORT_MOD_INSTALL_CREATE_DIRECTORY', JText::_('JLIB_INSTALLER_' . $this->route),
						$this->parent->getPath('extension_root')
					)
				);

				return false;
			}
		}

		// Since we created the module directory and will want to remove it if
		// we have to roll back the installation, let's add it to the
		// installation step stack

		if ($created)
		{
			$this->parent->pushStep(array('type' => 'folder', 'path' => $this->parent->getPath('extension_root')));
		}

		// Copy all necessary files
		if ($this->parent->parseFiles($this->manifest->files, -1) === false)
		{
			// Install failed, roll back changes
			$this->parent->abort();

			return false;
		}

		// If there is a manifest script, let's copy it.
		if ($this->get('manifest_script'))
		{
			$path['src'] = $this->parent->getPath('source') . '/' . $this->get('manifest_script');
			$path['dest'] = $this->parent->getPath('extension_root') . '/' . $this->get('manifest_script');

			if (!file_exists($path['dest']) || $this->parent->getOverwrite())
			{
				if (!$this->parent->copyFiles(array($path)))
				{
					// Install failed, rollback changes
					$this->parent->abort(JText::_('JLIB_INSTALLER_ABORT_MOD_INSTALL_MANIFEST'));

					return false;
				}
			}
		}

		// Parse optional tags
		$this->parent->parseMedia($this->manifest->media, $clientId);
		$this->parent->parseLanguages($this->manifest->languages, $clientId);

		// Parse deprecated tags
		$this->parent->parseFiles($this->manifest->images, -1);

		// Database Processing Section
		$row = JTable::getInstance('NExtension', 'NewsletterTable');

		// Was there a module already installed with the same name?
		if ($id)
		{
			// Load the entry and update the manifest_cache
			$row->load($id);
			$row->title = $this->get('name'); // update name
			$row->set('params', $this->parent->getParams());

			if (!$row->store())
			{
				// Install failed, roll back changes
				$this->parent
					->abort(JText::sprintf('JLIB_INSTALLER_ABORT_MOD_ROLLBACK', JText::_('JLIB_INSTALLER_' . $this->route), $db->stderr(true)));

				return false;
			}
		}
		else
		{
			$row->set('title', $this->get('name'));
			$row->set('extension', $this->get('element'));
			$row->set('type', '2');
			$row->set('params', $this->parent->getParams());
			$row->set('namespace', $namespace);

			if (!$row->store())
			{
				// Install failed, roll back changes
				$this->parent
					->abort(JText::sprintf('JLIB_INSTALLER_ABORT_MOD_ROLLBACK', JText::_('JLIB_INSTALLER_' . $this->route), $db->stderr(true)));
				return false;
			}

			// Set the insert id
			$row->extension_id = $db->insertid();

			// Since we have created a module item, we add it to the installation step stack
			// so that if we have to rollback the changes we can undo it.
			$this->parent->pushStep(array('type' => 'extension', 'extension_id' => $row->extension_id));
		}

		// Let's run the queries for the module
		// If Joomla 1.5 compatible, with discrete sql files, execute appropriate
		// file for utf-8 support or non-utf-8 support

		// Start Joomla! 1.6
//		ob_start();
//		ob_implicit_flush(false);
//
//		if ($this->parent->manifestClass && method_exists($this->parent->manifestClass, $this->route))
//		{
//			if ($this->parent->manifestClass->{$this->route}($this) === false)
//			{
//				// Install failed, rollback changes
//				$this->parent->abort(JText::_('JLIB_INSTALLER_ABORT_MOD_INSTALL_CUSTOM_INSTALL_FAILURE'));
//
//				return false;
//			}
//		}
//
//		// Append messages
//		$msg .= ob_get_contents();
//		ob_end_clean();

		// Finalization and Cleanup Section

		// Lastly, we will copy the manifest file to its appropriate place.
		if (!$this->parent->copyManifest(-1))
		{
			// Install failed, rollback changes
			$this->parent->abort(JText::_('JLIB_INSTALLER_ABORT_MOD_INSTALL_COPY_SETUP'));

			return false;
		}

		// And now we run the postflight
//		ob_start();
//		ob_implicit_flush(false);
//
//		if ($this->parent->manifestClass && method_exists($this->parent->manifestClass, 'postflight'))
//		{
//			$this->parent->manifestClass->postflight($this->route, $this);
//		}
//
//		// Append messages
//		$msg .= ob_get_contents();
//		ob_end_clean();
//
//		if ($msg != '')
//		{
//			$this->parent->set('extension_message', $msg);
//		}

		return $row->get('extension_id');
	}

	/**
	 * Custom update method
	 *
	 * This is really a shell for the install system
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   11.1
	 */
	public function update()
	{
		// Set the overwrite setting
		$this->parent->setOverwrite(true);
		$this->parent->setUpgrade(true);
		// Set the route for the install
		$this->route = 'Update';

		// Go to install which handles updates properly
		return $this->install();
	}


	/**
	 * Custom uninstall method
	 *
	 * @param   integer  $id  The id of the module to uninstall
	 *
	 * @return  boolean  True on success
	 *
	 * @since   11.1
	 */
	public function uninstall($id)
	{
		// Initialise variables.
		$row = null;
		$retval = true;
		$db = $this->parent->getDbo();

		// First order of business will be to load the module object table from the database.
		// This should give us the necessary information to proceed.
		$row = JTable::getInstance('NExtension', 'NewsletterTable');

		if (!$row->load((int) $id) || !strlen($row->extension))
		{
			JError::raiseWarning(100, JText::_('JLIB_INSTALLER_ERROR_MOD_UNINSTALL_ERRORUNKOWNEXTENSION'));
			return false;
		}

		// Is the module we are trying to uninstall a core one?
		// Because that is not a good idea...
		if ($row->protected)
		{
			JError::raiseWarning(100, JText::sprintf('JLIB_INSTALLER_ERROR_MOD_UNINSTALL_WARNCOREMODULE', $row->name));
			return false;
		}

		// Get the extension root path
		$element = $row->extension;
		$namespace = $row->namespace;
		
		if (empty($namespace)) { 
                    $this->parent->abort(JText::sprintf('COM_NEWSLETTER_INSTALLER_ABORT_PLG_INSTALL_NONAMESPACE'));
                    return false;
		}
		
		$folder = explode('.', $namespace);
		$folder = $folder[0];
		
		$client = 1;

		$this->parent->setPath('extension_root', $this->_extPath . DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR . $element);
		
		$this->parent->setPath('source', $this->parent->getPath('extension_root'));

		// Get the package manifest objecct
		// We do findManifest to avoid problem when uninstalling a list of extensions: getManifest cache its manifest file.
		$this->parent->findManifest();
		$this->manifest = $this->parent->getManifest();

		// Attempt to load the language file; might have uninstall strings
		//$this->loadLanguage(($row->client_id ? JPATH_ADMINISTRATOR : JPATH_SITE)  . DIRECTORY_SEPARATOR . 'extensions' . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR . $element);

		// If there is an manifest class file, let's load it
//		$this->scriptElement = $this->manifest->scriptfile;
//		$manifestScript = (string) $this->manifest->scriptfile;
//
//		if ($manifestScript)
//		{
//			$manifestScriptFile = $this->parent->getPath('extension_root') . '/' . $manifestScript;
//
//			if (is_file($manifestScriptFile))
//			{
//				// Load the file
//				include_once $manifestScriptFile;
//			}
//
//			// Set the class name
//			$classname = $element . 'InstallerScript';
//
//			if (class_exists($classname))
//			{
//				// Create a new instance
//				$this->parent->manifestClass = new $classname($this);
//				// And set this so we can copy it later
//				$this->set('manifest_script', $manifestScript);
//
//				// Note: if we don't find the class, don't bother to copy the file
//			}
//		}
//
//		ob_start();
//		ob_implicit_flush(false);
//
//		// Run uninstall if possible
//		if ($this->parent->manifestClass && method_exists($this->parent->manifestClass, 'uninstall'))
//		{
//			$this->parent->manifestClass->uninstall($this);
//		}
//
//		$msg = ob_get_contents();
//		ob_end_clean();

		if (!($this->manifest instanceof JXMLElement))
		{
			// Make sure we delete the folders
			JFolder::delete($this->parent->getPath('extension_root'));
			JError::raiseWarning(100, JText::_('JLIB_INSTALLER_ERROR_MOD_UNINSTALL_INVALID_NOTFOUND_MANIFEST'));

			return false;
		}

		/*
		 * Let's run the uninstall queries for the component
		 *	If Joomla 1.5 compatible, with discreet sql files - execute appropriate
		 *	file for utf-8 support or non-utf support
		 */
		// Try for Joomla 1.5 type queries
		// Second argument is the utf compatible version attribute
//		$utfresult = $this->parent->parseSQLFiles($this->manifest->uninstall->sql);
//
//		if ($utfresult === false)
//		{
//			// Install failed, rollback changes
//			JError::raiseWarning(100, JText::sprintf('JLIB_INSTALLER_ERROR_MOD_UNINSTALL_SQL_ERROR', $db->stderr(true)));
//			$retval = false;
//		}
//
//		// Remove the schema version
//		$query = $db->getQuery(true);
//		$query->delete()->from('#__schemas')->where('extension_id = ' . $row->extension_id);
//		$db->setQuery($query);
//		$db->Query();

		// Remove other files
		$this->parent->removeFiles($this->manifest->media);
		$this->parent->removeFiles($this->manifest->languages, $row->client_id);

		// Let's delete all the module copies for the type we are uninstalling
		$query = $db->getQuery(true);
		$query->select($query->qn('newsletters_ext_id'))->from($query->qn('#__newsletter_newsletters_ext'));
		$query->where($query->qn('extension_id') . ' = ' . $query->q($row->extension_id));
		$db->setQuery($query);

		try
		{
			$modules = $db->loadColumn();
		}
		catch (JException $e)
		{
			$modules = array();
		}

		// Do we have any module copies?
		if (count($modules))
		{
			// Ensure the list is sane
			JArrayHelper::toInteger($modules);
			$modID = implode(',', $modules);

			// Wipe out any instances in the modules table
			$query = 'DELETE' . ' FROM #__newsletter_newsletters_ext' . ' WHERE extension_id IN (' . $modID . ')';
			$db->setQuery($query);

			try
			{
				$db->query();
			}
			catch (JException $e)
			{
				JError::raiseWarning(100, JText::sprintf('JLIB_INSTALLER_ERROR_MOD_UNINSTALL_EXCEPTION', $db->stderr(true)));
				$retval = false;
			}
		}

		// Now we will no longer need the module object, so let's delete it and free up memory
		$row->delete($row->extension_id);
		$query = 
			'DELETE FROM #__newsletter_newsletters_ext' .
			' WHERE extension_id = ' . $db->Quote($row->extension_id) .	' AND native = 0';
		$db->setQuery($query);
		$db->Query($query);
		
		// Now we will no longer need the module object, so let's delete it and free up memory
		$row->delete($row->extension_id);
		$query = 'DELETE FROM #__newsletter_extensions WHERE extension_id = ' . $db->Quote($row->extension_id);
		$db->setQuery($query);
		$db->Query();
		
		unset($row);

		// Remove the installation folder
		if (!JFolder::delete($this->parent->getPath('extension_root')))
		{
			// JFolder should raise an error
			$retval = false;
		}

		return $retval;
	}

	
	/**
	 * Custom discover method
	 *
	 * @return  array  JExtension list of extensions available
	 *
	 * @since   11.1
	 */
	public function discover()
	{
		$results = array();
		$list = JFolder::folders($this->_extPath);
		$admin_info = JApplicationHelper::getClientInfo('administrator', true);

		foreach($list as $folder) {
			foreach(JFolder::folders("$this->_extPath/$folder") as $plugin) {
				$admin_list[$plugin] = "$this->_extPath/$folder/$plugin";
			}	
		}
		
		foreach ($admin_list as $plugin => $pluginpath)
		{
			if ($xml = $this->parent->isManifest("$pluginpath/$plugin.xml")) {
			
				$this->parent->setPath('source', $pluginpath);
				$this->parent->findManifest();
				
				$extension = JTable::getInstance('NExtension', 'NewsletterTable');
				$extension->set('title', (string) $xml->name);
				$extension->set('extension',  $plugin);
				$extension->set('params', $this->parent->getParams());
				$extension->set('type', '2');
				$extension->set('namespace', (string) $xml->namespace);
				$results[] = clone $extension;
			}	
		}

		return $results;
	}
}
