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

     public function updateBerkas($payload){
          $result['success'] = false;

          $this->db->where('berkasid', $payload['berkasid']);
          $this->db->update('r_berkas', array(
               'keterangan' => $payload['keterangan'],
               'with_file' => $payload['with_file']
          ));

          if($this->db->affected_rows() > 0){
               $result['success'] = true;
          }

          return $result;
     }

     private function getlastidberkas(){
          $this->db->select('RIGHT(berkasid, 2) as id');
          $this->db->from('r_berkas');
          $this->db->order_by('berkasid', 'DESC');
          $this->db->limit(1, 0);

          $q = $this->db->get();
          if($q->num_rows() > 0){
               $id = $q->row_array()['id'];
               $id = $id + 1;
               $id = str_pad($id, 2, '0', STR_PAD_LEFT);
               return "B".$id;
          }else{
               return 'B01';
          }
     }

     public function insertBerkas($payload){
          $res['success'] = false;
          $id  = $this->getlastidberkas();
          
          $add = array(
               'berkasid' => $id,
               'keterangan' => $payload['keterangan'],
               'with_file' => $payload['with_file']
          );

          $this->db->insert('r_berkas', $add);

          if($this->db->affected_rows() > 0){
               $res['success']     = true;
               $res['result']      = $add;
          }

          return $res;
     }
}
?>