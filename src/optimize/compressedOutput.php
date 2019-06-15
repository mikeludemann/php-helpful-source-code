<?php 

if(extension_loaded("zlib") AND strstr($_SERVER["HTTP_ACCEPT_ENCODING"],"gzip")) {

	@ob_start("ob_gzhandler"); 

}

?> 
