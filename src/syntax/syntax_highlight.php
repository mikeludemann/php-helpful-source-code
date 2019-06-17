<?
# Language number: Only really used in Ultraedit. [DOOMED]
$lang_num = 0;
# Language name.
$lang_name = "";
# Language configuration hashtable. 
$config = array();
$color = 0;
$parsing = "";
$indent = array();
$unindent = array();
# Hashtable of keywords - maps from keyword to colour.
$keywords = array();
# Used for conversion from keyword -> regexp.
$keypats = array();
# Which colours to use for each 'level'.
$colours = array("blue", "purple", "gray", "brown", "blue", "purple", "gray", "brown");
$stringchars = array();

# Caches for the harvesters.
$comcache = "";
$stringcache = "";

# Used by the configuration file, this takes in the first line (which
# is used to describe general language traits).

function parse_ufile_config($config_line)
{

	$valid_keys = "";

	# Keywords that can be used in 'Foo = Bar' context.
	$valid_keys = "Escape Char|Line Comment Num|Line Comment Alt|Line Comment|Block Comment On|Block Comment Off|Block Comment On Alt|Block Comment Off Alt|String Chars";

	# Keywords that should be used on their own. Note that the _LANG
	# ones are fairly doomed.
	$valid_keywords = "Notrim|Nocase|Noquote|PERL|HTML_LANG|FORTRAN_LANG|LATEX_LANG";
	$done = 0;
	$out = array();
	while (!$done)
	{
		$config_line = ltrim($config_line);

		# bla = bla matched.
		if (preg_match("/^[s]*(".$valid_keys.")[s]*=(.*)/", $config_line, $matches))
		{
			$key = $matches[1];
			$val = $matches[2];
			$val = trim($val);
		}
		# Single keyword matched.
		else if (preg_match("/^[s]*(".$valid_keywords.")[s]*(.*)/", $config_line, $matches))
		{
			$out[$matches[1]] = 1;
			$config_line = $matches[2];
			continue;
		}
		else
		{
			return array();
		}

		# We've got a 'foo = bar' case, so strip out the the string up to the 
		# next valid keyword, and recreate the config line.
		$reg = "/^(.*?)s*(".$valid_keys."|".$valid_keywords.")(.+)$/";
		if (preg_match($reg, $val, $matches))
		{
			$value = $matches[1];
			$config_line = $matches[2].$matches[3];
			$done = 0;
		}
		else
		{
			$value = $val;
			$done = 1;
		}
		if (!isset($value)) {$value = 1;}
		$out[$key] = $value;
	}
	return $out;
}

# Go through the string, highlighting keywords. At the moment this is a bit kludgy, with
# 'fwble' (voted least likely to be a keyword :o) being used to act as a placeholder for
# 'font' and 'r', 'n' being used for < and > respectively.
function munge($tomunge)
{
	global $keypats, $colours, $keywords;

	$tomunge = str_replace("&gt;", ">", $tomunge);
	$tomunge = str_replace("&lt;","<", $tomunge);
	foreach (array_keys($keypats) as $keyobj) 
	{
		$keyobj = str_replace("/","/",$keyobj); 

		if (preg_match("/".$keyobj."/", $tomunge, $matches))
		{
			$matched = $matches[1];
			$color = $colours[(int)($keywords[$matched])-1];
			$tomunge = preg_replace("/".$keyobj."/","nfwble=" . $color . "r$matchedn/fontr", $tomunge);

		}
	}
	# Replace all symbols.
	$tomunge = str_replace(">","&gt;",$tomunge);
	$tomunge = str_replace("<","&lt;",$tomunge);
	$tomunge = str_replace("n","<",$tomunge);
	$tomunge = str_replace("r",">",$tomunge);
	$tomunge = str_replace("fwble","font color",$tomunge);
	return $tomunge;
}

# Takes in a filename of a highlight file, and creates the necessary settings.
function psh_parse_file($highlightfile)
{
	global $keywords;
	global $indent;
	global $unindent;
	global $keypats;
	global $colours;
	global $config;
	$filehandle = fopen ($highlightfile, "r") or die("Unable to open syntax file: $!");


	while(!feof ($filehandle)) 
	{
		$parsing = fgets($filehandle, 4096);
		# Grab language number, name, and config string.
		if (preg_match('//L([0-9]+)"([^"]*)"(.*)/', $parsing, $matches))
		{
			$lang_num = $matches[1];
			$lang_name = $matches[2];
			$config = parse_ufile_config($matches[3]);
			continue;
		}
		# Move onto a new colour category.
		else if (preg_match("/^/C([0-9]+)s*(.*)/", $parsing, $matches))
		{
			$color = $matches[1];
			continue;
		}
		# Set indenting strings. This nifty reg-exp removes the outer quotes, so
		# the explode can do its work.
		else if (preg_match('/^/Indent Stringss*=s*"(.*)"$/', $parsing, $matches))
		{
			$indent = explode("", "", $matches[1]);
			continue;
		}
		# As above, but for unindenting strings.
		else if (preg_match('/^/Unindent Stringss*=s*"(.*)"$/', $parsing, $matches))
		{
			$unindent = explode("", "", $matches[1]);
			continue;
		}
		# Split any keywords up.
		$keylist = preg_split("/s+/", $parsing, -1, PREG_SPLIT_NO_EMPTY);

		foreach($keylist as $k)
		{
			$keywords[$k] = $color;
		} 

	}
	fclose($filehandle);
	$keyword_keys = array_keys($keywords);

	# Create the regexps for the keywords. 'b' specifies a word boundary.
		foreach($keyword_keys as $k)
		{
			$keypats["b(".preg_quote($k).")b"] = $k;
		} 

}

# A handy function to find the longest element of the array that is present at the beginning
# of the provided string. For example, given an array of 'foo' and 'foot' and the string 'football',
# this returns 'foot'.
function starts_with($text, $array)
{
	$ml = 0;
	$curr = "";

	foreach($array as $i)
	{
		$l = strlen($i);
		if (substr($text, 0, $l)==$i && ($text[$l]==" " || $l==1 || $text[$l]=="n" || $text[$l]=="t" || $text[$l]=="." || $text[$l]==";" || $l==strlen($text)))
		{
			if ($l>$ml) 
			{
				$curr = $i;
				$ml = $l;
			}
		}
	}
	return $curr;
}

# Load a file and return it as a (big 🙂 string.
function psh_load_file($filename)
{
	$filehandle = fopen ($filename, "r") or die("Could not open $filename for reading.");
	$text = fread($filehandle, filesize($filename));
	fclose($filehandle);
	return $text;
} 

# Do the brunt work of highlighting the text. Harvesting can be set through the latter two
# arguments.
function psh_highlight_text($text, $cachecomments=0, $cachestrings=0)
{
	global $unindent, $indent, $config, $comcache, $stringcache;
	$text = str_replace("\"", "", $text);
	$text = str_replace("&", "&amp;", $text);
		$text = str_replace("<", "&lt;", $text);
	$text = str_replace(">", "&gt;", $text);
	$inquote = 0;
	$incomment = 0;
	$inbcomment = 0;
	$inwhitespace = 1;
	# Get the lines.
	$arr = preg_split("/n/", $text);
	# Current indent level.
	$ind = 0;

	# Retrieve the values from the config. More for readability than anything.
	$bkc = $config["Block Comment On"];
	$bku = $config["Block Comment Off"];
	$lnc = $config["Line Comment"];
	$esc = preg_quote($config["Escape Char"]);

	# Store the lengths of the comment strings - saves recalculating them lots.
	$bkcl = strlen($bkc);
	$bkul = strlen($bku);
	$lncl = strlen($lnc);

	# Create an array of the string characters.
	$stringchars = array();
	for($i=0; $i<strlen($config["String Chars"]); $i++)
	{ 
		array_push($stringchars, $config["String Chars"][$i]);
	}

	# Set up flags. 'PERL' ensures $# is not treated as '$<comment>', and Notrim
	# prevents preceding white-space removal.
	if (isset($config["PERL"])) $perl = 1;
	if (isset($config["Notrim"])) $notrim = 1;
	# Clear the harvest caches.
	$comcache = ""; 
	$stringcache = "";
	# Used to ensure quote matching works 🙂
	$currquotechar = "";

	$begseen = 0; 
	$newline = 0;
	foreach ($arr as $line) 
	{
		$inwhitespace = 1;
		# Close any hanging font tags.
		if ($incomment) 
		{
			print "</font>";
		}
		$incomment = 0;
		# Strip leading and trailing spaces
		if (!isset($notrim)) $line = trim($line);

		$lineout = "";
		$lineorig = $line;
		# Print out the current indent.
		if ($begseen>0 && starts_with($lineorig, $unindent)!="")
		{
			$lineout = str_repeat("        ", ($ind-1));
		}
		else
		{
			$lineout = str_repeat("        ", $ind);
		}

		$ln = strlen($lineorig);
		for ($j=0; $j<$ln; $j++)
		{
			$currchar = $lineorig[$j];
			# Handle line comments. This is made slightly faster by going straight to
			# the next line - as nothing else can be done.
			if (isset($lnc) && (substr($line, $j, $lncl)==$lnc) && !$inquote && !$incomment && !($perl && $j>0 && $line[$j-1]=="$"))
			{
				$line = substr($line, $j);
				$lineout = munge($lineout);
				print $lineout;
				print "<font color='green'>$line";
				if ($cachecomments) $comcache .= " ".substr($line, $lncl);
				$lineout = "";
				$incomment = 1;
				$j = $ln + 1;
				continue;

			}
			# Handle opening block comments. Sadly this can't be done quickly (like with
			# line comments) as we may have 'foo /* bar */ foo'.
			if (isset($bkc) && (substr($line, $j, $bkcl)==$bkc) && !$inquote && !$inbcomment)
			{
				$lineout = munge($lineout);

				print $lineout;
				print "<font color='green'>$bkc";
				$lineout = "";
				$inbcomment = 1;
				$j += $bkcl-1;
				continue;
			}
			# Handle closing comments.
			if (isset($bku) && $inbcomment && (substr($line, $j, $bkul)==$bku))
			{
				$lineout .= "$bku</font>";
				print $lineout;
				$lineout = "";
				$inbcomment = 0;
				$j += $bkul;
				continue;
			}
			# If we're in a comment, skip keyword checking, cache the comments, and go
			# to the next char.
			if ($incomment || $inbcomment) 
			{ 
				$lineout .= $currchar; 
				if ($newline) 
				{
					$comcache .= " ";
					$newline = 0;
				}
				if ($cachecomments) $comcache .= $currchar;
				continue; 
			}
			# Handle quotes.
			if (in_array($currchar, (array)$stringchars)) 
			{
				# In case no escape character is set.
				if (!isset($esc)) { $esc = ""; }
				# First quote, so go blue.
				if (isset($inquote) && !$inquote)
				{
					$lineout = munge($lineout);
					print $lineout;
					$inquote = 1;
					if ($cachestrings) $stringcache.=" ";
					$lineout = $currchar."<font color='blue'>";
					$currquotechar = $currchar;
				}
				# Last quote, so turn off font colour.
				else if ($currchar == $currquotechar && $lineorig[$j-1] != $esc)
				{
					$inquote = 0;
					$lineout .= "</font>".$lineorig[$j];
					print $lineout;
					$lineout = "";
					$currquotechar = "";
				}
			}
			# If we've got an indent character, increase the level, and add an indent.
			else if (!$inquote && ($stri=starts_with(substr($line, $j), $indent))!="") 
			{
				if (!$inwhitespace) 
				{
					$lineout .= str_repeat("        ", $ind);
				}
				$lineout .= $stri;
				$ind++;
				$j += strlen($stri)-1;
				$begseen++;
			}
			# If we've got an unindent (and we are indented), go back a level.
			else if ($begseen>0 && !$inquote && ($stru=starts_with(substr($line, $j), $unindent))!="") 
			{

				$begseen--;
				$ind--;
				if (!$inwhitespace) 
				{
					$lineout .= str_repeat("        ", $ind);

				}
				$lineout .= $stru;
				$j += strlen($stru)-1;
			}
			# Add the characters to the output, and cache strings.
			else if (!$inwhitespace || $currchar != " " || $currchar != "t") 
			{
				if ($inquote)
				{
					$stringcache.=$currchar;
				} 
				$lineout .= $currchar;
			}

		} 
		if ($currchar != " " && $currchar != "t") 
		{
			$inwhitespace = 0;
		}
		if (!$incomment && !$inbcomment) 
		{
			$lineout = munge($lineout);
		}
		$lineout .= "n";
		print $lineout;
		$newline = 1;

	}
	# If we've finished, and are still in a comment, close the font tag.
	if ($incomment || $inbcomment)
	{
		print "</font>";
	}
}

# A handy function (which could probably be much smaller 🙂 to take
# a hashtable of 'word'->'occurrences' and return a hashtable of
# 'occurrences'->(array of words in alphabetical order).
function psh_get_sorted_count($unsorted)
{
	$sorted = array();
	foreach(array_keys($unsorted) as $k)
	{
		$num = $unsorted[$k];
		if (!isset($sorted[$num])) $sorted[$num] = array();
		array_push ($sorted[$num], $k);
	}
	krsort($sorted);
	foreach(array_keys($sorted) as $k)
	{
		$results = $sorted[$k];
		sort($results); 
	}
	return $sorted;
}

# Produces a hashtable mapping from 'word'->'occurrences'. Also filters out common words, and
# any keywords in the language.
function psh_filter_words($comstring)
{
	global $keywords;

	$comstring = trim($comstring);
	$comout = array();
	$wordorder = array();
	$comstring = preg_replace ("/[^a-zA-Z ]/", " ", $comstring);
	$comstring = preg_replace ("/s+/", " ", $comstring);
	$arr = explode(" ", $comstring);
	$commonwords = array("the", "and", "this", "that", "you", "then", "when", "who", "why", "what", "how", "with", "are", "may", "she", "him", "her"); 
	foreach($arr as $a)
	{
		if (strlen($a)>3 && !in_array($a, $commonwords) && !in_array($a, array_keys($keywords))) 
		{
			$a = strtolower($a);
			$comout[$a]++;
		}
	}

	return $comout;
}

?>
