<?php
// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

LoadPluginLang('catalog', 'config', '', '', '#');

include_once(root . "/plugins/catalog/lib/common.php");

$db_update = array(
	array(
		'table'  => 'news',
		'action' => 'modify',
		'fields' => array(
			array('action' => 'drop', 'name' => 'catalog'),
		)
	)
);

if ($_REQUEST['action'] == 'commit') {
	if (fixdb_plugin_install('catalog', $db_update, 'deinstall')) {
		plugin_mark_deinstalled('catalog');
	}
	remove_catalog_urls();
} else {
	generate_install_page('catalog', $lang['catalog']['uninstall'], 'deinstall');
}