<?php
require "../config/configuration.php";
header("X-Container-Meta-Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Disposition, Content-Type, Content-Length, Accept-Encoding,X-Requested-With");
header("Content-type:application/json");
$response = array();

if (isset($_GET['action']) && isset($_GET['key'])) {


	if ($_GET['key'] == API_KEY) {
		switch ($_GET['action']) {
			case 'login':
				$data = json_decode(file_get_contents("php://input"));
				if (isset($data->email)  && isset($data->password)) {
					$user = loginUserApi($data->email, $data->password);
					if (!$user) {
						$response['error'] = true;
						$response['message'] = 'Invalid Email or Password.';
					} elseif (!$user['isactive']) {
						$response['error'] = true;
						$response['message'] = 'Account has been deactivated. Please contact your Supervisor or System Administrator.';
					} else {
						$response['error'] = false;
						$response['message'] = 'Login Successfull.';
						$response['user'] = $user;
					}
				} else {
					$response['error'] = true;
					$response['message'] = 'Error Occured. Code: 01DATA';
					$response['data'] = $data;
				}
				break;
			case 'userportfolios':
				$data = json_decode(file_get_contents("php://input"));
				if ($data->user_id > 0) {
					$portfolios = GetUserPortfolios($data->user_id);

					$response['error'] = false;
					$response['portfolios'] = $portfolios;
				} else {
					$response['error'] = true;
					$response['message'] = 'Error Occured. Code: 01DATA';
				}
				break;
			case 'singleportfolio':
				$data = json_decode(file_get_contents("php://input"));
				if ($data->id > 0) {
					$portfolio = getPortfolioById($data->id);
					if ($portfolio == '0') {
						$response['error'] = true;
						$response['message'] = 'Record not found';
					} else {
						$response['error'] = false;
						$response['portfolio'] = $portfolio;
					}
				} else {
					$response['error'] = true;
					$response['message'] = 'Error Occured. Code: 02DATA';
				}
				break;
			case 'newregister':
				$data = json_decode(file_get_contents("php://input"));
				if (isDataAvailable($data, array('email', 'password', 'name', 'city', 'phone', 'dp'))) {
					$check = checkDuplicate($data->email);
					if (!$check) {
						$status = RegisterUser($data);
						if (!$status) {
							$response['error'] = true;
							$response['message'] = 'Account not created due to technical error. (EXCP)';
						} else {
							$response['error'] = false;
							$response['message'] = 'Your account has been created Successfully.';
						}
					} else {
						$response['error'] = true;
						$response['message'] = 'This email already exists. Please choose different email address.';
					}
				} else {
					$response['error'] = true;
					$response['message'] = 'Error Occured. Code: 02DATA';
				}
				break;
			case 'updateuser':
				$data = json_decode(file_get_contents("php://input"));
				if (IsUserActive($data->user_id)) {
					$status = updateUser($data);
					if (!$status) {
						$response['error'] = true;
						$response['message'] = 'Profile not updated due to technical error. (EXCP)'; //Exception
					} else {
						$response['error'] = false;
						$response['message'] = 'Your profile has been updated Successfully.';
					}
				} else {
					$response['error'] = true;
					$response['message'] = 'Account Deactivated, Please contact your Supervisor or System Administrator';
				}
				break;
			case 'portfolioreport':
				header("Content-type:multipart/form-data");
				if (IsUserActive($_POST['user_id'])) {
					$status = SavePortfolio($_POST);
					if (!$status) {
						$response['error'] = true;
						$response['message'] = 'Profile not updated due to technical error. (EXCP)'; //Exception
					} else {
						$response['error'] = false;
						$response['message'] = 'Your profile has been updated Successfully.';
					}
				} else {
					$response['error'] = true;
					$response['message'] = 'Account Deactivated, Please contact your Supervisor or System Administrator';
				}
				break;
			default:
				$response['error'] = true;
				$response['message'] = 'Invalid Operation Called';
		}
	} else {
		$response['error'] = true;
		$response['message'] = 'Access denied. [Unauthorized request]';
	}
} else {
	$response['error'] = true;
	$response['message'] = 'Invalid Request';
}
echo json_encode($response);
