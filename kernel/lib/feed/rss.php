<?php

class rss extends system {
	
	private $encoding = "UTF-8";
    private $title;
    private $language;
    private $description;
    private $link;
    private $generator = "icmf_rss_generator";
    private $version = "2.0";

	function rss(){
		global $settings;
		system::debug($settings['debugFile'], "chrF", "	Function=> rss.php-> rss()\n");
		
		$this->title = $settings['websiteTitle'];
    	$this->language = $settings['language'];
    	$this->description = $settings['description'];
//    	echo ($this->language);
    	
	}

    public function create($items) {
    	global $settings;
		system::debug($settings['debugFile'], "chrF", "	Function=> rss.php-> create($items)\n");
   	
        $res = "";
        // header
        $res .= "<?xml version=\"1.0\" encoding=\"".$this->encoding."\"?>\n";
        $res .= "<rss version=\"$this->version\">\n";
        $res .= "\t<channel>\n";
        $res .= "\t\t<title><![CDATA[".$this->title."]]></title>\n";
        $res .= "\t\t<description><![CDATA[".$this->description."]]></description>\n";
        $res .= "\t\t<link>".$this->link."</link>\n";
        $res .= "\t\t<language>".$this->language."</language>\n";
        $res .= "\t\t<generator>".$this->generator."</generator>\n";
        //items
        foreach($items as $item) {
            $res .= "\t\t<item>\n";
            $res .= "\t\t\t<category>".stripslashes($item['category'])."</category>\n";
            $res .= "\t\t\t<title><![CDATA[".stripslashes($item['title'])."]]></title>\n";
            $res .= "\t\t\t<description><![CDATA[".stripslashes($item['brief'])."]]></description>\n";
            if (!empty($item['timeStamp']))
            $res .= "\t\t\t<pubDate>".stripslashes($item['timeStamp'])."</pubDate>\n";
            if (!empty($item["link"]))
            $res .= "\t\t\t<link>http://$settings[domainName]".stripslashes($item["link"])."</link>\n";
            $res .= "\t\t</item>\n";
        }
        //footer
        $res .= "\t</channel>\n";
        $res .= "</rss>\n";
//        print_r ($res);
//        echo $res;
        return $res;
        
    }

	public function load($source, $count=2){
		global $settings;
		system::debug($settings['debugFile'], "chrF", "	Function=> rss.php-> load($source, $count)\n");

		$xmlDoc = new DOMDocument();
		$xmlDoc->load($source);
		
		//get elements from "<channel>"
		$channel = $xmlDoc->getElementsByTagName('channel')->item(0);
		$feed[channelLink] = $channel->getElementsByTagName('title')->item(0)->childNodes->item(0)->nodeValue;
		$feed[channelTitle] = $channel->getElementsByTagName('link')->item(0)->childNodes->item(0)->nodeValue;
		$feed[channelDescription] = $channel->getElementsByTagName('description')->item(0)->childNodes->item(0)->nodeValue;

		//get and output "<item>" elements
		$x = $xmlDoc->getElementsByTagName('item');
		for ($i=0; $i<=$count; $i++){
			$feed[items][$i][title] = $x->item($i)->getElementsByTagName('title')->item(0)->childNodes->item(0)->nodeValue;
			$feed[items][$i][link] = $x->item($i)->getElementsByTagName('link')->item(0)->childNodes->item(0)->nodeValue;
			$feed[items][$i][description] = $x->item($i)->getElementsByTagName('description')->item(0)->childNodes->item(0)->nodeValue;
		}
		
		return $feed;
	}

}

?>