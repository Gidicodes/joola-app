<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserRegistrationRequest;
use App\Services\{UserService, TripService, LocationService, ChatService};
use Tymon\JWTAuth\Facades\JWTAuth;




class AdminController extends Controller
{
    private $userService, $tripService, $chat, $locationService;
    //
    public function __construct(UserService $userService, TripService $trip, LocationService $location, ChatService $chat)
    {
        $this->userService = $userService;
        $this->tripService = $trip;
        $this->locationService = $location;
        $this->chat = $chat;
    }

    /**
     * 
     * User Registration
     * @return JsonResponse
     */
    public function register(UserRegistrationRequest $request)
    {
        $data = $request->validated();
        $user = $this->userService->register($data, 1);
        $token = JWTAuth::fromUser($user);
        return $this->respondWithSuccess(['data' => compact('user', 'token')], 201);
    }


    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $token = $this->userService->authenticate($credentials, 1);
        if (is_array($token)) {
            return $this->respondWithError(['error' => $token['error']], $token['status']);
        }
        return $this->respondWithSuccess(['data' => compact('token')], 201);
    }


    public function createDriver(UserRegistrationRequest $request)
    {
        $data = $request->validated();
        $user = $this->userService->createDriver($data);
        return $this->respondWithSuccess(['data' => compact('user')], 201);
    }

    public function getTrips(Request $request)
    {
        $per_page = $request->get('perPage') ?? 15;
        return $this->respondWithSuccess($this->tripService->getAllRequests($per_page));
    }

    public function getTripRequests(Request $request)
    {
        $per_page = $request->get('perPage') ?? 15;
        return $this->respondWithSuccess($this->tripService->getTripByStatus(2, $per_page));
    }

    public function getInProgressTrips(Request $request)
    {
        $per_page = $request->get('perPage') ?? 15;
        return $this->respondWithSuccess($this->tripService->getBulkTripByStatus([1,3], $per_page));
    }

    /**
     * Gets Single Trip Infomation
     * @return JsonResponse
     */
    public function getSingleTrip(int $id)
    {
        return $this->respondWithSuccess($this->tripService->getTrip($id));
    }


    /**
     * Assigns a driver to a trip
     * @return JsonResponse
     */
    public function assignDriver(int $id, string $uuid)
    {
        return $this->respondWithSuccess($this->tripService->assignDriver($id, $uuid));
    }

    /**
     * Deletes Trip
     * @return JsonResponse
     */
    public function deleteTrip(int $id)
    {
        return $this->respondWithSuccess($this->tripService->delete($id));
    }

    public function getUser(string $uuid)
    {
        return $this->respondWithSuccess($this->userService->getUserByUuid($uuid));
    }

    public function toggleUserStatus(string $uuid)
    {
        return $this->respondWithSuccess($this->userService->toggleUserStatus($uuid));
    }

    public function getUserLocation(string $uuid)
    {
        return $this->respondWithSuccess($this->userService->getUserLocation($uuid));
    }

    public function getNotification(){
        return $this->respondWithSuccess($this->userService->getUserNotifications());
    }
}
