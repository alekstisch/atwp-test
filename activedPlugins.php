<?php
define('WPPATH', $_SERVER['PWD']);

require_once( WPPATH.'/wp-load.php' );

function at_get_new_plugin_list(){
	
	$list_plugins = explode( PHP_EOL, file_get_contents(WPPATH.'/wp-content/plugins/plug-main/pluginsList.txt'));
	
	$new_list_plugin = [];

	foreach( $list_plugins as $url ){
		
		$a_url = explode( '/', $url );
		$path = array_pop($a_url);
		$new_list_plugin[] = preg_replace('/(\S+)\.\S+(\s+)?/', '$1', $path);
		
	}
	
	return $new_list_plugin;
}

function at_actived_new_plugin(){
	
	$wp_plugins = get_plugins();
	$new_plugins_list = at_get_new_plugin_list();
	$active_plugins = get_option('active_plugins');
	
	$active_plugins = !empty( $active_plugins ) ? $active_plugins : [0 => ''];
	
	if( is_array($wp_plugins) ){
		
		foreach( $wp_plugins as $k => $list_wp_plugins){
			
			$an = explode( '/', $k );
			$name = trim(array_shift($an));
			
			if( in_array( $name, $new_plugins_list ) ){
				
				if( !in_array( $k, $active_plugins ) ){
					
					$active_plugins[] = $k;
					
				}
				
			}
			
		}
		if( array_key_exists( 0, $active_plugins ) ) unset($active_plugins[0]);
		
		update_option( 'active_plugins', $active_plugins );
		
	}
	
}
at_actived_new_plugin();
