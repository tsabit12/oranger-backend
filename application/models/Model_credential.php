<?php

class Model_credential extends CI_Model
{
    public function getkey()
    {
         $result['success'] = false;

         $this->db->select('key');
         $this->db->from('apikey');
         $this->db->limit(1, 0);

         $q = $this->db->get();
        if($q->num_rows() > 0) {
             $result['success'] = true;
             $result['data'] = $q->row_array()['key'];
        }

         return $result;

    }
}

?>