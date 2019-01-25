<?php
class m_seo extends masterModule{

	private $hash = array();
	private $site;
	private $html;
	private $actual;

	function m_seo(){
		global $settings;
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> seo Module >> model/seo.php-> m_seo()\n");

	}

	###########################
	# Object (words)          #
	###########################
	// List Object
	public function m_listObject($viewMode, $filter = null){
		global $settings, $system, $lang;
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> seo Module >> model/seo.php-> m_listObject($viewMode, $filter)\n");

		$filter = !empty($filter) ? $system->filterSplitter($filter) : null;
		$system->xorg->pagination->paginateStart("seo", "c_$viewMode", "`base`.`id`, `base`.`active`, `base`.`name`, `$settings[seoCategory]`.`name`", "`$settings[seoObject]` as `base`, `$settings[seoCategory]`", "`base`.`category` = `$settings[seoCategory]`.`id` $filter", "`base`.`timeStamp` DESC", "", "", "", "", 20, 7);

		$count = 1;
		while ($row = $system->dbm->db->fetch_array()){

			$entityList[$count][num] = $count;
			$entityList[$count][active] = $row[active];
			$entityList[$count][id] = $row[id];
			$entityList[$count][name] = $row[name];
			$entityList[$count][category] = $row[category];
			$entityList[$count][timeStamp] = $system->time->iCal->dator($row[timeStamp]);

			$count++;
		}
		$system->xorg->smarty->assign("navigation", $system->xorg->pagination->renderFullNav());
		$system->xorg->smarty->assign("entityList", $entityList);
		$system->xorg->smarty->display($settings['moduleAddress'] . "/" . $settings['moduleName'] . "/view/tpl/object/$viewMode" . $settings['ext4']);
	}
	###########################
	# Sitemap Generator       #
	###########################

	public function m_sitemapGenerate($site, $navigate=true){
		global $settings, $system, $lang;
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> seo Module >> model/seo.php-> m_sitemapGenerate($site, $navigate)\n");

		$this->site = (strstr($site, 'http://')) ? $site : 'http://' . $site;
		if($navigate) {
			$this->link($this->site);
		}
		
		$path = ($_SESSION[uid] == 1) ? 'sitemap.xml' : 'home/'. $_SESSION['uid'] . '/files/sitemap.xml';
		
		$f = fopen($path, 'w+');
		$r = fwrite($f, $this->generateSiteMap());
		fclose($f);
//		$this->ping($this->site . 'sitemap.xml');
		$system->watchDog->exception("s", $lang['sitemapGenerated'], sprintf($lang['successfulDone'], sprintf($lang['sitemapGeneratedDownloadIt'], "<a href='$settings[domain]/$path'>$lang[here]</a>")));
	}

	public function getTitle() {
		global $settings;
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> seo Module >> model/seo.php-> getTitle()\n");
		
		$preg2="/<title>(.*?)<\/title>/i";
		$title = array();
		preg_match($preg2,$this->html,$title);
		return $title[1];
	}

	public function getLinks() {
		global $settings;
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> seo Module >> model/seo.php-> getLinks()\n");
		
		$preg = "/<a.*? href=(\"|')(.*?)(\"|').*?>(.*?)<\/a>/i";
		$links = array();
		preg_match_all($preg,$this->html,$links);
		$urlsTemp = $links[2];
		$linksTemp = $links[4];
		$urls = array();
		$baseUrl = '';
		$linkTexts = array();

		// set base url

		// make sure we use real url in case of redirection
		$headers = get_headers($this->actual, 1);
		if (isset($headers['Location'])) {
			if (strpos($headers['Location'], $this->site) !== false) {
				$baseUrl = $headers['Location'];
			} else {
				$baseUrl = $this->site . '/' . $headers['Location'];
			}
		} else {
			$baseUrl = $this->actual;
		}

		if ( !(substr($baseUrl, -1) == '/') ) {
			if ( is_dir($baseUrl) ) { // if base url is a dir without trailing slash
				$baseUrl .= '/';
			} else if ( $baseUrl != $this->site ) { // base url is a file and not the root
				$baseUrl = str_replace(basename($baseUrl), '', $baseUrl);
			}
		}

		foreach ($urlsTemp as $i=>$url){
			if(strstr($url,$this->site)) { //If it has link to absolute url path with domain
				$urls[] = $url;
				//$linkTexts[] = $linksTemp[$i];
			} else if( substr($url, 0, 1) == '/' ) { //If it has link to absolute url path without domain
				$urls[] = $this->site . $url;
				//$linkTexts[] = $linksTemp[$i];
			} else if( !preg_match("/^(mailto|http|\#)/",$url) ) { //If it has link to a relative URL.

				if (substr($url,0,2) == './') {
					$urls[] = $baseUrl . substr($url,2);
				} else if (substr($url,0,3) == '../') {
					$pad = substr_count($url,'../');
					$baseUrlTemp = $baseUrl;
					for ($i=1; $i<=$pad+1; $i++) {
						$baseUrlTemp = substr($baseUrlTemp, 0, strrpos($baseUrlTemp, '/') );
					}
					$urls[] = $baseUrlTemp . '/' . str_replace('../','',$url);
				} else {
					$urls[] = $baseUrl . $url;
				}

			}
		}
		
		return $urls;
	}

	public function link($link) {
		global $settings;
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> seo Module >> model/seo.php-> link($link)\n");
		
		// remove hash portion of url to make sure the url isn't reprocessed
		$hashPos = strpos($link,'#');
		if ($hashPos !== false) $link = substr($link, 0, $hashPos);
		if(!empty($link) && !isset($this->hash[$link])) {
			$this->actual = $link;
			$this->navigate();
		}
	}

	public function navigate() {
		global $settings;
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> seo Module >> model/seo.php-> navigate()\n");
		
		$this->html = file_get_contents($this->actual);

		if ($this->html) { // make sure we have something to parse
			// remove html comments in order to avoid hidden urls
			$this->html = preg_replace('/<!--.*?-->/s','',$this->html);
			$title = $this->getTitle();
			$this->hash[$this->actual] = $title;

			if($title==null || $title=="") {
				$this->hash[$this->actual] = "Untitled";
			}

			$links = $this->getLinks();
			foreach($links as $link) {
				$this->link($link);
			}
		}
	}

	public function getHash() {
		global $settings;
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> seo Module >> model/seo.php-> getHash()\n");
		
		return $this->hash;
	}

	public function generateSiteMap() {
		global $settings;
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> seo Module >> model/seo.php-> generateSiteMap()\n");
		
		$xml = new SimpleXMLElement("<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\"></urlset>");
		$xml->addAttribute('encoding', 'UTF-8');
//		print_r($this->hash);
		foreach($this->hash as $url=>$title) {
			$urlNode = $xml->addChild("url");
			$urlNode->addChild("loc",$url);
			$priority = 0.5;
			if($url == $this->site) {
				$priority = 1.0;
			}
			$urlNode->addChild("priority",$priority);
		}
		return $xml->asXML();
	}

	public function ping($sitemap_url){
		global $settings;
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> seo Module >> model/seo.php-> ping($sitemap_url)\n");
		
		$curl_req = array();
		$urls = array();
		$urls[] = "http://www.google.com/webmasters/tools/ping?sitemap=".urlencode($sitemap_url);
		$urls[] = "http://www.bing.com/webmaster/ping.aspx?siteMap=".urlencode($sitemap_url);
		$urls[] = "http://search.yahooapis.com/SiteExplorerService/V1/updateNotification?appid=YahooDemo&url=".urlencode($sitemap_url);
		$urls[] = "http://submissions.ask.com/ping?sitemap=".urlencode($sitemap_url);

		foreach ($urls as $url){
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURL_HTTP_VERSION_1_1, 1);
			$curl_req[] = $curl;
		}
		//initiating multi handler
		$multiHandle = curl_multi_init();

		// adding all the single handler to a multi handler
		foreach($curl_req as $key => $curl){
			curl_multi_add_handle($multiHandle,$curl);
		}

		do{
			$multi_curl = curl_multi_exec($multiHandle, $isactive);
		}
		while ($isactive || $multi_curl == CURLM_CALL_MULTI_PERFORM );

		$success = true;
		foreach($curl_req as $curlO){
			if(curl_errno($curlO) != CURLE_OK)
			{
				$success = false;
			}
		}
		curl_multi_close($multiHandle);
		return $success;
	}

}
?>