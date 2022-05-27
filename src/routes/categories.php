<?php

/* POST get category*/

$app->post('/api/getCategories', function ($request, $response, $args) {



    try {
        $db = new db();
        $conection = $db->connectDb();
        $data = $request->getParsedBody();

        $id_user = $conection->real_escape_string($data['id']);
        

        $sql= "SELECT * FROM categories WHERE idParentCategory = $id_user";


        $resultado = $conection->query($sql);

        if ($resultado->num_rows > 0) {
            $arrayData = [];
            while ($row = $resultado->fetch_assoc()) {
                $arrayData[] = $row;
            }
            $response->getBody()->write(json_encode(
                [
                    "status" => "success",
                    "data" => $arrayData
                ]
            ));
        } else {
            $response->getBody()->write(json_encode(
                [
                    "status" => "noData"
                ]
            ));
        }
        $resultado = null;
        $db = null;
    } catch (PDOException $e) {
        echo '{"error : { "text : ' . $e . '}';
    }
});

/* POST Create category*/

$app->post('/api/createCategory', function ($request, $response, $args) {

    try {
        $db = new db();
        $conection = $db->connectDb();
        $data = $request->getParsedBody();

        /* $id_category = $conection->real_escape_string($data['id']); */
        $idParentCategory = $conection->real_escape_string($data['idParentCategory']);
        $code = $conection->real_escape_string($data['code']);
        $title = $conection->real_escape_string($data['title']);
        $description = $conection->real_escape_string($data['description']);


        $query = $conection->query("INSERT INTO `categories`
        (`idParentCategory`,
         `code`,
         `title`,
         `description`) 
        VALUES
        ('$idParentCategory',
         '$code',
         '$title',
         '$description')
    ");

        $status = "success";

        if (!$query) {
            $status = "errorInsert";
        }

        $response->getBody()->write(json_encode(
            [
                "status" => $status,
                "query" => $query

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


/* Delete category  */

$app->post('/api/deleteCategory', function ($request, $response, $args) {

    try {
        $db = new db();
        $conection = $db->connectDb();
        $data = $request->getParsedBody();
        $id_category = $data['id'];
        
        $todaydate = date("Y-m-d", time()); //fecha actual
        $sqlDate   = date('Y-m-d', strtotime($todaydate));

        $query = $conection->query("SELECT id FROM categories WHERE id = '$id_category'");

        if ($query->num_rows > 0) {

            $query = $conection->query("UPDATE `categories` SET 
            deleteData = '$sqlDate'
            WHERE id = '$id_category'");

            $status = "success";

            if (!$query) {
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
                    "status" => "CategoryNotFound"
                ]
            ));

            $db = null;
            return $response;
        }
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

/* Update category */

$app->put('/api/updateCategory', function ($request, $response, $args) {

    try {
        $db = new db();
        $conection = $db->connectDb();
        $data = $request->getParsedBody();

        $id_category = $conection->real_escape_string($data['id']);

        $code = $conection->real_escape_string($data['code']);
        $title = $conection->real_escape_string($data['title']);
        $description = $conection->real_escape_string($data['description']);

        $todaydate = date("Y-m-d", time()); //fecha actual
        $sqlDate   = date('Y-m-d', strtotime($todaydate));


        $query = $conection->query("UPDATE `categories` SET 

        code = '$code', 
        title = '$title', 
        description = '$description', 
        updateData = '$sqlDate'

         WHERE id = '$id_category'");

        $status = "success";

        if (!$query) {
            $status = "errorInsert";
        }

        $response->getBody()->write(json_encode(
            [
                "status" => $status,
                "data" => $data
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
