<?php
/*
=====================================================
 Символьный каталог
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

add_act('index', 'catalog_main');
register_plugin_page('catalog','id','catalog_url');

LoadPluginLang('catalog', 'config', '', '', '#');

function catalog_main() {
	global $lang, $template, $mysql, $twig, $userROW;
	
    $tpath = locatePluginTemplates(array('skins/catalog_symbol', ':'), 'catalog', pluginGetVariable('catalog', 'localsource'), pluginGetVariable('catalog','localskin'));
    $xt = $twig->loadTemplate($tpath['skins/catalog_symbol'].'skins/catalog_symbol.tpl');
	
	$separator = pluginGetVariable('catalog', 'separator') ? pluginGetVariable('catalog', 'separator') : ' | ';
	
	$catalog_ru = explode(" ", "А Б В Г Д Е Ж З И К Л М Н О П Р С Т У Ф Х Ц Ч Ш Щ Ы Э Ю Я");
	foreach($catalog_ru as $k=>$v){
		$catalog_ru[$k] = "<li><a href=\"/catalog/{$v}/\" class=\"symbol_ru\" title=\"".$lang['catalog']['news_let']." {$v}\" target=\"_self\"><span>{$v}</span></a></li>";
	}
	$catalog_ru = implode(' '.$separator.' ', $catalog_ru);

	$catalog_en = explode(" ", "A B C D E F G H I J K L M N O P Q R S T U V W X Y Z");
	foreach($catalog_en as $k=>$v){
		$catalog_en[$k] = "<li><a href=\"/catalog/{$v}/\" class=\"symbol_en\" title=\"".$lang['catalog']['news_let']." {$v}\" target=\"_self\"><span>{$v}</span></a></li>";
	}
	$catalog_en = implode(' '.$separator.' ', $catalog_en);

	$catalog_kod = explode(" ", "0 1 2 3 4 5 6 7 8 9");
	foreach($catalog_kod as $k=>$v){
		$catalog_kod[$k] = "<li><a href=\"/catalog/{$v}/\" class=\"symbol_kod\" title=\"".$lang['catalog']['news_kod']." {$v}\" target=\"_self\"><span>{$v}</span></a></li>";
	}
	$catalog_kod = implode(' '.$separator.' ', $catalog_kod);
	
	$tVars = array(
		'catalog_ru' 	=> $catalog_ru,
		'catalog_en' 	=> $catalog_en,
		'catalog_kod' 	=> $catalog_kod,
		'css'         	=> $tpath['url::'],
	);

	$template['vars']['catalog_symbol'] = $xt->render($tVars);
}

function catalog_url($params) {
	global $tpl, $template, $twig, $mysql, $SYSTEM_FLAGS, $config, $userROW, $newsID, $lang, $CurrentHandler, $callingParams;
	
    $tpath = locatePluginTemplates(array('skins/catalog', 'skins/catalog_entry', ':'), 'catalog', pluginGetVariable('catalog', 'localsource'), pluginGetVariable('catalog','localskin'));
    $xt = $twig->loadTemplate($tpath['skins/catalog'].'skins/catalog.tpl');

	$symbol = isset($params['id'])?$params['id']:$CurrentHandler['params']['id'];

    $limitCount = pluginGetVariable('catalog', 'limit_page') ? pluginGetVariable('catalog', 'limit_page') : '5';

    $pageNo		= intval($params['page'])?intval($params['page']):intval($_REQUEST['page']);
    if ($pageNo < 1)	$pageNo = 1;
    if (!$limitStart)	$limitStart = ($pageNo - 1)* $limitCount;

    $count = $mysql->result('SELECT COUNT(*) as count FROM '.prefix.'_news WHERE catalog = '. db_squote($symbol).' ');

    if(!$count)
        return msg(array("type" => "error", "text" => $lang['catalog']['not_symbol']));


	$SYSTEM_FLAGS['info']['title']['group'] = ''.$lang['catalog']['title'].' '.$symbol.' '.$pageNo.'';

    $countPages = ceil($count / $limitCount);

    if(!is_array($userROW))
        return msg(array("type" => "error", "info" => $lang['catalog']['not_login']));

        if ($countPages > 1 && $countPages >= $pageNo){
			$paginationParams = checkLinkAvailable('catalog', '') ?
				array('pluginName' => 'catalog', 'pluginHandler' => '', 'params' => array('id' => $symbol),  'xparams' => array(), 'paginator' => array('page', 0, false)) :
				array('pluginName' => 'core', 'pluginHandler' => 'plugin', 'params' => array('plugin' => 'catalog', 'handler' => ''), 'xparams' => array(), 'paginator' => array('page', 1, false));


            $navigations = LoadCatalogVariables();
            $pages = generatePagination($pageNo, 1, $countPages, 10, $paginationParams, $navigations);
        }
		
	include_once root . 'includes/news.php';

	foreach ($mysql->select('select * from '.prefix.'_news WHERE catalog = '. db_squote($symbol).' AND approve = 1 ORDER BY catalog ASC LIMIT '.intval($limitStart).', '.intval($limitCount)) as $row){
		$entries .= news_showone($newsID, '', array('overrideTemplateName' => 'skins/catalog_entry', 'overrideTemplatePath' => $tpath['skins/catalog_entry'], 'emulate' => $row, 'style' => 'export', 'plugin' => 'catalog'));
	}
	
	if ($limitStart) {
		$prev = floor($limitStart / $limitCount);
		$PageLink = checkLinkAvailable('catalog', '')?
			generatePageLink(array('pluginName' => 'catalog', 'pluginHandler' => '', 'params' => array('id' => $symbol), 'xparams' => array(), 'paginator' => array('page', 0, false)), $prev):
			generatePageLink(array('pluginName' => 'core', 'pluginHandler' => 'plugin', 'params' => array('plugin' => 'catalog', 'handler' => ''), 'xparams' => array(), 'paginator' => array('page', 1, false)), $prev);

		$gvars['regx']["'\[prev-link\](.*?)\[/prev-link\]'si"] = str_replace('%page%',"$1",str_replace('%link%',$PageLink, $navigations['prevlink']));
	} else {
		$gvars['regx']["'\[prev-link\](.*?)\[/prev-link\]'si"] = "";
		$prev = 0;
	}

	if (($prev + 2 <= $countPages)){
		$PageLink = checkLinkAvailable('catalog', '')?
			generatePageLink(array('pluginName' => 'catalog', 'pluginHandler' => '', 'params' => array('id' => $symbol), 'xparams' => array(), 'paginator' => array('page', 0, false)), $prev+2):
			generatePageLink(array('pluginName' => 'core', 'pluginHandler' => 'plugin', 'params' => array('plugin' => 'catalog', 'handler' => ''), 'xparams' => array(), 'paginator' => array('page', 1, false)), $prev+2);

			$gvars['regx']["'\[next-link\](.*?)\[/next-link\]'si"] = str_replace('%page%',"$1",str_replace('%link%',$PageLink, $navigations['nextlink']));
	} else {
		$gvars['regx']["'\[next-link\](.*?)\[/next-link\]'si"] = "";
	}
	
	$separator = pluginGetVariable('catalog', 'separator') ? pluginGetVariable('catalog', 'separator') : ' | ';
	
	$catalog_ru = explode(" ", "А Б В Г Д Е Ж З И К Л М Н О П Р С Т У Ф Х Ц Ч Ш Щ Ы Э Ю Я");
	foreach($catalog_ru as $k=>$v){
		$catalog_ru[$k] = "<li><a href=\"/catalog/{$v}/\" class=\"catalog_ru\" title=\"".$lang['catalog']['news_let']." {$v}\" target=\"_self\"><span>{$v}</span></a></li>";
	}
	$catalog_ru = implode(' '.$separator.' ', $catalog_ru);

	$catalog_en = explode(" ", "A B C D E F G H I J K L M N O P Q R S T U V W X Y Z");
	foreach($catalog_en as $k=>$v){
		$catalog_en[$k] = "<li><a href=\"/catalog/{$v}/\" class=\"catalog_en\" title=\"".$lang['catalog']['news_let']." {$v}\" target=\"_self\"><span>{$v}</span></a></li>";
	}
	$catalog_en = implode(' '.$separator.' ', $catalog_en);

	$catalog_kod = explode(" ", "0 1 2 3 4 5 6 7 8 9");
	foreach($catalog_kod as $k=>$v){
		$catalog_kod[$k] = "<li><a href=\"/catalog/{$v}/\" class=\"catalog_kod\" title=\"".$lang['catalog']['news_kod']." {$v}\" target=\"_self\"><span>{$v}</span></a></li>";
	}
	$catalog_kod = implode(' '.$separator.' ', $catalog_kod);
	
	$tVars = array(
		'entries' 		=> isset($entries)?$entries:'',
		'catalog_ru' 	=> $catalog_ru,
		'catalog_en' 	=> $catalog_en,
		'catalog_kod' 	=> $catalog_kod,
		'symbol' 		=> $symbol,
		'css'         	=> $tpath['url::'],
            'pages' => array(
            'true' => (isset($pages) && $pages)?1:0,
            'print' => isset($pages)?$pages:''
                            ),
        'prevlink' 		=> array(
                    'true' => !empty($limitStart)?1:0,
                    'link' => str_replace('%page%',
                                            "$1",
                                            str_replace('%link%',
                                                checkLinkAvailable('catalog', '')?
                generatePageLink(array('pluginName' => 'catalog', 'pluginHandler' => '', 'params' => array('id' => $symbol), 'xparams' => array(), 'paginator' => array('page', 0, false)), $prev = floor($limitStart / $limitCount)):
                generatePageLink(array('pluginName' => 'core', 'pluginHandler' => 'plugin', 'params' => array('plugin' => 'catalog', 'handler' => ''), 'xparams' => array(), 'paginator' => array('page', 1, false)),$prev = floor($limitStart / $limitCount)),
                                                isset($navigations['prevlink'])?$navigations['prevlink']:''
                                            )
                    ),
        ),
        'nextlink' 		=> array(
                    'true' => ($prev + 2 <= $countPages)?1:0,
                    'link' => str_replace('%page%',
                                            "$1",
                                            str_replace('%link%',
                                                checkLinkAvailable('catalog', '')?
                generatePageLink(array('pluginName' => 'catalog', 'pluginHandler' => '', 'params' => array('id' => $symbol), 'xparams' => array(), 'paginator' => array('page', 0, false)), $prev+2):
                generatePageLink(array('pluginName' => 'core', 'pluginHandler' => 'plugin', 'params' => array('plugin' => 'catalog', 'handler' => ''), 'xparams' => array(), 'paginator' => array('page', 1, false)), $prev+2),
                                                isset($navigations['nextlink'])?$navigations['nextlink']:''
                                            )
                    ),
        ),
	);

	$template['vars']['mainblock'] = $xt->render($tVars);
}

function LoadCatalogVariables()
{
	$tpath = locatePluginTemplates(array(':'), 'catalog', pluginGetVariable('catalog', 'localsource'), pluginGetVariable('catalog', 'localskin'));
	return parse_ini_file($tpath[':'] . '/skins/variables.ini', true);

}