<?php
if (!defined('NGCMS'))
{
	die ('HAL');
}

LoadPluginLang('catalog', 'config', '', '', '#');

include_once(root . "/plugins/catalog/lib/common.php");

pluginsLoadConfig();
function plugin_catalog_install($action) {
	global $lang, $mysql;
	
	if ($action != 'autoapply')
	
	$db_update = array(
	array(
		'table' => 'news',
		'action' => 'modify',
		'key' => 'primary key (`id`)',
		'fields' => array(
			array('action' => 'cmodify', 'name' => 'catalog', 'type' => 'varchar(3)', 'params' => 'NOT NULL')
		)
	),
	);
	
	switch ($action) {
		case 'confirm':
			generate_install_page('catalog', $lang['catalog']['install']);
			break;
		case 'autoapply':
		case 'apply':
			if (fixdb_plugin_install('catalog', $db_update, 'install', ($action == 'autoapply') ? true : false)) {
				plugin_mark_installed('catalog');
				create_catalog_urls();
			} else {
				return false;
			}
			
            extra_commit_changes();
			
			break;
	}

	return true;
}