<?php
class Model_kandidat extends CI_Model {
     public function totalRows(){
          $this->db->from('t_mitra');
          $this->db->where(array(
               'status' => 'S1',
               'jabatan' => 'K01'
          ));

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

     public function berkas($username)
     {
          $result['success'] = false;

          $this->db->select('a.berkasid, a.file_name, b.keterangan, a.nilai');
          $this->db->from('t_berkas_mitra a');
          $this->db->join('r_berkas b', 'a.berkasid = b.berkasid');
          $this->db->where('a.username', $username);

          $q = $this->db->get();
          if($q->num_rows() > 0){
               $result['success'] = true;
               $result['data'] = $q->result_array();
          }

          return $result;
     }

     public function updateberkas($payload){
          $result['success'] = false;

          $this->db->where(array(
               'berkasid' => $payload['berkasid'],
               'username' => $payload['username']
          ));

          $this->db->update('t_berkas_mitra', array(
               'nilai' => $payload['nilai']
          ));

          if($this->db->affected_rows() > 0){
               $result['success'] = true;
          }

          return $result;
     }

     public function addpks($body){
          $result['success'] = false;

          $data = array(
               'id_mitra' => $body['username'],
               'no_pks' => $body['nopks'],
               'judul_pks' => $body['judul'],
               'tgl_mulai' => $body['mulai'],
               'tgl_selesai' => $body['selesai']
          );

          $this->db->insert('t_pks', $data);
          if($this->db->affected_rows() > 0){
               $this->db->where('username', $body['username']);
               $this->db->update('t_mitra', array(
                    'status' => 'S4',
                    'password' => md5($body['username'].'AAaa123$'),
               ));
               if($this->db->affected_rows() > 0){
                    $result['success'] = true;
               }else{
                    //rolback
                    $this->db->where('id_mitra', $body['username']);
                    $this->db->delete('t_pks');
               }
          }

          return $result;
     }

     public function tidaklulus($body){
          $result['success'] = false;

          $this->db->where('username', $body['username']);
          $this->db->update('t_mitra', array( 'status' => 'S3' ));

          if($this->db->affected_rows() > 0){
               $result['success'] = true;
          }

          return $result;
     }
}
?>