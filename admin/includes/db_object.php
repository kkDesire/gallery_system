<?php

class Db_object{

    public $errors = array();
    public $upload_errors_array = array(
        UPLOAD_ERR_OK          => "There no error" ,
        UPLOAD_ERR_INI_SIZE    => "The upload file exceeds the upload_max_filesize directive in php.ini.",
        UPLOAD_ERR_FORM_SIZE   => "The upload file exceed the MAX_FILE_SIZE directive that was specified in the html.",
        UPLOAD_ERR_PARTIAL     => "The upload file was only partially upload.",
        UPLOAD_ERR_NO_FILE     => "No file was upload",
        UPLOAD_ERR_NO_TMP_DIR  => "Missing a temporary folder",
        UPLOAD_ERR_CANT_WRITE  => "Failed to write file to disk",
        UPLOAD_ERR_EXTENSION   => "A PHP extension stopped the file upload"
    );


    public function set_file($file){
        if (empty($file) || !$file || !is_array($file)){
            $this->errors[] = "There was no file upload here";
            return false;
        }elseif ($file['error'] != 0){
            $this->errors[] = $this->upload_errors_array[$file['error']];
            return false;
        }else{
            $this->user_image = basename($file['name']);
            $this->tmp_path = $file['tmp_name'];
            $this->type     = $file['type'];
            $this->size     = $file['size'];
        }
    }//End of the set_file Method



    public static function find_all(){
        return static::find_by_query("SELECT * FROM " . static::$db_table ." ");
    }//End of the find_all Method

    public static function find_by_id($id){
        global $database;
        $the_result_array = static::find_by_query("SELECT * FROM " . static::$db_table . " WHERE id = {$id} LIMIT 1");
        return !empty($the_result_array) ? array_shift($the_result_array) : false;
    }//End of the Find_by_id Method

    public static function find_by_query($sql){
        global $database;
        $result_set = $database->query($sql);
        $the_object_array = array();
        while($row = mysqli_fetch_array($result_set)){
            $the_object_array[] = static::instantiation($row);
        }
        return $the_object_array;
    }// End of the Find_this_query Method

    public static function instantiation($the_record){

        $calling_class = get_called_class();
        $the_object = new $calling_class;
        foreach ($the_record as $the_attribute => $value){
            if ($the_object->has_the_attribute($the_attribute)){
                $the_object->$the_attribute = $value;
            }
        }
        return $the_object;
    }//End of the intatiation Method

    private function has_the_attribute($the_attribute){
        $object_properties = get_object_vars($this);
        return array_key_exists($the_attribute, $object_properties);
    }//End of the has_the_attribute Method

    protected function properties(){

        $properties = array();
        foreach (static::$db_table_fiels as $db_field){

            if (property_exists($this, $db_field)){

                $properties[$db_field] = $this->$db_field;
            }

        }
        return $properties;
    }//End of the Properties Method

    protected function clean_properties(){
        global $database;
        $clean_properties = array();

        foreach ($this->properties() as $key => $value){
            $clean_properties[$key] = $database->escape_string($value);
        }
        return $clean_properties;
    }//End the of clean_properties Method


    public function save(){
        return isset($this->id) ? $this->update() : $this->create();
    }//End of the save Method

    public function create(){
        global $database;

        $properties = $this->clean_properties();

        $sql = "INSERT INTO " . static::$db_table . "(" . implode(",", array_keys($properties)) . ")";
        $sql .= "VALUES('" . implode("','", array_values($properties)) . "')";

        if ($database->query($sql)){

            $this->id = $database->the_insert_id();

            return true;

        }else{

            return false;

        }

    }//End of Create Method


    public function update(){
        global $database;

        $properties = $this->clean_properties();
        $properties_pairs = array();

        foreach ($properties as $key => $value) {
            $properties_pairs[] = "{$key} = '{$value}'";
        }

        $sql = "UPDATE " . static::$db_table . " SET ";
        $sql .= implode(",", $properties_pairs);
        $sql .= " WHERE id= " . $database->escape_string($this->id);

        $database->query($sql);
        return (mysqli_affected_rows($database->connection) == 1) ? true : false;


    }//End of Update Methode

    public function delete(){
        global $database;
        $sql = "DELETE FROM " . static::$db_table . " ";
        $sql .= "WHERE id=" . $database->escape_string($this->id);
        $sql .= " LIMIT 1";
        $database->query($sql);

        return (mysqli_affected_rows($database->connection) == 1) ? true : false;
    }//End of Delete Method


    public static function count_all(){
        global $database;
        $sql = "SELECT COUNT(*) FROM " . static::$db_table;
        $result_set = $database->query($sql);
        $row = mysqli_fetch_array($result_set);
        return array_shift($row);//La fontion array_shift extrait le premier element du table et si on fait un print_r sur le tableau la premiere valeur n'y sera plus :)
    }





}//End of the Db_object Class

?>