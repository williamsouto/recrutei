<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Role;
use App\User;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * @OA\Info(
     *      version="1.0.0",
     *      title="Recrutei Api",
     *      description="User registration, permissions and cars api",
     *      @OA\Contact(
     *          url="https://www.linkedin.com/in/william-souto-180004a6/"
     *      )
     * ),
     * @OA\Server(
     *      url=L5_SWAGGER_CONST_HOST
     * ),
     * @OA\SecurityScheme(
     *     type="oauth2",
     *     description="Use a global client_id / client_secret and your username / password combo to obtain a token",
     *     name="bearer",
     *     in="header",
     *     scheme="bearer",
     *     securityScheme="bearer",
     *     bearerFormat="JWT",
     *     @OA\Flow(
     *         flow="password",
     *         authorizationUrl="/oauth/authorize",
     *         tokenUrl="/oauth/token",
     *         refreshUrl="/oauth/token/refresh",
     *         scopes={
     *             "manager": "All Privileges",
     *             "list-car": "List the cars",
     *             "create-car": "Create a car",
     *             "update-car": "Update a car",
     *             "delete-car": "Delete a car"
     *         }
     *     )
     * ),
     * @OA\ExternalDocumentation(
     *     description="Find out more about Swagger",
     *     url="https://www.swagger.io"
     * ),
     * @OA\Post(
     *     path="/api/register",
     *     operationId="register",
     *     tags={"Authentication"},
     *     summary="Register a user",
     *     description="Returns infos of token access",
     *     @OA\RequestBody(
     *         description="Object that needs to be added to the store",
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"name","email","password"},
     *                 @OA\Property(
     *                     property="name",
     *                     description="Name of the user",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     description="Email of the user",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     description="Password of the user",
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
     *     )
     * )
     *
     * Register a user to get a access token
     *
     * @param Request $request
     * @return ResponseFactory|Response
     */
    public function register(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'name'     => 'required|min:1',
            'password' => 'required|min:1'
        ]);

        $user = User::firstOrNew(['email' => $request->email]);
        DB::beginTransaction();

        $user->email = $request->email;
        $user->name = $request->name;
        $user->password = bcrypt($request->password);
        $user->save();

        Role::create(['user_id' => $user->id, 'role' => 'list-car']);
        DB::commit();

        $http = new Client();

        $oauthClient = DB::table('oauth_clients')->where('password_client', 1)->first();

        $response = $http->post(url('oauth/token'), [
            'form_params' => [
                'grant_type'    => 'password',
                'client_id'     => $oauthClient->id,
                'client_secret' => $oauthClient->secret,
                'username'      => $request->email,
                'password'      => $request->password,
                'scope'         => 'list-car'
            ],
        ]);

        return response(['data' => json_decode((string) $response->getBody(), true)]);
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     operationId="login",
     *     tags={"Authentication"},
     *     summary="Login",
     *     description="Returns infos of token access",
     *     @OA\RequestBody(
     *         description="Object that needs to be added to the login",
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"email","password"},
     *                 @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="integer"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok",
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity",
     *     )
     * )
     *
     * Login
     *
     * @param Request $request
     * @return ResponseFactory|Response
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:1'
        ]);

        $user = User::where(['email' => $request->email])->first();

        if (!$user) {
            return response(['status' => 'error', 'message' => 'User not found']);
        }

        if (Hash::check($request->password, $user->password)) {
            $scopes = Role::where('user_id', $user->id);
            $scopes = $scopes->count() > 0 ? $scopes->get()->implode('role', ' ') : 'list-car';

            $oauthClient = DB::table('oauth_clients')->where('password_client', 1)->first();
            $http = new Client();

            $response = $http->post(url('oauth/token'), [
                'form_params' => [
                    'grant_type'    => 'password',
                    'client_id'     => $oauthClient->id,
                    'client_secret' => $oauthClient->secret,
                    'username'      => $request->email,
                    'password'      => $request->password,
                    'scope'         => $scopes
                ],
            ]);

            return response(['status' => 'ok', 'data' => json_decode((string) $response->getBody(), true)]);
        }

        return response(['status' => 'error', 'message' => 'Password invalid']);
    }

    /**
     * @OA\Put(
     *     path="/api/users",
     *     operationId="update",
     *     tags={"User"},
     *     summary="Update user",
     *     description="Returns data user",
     *     @OA\RequestBody(
     *         description="Object that needs to be added to the update",
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="integer"
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
     *     security={
     *         {"bearer": {"list-car"}}
     *     },
     * )
     *
     * Update User
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function update(Request $request)
    {
        $data = $request->validate([
            'email'    => 'nullable|email',
            'name'     => 'nullable|min:1',
            'password' => 'nullable|min:1'
        ]);

        $data = collect($data)->filter(function ($value) {
            return !empty($value);
        })->all();

        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        User::find($request->user()->id)->update($data);
        return response()->json(['data' => $data]);
    }
}
