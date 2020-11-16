<?php

declare(strict_types = 1);

namespace MyGarden;

use MyGarden\Controllers\UserController;
use MyGarden\Database\DatabaseConnection;
use MyGarden\Repositories\RepositoryCollection;
use MyGarden\Views\View;

include '.env.php';

class App
{
//    protected CardController $cardController;
//
//    protected Redirect $redirect;
//


    protected DatabaseConnection $databaseConnection;

    protected RepositoryCollection $repositoryCollection;

    protected UserController $userController;

    public function __construct()
    {
//        $this->redirect = new Redirect();


        $this->databaseConnection = new DatabaseConnection();

        $this->repositoryCollection = new RepositoryCollection($this->databaseConnection);

        $this->userController = new UserController($this->repositoryCollection);

//        $this->cardController = new CardController();
//
//        $this->cardControllerViewWrapper = new CardControllerViewWrapper($this->cardController, $this->redirect);
    }
//
//    public function run()
//    {
//        $uri = $_SERVER['REQUEST_URI'];
//
//        if (preg_match('/^\/api\/.*/', $uri)) {
//            //Card API Routes
//
//            header('Content-Type: application/json');
//
//            if (preg_match('/^\/api\/index$/', $uri) == 1) {
//                $response = $this->cardController->index();
//                http_response_code(200);
//            } elseif (preg_match('/^\/api\/create$/', $uri) == 1) {
//                $response = $this->cardController->create($_POST['category'], $_POST['question'], $_POST['answer']);
//                http_response_code(201);
//            } elseif (preg_match('/^\/api\/delete$/', $uri) == 1) {
//                $response = $this->cardController->delete($_POST['id']);
//                http_response_code(204);
//            } elseif (preg_match('/(?<=^\/api\/show\/)[0-9]+$/', $uri, $matches) == 1) {
//                $response = $this->cardController->show($matches[0]);
//                http_response_code(200);
//            } else {
//                http_response_code(404);
//                $response = 'Endpoint not found';
//            }
//
//            echo json_encode($response);
//        } else {
//            //Card Web Routes
//
//            if (preg_match('/^\/index$/', $uri) == 1) {
//                $this->cardControllerViewWrapper->index();
//            } elseif (preg_match('/^\/createForm$/', $uri) == 1) {
//                $this->cardControllerViewWrapper->createForm();
//            } elseif (preg_match('/^\/create$/', $uri) == 1) {
//                $this->cardControllerViewWrapper->create($_POST['category'], $_POST['question'], $_POST['answer']);
//            } elseif (preg_match('/^\/delete$/', $uri) == 1) {
//                $this->cardControllerViewWrapper->delete($_POST['id']);
//            } elseif (preg_match('/(?<=^\/show\/)[0-9]+$/', $uri, $matches) == 1) {
//                $this->cardControllerViewWrapper->show($matches[0]);
//            } else {
//                $this->redirect->sendTo("/index");
//            }
//        }
//    }

    public function run()
    {

        $this->userController->store('dan', 'dan@email.com', 'password');

        header('content-type: application/json');

        echo json_encode($this->userController->getUserFromEmailAndPassword('dan@email.com', 'password'));

//        var_dump($this->userController->getUserFromEmailAndPassword('dan@email.com', 'password'));
        exit();


        $user = $this->repositoryCollection->userRepository->getUser(1);

        $plantsByLocation = [];

        foreach ($user->getGarden()->getPlants() as $plant) {
            $plantsByLocation[$plant['xCoordinate']] = [
                $plant['yCoordinate'] => $plant['plant']
            ];
        }
        $this->view = new View;
        echo $this->view->display('index.php', [
            'garden' => $user->getGarden(),
            'plantsByLocation' => $plantsByLocation
        ]);

//        $uri = $_SERVER['REQUEST_URI'];
//
//        if (preg_match('/^\/index$/', $uri) == 1) {
//            $this->cardControllerViewWrapper->index();
//        } elseif (preg_match('/^\/createForm$/', $uri) == 1) {
//            $this->cardControllerViewWrapper->createForm();
//        } elseif (preg_match('/^\/create$/', $uri) == 1) {
//            $this->cardControllerViewWrapper->create($_POST['category'], $_POST['question'], $_POST['answer']);
//        } elseif (preg_match('/^\/delete$/', $uri) == 1) {
//            $this->cardControllerViewWrapper->delete($_POST['id']);
//        } elseif (preg_match('/(?<=^\/show\/)[0-9]+$/', $uri, $matches) == 1) {
//            $this->cardControllerViewWrapper->show($matches[0]);
//        } else {
//            $this->redirect->sendTo("/index");
//        }

    }
}
//
//<!--$plantRespository = new PlantRepository();-->
//<!---->
//<!--$gardenRepository = new GardenRepository($plantRespository);-->
//<!---->
//<!--$userRespository = new UserRepository($gardenRepository);-->
//<!---->
//<!---->
//<!---->
//<!--$user = $userRespository->getUser(1);-->
//<!---->
//<!--$plantsByLocation = [];-->
//<!---->
//<!--foreach ($user->getGarden()->getPlants() as $plant){-->
//<!--    $plantsByLocation[$plant['xCoordinate']] = [-->
//<!--        $plant['yCoordinate'] => $plant['plant']-->
//<!--    ];-->
//<!--}-->
//<!---->
//<!--$uri = $_SERVER['REQUEST_URI'];-->
//<!---->
//<!--if (preg_match('/^\/index$/', $uri) == 1) {-->
//<!--    $this->cardControllerViewWrapper->index();-->
//<!--} elseif (preg_match('/^\/createForm$/', $uri) == 1) {-->
//<!--    $this->cardControllerViewWrapper->createForm();-->
//<!--} elseif (preg_match('/^\/create$/', $uri) == 1) {-->
//<!--    $this->cardControllerViewWrapper->create($_POST['category'], $_POST['question'], $_POST['answer']);-->
//<!--} elseif (preg_match('/^\/delete$/', $uri) == 1) {-->
//<!--    $this->cardControllerViewWrapper->delete($_POST['id']);-->
//<!--} elseif (preg_match('/(?<=^\/show\/)[0-9]+$/', $uri, $matches) == 1) {-->
//<!--    $this->cardControllerViewWrapper->show($matches[0]);-->
//<!--} else {-->
//<!--    $this->redirect->sendTo("/index");-->
//<!--}-->