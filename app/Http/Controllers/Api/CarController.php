<?php

namespace App\Http\Controllers\Api;

use App\Car;
use App\Http\Requests\CarsFormRequest;
use App\Http\Resources\Car as CarResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CarController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/cars",
     *     tags={"Car"},
     *     summary="Lists all cars created",
     *     description="Return all cars created",
     *     operationId="index",
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *     ),
     *     security={
     *         {"bearer": {"list-car"}}
     *     }
     * )
     *
     * @return CarResource|AnonymousResourceCollection
     */
    public function index()
    {
        $cars = Car::all();
        return CarResource::collection($cars);
    }

    /**
     * @OA\Get(
     *     path="/api/cars/{id}",
     *     tags={"Car"},
     *     summary="Lists all cars created by filter",
     *     description="Return all cars created",
     *     operationId="show",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id of the car",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             default="available"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *     ),
     *     security={
     *         {"bearer": {"list-car"}}
     *     }
     * )
     *
     * @param integer $id
     * @return CarResource|AnonymousResourceCollection
     */
    public function show($id)
    {
        $cars = Car::where('car_id', $id)->first();
        return new CarResource($cars);
    }

    /**
     * @OA\Post(
     *     path="/api/cars",
     *     operationId="store",
     *     tags={"Car"},
     *     summary="Save",
     *     description="Save a car with the data informed",
     *     @OA\RequestBody(
     *         description="Object that needs to be added to the car",
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"model", "category", "motor_power", "ports", "board", "type_vehicle", "year", "fuel", "mileage", "exchange", "direction", "color"},
     *                 @OA\Property(
     *                     property="model",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="category",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="motor_power",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="ports",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="board",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="type_vehicle",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="year",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="fuel",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="mileage",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="exchange",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="direction",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="color",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="options",
     *                     type="string"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Created",
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *     ),
     *     security={
     *         {"bearer": {"create-car"}}
     *     }
     * )
     *
     * Save the car
     *
     * @param CarsFormRequest $request
     * @return CarResource
     */
    public function store(CarsFormRequest $request)
    {
        $data = $request->validated();

        $data = collect($data)->filter(function ($value) {
            return !empty($value);
        })->all();

        $car = $request->user()->cars()->create($data);
        return new CarResource($car);
    }

    /**
     * @OA\Put(
     *     path="/api/cars/{id}",
     *     operationId="update",
     *     tags={"Car"},
     *     summary="Update car",
     *     description="Returns data car",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id of the car",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         description="Object that needs to be update to the car",
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"model", "category", "motor_power", "ports", "board", "type_vehicle", "year", "fuel", "mileage", "exchange", "direction", "color"},
     *                 @OA\Property(
     *                     property="model",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="category",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="motor_power",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="ports",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="board",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="type_vehicle",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="year",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="fuel",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="mileage",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="exchange",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="direction",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="color",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="options",
     *                     type="string"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *     ),
     *     security={
     *         {"bearer": {"update-car"}}
     *     },
     * )
     *
     * @param CarsFormRequest $request
     * @param Car             $car
     *
     * @return CarResource
     */
    public function update(CarsFormRequest $request, Car $car)
    {
        $data = $request->validated();

        $car->update($data);
        return new CarResource($car);
    }

    /**
     * @OA\Delete(
     *     path="/api/cars/{id}",
     *     tags={"Car"},
     *     summary="Deletes a car",
     *     operationId="destroy",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *     ),
     *     security={
     *         {"bearer": {"delete-car"}}
     *     }
     * )
     *
     * Delete role of the user
     *
     * @param Request $request Resource Request
     * @param Car     $car     Model Car
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(Request $request, Car $car)
    {
        if ($request->user()->id != $car->user_id) {
            return response()->json(['error' => "Unauthorized action"], 401);
        }

        $car->delete();
        return response()->json(['data' => ['message' => 'Record deleted with success.']]);
    }
}
