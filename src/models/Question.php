<?php
require_once __DIR__ . '/../config/database.php';

class Question {
    public function getAllQuestions($mysqli) {
        $query = "SELECT registration.user_name, question,question_id, question_description,question_code_block,tags,post_id FROM question JOIN registration ON question.user_id = registration.user_id  ORDER BY question_id DESC";
        $result = $mysqli->query($query);

        $questions = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $questions[] = $row;
            }
        }
        return $questions;
    }

    public function createQuestion($mysqli, $question, $questionDescription, $tag, $id, $post_id) {
        $stmt = $mysqli->prepare("INSERT INTO question(question, question_description, tags, user_id, post_id) VALUES(?, ?, ?, ?, ?)");
        $stmt->bind_param('sssii', $question, $questionDescription, $tag, $id, $post_id);
        return $stmt->execute();
    }
       

    public function getQuestionById($mysqli, $id) {
        $stmt = $mysqli->prepare("SELECT * FROM question WHERE question_id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
?>
