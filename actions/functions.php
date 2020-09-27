<?php
// Encrypt a string (for password)
function encryptThis($string)
{
    return md5($string);
}
// Check User login
// function loginUser($email, $password)
// {

//     global $pdo;
//     $password = encryptThis($password);

//     $sql = "SELECT * FROM  users WHERE email = '$email' AND password = '$password' AND isactive = 1 AND status = 1 LIMIT 1";

//     $stmt = $pdo->prepare($sql);
//     $stmt->execute();

//     $data = false;
//     if ($stmt->rowCount() > 0) {
//         while ($row = $stmt->fetch()) {
//             updateLoginTime($row['user_id']);
//             return $row;
//         }
//     }
//     return false;
// }

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
function SaleEntry($data)
{
    global $pdo;
    $fields =  "SET  cus_name=?, cus_phone=?, totalpackets=?, selfie=?,  user_id=? , datetime=?, cus_cnic=?, feedback=?";
    try {
        $stmt = $pdo->prepare("INSERT INTO sales $fields");

        $stmt->execute(
            array(
                $data['name'],
                $data['phone'],
                $data['packets'],
                $data['selfie'],
                $data['user_id'],
                $data['datetime'],
                $data['cnic'],
                $data['feedback']
            )
        );
    } catch (PDOException $Exception) {
        echo $Exception;
        return false;
    }
    UpdateTargets();
    return true;
}
function UpdateTargets()
{
    global $pdo;
    $fields =  "update users set target = target +15 where (target_updated_date <> CURRENT_DATE() OR target_updated_date IS NULL) AND isactive =1 AND status =2";
    try {
        $stmt = $pdo->prepare($fields);
        $stmt->execute();
        $sql =  "update users set target_updated_date=CURRENT_DATE() where (target_updated_date <> CURRENT_DATE() OR target_updated_date IS NULL) AND isactive =1 AND status =2";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    } catch (PDOException $Exception) {
        echo $Exception;
        return false;
    }
    return true;
}
function RegisterUser($data)
{
    $userDp = SaveImage($data->dp, $data->email);
    $password = encryptThis($data->password);
    global $pdo;
    $fields =  "SET  name=?, password=?, email=?, isactive=?, city=?, phone=?, status=?, dp=?";
    try {
        $stmt = $pdo->prepare("INSERT INTO users $fields");

        $stmt->execute(
            array(
                $data->name,
                $password,
                $data->email,
                1,
                $data->city,
                $data->phone,
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

function setUserSession($data)
{
    //session_start();
    $_SESSION['user_logged'] = true;
    $_SESSION['user_id'] = $data['user_id'];
    $_SESSION['user_email'] = $data['email'];
    $_SESSION['status'] = $data['status'];
}
function setMUserSession()
{
    $_SESSION['user_logged'] = true;
    $_SESSION['user_id'] = '1';
    $_SESSION['user_email'] = 'admin';
    $_SESSION['status'] = '1';
}


function logout()
{
    session_destroy();
}

function isLogin()
{
    if (isset($_SESSION['user_logged']) && $_SESSION['user_logged']) {
        if ($_SESSION['status'] == 1) {
            $user = getUserbyId($_SESSION['user_id']);
            if ($user['email'] == $_SESSION['user_email'])
                return true;
            else
                return false;
        } else
            return false;
    } else {
        return false;
    }
    //Original Implementation for CheckLogin
    // if (isset($_SESSION['user_logged']) && $_SESSION['user_logged']) {              
    //    if ($_SESSION['status']==1)
    //         return true; 
    //     else
    //         return false;
    // }else {
    //     return false;
    // }


    //var_dump($_SESSION);die();

    // if (isset($_SESSION['user_logged']) && $_SESSION['user_logged']) {
    //      $url = $_SERVER[REQUEST_URI];       

    // if ($_SESSION['status']==3) {
    //     if(preg_match('(pages/users|edit-customers|edit-customercontact|pages/services|edit-facility|edit-report)', $url) === 1) { 
    //         return false;       
    //     }
    // }
    // if ($_SESSION['status']==2) {
    //     if(preg_match('(pages/users|pages/services)', $url) === 1) { 
    //         return false;       
    //     }
    // }
    // return true;
    // }
    // return false;
}

function redirect($page)
{
    header('Location: ' . $page);
    exit();
}

function getUserbyId($id)
{
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM users WHERE user_id = ? LIMIT 1');
    $stmt->execute(array($id));

    $data = $stmt->fetch();

    if (!$data) {
        $data = false;
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
function SaveImage($imagefile_rec, $email)
{
    $currentdate = date('d-m-Y-h:i:s', time());
    $imageFile = $email . "_" . $currentdate . ".jpg";
    $targetPath =  ADMIN_IMAGE . $imageFile;
    $upload = file_put_contents($targetPath, base64_decode($imagefile_rec));

    if ($upload == false)
        return "No Image";
    else
        return $imageFile;
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

//TODO: Upload File Product
function uploadFile($files, $inputValue, $dir, $oldFile)
{
    $filename = $files[$inputValue]['name'];

    if ($oldFile == "" || ($oldFile != $filename && $filename != "")) {
        if ($oldFile != "") {
            // Delete previous file
            $oldFile && unlink($dir . $oldFile);
        }

        $tmpFilePath = $files[$inputValue]['tmp_name'];
        $filename = file_newname($dir, $filename);

        //    Make sure we have a filepath
        if ($tmpFilePath != "") {
            //Setup our new file path

            $newFilePath = $dir . $filename;

            //Upload the file into the temp dir
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                return $filename;
            }
        }
    }


    return false;
}
//TODO: Change File name if already exist same name in images Product
function file_newname($path, $filename)
{

    if ($pos = strrpos($filename, '.')) {
        $name = substr($filename, 0, $pos);
        $ext = substr($filename, $pos);
    } else {
        $name = $filename;
        $ext = "";
    }

    $newpath = $path . '/' . $filename;
    $newname = $filename;
    $counter = 0;
    while (file_exists($newpath)) {
        $newname = $name . '_' . $counter . $ext;
        $newpath = $path . '/' . $newname;
        $counter++;
    }

    return $newname;
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
function getTodayActiveBAs()
{
    global $pdo;
    $sql = "SELECT DISTINCT count(user_id) as ActiveToday from sales where DATE_FORMAT(datetime,'%Y-%m-%d') =CURRENT_DATE() group by user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $data = false;
    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch()) {
            $data[] = $row;
        }
    }
    return $data == false ? 0 : count($data);
}
function getTotalTarget()
{
    global $pdo;
    $sql = "SELECT sum(target) as total FROM users";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetch();
    if (!$data) {
        $data = '0';
    }
    return $data['total'];
}
function getTotalNumber($table, $where)
{
    global $pdo;
    $sql = "SELECT count(*) as total FROM $table" . ' ' . $where;
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetch();
    if (!$data) {
        $data = '0';
    }
    return $data['total'];
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

function GetBASalesByID($id, $approval)
{
    global $pdo;
    $sql = "SELECT * FROM sales WHERE user_id = $id " . $approval;
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $data = false;
    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch()) {
            $data[] = $row;
        }
    }
    return $data;
}
function GetTotalSaleByApproval($id, $approval)
{
    global $pdo;
    $sql = "SELECT COUNT(id) as total FROM sales where user_id=$id " . $approval;
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetch();
    if (!$data) {
        $data = 0;
    }
    return $data['total'];
}
function GetUserPortfolios($id)
{
    global $pdo;
    $sql = "SELECT * FROM portfolios where user_id=$id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data = false;
    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch()) {
            $data[] = $row;
        }
    }
    return $data;
}

function GetAllBAs()
{
    global $pdo;
    $sql = "SELECT * FROM portfolios where status=2"; //Status 2 for BA, 1 for admins
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $data = false;
    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch()) {
            $data[] = $row;
        }
    }
    return $data;
}

//TODO: Get all Error Report from database
function GetAllwithError()
{
    global $pdo;
    $sql = "SELECT * FROM feedbacks f, users u where f.user_id = u.user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $data = false;
    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch()) {
            $data[] = $row;
        }
    }
    return $data;
}
function GetSalesWithStatus($approval, $rejection, $pending)
{
    global $pdo;
    $sql = "SELECT * FROM users u, sales s where u.user_id = s.user_id AND s.approval=$approval AND s.rejection=$rejection AND s.pending=$pending"; //Status 2 for BA, 1 for admins
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $data = false;
    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch()) {
            $data[] = $row;
        }
    }
    return $data;
}
function GetAllwithSales()
{
    global $pdo;
    $sql = "SELECT * FROM users u, sales s where u.user_id = s.user_id"; //Status 2 for BA, 1 for admins
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $data = false;
    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch()) {
            $data[] = $row;
        }
    }
    return $data;
}
function GetEntryStatus($approval, $rejection, $pending)
{
    $status = "";
    if ($approval == 1)
        $status = "Approved";
    if ($rejection == 1)
        $status = "Rejected";
    if ($pending)
        $status = "Pending for Approval";
    return $status;
}


function CheckFileExtSize($file, $name)
{
    $validextensions = array("jpeg", "jpg", "png", "JPG");
    $fileExtension = end(explode('.', basename($file[$name]['name'])));
    if (($file[$name]["size"] < 1000000) && in_array($fileExtension, $validextensions)) {
        return true;
    } else {
        return false;
    }
}
function formatDate($date)
{
    $toFormat = date_create($date);
    return date_format($toFormat, "d/m/Y");
}
