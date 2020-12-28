<?php

declare(strict_types = 1);

namespace MyGarden\Controllers;

use MyGarden\Exceptions\MissingParameter;
use MyGarden\Exceptions\OutOfRangeInt;
use MyGarden\Exceptions\OverMaxChars;
use MyGarden\Exceptions\UnderMinChars;
use MyGarden\Exceptions\WrongTypeParameter;
use MyGarden\Models\Garden;
use MyGarden\Repositories\RepositoryCollection;
use MyGarden\Request\Request;
use MyGarden\Responses\GardenResponse;
use MyGarden\Views\ViewInterface;

class GardenController extends Controller
{
    public function __construct(RepositoryCollection $repositoryCollection, ViewInterface $view)
    {
        parent::__construct($repositoryCollection, $view);

        $this->response = new GardenResponse();
    }

    /**
     * @throws \Exception
     */
//    public function getAll(): void
//    {
//        $userId = $this->user->getId();
//
//        $plantArray = $this->repositoryCollection->plantRepository->getUserPlants($userId);
//
//        $this->response = new Response(
//            200,
//            $plantArray->getItems()
//        );
//
//        $this->view->display($this->response);
//    }
//
//    /**
//     * @param Request $request
//     * @throws NotFound
//     * @throws OutOfRangeInt
//     * @throws OverMaxChars
//     * @throws UnderMinChars
//     * @throws \Exception
//     */
//    public function get(Request $request): void
//    {
//        $userId = $this->user->getId();
//
//        $plantId = $request->params['id'];
//
//        $plantId = $request->validateInteger($plantId);
//
//        $plant = $this->repositoryCollection->plantRepository->getUserPlant($userId, $plantId);
//
//        $this->response = new Response(
//            200,
//            $plant
//        );
//
//        $this->view->display($this->response);
//    }
//
//    /**
//     * @param Request $request
//     * @throws NotFound
//     * @throws \Exception
//     */
//    public function delete(Request $request): void
//    {
//        $userId = $this->user->getId();
//
//        $plantId = $request->params['id'];
//
//        $plantId = $request->validateInteger($plantId);
//
//        $this->repositoryCollection->plantRepository->deleteUserPlant($userId, $plantId);
//
//        $this->response = new Response(
//            204,
//            ''
//        );
//
//        $this->view->display($this->response);
//    }

    /**
     * @param Request $request
     * @throws MissingParameter
     * @throws OutOfRangeInt
     * @throws OverMaxChars
     * @throws UnderMinChars
     * @throws WrongTypeParameter
     * @throws \Exception
     */
    public function store(Request $request): void
    {
        $userId = $this->user->getId();

        $request->validateExistsWithType([
            'name' => [
                'type' => 'string',
            ],
            'dimensionX' => [
                'type' => 'integer',
            ],
            'dimensionY' => [
                'type' => 'integer',
            ],
        ]);

        $name = $request->params['name'];

        $dimensionX = $request->params['dimensionX'];

        $dimensionY = $request->params['dimensionY'];

        $garden = new Garden(null, $userId, $name, $dimensionX, $dimensionY);

        $garden = $this->repositoryCollection->gardenRepository->saveUserGarden($garden);

        $this->response->setCode(201);

        $this->response->setSingleResponse($garden);

        $this->view->display($this->response);
    }

    /**
     * @param Request $request
     * @throws \Exception
     */
//    public function update(Request $request): void
//    {
//        $userId = $this->user->getId();
//
//        $plantId = $request->params['id'];
//
//        //TODO put this in validateExistsWithType() OR RENAME
//        $plantId = $request->validateInteger($plantId);
//
//        $request->validateExistsWithType([
//             'englishName' => [
//                 'type' => 'string',
//             ],
//             'latinName' => [
//                 'type' => 'string',
//             ],
//             'imageLink' => [
//                 'type' => 'string',
//             ],
//        ]);
//
//        $englishName = $request->params['englishName'];
//
//        $latinName = $request->params['latinName'];
//
//        $imageLink = $request->params['imageLink'];
//
//        $plant = new Plant($plantId, $userId, $englishName, $latinName, $imageLink);
//
//        $plant = $this->repositoryCollection->plantRepository->updateUserPlant($userId, $plant);
//
//        $this->response = new Response(
//            200,
//            $plant
//        );
//
//        $this->view->display($this->response);
//    }
}