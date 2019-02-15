<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Role as RoleResource;
use App\Role;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class RoleController extends Controller
{
    /**
     * Constructor Class
     *
     * RoleController constructor.
     */
    public function __construct()
    {
        $this->middleware('scopes:manager');
    }

    /**
     * @OA\Get(
     *     path="/api/roles",
     *     tags={"Role"},
     *     summary="Lists all roles created",
     *     description="Return all roles created",
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
     *         {"bearer": {"manager"}}
     *     }
     * )
     *
     * Get Roles
     *
     */
    public function index()
    {
        $roles = Role::all();
        return RoleResource::collection($roles);
    }

    /**
     * @OA\Get(
     *     path="/api/roles/{id}/{role}",
     *     tags={"Role"},
     *     summary="Lists all roles created by filter",
     *     description="Return all roles created",
     *     operationId="show",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id of the user",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="role",
     *         in="path",
     *         description="Name of the role",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
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
     *         {"bearer": {"manager"}}
     *     }
     * )
     * Get roles by Iduser and role.
     *
     * @param int    $idUser Id user
     * @param string $role   Role
     *
     * @return AnonymousResourceCollection
     */
    public function show($idUser, $role)
    {
        $roles = Role::where(['user_id' => $idUser, 'role' => $role])->get();
        return RoleResource::collection($roles);
    }

    /**
     * @OA\Get(
     *     path="/api/roles/{id}",
     *     tags={"Role"},
     *     summary="Lists all roles created to the user",
     *     description="Return all roles created",
     *     operationId="showRoles",
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
     *         response=401,
     *         description="Unauthorized",
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *     ),
     *     security={
     *         {"bearer": {"manager"}}
     *     }
     * )
     * Get roles by Iduser
     *
     * @param int $idUser Id user
     *
     * @return AnonymousResourceCollection
     */
    public function showRoles($idUser)
    {
        $roles = Role::where(['user_id' => $idUser])->get();
        return RoleResource::collection($roles);
    }

    /**
     * @OA\Post(
     *     path="/api/roles",
     *     operationId="store",
     *     tags={"Role"},
     *     summary="Save role",
     *     description="Save a role with the data informed",
     *     @OA\RequestBody(
     *         description="Object that needs to be added to the role",
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"user_id", "role"},
     *                 @OA\Property(
     *                     property="user_id",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="role",
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
     *         {"bearer": {"manager"}}
     *     }
     * )
     *
     * Saves a rule to the user
     *
     * @param Request $request
     * @return RoleResource
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id'  => 'required|integer',
            'role'     => 'required|string|min:1'
        ]);

        if (User::where('id', $data['user_id'])->get()->isEmpty()) {
            return response()->json(['error' => "Unauthorized action"], 401);
        }

        if (Role::where(['user_id' => $data['user_id'], 'role' => $data['role']])->get()->count() > 0) {
            return response()->json(['error' => "Unauthorized action"], 401);
        }

        $role = Role::create($data);
        return new RoleResource($role);
    }

    /**
     * @OA\Delete(
     *     path="/api/roles/{id}/{role}",
     *     tags={"Role"},
     *     summary="Delete a role",
     *     operationId="destroy",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="role",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
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
     *         {"bearer": {"manager"}}
     *     }
     * )
     *
     * @param int    $id   Id of the user
     * @param string $role Name Rule
     *
     * @return JsonResponse
     */
    public function destroy($id, $role)
    {
        Role::where(['user_id' => $id, 'role' => $role])->delete();
        return response()->json(['data' => ['message' => 'Record deleted with success.']]);
    }
}
