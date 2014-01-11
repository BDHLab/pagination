<?php

namespace BDHLab\Pagination;

class Pagination {
	
	private $limit_i = 10;			// Number of items to display
	private $limit_p = 3;			// Number of pages to display
	private $url = '';				// Url to base the pagination on
	private $leftpages = 0;			// Number of pages to show on left
	private $rightpages = 0;			// Number of pages to show on right
	private $x = 0;					// SQL LIMIT param1
	private $y = 0;					// SQL LIMIT param2
	private $pages = 0;
	private $page = 0;

	public function __construct($settings) {
		$this->prepare($settings);
	}
	
	private function prepare ($data){
		if (is_array($data)){
			
			if (array_key_exists('url', $data)){
				$this->url = $data['url'];
			}
			
			if (array_key_exists('total_rows', $data)){
				$total_rows = $data['total_rows'];
			}
			
			if (array_key_exists('max_items', $data)){
				$this->limit_i = $data['max_items'];
			}
			
			if (array_key_exists('max_pages', $data)){
				$this->limit_p = $data['max_pages'];
			}
			
			if (array_key_exists('current_page', $data)){
				$this->page = $data['current_page'];
			}
			
		}else{
			return 0;
		}
		
		$this->x = ($this->page-1)*$this->limit_i;
		$this->y = $this->limit_i;
		
		if ($this->limit_i<=$total_rows){
			if (($total_rows/$this->limit_i) > (floor($total_rows/$this->limit_i))){
				$this->pages = floor(($total_rows/$this->limit_i)+1);
			}else{
				$this->pages = floor($total_rows/$this->limit_i);
			}
		}else{
			$this->pages = 1;
		}
	}
	
	public function toArray (){
		if ($this->page > $this->pages){
			return null;
		}
		
		$return = array ();
		
		if ($this->pages>=1){
			
			if (($this->page-1)!=0){ // Previous Page
				$return[] = array (
					'name' => '<',
					'url' => $this->url.($this->page-1)
				);
			}else {
				$return[] = array (
					'name' => '<',
					'url' => '#'
				);
			}
			
			if ($this->page!=1){
				$return[] = array (
					'name' => 1,
					'url' => $this->url.'1'
				);
			}
			
			$temp = array ();
			$y = 1;
			for ($i = ($this->page-1); $y <= $this->limit_p; $i--){ // Loop through pages
				if ($i<=1){
					break;
				}
				$temp[] = $i;
				
				$y++;
			}

			if (!empty($temp)){
				if (($temp[sizeof($temp)-1]-1)==1){
					
				}else{
					$return[] = array (
						'name' => '...',
						'url' => ''
					);
				}
			}
			
			$temp = array_reverse($temp);
			
			foreach ($temp as $item){
				$return[] = array (
					'name' => $item,
					'url' => $this->url.$item
				);
			}
			
			$return[] = array (
				'name' => $this->page,
				'url' => $this->url.$this->page,
				'selected' => 1
			);
			
			for ($i = 1; $i <= $this->limit_p; $i++){ // Loop through pages
				if (($this->page + $i)>=$this->pages){
					break;
				}
				$return[] = array (
					'name' => $this->page+$i,
					'url' => $this->url.($this->page+$i)
				);
			}
			
			if (($this->page+$i)==$this->pages || $this->page == $this->pages){
				
			}else{
				$return[] = array (
					'name' => '...',
					'url' => ''
				);
			}
			
			if ($this->page!=$this->pages){
				$return[] = array (
					'name' => $this->pages,
					'url' => $this->url.$this->pages
				);
			}
			
			if (($this->page+1)<=$this->pages) { // Next Page
				$return[] = array (
					'name' => '>',
					'url' => $this->url.($this->page+1)
				);
			}else {
				$return[] = array (
					'name' => '>',
					'url' => '#'
				);
			}
		}
		
		return $return;
	}
}