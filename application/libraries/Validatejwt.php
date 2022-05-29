<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Validatejwt
{
    function cek_token()
    {
         $result['isValid'] = FALSE;
         $result['message'] = 'This service required token';

         $headers = getallheaders();

         if(isset($headers['X-USER']) || isset($headers['x-user'])){
          $key = getenv('TOKEN_SECRET');

          $xuser = null;
          if(isset($headers['X-USER'])) $xuser = $headers['X-USER'];
          if(isset($headers['x-user'])) $xuser = $headers['x-user'];
     
          try {
               $decoded = JWT::decode($xuser, new Key($key, 'HS256'));
               $result['isValid'] = TRUE;
               $result['message'] = 'Oke';
          } catch (Exception $ex) {
               $result['message'] = 'Invalid user token';
          }
         }

         return $result;
    }
}
?>