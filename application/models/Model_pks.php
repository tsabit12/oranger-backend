<?php
class Model_pks extends CI_Model {
     public function totalRows(){
          $this->db->from('t_pks');

          return $this->db->get()->num_rows();
     }

     public function data($page, $limit){
          $this->db->select('username, id_mitra, nama_mitra, alamat, no_ktp, tempat_lahir, tanggal_lahir, jenis_kelamin, agama, no_hp, email, kantor, npwp, alamat_domisili, status_kawin');
          $this->db->from('t_mitra');
          $this->db->where(array(
               'status' => 'S1',
               'jabatan' => 'K01'
          ));

          $this->db->limit($limit, $page);
          $this->db->order_by("nama_mitra", "asc");

          return $this->db->get()->result_array();
     }


}
?>