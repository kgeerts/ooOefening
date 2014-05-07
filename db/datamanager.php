<?php

require_once($serverpath['db'] . '/opslagmedium.php');

class DataManager {

    function getSettingAsObject($id = 1) {
        $data = self::getSettingData($id);
        return new mailsetting($data);
    }

    function getSettingData($id = 1) {
        $data = OpslagMedium::getObjectdata('mailsettings', $id);
        return $data;
    }

    function getbooksids($orderid) {
        $query = ' orderid=' . $orderid;
        $data = OpslagMedium::getObjectdataOnQuery('bestelling_boek', $query);
        return $data;
    }

    /*
      function getUserObject($id=1){
      $data=self::getSettingData($id);
      return new user ($data);
      }
      private function getUserData($id)
      {
      $data=OpslagMedium::getObjectdata('user',$id);
      return $data;
      }

     */
}

?>
