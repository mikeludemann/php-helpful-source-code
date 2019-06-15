<?php 

$start = time() + (double)microtime(); 
 
for($i = 0; $i < 100000; $i++); 

$ende = time() + (double)microtime(); 

$diff = round($ende-$start,6);

echo $diff . " seconds"; 

?>