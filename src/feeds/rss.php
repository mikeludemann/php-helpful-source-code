
<?php 

class rss { 

	/* 
		* RSS 2.0 Class 
	*/ 

	protected $mime; 
	protected $charset; 
	protected $title; 
	protected $link; 
	protected $description; 
	protected $language; 
	protected $copyright; 
	protected $managingEditor; 
	protected $webMaster; 
	protected $pubDate; 
	protected $lastBuildDate; 
	protected $category; 
	protected $generator; 
	protected $docs; 
	protected $ttl; 
	protected $textInput; 
	protected $domain; 
	protected $starttime; 
	protected $itemid=0; 
	protected $caching=false; 
	protected $cachefile; 
	protected $cachetime; 
  protected $items=array(); 
  
	public function __construct($title, $link, $description, $charset = "UTF-8", $starttime = null) { 

		if (stristr($_SERVER["HTTP_ACCEPT"],"application/rss+xml")){ 

      $this->mime = "application/rss+xml"; 
    
    } elseif (stristr($_SERVER["HTTP_ACCEPT"],"application/xml")){

      $this->mime = "application/xml";
    
    }	else {
      
      $this->mime = "text/xml";
    
    } 

    $this->charset = $charset; 
    
    header("content-type: ".$this->mime."; charset=".$this->charset); 
    
		$this->title = $title; 
		$this->link = $link; 
    $this->description = $description; 
    
		if($starttime === null and $starttime !== false){

      $this->starttime = microtime(true); 
    
    }	elseif($starttime !== false) {

      $this->starttime = floatval($starttime); 
    
    }

  } 
   
	public function caching($cachefile, $activate = true, $cachetime = 1000) { 

		$this->caching = true; 
		$this->cachefile = $cachefile; 
    $this->cachetime = $cachetime; 
    
  } 
  
	public function setLanguage($language) { 

    $this->language = $language; 
    
  } 
  
	public function setCopyright($copyright) { 

    $this->copyright = $copyright; 
    
  } 
   
	public function setManagingEditor($email, $name) { 

    $this->managingEditor = array("email" => $email, "name" => $name); 
    
  } 
  
	public function setWebMaster($email, $name) {
    
    $this->webMaster = array("email" => $email, "name" => $name); 
    
  } 
  
	public function setPublicDate($timestamp) { 

    $this->pubDate = date("r", $timestamp); 
    
  } 
  
	public function setLastBuildDate($timestamp) { 

    $this->lastBuildDate = date("r", $timestamp); 
    
  } 
  
	public function setCategory($category) { 

    $this->category = (array)explode(",", $category); 
     
  } 
   
	public function setDocs($docs) { 

    $this->docs = $docs; 
    
  } 
  
	public function setTTL($minutes) { 

    $this->ttl = $minutes; 
    
  } 
  
	public function setImage($url) { 

    $this->image = $url; 
    
  } 
   
	public function setTextInput($title, $description, $name, $link) { 

    $this->textInput = array("title" => $title, "description" => $description, "name" => $name, "link" => $link); 
    
  } 
  
	public function addItem($title, $description, $link = null, $html = false) { 

    $this->itemid++; 
    
		if($html !== false) {

      $description = htmlentities($description, ENT_QUOTES, $this->charset, true);
    
    }

    $this->items[$this->itemid] = array("title" => $title, "description" => $description, "name" => $name, "link" => $link); 
    
    return $this->itemid; 
    
  } 
   
	public function itemSetAuthor($email, $name, $item = null) { 

    if($item !== null and intval($item) > 0) {
    
      $item = intval($item); 
    
    }	else {

      $item = $this->itemid;
    
    } 

    $this->items[$item]["author"] = array("email" => $email, "name" => $name); 
    
  } 
  
	public function itemSetCategory($category, $item = null) { 

		if($item !== null and intval($item) > 0) {

      $item = intval($item); 
    
    }	else {

      $item = $this->itemid;
    
    }

    $this->items[$item]["category"] = (array)explode(",", $category); 
    
  } 
  
	public function itemSetComments($url, $item = null) { 

		if($item !== null and intval($item) > 0) {

      $item = intval($item); 
    
    }	else {

      $item = $this->itemid; 
    
    }

    $this->items[$item]["comments"] = $url; 
    
  } 
  
	public function itemSetEnclosure($url, $length, $type, $item = null) {

		if($item !== null and intval($item) > 0) {

      $item = intval($item); 
    
    }	else {

      $item = $this->itemid; 
    
    } 

    $this->items[$item]["enclosure"][] = array("url" => $url, "length" => $length, "type" => $type); 
    
  } 
  
	public function itemSetGUID($guid, $isPermaLink = "true", $item = null) { 

		if($item !== null and intval($item) > 0){

      $item = intval($item); 
    
    }	else {

      $item = $this->itemid;
    
    } 
		if(strval($isPermaLink) != "true") {

      $isPermaLink = "false"; 
    
    }

    $this->items[$item]["guid"] = array("guid" => $guid, "isPermaLink" => strval($isPermaLink)); 
    
  } 
  
	public function itemSetPublicDate($timestamp, $item = null) { 

		if($item !== null and intval($item) > 0) {

      $item = intval($item); 
    
    }	else {

      $item = $this->itemid; 
    
    }

    $this->items[$item]["pubDate"] = date("r", $timestamp); 
    
  } 
   
	public function itemSetSource($url, $name, $item = null) { 
		if($item !== null and intval($item) > 0) {

      $item = intval($item); 
    
    }	else {

      $item = $this->itemid; 
    
    }

    $this->items[$item]["source"] = array("url" => $url, "name" => $name); 
    
  } 
  
	private function itemOutput() { 

    $out = ""; 
    
		foreach ($this->items AS $item) { 

			$out .= "<item> 
      <title>" . $item["title"] . "</title> 
      <description>" . $item["description"] . "</description>\r\n"; 
			if(isset ($item["link"])) {

        $out .= "<link>" . $item["link"] . "</link>\r\n"; 
      
      }

			if(isset ($item["author"])) {

        $out .= "<author>" . $item["author"]["email"] . " (" . $item["author"]["name"] . ")</author>\r\n"; 
      
      }

			if(isset ($item["category"])) {

        $out .= "<category>" . implode("</category>\r\n<category>", $item["category"]) . "</category>\r\n"; 
      
      }

			if(isset ($item["comments"])) {

        $out .= "<comments>" . $item["comments"] . "</comments>\r\n"; 
      
      }

			if(isset ($item["guid"])) {

        $out .= "<guid isPermaLink=\"" . $item["guid"]["isPermaLink"] . "\">" . $item["guid"]["guid"] . "</guid>\r\n"; 
      
      }

			if(isset ($item["pubDate"])) {

        $out .= "<pubDate>" . $item["pubDate"] . "</pubDate>\r\n"; 
      
      }
			if(isset ($item["source"])) {

        $out .= "<source url=\"" . $item["source"]["url"] . "\">" . $item["source"]["name"] . "</source>\r\n"; 
      
      }

			if(isset ($item["enclosure"])) { 

				foreach ($item["enclosure"] AS $enclosure) {

          $out .= "<enclosure url=\"" . $enclosure["url"] . "\" length=\"" . $enclosure["length"] . "\" type=\"" . $enclosure["type"] . "\" />\r\n"; 
        
        }

      } 
      
      $out .= "</item>\r\n"; 
      
    } 
    
    return $out; 
    
  } 
  
	public function output() { 

		if($this->caching === true and filemtime($this->cachefile) + $this->cachetime > time()) { 

      $handle=fopen($this->cachefile, "wb"); 
      
      $out=fread($handle, filesize($this->cachefile)); 
      
      fclose($handle); 
      
			if($out !== false) { 

        echo $out; 
        
        return true; 
        
      } 
      
    } 
    
		$out = "<?xml version=\"1.0\" ?> 
    <rss version=\"2.0\" xmlns:atom=\"http://www.w3.org/2005/Atom\"> 
    <channel> 
    <title>" . $this->title . "</title> 
    <link>" . $this->link . "</link> 
    <description>" . $this->description . "</description>\r\n"; 

		if(strlen($this->language) > 0) {

      $out .= "<language>" . $this->language . "</language>\r\n"; 
    
    }

		if(strlen($this->copyright) > 0) {

      $out .= "<copyright>" . $this->copyright . "</copyright>\r\n"; 
    
    }

		if(count($this->managingEditor) > 0) {

      $out .= "<managingEditor>" . $this->managingEditor["email"] . " (" . $this->managingEditor["name"] . ")</managingEditor>\r\n"; 
    
    }

		if(count($this->webMaster) > 0) {

      $out .= "<webMaster>" . $this->webMaster["email"] . " (" . $this->webMaster["name"] . ")</webMaster>\r\n";
    
    } 
    
		if(strlen($this->pubDate) > 0) {

      $out .= "<pubDate>" . $this->pubDate . "</pubDate>\r\n";

    } 
      
		if(strlen($this->lastBuildDate) > 0) {

      $out .= "<lastBuildDate>" . $this->lastBuildDate . "</lastBuildDate>\r\n"; 
    
    }
      
		if(count($this->category) > 0) {

      $out .= "<category>" . implode("</category>\r\n<category>", $this->category) . "</category>\r\n";
    
    } 
      
		if(strlen($this->docs) > 0) {

      $out .= "<docs>" . $this->docs . "</docs>\r\n";
    
    } 
      
		if(strlen($this->ttl) > 0) {

      $out .= "<ttl>" . $this->ttl . "</ttl>\r\n"; 
    
    }
      
		if(strlen($this->image) > 0) {

			$out .= "<image> 
      <url>" . $this->image . "</url> 
      <title>" . $this->title . "</title> 
      <link>" . $this->link . "</link> 
      </image>\r\n"; 

    }

		if(count($this->textInput) > 0) {

			$out .= "<textInput> 
      <title>" . $this->textInput["title"] . "</title> 
      <description>" . $this->textInput["description"] . "</description> 
      <name>" . $this->textInput["name"] . "</name> 
      <link>" . $this->textInput["link"] . "</link> 
      </textInput>\r\n"; 
    
    }

    $out .= "<atom:link href=\"http://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]."\" rel=\"self\" type=\"application/rss+xml\" />\r\n";
    $out .= $this->itemOutput(); 
    $out .= "</channel> 
    </rss>"; 

		if($starttime !== false) {

      $out .= "\r\n<!-- Generated in " . number_format(round((microtime(true)-$this->starttime), 2), 2, ",", ".") . "ms -->";
    
    }
      
		if($this->caching === true and filemtime($this->cachefile) + $this->cachetime < time()) { 

      $handle=fopen($this->cachefile, "wb"); 
      
      fwrite($handle, $out); 
      
      fclose($handle); 
      
    } 
    
    echo $out; 
    
  } 
  
} 

?>
