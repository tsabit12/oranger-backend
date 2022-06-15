<?php

class Model_estimasi extends CI_Model {
     public function getdata($payload, $limit=null, $type=null){
          $page = (int)$payload['page'];

          $this->db->from('rekap_kemitraan a');
          $this->db->join('r_kantor b', 'a.KDKANTOR = b.nopend');

          $this->db->where('a.TANGGAL >=', $payload['tgl_awal']);
          $this->db->where('a.TANGGAL <=', $payload['tgl_akhir']);
          if($payload['regional'] !== 'P0'){
               if($payload['kprk'] !== 'P0'){
                    $this->db->where('b.nopend', $payload['kprk']);
               }else{
                    $this->db->where('a.KDREGIONAL', $payload['regional']);
               }
          }


          if($type === 'count'){
               $this->db->group_by(array('b.nopend', 'a.ID_MITRA', 'a.NAMA_MITRA'));
               return $this->db->count_all_results();
          }else{
               $this->db->select('c.id_wilayah, b.nopend as kprk, a.ID_MITRA as id_mitra,  UPPER(a.NAMA_MITRA) as nama_mitra, SUM(a.BSU_KEHADIRAN) as bsu_insentif');
               $this->db->select('SUM(a.JUMLAH_HARI) as jumlah_hari, SUM(a.JUMLAH_BERHASIL_ANTAR) as berhasil_antar, SUM(a.BSU_INSENTIF) as bsu_fee');
               $this->db->select('SUM(a.BSU_BONUS) as bonus, (SUM(a.BSU_INSENTIF) + SUM(a.BSU_KEHADIRAN) + SUM(a.BSU_BONUS)) as total_fee');

               $this->db->join('r_wilayah c', 'b.idwilayah = c.nopend');
               $this->db->group_by(array('c.id_wilayah', 'b.nopend', 'a.ID_MITRA', 'a.NAMA_MITRA'));

               $this->db->order_by("c.id_wilayah", "asc");
               $this->db->order_by("b.nopend", "asc");
               $this->db->order_by("a.NAMA_MITRA", "asc");
               $this->db->limit($limit, $page);
               return $this->db->get()->result_array();
          }

     }
}

?>