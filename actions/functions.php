<?php
// Encrypt a string (for password)
function encryptThis($string)
{
    return md5($string);
}

// Check User login for the API
function loginUserApi($email, $password)
{

    global $pdo;
    $password = encryptThis($password);

    $sql = "SELECT * FROM  users WHERE email = ? AND password = ?  AND status = 'user' LIMIT 1";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($email, $password));

    $data = false;
    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch()) {
            updateLoginTime($row['user_id']);
            return $row;
        }
    }
    return false;
}
//Check data exists in the post variable
function isDataAvailable($data, $params)
{
    foreach ($params as $param) {
        if (!isset($data->$param)) {
            return false;
        }
    }
    return true;
}
function setUserSession($data)
{
    //session_start();
    $_SESSION['user_logged'] = true;
    $_SESSION['user_id'] = $data['user_id'];
    $_SESSION['user_email'] = $data['email'];
    $_SESSION['status'] = $data['status'];
}

function RegisterUser($data)
{
    $userDp = '';
    $password = encryptThis($data['password']);
    global $pdo;
    $fields =  "SET  name=?, password=?, email=?, isactive=?, city=?, phone=?, status=?, dp=?";
    try {
        $stmt = $pdo->prepare("INSERT INTO users $fields");

        $stmt->execute(
            array(
                $data['name'],
                $password,
                $data['email'],
                1,
                $data['city'],
                $data['phone'],
                'user',
                $userDp
            )
        );
    } catch (PDOException $Exception) {
        echo $Exception;
        return false;
    }
    return true;
}
function SavePortfolio($data)
{
    $picture = SavePortfolioImage($_FILES['picture'], $data['user_id']);
    global $pdo;
    $fields =  "SET  title=?,description=?, picture=?, user_id=?";
    try {
        $stmt = $pdo->prepare("INSERT INTO portfolios $fields");

        $stmt->execute(
            array(
                $data['title'],
                $data['description'],
                $picture,
                $data['user_id'],
            )
        );
    } catch (PDOException $Exception) {
        echo $Exception;
        return false;
    }
    return true;
}
function updateUser($data)
{

    global $pdo;
    $fields =  "SET  name=?, phone=?, city=? WHERE user_id=?";
    try {
        $stmt = $pdo->prepare("UPDATE users $fields");

        $stmt->execute(
            array(
                $data->name,
                $data->phone,
                $data->city,
                $data->user_id
            )
        );
    } catch (PDOException $Exception) {
        echo $Exception;
        return false;
    }
    return true;
}
// Update login Time
function updateLoginTime($id)
{
    global $pdo;
    $now = date("Y-m-d H:i:s");
    $sql = "UPDATE users SET logintime ='$now' WHERE user_id=$id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
}

function logout()
{
    session_destroy();
}


function redirect($page)
{
    header('Location: ' . $page);
    exit();
}


//Check if the user is active or not.
function IsUserActive($id)
{
    global $pdo;
    $stmt = $pdo->prepare('SELECT isactive FROM users WHERE user_id = ? LIMIT 1');
    $stmt->execute(array($id));
    $data = $stmt->fetch();
    if (!$data) {

        $data = false;
    }
    return $data['isactive'];
}

//TODO: Upload Image into the database
function SavePortfolioImage($imagefile_rec, $user)
{
    $currentdate = date('d-m-Y-his', time());
    $imageFile = "User-" . $user . "-" . $currentdate . ".jpg";

    $targetPath =  ADMIN_IMAGE . $imageFile;

    $avatar_name = $_FILES["picture"]["name"];
    $avatar_tmp_name = $_FILES["picture"]["tmp_name"];
    $upload = move_uploaded_file($avatar_tmp_name, $targetPath);

    //$upload = file_put_contents($targetPath, base64_decode($imagefile_rec));

    if ($upload == false)
        return "No Image";
    else
        return $imageFile;
}


function deleteQuery($id, $table, $column)
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("DELETE FROM $table WHERE $column=? LIMIT 1");
        $stmt->execute(array($id));
    } catch (PDOException $Exception) {
        return false;
    }

    return true;
}
function deleteQueryALL($id, $table, $column)
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("DELETE FROM $table WHERE $column=? ");
        $stmt->execute(array($id));
    } catch (PDOException $Exception) {
        return false;
    }

    return true;
}

function getPortfolioById($id)
{
    global $pdo;
    $sql = "SELECT * FROM portfolios where id =" . $id;
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetch();
    if (!$data) {
        $data = '0';
    }
    return $data;
}
function checkDuplicate($email)
{
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
    $stmt->execute(array($email));
    $data = $stmt->fetch();
    if (!$data) {
        $data = false;
    } else {
        $data = true;
    }
    return $data;
}

function GetUserPortfolios($id)
{
    global $pdo;
    $sql = "SELECT * FROM portfolios where user_id=$id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data = [];
    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch()) {
            $data[] = $row;
        }
    }
    return $data;
}

function formatDate($date)
{
    $toFormat = date_create($date);
    return date_format($toFormat, "d-M-Y h:m A");
}
