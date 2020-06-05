<?php
class Paginate{
    public $current_page;
    public $items_per_page;
    public $items_total_count;


    public function __construct($page=1, $items_per_page=4, $items_total_count=0){
        $this->current_page      = (int)$page;
        $this->items_per_page    = (int)$items_per_page;
        $this->items_total_count = (int)$items_total_count;
    }//End of the __construct Method

    public function next(){
        return $this->current_page + 1;
    }//End of the next Metho

    public function previous(){
        return $this->current_page - 1;
    }//End of the previous Method

    public function page_total(){
        return ceil($this->items_total_count/$this->items_per_page);
    }//End of the page_total Method

    public function has_previous(){
        return $this->previous() >= 1 ? true : false;
    }//End of the hash_previous Method

    public function has_next(){
        return $this->next() <= $this->page_total() ? true : false;
    }//End of the hash_next Method

    public function offset(){
        return ($this->current_page - 1) * $this->items_per_page;
    }//End oh thr offset Method



}//En of the paginate Class



?>