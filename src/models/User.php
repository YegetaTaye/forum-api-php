<?php
require_once __DIR__ . '/../config/database.php';

class User {
    public function getAllUsers($mysqli) {
        $query = "SELECT * FROM registration";
        $result = $mysqli->query($query);

        $users = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
        }
        return $users;
    }


    public function getUserById($mysqli, $id) {
        $stmt = $mysqli->prepare("SELECT registration.user_id,user_name,user_email,first_name,last_name FROM registration LEFT JOIN profile ON registration.user_id = profile.user_id WHERE registration.user_id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function createUser($mysqli, $userName, $firstName, $lastName, $email, $password) {
        // Check if user already exists
        $stmt = $mysqli->prepare("SELECT * FROM registration WHERE user_email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return ['status' => 400, 'message' => 'An account with this email already exists!'];
        }

        // Password encryption
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert user into the registration
        $stmt = $mysqli->prepare("INSERT INTO registration(user_name,user_email,user_password)VALUES(?,?,?)");
        $stmt->bind_param('sss', $userName, $email, $hashed_password);

        if (!$stmt->execute()) {
            return ['status' => 500, 'message' => 'Database connection error'];
        }

        $stmt = $mysqli->prepare("SELECT * FROM registration WHERE user_email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();


        $user_id;
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $user_id = $row['user_id'];
            }
        } else {
            echo "No results found.";
        }
        

        // Insert user into the profile table
        $stmt = $mysqli->prepare("INSERT INTO profile(user_id,first_name,last_name)VALUES(?,?,?)");
        $stmt->bind_param('sss', $user_id, $firstName, $lastName);

        if ($stmt->execute()) {
            return ['status' => 201, 'message' => 'User created successfully'];
        } else {
            return ['status' => 500, 'message' => 'Database connection error'];
        }
    }

    public function getUserByEmail($mysqli, $email) {
        $stmt = $mysqli->prepare("SELECT * FROM registration WHERE user_email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
?>
