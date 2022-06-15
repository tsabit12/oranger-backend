<?php
class Model_pks extends CI_Model {
     private function getcurdate(){
          $sql = "select NOW() as currentdate_value";
          $q = $this->db->query($sql)->row_array();

          return $q['currentdate_value'];
     }
     public function totalRows(){
          $this->db->from('t_pks');

          return $this->db->get()->num_rows();
     }

     public function data($payload, $limit){
          $page = (int)$payload['page'];

          $this->db->select('a.no_pks, a.judul_pks, b.nama_mitra, a.tgl_mulai, a.tgl_selesai, b.no_ktp, b.tempat_lahir, b.tanggal_lahir, b.jenis_kelamin');
          $this->db->select('b.agama, b.no_hp, b.email, b.kantor, a.tgl_insert');
          $this->db->from('t_pks a');
          $this->db->join('t_mitra b', 'a.id_mitra = b.username');

          if(isset($payload['nik'])) $this->db->where('b.no_ktp', $payload['nik']);
          if(isset($payload['status'])){
               $curdate = $this->getcurdate();
               if($payload['status'] == 'P01'){ //aktif
                    $this->db->where('a.tgl_selesai >=', $curdate);
               }else if($payload['status'] == 'P02'){
                    $this->db->where('a.tgl_selesai <=', $curdate);
               }
          }

          $this->db->limit($limit, $page);
          $this->db->order_by("a.tgl_insert", "desc");
          $q = $this->db->get();

          $res = [];

          if($q->num_rows() > 0){
               foreach($q->result_array() as $key){
                    $status = 'p1';
                    if(date("Y-m-d h:m:s") >= $key['tgl_selesai']) $status = 'p2'; //expired
                    $res[] = array(
                         'status' => $status,
                         'no_pks' => $key['no_pks'],
                         'judul_pks' => $key['judul_pks'],
                         'nama_mitra' => $key['nama_mitra'],
                         'tgl_mulai' => date("Y-m-d", strtotime($key['tgl_mulai'])),
                         'tgl_selesai' => date("Y-m-d", strtotime($key['tgl_selesai'])),
                         'no_ktp' => $key['no_ktp'],
                         'tempat_lahir' => $key['tempat_lahir'],
                         'tanggal_lahir' => $key['tanggal_lahir'],
                         'jenis_kelamin' => $key['jenis_kelamin'],
                         'agama' => $key['agama'],
                         'no_hp' => $key['no_hp'],
                         'email' => $key['email'],
                         'kantor' => $key['kantor'],
                         'tgl_insert' => $key['tgl_insert']
                    );
               }
          }

          return $res;
     }


}
?>