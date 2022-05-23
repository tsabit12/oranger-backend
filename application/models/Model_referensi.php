<?php
class Model_referensi extends CI_Model {
     public function berkas(){
          $this->db->from('r_berkas');
          $this->db->order_by('keterangan', 'ASC');

          return $this->db->get()->result_array();
     }

     public function kantor(){
          $this->db->select('b.wilayah, a.nopend, a.NamaKtr as kantor');
          $this->db->from('r_kantor a');
          $this->db->join('r_wilayah b', 'a.idwilayah = b.nopend');
          $this->db->where("a.nopend = a.kprk and a.nopend <> '40005'", NULL, FALSE);
          $this->db->order_by("b.id_wilayah", "asc");
          $this->db->order_by("a.nopend", "asc"); 

          return $this->db->get()->result_array();
     }
}
?>