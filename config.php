<?php

# protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

pluginsLoadConfig();
LoadPluginLang('catalog', 'config', '', '', '#');

switch ($_REQUEST['action']) {
	case 'about':			about();		break;
	default: main();
}

function about()
{global $twig, $lang, $breadcrumb;
	$tpath = locatePluginTemplates(array('main', 'about'), 'catalog', 1);
	$breadcrumb = breadcrumb('<i class="fa fa-sort-alpha-desc btn-position"></i><span class="text-semibold">'.$lang['catalog']['catalog'].'</span>', array('?mod=extras' => '<i class="fa fa-puzzle-piece btn-position"></i>'.$lang['extras'].'', '?mod=extra-config&plugin=catalog' => '<i class="fa fa-sort-alpha-desc btn-position"></i>'.$lang['catalog']['catalog'].'',  '<i class="fa fa-sort-alpha-desc btn-position"></i>'.$lang['catalog']['about'].'' ) );

	$xt = $twig->loadTemplate($tpath['about'].'about.tpl');
	$tVars = array();
	$xg = $twig->loadTemplate($tpath['main'].'main.tpl');
	
	$about = 'версия 0.1';
	
	$tVars = array(
		'global' => $lang['catalog']['about'],
		'header' => $about,
		'entries' => $xt->render($tVars)
	);
	
	print $xg->render($tVars);
}

function main()
{global $twig, $lang, $breadcrumb;
	
	$tpath = locatePluginTemplates(array('main', 'general.from'), 'catalog', 1);
	$breadcrumb = breadcrumb('<i class="fa fa-sort-alpha-desc btn-position"></i><span class="text-semibold">'.$lang['catalog']['catalog'].'</span>', array('?mod=extras' => '<i class="fa fa-puzzle-piece btn-position"></i>'.$lang['extras'].'', '?mod=extra-config&plugin=catalog' => '<i class="fa fa-sort-alpha-desc btn-position"></i>'.$lang['catalog']['catalog'].'' ) );

	if (isset($_REQUEST['submit'])){
		pluginSetVariable('catalog', 'limit_page', intval($_REQUEST['limit_page']));
		pluginSetVariable('catalog', 'separator', $_REQUEST['separator']);
		pluginSetVariable('catalog', 'localsource', (int)$_REQUEST['localsource']);
		pluginsSaveConfig();
		msg(array("type" => "info", "info" => $lang['catalog']['save']));
		return print_msg( 'info', $lang['catalog']['catalog'], $lang['catalog']['save'], 'javascript:history.go(-1)' );
	}
	
	$xt = $twig->loadTemplate($tpath['general.from'].'general.from.tpl');
	$xg = $twig->loadTemplate($tpath['main'].'main.tpl');
	
	$limit_page = pluginGetVariable('catalog', 'limit_page');
	$separator = pluginGetVariable('catalog', 'separator');
	
	$tVars = array(
		'limit_page'   	=> $limit_page,
		'separator'   	=> $separator,
		'localsource'   => MakeDropDown(array(0 => 'Шаблон сайта', 1 => 'Плагина'), 'localsource', (int)pluginGetVariable('catalog', 'localsource')),
	);
	
	$tVars = array(
		'global' => $lang['catalog']['common'],
		'header' => '<a href="?mod=extra-config&plugin=catalog&action=about">'.$lang['catalog']['about'].'</a>',
		'entries' => $xt->render($tVars)
	);
	
	print $xg->render($tVars);
}
