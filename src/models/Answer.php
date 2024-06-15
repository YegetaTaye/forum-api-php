<?php
require_once __DIR__ . '/../config/database.php';

class Answer {
    public function getAllAnswers($mysqli) {
        $query = "INSERT INTO answer(answer,question_id,user_id)VALUES(?,?,?)";
        $result = $mysqli->query($query);

        $answers = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $answers[] = $row;
            }
        }
        return $answers;
    }

    public function createAnswer($mysqli, $body, $question_id, $user_id) {
        $stmt = $mysqli->prepare("INSERT INTO answer(answer,question_id,user_id)VALUES(?,?,?)");
        $stmt->bind_param('sii', $body, $question_id, $user_id);
        return $stmt->execute();
    }

    public function getAnswerById($mysqli, $id) {
        $stmt = $mysqli->prepare("SELECT answer_id, answer, question_id, registration.user_id, registration.user_name FROM answer LEFT JOIN registration ON answer.user_id = registration.user_id WHERE answer.question_id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();

        $answers = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $answers[] = $row;
            }
        }
        return $answers;
    }
}
?>
