<?php
class Model_kandidat extends CI_Model {
     public function totalRows(){
          $this->db->from('t_mitra');
          $this->db->where('jabatan','K01');
          $this->db->where_in('status', ['S1','S2','S3']);

          return $this->db->get()->num_rows();
     }

     public function data($page, $limit){
          $this->db->select('a.username, a.id_mitra, a.nama_mitra, a.alamat, a.no_ktp, a.tempat_lahir, a.tanggal_lahir');
          $this->db->select('a.jenis_kelamin, a.agama, a.no_hp, a.email, a.kantor, a.npwp, a.alamat_domisili, b.keterangan as status, a.status as statusid');
          $this->db->from('t_mitra a');
          $this->db->join('r_mitrastatus b', 'a.status = b.statusid');
          $this->db->where('jabatan','K01');
          $this->db->where_in('a.status', ['S1','S2','S3']);

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
          $this->db->where(array(
               'a.username' => $username,
               'b.with_file' => 1
          ));

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

          if($this->db->affected_rows() >= 0){
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

     public function review($body){
          $result['success'] = false;
          $nilai = json_decode($body['nilai'], true);

          if(count($nilai) > 0){
               $listnilai = array();
               
               foreach($nilai as $key){
                    $listnilai[] = array(
                         'berkasid' => $key['berkasid'],
                         'nilai' => $key['value'],
                         'username' => $body['username']
                    );
               }

               if($body['fromstat'] == 'S3'){
                    $this->db->where('username', $body['username']);
                    $this->db->update_batch('t_berkas_mitra', $listnilai, 'berkasid');
               }else{
                    $this->db->insert_batch('t_berkas_mitra', $listnilai);
               }

               if($this->db->affected_rows() > 0){
                    $this->db->where('username', $body['username']);
                    $this->db->update('t_mitra', array( 'status' => $body['status'] ));

                    if($this->db->affected_rows() > 0){
                         $result['success'] = true;
                    }
               }
          }

          return $result;
     }
}
?>