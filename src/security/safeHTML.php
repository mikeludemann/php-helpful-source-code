
<?PHP

function safeHTML(&$str){

	$approvedtags = array(
		"p"=>array('align'),
		"b"=>array(),
		"i"=>array(),
		"a"=>array('href', 'target'),
		"em"=>array(),
		"br"=>array(),
		"strong"=>array(),
		"blockquote"=>array(),
		"tt"=>array(),
		"hr"=>array('align', 'width', 'size', 'noshade'),
		"li"=>array('type'),
		"ol"=>array('type', 'start'),
		"ul"=>array('type'),
		"pre"=>array()
  );

	$keys = array_keys($approvedtags);
	$text=split('<',$str);
  $first = 1;

	foreach($text as $value){

		$temp=split('>',$value);

		if(count($temp) > 1){

			$end='';
				$tag=split(' ',$temp[0]);

			if($tag[0][0] == '/'){

				$end='/';
					$tag[0]=substr($tag[0],1);

					}

			if(in_array($tag[0],$keys)){

				$string.='<'.$end.$tag[0];

				for($i=1; $i<=count($tag); $i++){

					$attributes=split('=',$tag[$i]);

					if(in_array($attributes[0], $approvedtags[$tag[0]])){

						$string.=' '.$tag[$i];

          }

        }

        $string.='>';

			} else{

				$string.='&lt;'.htmlentities($value);

      }

      $string.=htmlentities($temp[1]);

		} else{

			if(! $first){

				$string.='&lt;';

				}

				  $string .= htmlentities($value);

				}

      $first = 0;

		}

  $str = $string;

}

?>
