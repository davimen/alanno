<?php
session_start();
error_reporting(0);

define('WP_USE_THEMES', false);
require( dirname( __FILE__ ) . '/wp-blog-header.php' );
		
include str_replace('\\','/',dirname(__FILE__)).'/class/defineConst.php';
include str_replace('\\','/',dirname(__FILE__)).'/class/class.BUSINESSLOGIC.php';
include str_replace('\\','/',dirname(__FILE__)).'/class/class.CSS.php';
include str_replace('\\','/',dirname(__FILE__)).'/class/class.JAVASCRIPT.php';
include str_replace('\\','/',dirname(__FILE__)).'/class/class.HTML.php';
include str_replace('\\','/',dirname(__FILE__)).'/class/class.utilities.php';
include str_replace('\\','/',dirname(__FILE__)).'/class/class.SINGLETON_MODEL.php';

$dbf=SINGLETON_MODEL::getInstance("BUSINESSLOGIC");
$html=SINGLETON_MODEL::getInstance("HTML");
$css=SINGLETON_MODEL::getInstance("CSS");
$js=SINGLETON_MODEL::getInstance("JAVASCRIPT");
$utl=SINGLETON_MODEL::getInstance("UTILITIES");

$words = $dbf->getwords('vn', $arrayLang); 
if(empty($_SESSION["login"])){
    $_SESSION["login"]=session_id();
    $_SESSION["Free"]=1;	
}

if($URL[0]=='logout')
    $dbf->signout();
?>

<?php
get_header('order');
?>

<!-- #Content -->
<div id="Content">
	<div class="content_wrapper clearfix">
		<!-- .sections_group -->
		<div class="sections_group">
		
			<div class="entry-content" itemprop="mainContentOfPage">
			  <?php 			    	
				include("modum/bodymain.php"); 
			  ?>  
			</div>
		</div>
	</div>
</div>
<?php get_footer();