<?php
/*
Plugin Name: Database and Memory Usage
Plugin URI: thesetemplates.info/services/professionally-fix-troubleshoot-your-wordpress-issue-error-bug/ 
Description: This Plugin Show Total Database and Memory Usage with Memory Limits in Admin Dashboard Footer and Admin Dashboard Page
Version: 1.2
Author: WPFIXIT
Author URI: http://thesetemplates.info/services/professionally-fix-troubleshoot-your-wordpress-issue-error-bug/
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
							$content .= ' ~ load ' . $this->memory['wpo-load'] . ' of ' . $this->memory['wpo-limit'] . 'M';
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
					$panic  = '<span id="footer-thankyou"> WPFIXIT </span><a href="http://thesetemplates.info/services/professionally-fix-troubleshoot-your-wordpress-issue-error-bug/"><img width="80px" src="' . plugins_url( 'images/panic.png' , __FILE__ ) . '" /></a>';
					while ($row = mysql_fetch_array($rows))
						{
							$dbsize += $row['Data_length'] + $row['Index_length'];
						}
					$dbsize = wpo_fs_info($dbsize);
					echo "database {$dbsize['size']}{$dbsize['type']}{$panic}";
				}
			add_filter('admin_footer_text', 'wpo_db_size');
		}
add_action( 'admin_menu', 'register_wpfixit_menu_page' );

function register_wpfixit_menu_page(){
    add_menu_page( 'WPFIXIT', 'WPFIXIT', 'manage_options', 'WPFIXIT', 'wpfixit_menu_page', plugins_url( 'database-and-memory-usage-limits/menu_icon.png' ), 99 ); 
}

function wpfixit_menu_page(){
    echo '<style>
h1 {
font-size: 2em;
margin: .67em 0;
color: #09C;
}
	form    {
background: -webkit-gradient(linear, bottom, left 175px, from(#CCCCCC), to(#EEEEEE));
background: -moz-linear-gradient(bottom, #CCCCCC, #EEEEEE 175px);
margin:auto;
position:relative;
width:550px;
height:450px;
font-family: Tahoma, Geneva, sans-serif;
font-size: 14px;
font-style: italic;
line-height: 24px;
font-weight: bold;
color: #09C;
text-decoration: none;
-webkit-border-radius: 10px;
-moz-border-radius: 10px;
border-radius: 10px;
padding:10px;
border: 1px solid #999;
border: inset 1px solid #333;
-webkit-box-shadow: 0px 0px 8px rgba(0, 0, 0, 0.3);
-moz-box-shadow: 0px 0px 8px rgba(0, 0, 0, 0.3);
box-shadow: 0px 0px 8px rgba(0, 0, 0, 0.3);
}
input    {
width:375px;
display:block;
border: 1px solid #999;
height: 25px;
-webkit-box-shadow: 0px 0px 8px rgba(0, 0, 0, 0.3);
-moz-box-shadow: 0px 0px 8px rgba(0, 0, 0, 0.3);
box-shadow: 0px 0px 8px rgba(0, 0, 0, 0.3);
}
textarea#feedback {
width:375px;
height:150px;
}
textarea.message {
display:block;
}
input.button {
width:100px;
position:absolute;
right:20px;
bottom:20px;
background:#09C;
color:#fff;
font-family: Tahoma, Geneva, sans-serif;
height:30px;
-webkit-border-radius: 15px;
-moz-border-radius: 15px;
border-radius: 15px;
border: 1p solid #999;
}
input.button:hover {
background:#fff;
color:#09C;
}
textarea:focus, input:focus {
border: 1px solid #09C;
}
	</style>
<h1>Instantly Report any issue</h1><div id="contact-form-937">
<form target="_blank" action="http://thesetemplates.info/contact-page-wordpress/#contact-form-937" method="post" class="contact-form commentsblock">
<div>
		<label for="937-name" class="grunion-field-label name">Name<span> (required)</span></label><br>
		<input type="text" name="937-name" id="937-name" value="" class="name" size="40">
	</div>
<div>
		<label for="937-email" class="grunion-field-label email">Email<span> (Invoice will be sent to this email)</span></label><br>
		<input type="email" name="937-email" id="937-email" value="" class="email" size="40">
	</div>
<div>
		<label for="937-website" class="grunion-field-label url">Website</label><br>
		<input type="text" name="937-website" id="937-website" value="" class="url" size="40" >
	</div>
<div>
		<label for="contact-form-comment-937-comment" class="grunion-field-label textarea">Explain your issue in detail<span> (required)</span></label><br>
		<textarea name="937-comment" id="contact-form-comment-937-comment" rows="7"  cols="70" placeholder="You may create a post and copy/paste a post link in here or you may just explain your issue we will get back to you within 12 hours"></textarea>
	</div>
<p class="contact-submit">
		<input type="submit" value="Panic $35 »" class="pushbutton-wide"><br>
		<input type="hidden" id="_wpnonce" name="_wpnonce" value="7b9bf669bf"><input type="hidden" name="_wp_http_referer" value="/contact-page-wordpress/"><br>
		<input type="hidden" name="contact-form-id" value="937"><br>
		<input type="hidden" name="action" value="grunion-contact-form">
	</p>
</form>
</div><br /><h2>We can fix following issues</h2><li>Theme bugs</li><li>Plugin bugs</li><li>WordPress issues</li><li>Database problem</li><li>Slow website speed</li><li>Increase Resource Usage</li><br /><h1>Our Portfolio:<a target="_blank" href="http://thesetemplates.info/services/professionally-fix-troubleshoot-your-wordpress-issue-error-bug/"><img src="' . plugins_url( 'images/portfolio48.png' , __FILE__ ) . '" /></a></h1><br /><img src="' . plugins_url( 'images/100satisfaction-3.png' , __FILE__ ) . '" /><img src="' . plugins_url( 'images/100moneyback-3.png' , __FILE__ ) . '" />';	
}
?>