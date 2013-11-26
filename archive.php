<?php
class archive {
    /*
     *Initialize the class that handles all the database operations.
     * Here it has been name archiveModel.php
    */
	public function archive(){
		if(file_exists('archiveModel.php'))
            require_once('archiveModel.php');
			$this->model = new archiveModel;
	}
    /*
     * This is the control function of the archiving concept
     * It loads the datas(title and date) from the database
     * and then passes the datas to next function to build an array
     * of these datas suitable to build the archive tree.
     */
    public function archives() {
        $archives = $this->model->getArchivePost();
        $this->archives = $this->buildArray($archives);
    }
    /*
     * This function actually builds an array of variable that
     * must be displayed in the archive.
     * e.g. The display is in the format
     * year(num_post) inside it are months(num_post) in each month and
     * in each month the title of all the blogs posted in that month
     */
    public function buildArray($data) {
        $archive = array();//will be used to our archived data
        $years = $this->parse_date($data, 'Y');
        rsort($years);
        //create array of years and their occurances i.e. how many posts have been posted in a particular year
        foreach ($years as $value):
            if ($this->array_has_key($archive, $value)) {
                $archive[$value]['count'] = ++$count;
            } else {
                $count = 1;
                $archive[$value] = array('count'=>$count,'months'=>array());
            }
        endforeach;
        //create array of months for each year
        foreach($archive as $key => $value):
            $months = $this->parse_date($data, 'F', array(0 => 'Y', 1 => $key));
            rsort($months);
            foreach($months as $values):
                if($this->array_has_key($archive[$key]['months'], $values))
                        $archive[$key]['months'][$values]['count'] = ++$count;
                else {
                    $count = 1;
                    $archive[$key]['months'][$values] = array('count' => $count, 'posts' => array());
                }
            endforeach;
        endforeach;
        //Now we assign each post to its corresponding year and month
        foreach ($data as $value) {
            if (is_array($value)):
                foreach ($value as $key => $values):
                    if ($key == 'dateposted'):
                        $y = date('Y', strtotime($values));
                        $m = date('F', strtotime($values));
                    endif;
                endforeach;
                if ($this->array_has_key($archive, $y)) {
                    if ($this->array_has_key($archive[$y]['months'], $m))
                        array_push($archive[$y]['months'][$m]['posts'], $value['title']);
                }
            endif;
        }
        return $archive;
    }
    /*
     * Checks if the desired key of the array exists in it or not
     * return true on success and 
     * false on failure
     */
    private function array_has_key($array,$key){		
        if(!is_array($array))
            return false;
        foreach($array as $index => $value)
            if($key == $index)
                return true;
            return false;
    }
    /*
     * This function parses out year or month from the datas
     * and stores it in a variable
     * Parameters:-
     * 1). $data        :       The data to be parsed (it must be an array)
     * 2). $param       :       The paramter to be extracted out like 'Y','m',etc used in php date function
     * 3). $condition   :       Data to be extracted based on certain condition (must be an array)
     *                          I used this to extract months for a particular year
     *                          e.g. to extract the months for year 2013 it would be like
     *                          array(0=>'Y',1=>2013)
     */
    private function parse_date($data, $param,$condition=array()) {
	//we can enhance this function and parameters it accepts
        $temp = array();
        if(!is_array($data))//no need to proceed if $data is not an array
            return array();
        /*
         * Loop through the $data array
         */
        foreach ($data as $key => $value):
            if (is_array($value)) {
                foreach ($value as $keys => $values):
                    //dateposted is the column name in our table that stores tha date in yyyy-mm-dd hh:mm:ss format
                    if ($keys == 'dateposted'):
                        if (count($condition) == 0) {
                            $$param = date($param, strtotime($values));
                            array_push($temp, $$param);
                        } else {
                            if (date($condition[0], strtotime($values)) == $condition[1]) {
                                $$param = date($param, strtotime($values));
                                array_push($temp, $$param);
                            }
                        }
                    endif;
                endforeach;
            }
        endforeach;
        return $temp;
    }
    /*
     * Display the archived post
     */
    public function show() {
        if (isset($this->archives)):
            echo "\n";
            echo '<ul id="red" class="treeview-red">';
            foreach ($this->archives as $key => $value):
                echo "\n<li><span>{$key}({$value['count']})</span>\n<ul>";
                if (is_array($value)):
                    foreach ($value['months'] as $index => $values) {
                        if (is_array($values)):
                            echo "\n<li><span>{$index}({$values['count']})</span>\n<ul>";
                            foreach ($values['posts'] as $posts) {
                                $post = str_replace(' ', '-', $posts);
                                echo "\n<li><span><a href='#' target='_self'>{$posts}</a></span></li>";
                            }
                            echo "\n</ul>\n</li>";
                        endif;
                    }
                endif;
                echo "\n</ul>\n</li>\n";
            endforeach;
            echo '</ul><hr/>';
        endif;
    }
}
