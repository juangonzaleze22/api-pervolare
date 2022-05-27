<?php

$app->post('/api/login', function ($request, $response, $args) {
    
    try{
        /* LLAMADO DE CLASE BD Y CONEXION A BASE DE DATOS */
        $db = new db();
        $jwt = new jwtService();

        $con =  $db->connectDb();

        /* JSON ENVIADO POR POST */
        $data = $request->getParsedBody();
        $email = $con->real_escape_string($data["email"]);
        $password = $con->real_escape_string($data["password"]);
        $claveEncp = sha1($email."ñ".$password);
        
        $query = $con->query("SELECT * FROM users WHERE email = '$email'");
        if($query->num_rows == 0){
            $response->getBody()->write(json_encode(
                [
                    "status" => "errorEmail"
                ]
            ));
            return $response;
        }

        $row = $query->fetch_assoc();
        
        if($claveEncp != $row["password"]){

            $response->getBody()->write(json_encode(
                [
                    "status" => "errorPassword",
                    "password" => $claveEncp

                ]
            ));
            return $response;
        }

        $row["password"] = NULL;
        $token = $jwt->encodeToken($row);

        $response->getBody()->write(json_encode(
            [
                "status" => "success",
                "token" => $token,
                "user" => $row
                
            ]
        ));
        return $response;

    }catch (Exception $e) {
        $response->getBody()->write(json_encode(
            [
                "status" => "errorCatch",
                "error" => $e->getMessage()
            ]
        ));
        return $response;
    }  
});

