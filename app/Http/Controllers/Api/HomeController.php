<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use OpenApi\Annotations as OA;

class HomeController extends Controller
{
    /**
     * @OA\PathItem(path="/api")
     *
     * @OA\Info(
     *      version="0.0.0",
     *      title="Anophel API Documentation"
     *  )
     */
    public function index()
    {
        return "Anophel APi Documentation with swagger";
    }

    /**
     * @OA\Get(
     *      path="/api/users",
     *      summary="Get all users",
     *      tags={"users"},
     *      @OA\Response(
     *          response="200",
     *          description="Success"
     *      ),
     *     @OA\Response(response=400, description="Invalid request")
     * )
     */
    public function users(): \Illuminate\Http\JsonResponse
    {
        $users = User::all();

        return response()->json(['users' => $users]);
    }
}
