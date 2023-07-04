<?php


function create_catalog_urls()
{

 			$ULIB = new urlLibrary();
			$ULIB->loadConfig();

			$ULIB->registerCommand('catalog', '',
				array ('vars' =>
						array( 	'id' => array('matchRegex' => '.+?', 'descr' => array('russian' => 'Буквенный каталог')),
						'page' => array('matchRegex' => '\d{1,4}', 'descr' => array('russian' => 'Постраничная навигация'))
						),
						'descr'	=> array ('russian' => 'Символьный каталог'),
				)
			);

			$ULIB->saveConfig();

			$UHANDLER = new urlHandler();
			$UHANDLER->loadConfig();	
			$UHANDLER->registerHandler(0,
				array (
				'pluginName' => 'catalog',
				'handlerName' => 'id',
				'flagPrimary' => true,
				'flagFailContinue' => false,
				'flagDisabled' => false,
				'rstyle' => 
				array (
				  'rcmd' => '/catalog/[id/{id}/][page/{page}/]',
				  'regex' => '#^/catalog/(.+?)/(?:page/(\\d{1,4})/){0,1}$#',
				  'regexMap' => 
				  array (
					1 => 'id',
					2 => 'page',
				  ),
				  'reqCheck' => 
				  array (
				  ),
				  'setVars' => 
				  array (
				  ),
				  'genrMAP' => 
				  array (
					0 => 
					array (
					  0 => 0,
					  1 => '/catalog/',
					  2 => 0,
					),
					1 => 
					array (
					  0 => 1,
					  1 => 'id',
					  2 => 1,
					),
					2 => 
					array (
					  0 => 0,
					  1 => '/',
					  2 => 1,
					),
					3 => 
					array (
					  0 => 0,
					  1 => 'page/',
					  2 => 3,
					),
					4 => 
					array (
					  0 => 1,
					  1 => 'page',
					  2 => 3,
					),
					5 => 
					array (
					  0 => 0,
					  1 => '/',
					  2 => 3,
					),
				  ),
				),
			  )
			);
    $UHANDLER->saveConfig();
}

function remove_catalog_urls()
{
    $ULIB = new urlLibrary();
    $ULIB->loadConfig();
    $ULIB->removeCommand('catalog', '');
    $ULIB->saveConfig();
    $UHANDLER = new urlHandler();
    $UHANDLER->loadConfig();
    $UHANDLER->removePluginHandlers('catalog', '');
    $UHANDLER->saveConfig();
}
