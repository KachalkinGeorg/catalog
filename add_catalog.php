<?php
/*
=====================================================
 Каталог для новости
-----------------------------------------------------
 Author: KachalkinGeorg
-----------------------------------------------------
 E-mail: KachalkinGeorg@yandex.ru
=====================================================
 © GK
-----------------------------------------------------
 Данный код защищен авторскими правами
=====================================================
*/

if (!defined('NGCMS'))
	exit('HAL');

class CatalogNewsFilter extends NewsFilter {
	
	function addNewsForm(&$tvars) {
		global $twig, $lang, $mysql;

		$row = $mysql->record("select * from " . prefix . "_news where id = " . db_squote($newsID));

        $ttvars = array(
            'localPrefix' => localPrefix,
			'admin_url' => admin_url,
			'catalog' => $row["catalog"],
            );

		$extends = 'additional';

		$tpath = locatePluginTemplates(array('catalog'), 'catalog', pluginGetVariable('catalog', 'localsource'));
        $tvars['extends'][$extends][] = [
			'title' => 'Каталог',
			'body' => $twig->render($tpath['catalog'].'/catalog.tpl', $ttvars),
			];

		return 1;


	}
	
	function addNews(&$tvars, &$SQL) {
		
		$SQL['catalog'] = intval($_REQUEST['catalog']);
		
		return 1;
	}
	
	function addNewsNotify(&$tvars, $SQL, $newsid) {
		global $mysql;
		
		$catalog = secure_html($_REQUEST['catalog']);
		$mysql->query("update " . prefix . "_news set catalog = " . db_squote($catalog) . " where id = " . intval($newsID));
		
	}
	
	function editNewsForm($newsID, $SQLold, &$tvars){
		global $mysql, $twig, $lang;
	
		$row = $mysql->record("select * from " . prefix . "_news where id = " . $newsID);
				
        $ttvars = array(
            'localPrefix' => localPrefix,
			'admin_url' => admin_url,
			'catalog' => $row["catalog"],
            );

		$extends = 'additional';

		$tpath = locatePluginTemplates(array('catalog'), 'catalog', pluginGetVariable('catalog', 'localsource'));
        $tvars['extends'][$extends][] = [
			'title' => 'Каталог',
			'body' => $twig->render($tpath['catalog'].'/catalog.tpl', $ttvars),
			];
	}
	
	function editNews($newsID, $SQLold, &$SQLnew, &$tvars)
	{global $mysql, $config;
	
		$catalog = isset($_REQUEST['catalog'])?secure_html($_REQUEST['catalog']):'';
		
		$mysql->query("update " . prefix . "_news set catalog = " . db_squote($catalog) . " where id = " . intval($newsID));

		return true;
	}
}

register_filter('news','catalog', new CatalogNewsFilter);