<?php

class pagination extends system{
	public $php_self;
	public $rows_per_page;
	public $total_rows = 0;
	public $links_per_page;
	private $balanceNumber;
	public $append = null;
	public $result;
	public $page = 1;
	public $max_pages = 0;
	public $offset = 0;
	public $op;
	public $mode;
	private $filter;


	public function pagination() {
		global $settings;
		system::debug($settings['debugFile'], "chrF", "	Function=> pagination.php-> pagination()\n");
		
		$this->rows_per_page = 50;
		$this->links_per_page = 7;
	}

	public function paginateStart($op, $mode, $fields, $tables, $where=null, $order_by=null, $group_by=null, $having=null, $limit=null, $distinct=null, $rows_per_page=20, $links_per_page=7, $append = null){
		global $system, $settings;
		system::debug($settings['debugFile'], "chrF", "	Function=> pagination.php-> paginateStart($op, $mode, $fields, $tables, $where, $order_by, $group_by, $having, $limit, $distinct, $rows_per_page, $links_per_page, $append)\n");

//echo "<br>paginateStart=> $tables<br>";

		$this->op = $op;
		if($_GET[filter]){
			$this->filter = $_GET[filter];
		}elseif($_POST[filter]){
			$this->filter = $_POST[filter];
		}else{
			$this->filter = 1;
		}
//echo "<br>fields--> <br>($fields <br>* tables--> <br> $tables <br>* where--><br> $where <br>* order_by--> <br> $order_by <br>* group_by--> <br> $group_by <br>* having--> <br> $having <br>* limit--> <br> $limit <br>* distinct--> <br> $distinct)<br>";
//echo "<br> **** 0 **** <br>";
		$this->result = $system->dbm->db->select($fields, $tables, $where, $order_by, $group_by, $having, $limit, $distinct);
//echo "<br> **** 1 **** <br>";
		$this->op = $op;
		$this->mode = $mode;
		$this->rows_per_page = (int)$rows_per_page;
		$links_per_page = (int)$links_per_page;
		$this->links_per_page = ($links_per_page/2 != 0) ? $links_per_page : $links_per_page + 1;
		$this->balanceNumber = ($this->links_per_page-1)/2;
		$this->append = $append;
//echo "<br> **** 2 **** <br>";		
		$this->php_self = htmlspecialchars($_SERVER['PHP_SELF']);
//echo "<br> **** 3 **** <br>";

		if (isset($_GET['p'])) {
			$this->page = intval($_GET['p']);
//echo "<br> **** 4 **** <br>";
		}

//echo "<br> **** 5 **** <br>";
		$this->total_rows = $system->dbm->db->count_rows($this->result);
//*****		
// echo "<p style='direction:ltr;color:red;'> total_rows =>> ".$this->total_rows."<p>"; 
		$this->max_pages = ceil($this->total_rows / $this->rows_per_page );
//echo "<br> **** 6 **** <br>";

		if ($this->links_per_page > $this->max_pages) {
			$this->links_per_page = $this->max_pages;
		}
//echo "<br> **** 7 **** <br>";
		if ($this->page > $this->max_pages || $this->page <= 0) {
			$this->page = 1;
		}
//echo "<br> **** 8 **** <br>";
		$this->offset = $this->rows_per_page * ($this->page - 1);
//echo "<br>(fields--> $fields <br>*<br> tables--> $tables <br>*<br> where--> $where <br>*<br> order_by--> $order_by <br>*<br> group_by--> $group_by <br>*<br> having--> $having <br>*<br> limit-offset--> $this->offset <br> , <br>  limit-rows_per_page--> $this->rows_per_page <br>*<br> distinct--> $distinct)<br>";
		$rs = $system->dbm->db->select($fields, $tables, $where, $order_by, $group_by, $having, "$this->offset, $this->rows_per_page", $distinct);
//*****		
// echo "<br><p style='direction:ltr;color:green;'> \$rs =>> ";
// print_r ($rs);
// echo "</p>";
		return $rs;
	}

	public function renderFirst() {
		global $settings;
		system::debug($settings['debugFile'], "chrF", "	Function=> pagination.php-> renderFirst()\n");

		if ($this->total_rows == 0)
		return FALSE;

		if ($this->page == 1) {
			return null;
		} else {
			return "<a href='/$this->op/$this->mode/$this->filter/1/$this->append'><span class='navigateSprite navigate-firstPage'>&nbsp;&nbsp;</span></a>";
		}
	}

	public function renderLast() {
		global $settings;
		system::debug($settings['debugFile'], "chrF", "	Function=> pagination.php-> renderLast()\n");

		if ($this->total_rows == 0)
		return FALSE;

		if ($this->page == $this->max_pages) {
			return null;
		} else {
			return "<a href='/$this->op/$this->mode/$this->filter/$this->max_pages/$this->append'><span class='navigateSprite navigate-lastPage'>&nbsp;&nbsp;</span></a>";
		}
	}

	public function renderNext() {
		global $settings;
		system::debug($settings['debugFile'], "chrF", "	Function=> pagination.php-> renderNext()\n");

		if ($this->total_rows == 0)
		return FALSE;

		if ($this->page < $this->max_pages) {
			$nextPage = $this->page+1;
			return "<a href='/$this->op/$this->mode/$this->filter/$nextPage/$this->append'><span class='navigateSprite navigate-nextPage'>&nbsp;&nbsp;</span></a>";
		} else {
			return null;
		}
	}

	public function renderPrev() {
		global $settings;
		system::debug($settings['debugFile'], "chrF", "	Function=> pagination.php-> renderPrev()\n");

		if ($this->total_rows == 0)
		return FALSE;

		if ($this->page > 1) {
			$prevPage = $this->page-1;
			return "<a href='/$this->op/$this->mode/$this->filter/$prevPage/$this->append'><span class='navigateSprite navigate-prevPage'>&nbsp;&nbsp;</span></a>";
		} else {
			return null;
		}
	}

	public function renderNav() {
		global $settings;
		system::debug($settings['debugFile'], "chrF", "	Function=> pagination.php-> renderNav()\n");
		
		if ($this->total_rows == 0)
		return FALSE;
		
		$end = $this->page + $this->balanceNumber;
		if ($end > $this->max_pages) {
			$end = $this->max_pages;
		}
		
		$start = $this->page - $this->balanceNumber;
		if($start < $this->balanceNumber){
			$start = 1;
		}
		$links = '';

		for($i = $start; $i <= $end; $i ++) {
			if ($i == $this->page) {
				$links .= "<span class='currentPage'>$i</span>";
			} else {
				$links .= "<a class='navCell' href='$this->op/$this->mode/$this->filter/$i/$this->append'>$i</a>";
			}
		}

		return $links;
	}

	public function renderFullNav() {
		global $lang, $settings, $system;
		system::debug($settings['debugFile'], "chrF", "	Function=> pagination.php-> renderFullNav()\n");

		$system->xorg->smarty->assign("op", $this->op);
		$system->xorg->smarty->assign("totalRows", $this->total_rows);
		$system->xorg->smarty->assign("renderFirst", $this->renderFirst());
		$system->xorg->smarty->assign("renderPrev", $this->renderPrev());
		$system->xorg->smarty->assign("renderNav", $this->renderNav());
		$system->xorg->smarty->assign("renderNext", $this->renderNext());
		$system->xorg->smarty->assign("renderLast", $this->renderLast());

		return $system->xorg->smarty->fetch($settings['commonTpl'] . "paginateNavigate" . $settings['ext4']);
	}
}
?>