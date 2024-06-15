<?php
require_once __DIR__ . '/../models/Question.php';
require_once __DIR__ . '/../helpers/response.php';

class QuestionController {
    public function getQuestions($mysqli) {
        $questionModel = new Question();
        $questions = $questionModel->getAllQuestions($mysqli);
        sendResponse(200, $questions);
    }

    public function createQuestion($mysqli) {
        $input = json_decode(file_get_contents('php://input'), true);

        if (true) {
            $questionModel = new Question();
            $result = $questionModel->createQuestion($mysqli, $input['question'], $input['questionDescription'],$input['tag'], $input['id'], $input['post_id']);
            if ($result) {
                sendResponse(201, ['message' => 'Question created successfully']);
            } else {
                sendResponse(500, ['message' => 'Failed to create question']);
            }
        } else {
            sendResponse(400, ['message' => 'Invalid inputmmm']);
        }
    }

    public function getQuestionById($mysqli, $id) {
        $questionModel = new Question();
        $question = $questionModel->getQuestionById($mysqli, $id);

        if (!$question) {
            sendResponse(404, ['message' => 'Record not found']);
            return;
        }

        sendResponse(200, ['data' => $question]);
    }
}
?>
