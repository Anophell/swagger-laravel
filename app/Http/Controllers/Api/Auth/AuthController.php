<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use OpenApi\Annotations as OA;

class AuthController extends Controller
{
    /**
     * @param RegisterRequest $request
     * @return JsonResponse
     *
     * @OA\Post(
     *     path="/api/register",
     *    summary="Register a new user",
     *     tags={"auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string" , example="Mohammad Abdorrahmani"),
     *             @OA\Property(property="email", type="string" , example="mohammad@anophel.com"),
     *             @OA\Property(property="password", type="string" ,format="password", example="123456789"),
     *            @OA\Property(property="password_confirmation", type="string" ,format="password", example="123456789"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User registered successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", description="Access token for the registered user"),
     *              @OA\Property(property="user", type="object", description="User details"),
     *         )
     *     ),
     *     @OA\Response(
     *           response=422,
     *           description="Validation error",
     *           @OA\JsonContent(
     *               @OA\Property(property="message", type="string", description="Error message describing validation failure"),
     *           ),
     *       ),
     * )
     */
    public function register(RegisterRequest $request): \Illuminate\Http\JsonResponse
    {
        $validatedData = $request->validated();

        $user = User::create([
           'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'remember_token' => Str::random(10)
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['token' => $token , 'user' => $user]);
    }

    /**
     * @OA\Post(
     *      path="/api/login",
     *      summary="Login a user",
     *      tags={"Authentication"},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *              @OA\Property(property="password", type="string", format="password", example="password"),
     *          ),
     *      ),
     *   @OA\Response(
     *          response=200,
     *          description="Login successful",
     *          @OA\JsonContent(
     *              @OA\Property(property="token", type="string", description="Access token for the authenticated user"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Invalid credentials or user not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", description="Error message"),
     *          ),
     *      ),
     *  )
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $token = Auth::user()->createToken('API Token')->accessToken;
            return response()->json(['token' => $token], 200);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="Logout the authenticated user",
     *     tags={"Authentication"},
     *     security={{"passport":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successfully logged out",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="You have been successfully logged out!"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated"),
     *         ),
     *     ),
     * )
     */
    public function logout (Request $request) {
        $token = $request->user()->token();
        $token->revoke();
        $response = ['message' => 'You have been successfully logged out!'];
        return response($response, 200);
    }

}
