<?php
require_once __DIR__ . '/../controllers/UserController.php';
require_once __DIR__ . '/../controllers/QuestionController.php';
require_once __DIR__ . '/../controllers/AnswerController.php';
require_once __DIR__ . '/../config/database.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

switch ($uri) {
    // User routes
    case '/public/api/users':
        $userController = new UserController();
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $userController->getUsers($mysqli);
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userController->createUser($mysqli);
        } else {
            sendResponse(405, ['message' => 'Method Not Allowed']);
        }
        break;

    // User login route
    case '/public/api/login':
        $userController = new UserController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userController->loginUser($mysqli);
        } else {
            sendResponse(405, ['message' => 'Method Not Allowed']);
        }
        break;

    case (preg_match('/\/public\/api\/users\/(\d+)/', $uri, $matches) ? true : false):
        $userController = new UserController();
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $userController->getUserById($mysqli, $matches[1]);
        } else {
            sendResponse(405, ['message' => 'Method Not Allowed']);
        }
        break;
    
    // Question routes
    case '/public/api/questions':
        $questionController = new QuestionController();
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $questionController->getQuestions($mysqli);
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $questionController->createQuestion($mysqli);
        } else {
            sendResponse(405, ['message' => 'Method Not Allowed']);
        }
        break;
    
    // File download route
    case '/public/api/questions/download':
        if (isset($_GET['file'])) {
            $questionController->downloadFile($_GET['file']);
        } else {
            sendResponse(400, ['message' => 'File parameter is missing']);
        }
        break;
    
    case (preg_match('/\/public\/api\/questions\/(\d+)/', $uri, $matches) ? true : false):
        $QuestionController = new QuestionController();
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $QuestionController->getQuestionById($mysqli, $matches[1]);
        } else {
            sendResponse(405, ['message' => 'Method Not Allowed']);
        }
        break;

    // Answer routes
    case '/public/api/answers':
        $answerController = new AnswerController();
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $answerController->getAnswers($mysqli);
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $answerController->createAnswer($mysqli);
        } else {
            sendResponse(405, ['message' => 'Method Not Allowed']);
        }
        break;
    
        case (preg_match('/\/public\/api\/answer\/(\d+)/', $uri, $matches) ? true : false):
            $answerController = new AnswerController();
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $answerController->getAnswerById($mysqli, $matches[1]);
            } else {
                sendResponse(405, ['message' => 'Method Not Allowed']);
            }
            break;

    default:
        sendResponse(404, ['message' => 'Not Found']);
        break;
}
?>
