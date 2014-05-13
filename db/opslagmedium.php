<?php

class OpslagMedium {

    private static function _getConnection() {
        static $hDB;

        if (isset($hDB)) {
            return $hDB;
        }

        $hDB = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die("Failure connecting to the database!");
        $db_selected = mysql_select_db(DB_DATABASE, $hDB);
        if (!$db_selected) {
            die('Can\'t use db : ' . mysql_error());
        }
        return $db_selected;
    }

    public function storeObject($table, $properties) {
        $link = OpslagMedium::_getConnection();
        $sql = "INSERT INTO " . $table . " ";
        $first = TRUE;

        foreach ($properties as $key=>$value) {
            $fieldname = $this->getDatafieldname($key);
            if (!is_null($fieldname) & ($fieldname <> 'id')) {  //only if we find a translation we are going to update      
                if ($first) {
                    $sql .= "SET ";
                    $first = FALSE;
                } else
                    $sql.=" , ";
                $Hyphen = $this->hyphenNeeded($key);
                $val = $this->$key;

                if (is_bool($val)) {
                    $val = ($val === true) ? '1' : '0';
                }
                if ($Hyphen == false) {
                    $sql .= "$fieldname = " . $val;
                } else {
                    $sql .= "$fieldname = '" . $val . "'";
                }
            }
        }
        if (!$first) {

            $res = mysql_query($sql);
            if (!$res)
                die('Something went wrong. Contact Adminstrator with errorcode = SO-' . $table . '.-' . mysql_errno() . '<br/>' . mysql_error() . '<br/>');
            $res = mysql_query(' SELECT LAST_INSERT_ID() as lastid;');
            $objectdata = mysql_fetch_object($res);
            if (intval($objectdata->lastid) > 0) {
                $id = intval($objectdata->lastid);
            }
            return $id;
        } else {
            return null;
        }
    }
public function updateObject($table, $id, $object) {

        OpslagMedium::_getConnection();

        $sql = "UPDATE " . $table . " ";
        $first = TRUE;

        foreach ($object as $key) {
            $fieldname = $this->getDatafieldname($key);

            if (!is_null($fieldname)) {  //only if we find a translation we are going to update 
                if ($first) {
                    $sql .= "SET ";
                    $first = FALSE;
                }
                else
                    $sql.=" , ";
                $Hyphen = $this->hyphenNeeded($key);
                $val = $this->$key;

                if (is_bool($val)) {
                    $val = ($val === true) ? '1' : '0';
                }
                if ($Hyphen == false) {
                    $sql .= "$fieldname = " . $val;
                } else {
                    $sql .= "$fieldname = '" . $val . "'";
                }
            }
        }
        if ($first !== TRUE) { //in this case there wasn at least one field to update 
            $sql .= " WHERE id=" . $id;

            $res = mysql_query($sql);
            if (!$res)
                die('Something went wrong. Contact Adminstrator with errorcode = SO-' . $table . '.-' . mysql_errno() . '<br/>');
            return $res;
        }
    }

    public function getObjectIds($table, $options) {

        OpslagMedium::_getConnection();

        $sql = "SELECT id FROM " . $table;
        if ($options != "" && !is_null($options))
            $sql .= " " . $options;

        $res = mysql_query($sql);
        if (!$res)
            die('Something went wrong. Contact Adminstrator with errorcode = SO-' . $table . '.-' . mysql_errno() . '<br/>' . mysql_error() . '<br/>');
        $ids = array();
        while ($row = mysql_fetch_assoc($res)) {
            $ids[] = $row['id'];
        }
        return $ids;
    }

    public function getObjectdata($table, $id = -99) {

        OpslagMedium::_getConnection();

        $sql = "SELECT * FROM $table WHERE id = $id";
        $res = mysql_query($sql);
        if (!($res && mysql_num_rows($res) == 1)) {
            die("Failed getting object $id from $table<br/>");
        }
        $objectdata = mysql_fetch_assoc($res);
        return $objectdata;
    }

    private static function mysql_fetch_assoc_with_type($result) {
        $meta = null;
        while ($property = mysql_fetch_field($result)) {
            $meta[$property->name] = $property->type;
        }

        $objectdata = mysql_fetch_assoc($result);
        $objectdata['id'] = intval($objectdata['id']);
        foreach ($meta as $key => $val) {
            if ($val == 'int') {
                $objectdata[$key] = intval($objectdata[$key]);
            }
        }
        return $objectdata;
    }

    public function getReturnSetOnQuery($table, $query) {
        OpslagMedium::_getConnection();

        $sql = "SELECT * FROM $table WHERE $query";
        $res = mysql_query($sql);
        if (($res)) {
            return $res;
        } else {
            return null;
        }
    }

    public function getObjectdataOnQuery($table, $query) {

        OpslagMedium::_getConnection();

        $sql = "SELECT * FROM $table WHERE $query";
        $res = mysql_query($sql);
        if (!($res && mysql_num_rows($res) == 1)) {
            $objectdata = null;
        } else {
            $objectdata = mysql_fetch_object($res);
        } // nothing found

        return $objectdata;
    }

    public function doesRecordExist($table, $property, $val) {

        OpslagMedium::_getConnection();
        $sql = "SELECT COUNT(*) as counter FROM " . $table . " ";
        $fieldname = $this->getDatafieldname($property);
        if (!is_null($fieldname) && !is_null($val)) {  //only if we find a translation we are going to update 
            $sql .= "WHERE ";
            $Hyphen = $this->hyphenNeeded($property);
            if (is_bool($val)) {
                $val = ($val === true) ? '1' : '0';
            }
            if ($Hyphen == false) {
                $sql .= "$fieldname = " . $val;
            } else {
                $sql .= "$fieldname = '" . $val . "'";
            }
            $res = mysql_query($sql);
            if (!$res)
                die('Something went wrong. Contact Adminstrator with errorcode = SO-' . $table . '.-' . mysql_errno() . '<br/>');
            $objectdata = mysql_fetch_object($res);
            $objectdata->counter = intval($objectdata->counter);

            if ($objectdata->counter > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    
    public function callsp($sp) {

        OpslagMedium::_getConnection();
        $objectdata = null;
        $res = mysql_query($sp);
        $objectdata = mysql_fetch_object($res);

        return $objectdata;
    }

    public function deleteObject($table, $id) {

        OpslagMedium::_getConnection();

        $sql = "DELETE FROM " . $table . " WHERE id=" . $id;

        $res = mysql_query($sql);
        if (!$res)
            die('Something went wrong. Contact Adminstrator with errorcode = SO-' . $table . '.-' . mysql_errno() . '<br/>');
        return $res;
    }

}

?>
