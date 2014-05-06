<?php
/*
Plugin Name: Database and Memory Usage
Plugin URI: http://wpfixit.net/ 
Description: This Plugin Show Total Database and Memory Usage with Memory Limits in Admin Dashboard Footer and Admin Dashboard Page
Version: 1.5.0
Author: WPFIXIT
Author URI: http://wpfixit.net/
*/
	if (!function_exists('add_action'))
		{
			header('HTTP/1.0 403 Forbidden');
			header('HTTP/1.1 403 Forbidden');
			exit();
		}
?>
<?php
	function wpo_nfo()
		{
			echo "\n<!--Plugin WP Overview (lite) 2011.0723.2011 Active-->\n\n";
		}
	add_action('wp_head', 'wpo_nfo');
	add_action('wp_footer', 'wpo_nfo');
?>
<?php
	if (is_admin())
		{
			class wp_overview_lite
				{
					var $memory = false;
					function wpo()
						{
							return $this->__construct();
						}
					function __construct()
						{
							add_action('init', array(
									&$this,
									'wpo_limit'
							));
							add_action('wp_dashboard_setup', array(
									&$this,
									'wpo_dashboard'
							));
							add_action('wp_network_dashboard_setup', array(
									&$this,
									'wpo_network_dashboard'
							));
							add_filter('admin_footer_text', array(
									&$this,
									'wpo_footer'
							));
							$this->memory = array();
						}
					function wpo_limit()
						{
							$this->memory['wpo-limit'] = (int) ini_get('memory_limit');
						}
					function wpo_load()
						{
							$this->memory['wpo-load'] = function_exists('memory_get_usage') ? round(memory_get_usage() / 1024 / 1024, 2) : 0;
						}
					function wpo_consumption()
						{
							$this->memory['wpo-consumption'] = round($this->memory['wpo-load'] / $this->memory['wpo-limit'] * 100, 0);
						}
					function wpo_output()
						{
							$this->wpo_load();
							$this->wpo_consumption();
							$this->memory['wpo-load'] = empty($this->memory['wpo-load']) ? __('0') : $this->memory['wpo-load'] . __('M')
?>
<?php
							global $wpdb, $wp_version, $wpmu_version;
							$cur_locale   = get_locale();
							$mysql_status = array();
							$mysql_vars   = array();
							foreach ($wpdb->get_results('SHOW GLOBAL STATUS') as $result)
								{
									$mysql_status[$result->Variable_name] = $result->Value;
								}
							foreach ($wpdb->get_results('SHOW GLOBAL VARIABLES') as $result)
								{
									$mysql_vars[$result->Variable_name] = $result->Value;
								}
							$uptime_days    = $mysql_status['Uptime'] / 86400;
							$uptime_hours   = ($uptime_days - (int) $uptime_days) * 24;
							$uptime_minutes = ($uptime_hours - (int) $uptime_hours) * 60;
							$uptime_seconds = ($uptime_minutes - (int) $uptime_minutes) * 60;
							$uptime_string  = (int) $uptime_days . ' days, ' . (int) $uptime_hours . ' hours, ' . (int) $uptime_minutes . ' minutes, ' . (int) $uptime_seconds . ' seconds'
?>
<ul><li><strong>Mem</strong>:
<strong>WP </strong><span><?php echo WP_MEMORY_LIMIT?></span> (def)
<strong>Usage </strong><span><?php echo$this->memory['wpo-consumption'].'%'.' '.$this->memory['wpo-load']?></span> of
<strong>PHP Limit </strong><span><?php echo$this->memory['wpo-limit'].'M'?></span></li>
<li><br/><strong>Server</strong>:
<strong>OS </strong><span><?php echo PHP_OS?></span>
<strong>Software </strong><span><?php echo$_SERVER['SERVER_SOFTWARE']?></span>
<strong>Version </strong><span><?php echo(PHP_INT_SIZE*8).__('Bit')?></span></li>
<li><strong>Name </strong><span><?php echo$_SERVER['SERVER_NAME']?></span>
<strong>Address </strong><span><?php echo$_SERVER['SERVER_ADDR']?></span>
<strong>Port </strong><span><?php echo$_SERVER['SERVER_PORT']?></span></li>
<li><strong>Type </strong><span><?php echo php_uname()?></span></li>
<li><strong>System</strong>:
<strong>PHP </strong><span><?php echo PHP_VERSION?></span>
<strong>Active Plugins </strong><span><?php echo count(get_option('active_plugins'))?></span>
<strong>Zend </strong><span><?php echo zend_version()?></span></li>
<li><strong>Database</strong>:
<strong>SQL </strong><span><?php printf("%s\n",mysql_get_client_info())?></span>
<strong>Build </strong><span><?php echo$mysql_vars['version']?></span>
<strong>Charset </strong><span><?php echo DB_CHARSET?></span></li>
<li><strong>Name </strong><span><?php echo DB_NAME?></span>
<strong>Host </strong><span><?php echo DB_HOST?></span></li>
<li><strong>WordPress</strong>:
<strong>VER </strong><span><?php echo _e($wp_version)?></span><strong> Lang </strong><span><?php echo _e(WPLANG)?></span><strong> Loc </strong><span><?php echo$cur_locale?></span></li>
<li><strong>Max</strong>:
<strong>Post </strong><span><?php echo _e(ini_get('post_max_size'))?></span>
<strong>Upload </strong><span><?php echo _e(ini_get('upload_max_filesize'))?></span>
<strong>Input </strong><span><?php echo ini_get('max_input_time')?>s</span>
<strong>Exec </strong><span><?php $et=ini_get('max_execution_time');if($et>1000)$et/=1000;echo$et?>s</span></li>
<li><strong>Debug</strong>:
<strong>State </strong><span><?php echo(int)WP_DEBUG?></span>
<strong>Display </strong><span><?php echo(int)WP_DEBUG_DISPLAY?></span>
<strong>Log </strong><span><?php echo(int)WP_DEBUG_LOG?></span>
<strong>Script </strong><span><?php echo(int)SCRIPT_DEBUG?></span><br/></li>
<li><br/><strong>SQL Uptime</strong>:
<span><?php echo$uptime_string?></span><br/></li>
<li><br/><strong>Allow DB Repair </strong><span><?php echo(int)WP_ALLOW_REPAIR?></span><em> (since wp2.9)</em></li>
<li><strong>AutoSave </strong><span><?php echo(int)AUTOSAVE_INTERVAL?>s</span><em> (since wp2.5)</em><strong> Revisions </strong><span><?php echo(int)WP_POST_REVISIONS?></span><em> (since wp2.6)</em></li>
<li><strong>WP (Hyper, Super, W3 Total) Cache </strong><span><?php echo(int)WP_CACHE?></span><em> (since wp2.5)</em></li>
<li><strong>Magpie Cache </strong><span><?php echo(int)MAGPIE_CACHE_ON?></span><strong> Age </strong><span><?php echo(int)MAGPIE_CACHE_AGE.' seconds'?></span><em> (since wp1.5)</em></li>
<li><strong>Simplepie Cache </strong><span><?php echo(int)SIMPLEPIE_CACHE_ON?></span><strong> Age </strong><span><?php echo(int)SIMPLEPIE_CACHE_AGE.' seconds'?></span><em> (since wp2.8)</em></li>
<li><strong>Trash </strong><span><?php echo(int)WP_TRASH?></span><strong> Empty </strong><span><?php echo(int)EMPTY_TRASH_DAYS.' days'?></span><em> (since wp2.9)</em></li><br/>
<em><strong>Legend</strong> 0=disabled 1=enabled</em>
<?php
						}
					function wpo_dashboard()
						{
							if (!current_user_can('unfiltered_html'))
									return;
							wp_add_dashboard_widget('dashboard_overview', 'Overview', array(
									&$this,
									'wpo_output'
							));
						}
					function wpo_network_dashboard()
						{
							if (!current_user_can('unfiltered_html'))
									return;
							wp_add_dashboard_widget('network_dashboard_overview', 'Overview', array(
									&$this,
									'wpo_output'
							));
						}
					function wpo_footer($content)
						{
							$this->wpo_load();
							$content .= ' ~ Memory Load ' . $this->memory['wpo-load'] . ' out of ' . $this->memory['wpo-limit'] . 'M';
							return $content;
						}
				}
			add_action('plugins_loaded', create_function('', '$memory=new wp_overview_lite();'));
		}
?>
<?php
	if (is_admin())
		{
			function wpo_fs_info($filesize)
				{
					$bytes = array(
							'B',
							'K',
							'M',
							'G',
							'T'
					);
					if ($filesize < 1024)
							$filesize = 1;
					for ($i = 0; $filesize > 1024; $i++)
							$filesize /= 1024;
					$wpo_fs_info['size'] = round($filesize, 3);
					$wpo_fs_info['type'] = $bytes[$i];
					return $wpo_fs_info;
				}
			function wpo_db_size()
				{
					$rows   = mysql_query("SHOW table STATUS");
					$dbsize = 0;
					$panic  = '<span id="footer-thankyou"> <a style="color:red;" href="'.site_url().'/wp-admin/admin.php?page=WPFIXIT">WPFIXIT</a> </span>';
					while ($row = mysql_fetch_array($rows))
						{
							$dbsize += $row['Data_length'] + $row['Index_length'];
						}
					$dbsize = wpo_fs_info($dbsize);
					echo "Database Size {$dbsize['size']}{$dbsize['type']}{$panic}";
				}
			add_filter('admin_footer_text', 'wpo_db_size');
		}
add_action( 'admin_menu', 'register_wpfixit_menu_page' );

function register_wpfixit_menu_page(){
    add_menu_page( 'WPFIXIT', 'WPFIXIT', 'manage_options', 'WPFIXIT', 'wpfixit_menu_page', plugins_url( 'database-and-memory-usage-limits/menu_icon.png' ), 99 ); 
}

function wpfixit_menu_page(){
	
	$current_user = wp_get_current_user();
    /**
     * @example Safe usage: $current_user = wp_get_current_user();
     * if ( !($current_user instanceof WP_User) )
     *     return;
     */	
    ?>
    <h1>Submit Your Issue:</h1>
    <form action="http://www.wpfixit.net/?page_id=168" method="post" target="_blank">
    <label>Subject: </label><br /><input type="text" name="subject" /><br />
    <label>Issue: </label><br /><textarea name="issue" cols="60" rows="10" ></textarea>
    <input name="username" type="hidden" value="<?php echo urlencode(bloginfo('name')).'-'.$current_user->user_login ?>" />
    <input name="email" type="hidden" value="<?php echo $current_user->user_email ?>" />
    <?php submit_button('Submit Ticket $38'); ?>
    </form>
    <?php	
}
?>