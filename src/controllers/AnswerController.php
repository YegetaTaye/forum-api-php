<?php
require_once __DIR__ . '/../models/Answer.php';
require_once __DIR__ . '/../helpers/response.php';

class AnswerController {
    public function getAnswers($mysqli) {
        $answerModel = new Answer();
        $answers = $answerModel->getAllAnswers($mysqli);
        sendResponse(200, $answers);
    }

    public function createAnswer($mysqli) {
        $input = json_decode(file_get_contents('php://input'), true);

        if (isset($input['answer'], $input['question_id'], $input['user_id'])) {
            $answerModel = new Answer();
            $result = $answerModel->createAnswer($mysqli, $input['answer'], $input['question_id'], $input['user_id']);
            if ($result) {
                sendResponse(201, ['message' => 'Answer created successfully']);
            } else {
                sendResponse(500, ['message' => 'Failed to create answer']);
            }
        } else {
            sendResponse(400, ['message' => 'Invalid input']);
        }
    }

    public function getAnswerById($mysqli, $id) {
        $answerModel = new Answer();
        $answer = $answerModel->getAnswerById($mysqli, $id);

        if (!$answer) {
            sendResponse(404, ['message' => 'Record not found']);
            return;
        }

        sendResponse(200, [$answer]);
    }
}
?>
