<?php

class DatabaseObject {

    /*
    * Counts all rows in this table.
    * Returns number of rows
    */
    static function count_all() {
        global $db;

        $query  = "SELECT COUNT(*) FROM `" . static::$table_name . "`";
        $result_set = $db->query($query);
        $row = $db->fetch_assoc($result_set);
        return array_shift($row);
    }

    /*
    * Counts all rows in this table under some condition(s).
    * @param assoc_array may include key-value pairs as conditions.
    * Returns number of rows
    */
    static function count_with($assoc_array) {
        if (empty($assoc_array))
            return false;

        global $db;

        $query  = "SELECT COUNT(*) FROM `" . static::$table_name . "` ";
        $query .= "WHERE ";
        $first = true;
        foreach ($assoc_array as $field => $value) {
            if ($first) {
                $query .= "`{$field}` = '{$value}' ";
                $first = false;
            }
            else {
                $query .= " AND `{$field}` = '{$value}' ";
            }
        }
        $result_set = $db->query($query);
        $row = $db->fetch_assoc($result_set);
        return array_shift($row);
    }

    static function count_by_query($query = "") {
        global $db;

        $result_set = $db->query($query);
        $row = $db->fetch_assoc($result_set);
        return array_shift($row);
    }

    /*
    * Checks if row exists under some condition(s).
    * @param assoc_array may include key-value pairs as conditions.
    * Returns true if exists, false otherwise.
    */
    static function is_exist_with($assoc_array, $assoc_array_not_equal = array()) {
        if (empty($assoc_array))
            return false;

        global $db;

        $query  = "SELECT COUNT(*) FROM `" . static::$table_name . "` ";
        $query .= "WHERE ";
        $first = true;
        foreach ($assoc_array as $field => $value) {
            if ($first) {
                $query .= "`{$field}` = '{$value}' ";
                $first = false;
            }
            else {
                $query .= " AND `{$field}` = '{$value}' ";
            }
        }
        foreach ($assoc_array_not_equal as $field => $value) {
            $query .= " AND `{$field}` != '{$value}' ";
        }
        $query .= "LIMIT 1";
        $result_set = $db->query($query);
        $row = $db->fetch_assoc($result_set);
        return (array_shift($row) > 0);
    }

    /*
    * Finds all rows in the table.
    * @param fields may include specific required fields.
    * Returns array of rows.
    */
    static function find_all($fields = "*") {
        $query  = "SELECT {$fields} FROM `" . static::$table_name . "`";

        return static::find_by_query($query);
    }

    /*
    * Finds rows in the table by page.
    * @param page may include the required page
    * @param fields may include specific required fields.
    * Returns array of rows.
    */
    static function find_paginated($page = 1, $fields = "*", $where_clause = "", $order = "ASC") {

        $total_count = static::count_all();

        $pagination = new Pagination($page, $total_count, ITEMS_PER_PAGE);

        $query  = "SELECT {$fields} FROM `" . static::$table_name . "` ";
        $query .= $where_clause;
        $query .= "ORDER BY `" . ID . "` {$order} ";
        $query .= "LIMIT " . ITEMS_PER_PAGE . " ";
        $query .= "OFFSET " . $pagination->offset();

        return static::find_by_query($query);
    }

    /*
    * Finds row in the table by id.
    * @param id id to find
    * @param fields may include specific required fields.
    * Returns the specific row if found, false otherwise.
    */
    static function find_by_id($id = 0, $fields = "*") {
        $query  = "SELECT {$fields} FROM `" . static::$table_name . "` ";
        $query .= "WHERE `" . ID . "` = '{$id}' ";
        $query .= "LIMIT 1";

        $result_array = static::find_by_query($query);
        return !empty($result_array) ? array_shift($result_array) : false;
    }

    /*
    * Finds row in the table under some consition(s).
    * @param assoc_array may include key-value pairs as conditions.
    * @param fields may include specific required fields.
    * Returns the specific row if found, false otherwise.
    */
    static function find_element_by($assoc_array, $fields = "*", $order = "ASC") {
        if (empty($assoc_array))
            return false;

        $query  = "SELECT {$fields} FROM `" . static::$table_name . "` ";
        $query .= "WHERE ";
        $first = true;
        foreach ($assoc_array as $field => $value) {
            if ($first) {
                $query .= "`{$field}` = '{$value}' ";
                $first = false;
            }
            else {
                $query .= "AND `{$field}` = '{$value}' ";
            }
        }
        $query .= "ORDER BY `" . ID . "` {$order} ";
        $query .= "LIMIT 1";


        $result_array = static::find_by_query($query);
        return !empty($result_array) ? array_shift($result_array) : false;
    }

    /*
    * Finds rows in the table under some consition(s).
    * @param assoc_array may include key-value pairs as conditions.
    * @param fields may include specific required fields.
    * Returns array of rows.
    */
    static function find_elements_by($assoc_array, $fields = "*") {
        if (empty($assoc_array))
            return false;

        $query  = "SELECT {$fields} FROM `" . static::$table_name . "` ";
        $query .= "WHERE ";
        $first = true;
        foreach ($assoc_array as $field => $value) {
            if ($first) {
                $query .= "`{$field}` = '{$value}' ";
                $first = false;
            }
            else {
                $query .= " AND `{$field}` = '{$value}' ";
            }
        }

        return static::find_by_query($query);
    }

    /*
    * Finds rows in the table by specific query.
    * @param query query to execute
    * Returns array of rows.
    */
    static function find_by_query($query = "") {
        global $db;

        $result_set = $db->query($query);
        $object_array = array();
        while ($row = $db->fetch_assoc($result_set)) {
            $object_array[] = static::instantiate($row);
        }
        return $object_array;
    }

    static function find_entity_by_query($query = "") {
        global $db;

        $result_set = $db->query($query);
        $entity_array = array();
        while ($row = $db->fetch_assoc($result_set)) {
            $entity_array[] = array_shift($row);
        }
        return $entity_array;
    }

    /*
    * Instantiates the object with the row.
    * @param query query to execute
    * Returns array of rows.
    */
    private static function instantiate($row) {
        $object = new static;
        foreach ($row as $key => $value) {
            if ($object->has_attribute($key)) {
                $object->$key = $value;
            }
        }
        return $object;
    }

    private function has_attribute($attribute) {
        return array_key_exists($attribute, $this->attributes());
    }

    protected function attributes() {
        $attributes = array();
        foreach (static::$db_fields as $field) {
            if (property_exists($this, $field)) {
                $attributes[$field] = $this->$field;
            }
        }
        return $attributes;
    }

    protected function sanitized_attributes() {
        global $db;

        $clean_attributes = array();
        foreach ($this->attributes() as $key => $value) {
            $clean_attributes[$key] = $db->escape_value($value);
        }
        return $clean_attributes;
    }

    public function save() {
        return isset($this->id) ? $this->update() : $this->create();
    }

    protected function create() {
        global $db;

        $attributes = $this->attributes();

        $query  = "INSERT INTO `" . static::$table_name . "` (`";
        $query .= join("`, `", array_keys($attributes));
        $query .= "`) VALUES ('";
        $query .= join("', '", array_values($attributes));
        $query .= "')";

        if ($db->query($query)) {
            $this->id = $db->inserted_id();
            return true;
        }
        else {
            return false;
        }
    }

    protected function update() {
        global $db;

        $attributes = $this->attributes();
        $attribute_pairs = array();
        foreach ($attributes as $key => $value) {
            $attribute_pairs[] = "`{$key}` = '{$value}'";
        }

        $query  = "UPDATE `" . static::$table_name . "` SET ";
        $query .= join(", ", $attribute_pairs) . " ";
        $query .= "WHERE `id` = " . $db->escape_value($this->id);

        $db->query($query);
        return ($db->affected_rows() == 1) ? true : false;
    }

    public function delete() {
        global $db;

        $query  = "DELETE FROM `" . static::$table_name . "` ";
        $query .= "WHERE `id` = " . $db->escape_value($this->id) . " ";
        $query .= "LIMIT 1";

        $db->query($query);
        return ($db->affected_rows() == 1) ? true : false;
    }
}