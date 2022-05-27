<?php

/* Get Todos los clientes */

$app->get('/api/getUsers', function ($request, $response, $args) {

    $sql = "SELECT * from usuarios";

    try {
        $db = new db();
        $db = $db->connectDb();
        $resultado = $db->query($sql);

        if ($resultado->num_rows > 0) {
            $arrayData = [];
            while ($row = $resultado->fetch_assoc()) {
                $arrayData[] = $row;
            }
            echo json_encode($arrayData);
        } else {
            echo json_encode("empty users in the database");
        }
        $resultado = null;
        $db = null;
    } catch (PDOException $e) {
        echo '{"error : { "text : ' . $e . '}';
    }
});

/* POST Create user*/

$app->post('/api/createUser', function ($request, $response, $args) {

    try {
        $db = new db();
        $conection = $db->connectDb();
        $data = $request->getParsedBody();

        $name = $conection->real_escape_string($data['name']);
        $last_name = $conection->real_escape_string($data['last_name']);
        $email = $conection->real_escape_string($data['email']);
        $username = $conection->real_escape_string($data['username']);
        $password = $conection->real_escape_string($data['password']);
        $password_encp = sha1($email . 'ñ' . $password);


        $query = $conection->query("INSERT INTO `users`
        (`name`, `last_name`, `email`, `password`, `username`) 
        VALUES
        ('$name', '$last_name', '$email', '$password_encp', '$username')
    ");

        $status = "success";

        if (!$query) {
            $status = "errorInsert";
        }

        $response->getBody()->write(json_encode(
            [
                "status" => $status
            ]
        ));

        $db = null;
        return $response;
    } catch (PDOException $e) {
        $response->getBody()->write(json_encode(
            [
                "status" => "errorCatch",
                "error" => $e->getMessage()
            ]
        ));
        return $response;
    }
});

/* POST edit user*/

$app->put('/api/editUser', function ($request, $response, $args) {

    try {
        $db = new db();
        $conection = $db->connectDb();
        $data = $request->getParsedBody();

        $id_user = $conection->real_escape_string($data['id']);
        $name = $conection->real_escape_string($data['name']);
        $lastname = $conection->real_escape_string($data['lastname']);
        $telephone = $conection->real_escape_string($data['telephone']);
        $email = $conection->real_escape_string($data['email']);
        $address = $conection->real_escape_string($data['address']);
        $city = $conection->real_escape_string($data['city']);
        $password = $conection->real_escape_string($data['password']);
        $password_encp = sha1($email . 'ñ' . $password);

        $query = $conection->query("UPDATE `users` SET 
            name = '$name',
            lastname = '$lastname',
            telephone = '$telephone',
            email = '$email',
            address = '$address',
            city = '$city',
            password = '$password_encp'
         WHERE id = '$id_user'");

        $status = "success";

        if (!$query) {
            $status = "errorInsert";
        }

        $response->getBody()->write(json_encode(
            [
                "status" => $status
            ]
        ));

        $db = null;
        return $response;
    } catch (PDOException $e) {
        $response->getBody()->write(json_encode(
            [
                "status" => "errorCatch",
                "error" => $e->getMessage()
            ]
        ));
        return $response;
    }
});


$app->delete('/api/deleteUser', function ($request, $response, $args) {

    try {
        /* LLAMADO DE CLASE BD Y CONEXION A BASE DE DATOS */
        $db = new db();
        $con =  $db->connectDb();

        /* JSON ENVIADO POR POST */
        $data = $request->getParsedBody();
        $id_user = $data["id_user"];

        $query = $con->query("SELECT id FROM users WHERE id = '$id_user'");

        if ($query->num_rows > 0) {

            $delete = $con->query("DELETE FROM users WHERE id = '$id_user'");
            $status = "success";
            if (!$delete) {
                $status = "errorDelete";
            }

            $response->getBody()->write(json_encode(
                [
                    "status" => $status
                ]
            ));
            return $response;
        } else {
            $response->getBody()->write(json_encode(
                [
                    "status" => "usuarioNoExiste"
                ]
            ));

            $db = null;
            return $response;
        }
    } catch (Exception $e) {
        $response->getBody()->write(json_encode(
            [
                "status" => "errorCatch",
                "error" => $e->getMessage()
            ]
        ));
        return $response;
    }
});
