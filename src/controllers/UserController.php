<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../helpers/response.php';

class UserController {
    public function createUser($mysqli) {
        $input = json_decode(file_get_contents('php://input'), true);

        // Validation
        if (!isset($input['userName'], $input['firstName'], $input['lastName'], $input['email'], $input['password'])) {
            sendResponse(400, ['message' => 'Not all fields have been provided!']);
            return;
        }

        if (strlen($input['password']) < 8) {
            sendResponse(400, ['message' => 'Password must be at least 8 characters!']);
            return;
        }

        // $userName = filter_var($input['userName'], FILTER_SANITIZE_STRING);
        // $firstName = filter_var($input['firstName'], FILTER_SANITIZE_STRING);
        // $lastName = filter_var($input['lastName'], FILTER_SANITIZE_STRING);
        // $email = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
        $password = $input['password']; 

        $userModel = new User();
        $result = $userModel->createUser($mysqli,$input['userName'], $input['firstName'], $input['lastName'], $input['email'], $input['password']);

        sendResponse($result['status'], ['message' => $result['message']]);
    }

    public function getUsers($mysqli) {
        $userModel = new User();
        $users = $userModel->getAllUsers($mysqli);
        sendResponse(200, $users);
    }

    public function getUserById($mysqli, $id) {
        $userModel = new User();
        $user = $userModel->getUserById($mysqli, $id);

        if (!$user) {
            sendResponse(404, ['message' => 'Record not found']);
            return;
        }

        sendResponse(200, ['data' => $user]);
    }

    public function loginUser($mysqli) {
        $input = json_decode(file_get_contents('php://input'), true);

        if (!isset($input['email'], $input['password'])) {
            sendResponse(400, ['message' => 'Not all fields have been provided!']);
            return;
        }

        $userModel = new User();
        $user = $userModel->getUserByEmail($mysqli, $input['email']);

        if (!$user) {
            sendResponse(404, ['message' => 'No account with this email has been registered']);
            return;
        }

        
        if (!password_verify($input['password'], $user['user_password'])) {
            sendResponse(400, ['message' => 'Invalid Credentials']);
            return "djfhoigfh";
        }

        // Set a cookie
        $token = bin2hex(random_bytes(16)); // generate a random token
        setcookie("user_token", $token, time() + 24*3600, "/"); // expires in 1 hour


        sendResponse(200, [
            'token' => $token,
            'user' => [
                'id' => $user['user_id'],
                'display_name' => $user['user_name']
            ]
        ]);
    }
}
?>
