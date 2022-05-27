<?php

use \Firebase\JWT\JWT;

class MWAdministrador {
    /**
     * Example middleware invokable class
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param  callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */

    public function __invoke($request, $response, $next)
    {

        if (!$request->hasHeader('Authorization')) {
            $response->getBody()->write(json_encode(
                [
                    "status"=>"errorToken", 
                ]
            ));
            return $response;
        }

        $jwt = new jwtService();
        $token = $request->getHeaderLine('Authorization');

        $data = $request->getParsedBody();
        $id_usuario = $data["id_usuario"];

        $dataToken = $jwt->decodeToken($token);
        $idToken = $dataToken->data->id_usuario;
        if($idToken != $id_usuario){
            $response->getBody()->write(json_encode(
                [
                    "status"=>"errorUsuario",  
                    "data"=>$data
                ]
            ));
            return $response;
        }

        $response = $next($request, $response);
        return $response;
    }
}

class jwtService{

    private $key = "estimatu627900JWT$";

    public function encodeToken($data){
        $key = $this->key;
        $payload = [
            "data"=>$data,
            "fecha"=> date("Y-m-d H:i:s")
        ];

        return JWT::encode($payload, $key, "HS256");
    }

    public function decodeToken($token){
        $key = $this->key;
        $decoded = JWT::decode($token, $key, array('HS256'));
        return $decoded;
    }

}

?>