<?php

namespace App\Http\Controllers;

use App\Model\User;
use App\Model\plan;
use App\Model\subscription;
use App\Model\transaction;
use Illuminate\Http\Request;
use Validator;
use Carbon\Carbon;

   /**
 * @OA\Info(
 *      version="1.0.0",
 *      title="L5 OpenApi",
 *      description="L5 Swagger OpenApi description",
 *      @OA\Contact(
 *          email="darius@matulionis.lt"
 *      ),
 *     @OA\License(
 *         name="Apache 2.0",
 *         url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *     )
 * )
 */

/**
 *  @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="L5 Swagger OpenApi dynamic host server"
 *  )
 *
 *  @OA\Server(
*      url="https://projects.dev/api/v1",
 *      description="L5 Swagger OpenApi Server"
 * )
 */

/**
 * @OA\SecurityScheme(
 *     type="oauth2",
 *     description="Use a global client_id / client_secret and your username / password combo to obtain a token",
 *     name="Password Based",
 *     in="header",
 *     scheme="https",
 *     securityScheme="Password Based",
 *     @OA\Flow(
 *         flow="password",
 *         authorizationUrl="/oauth/authorize",
 *         tokenUrl="/oauth/token",
 *         refreshUrl="/oauth/token/refresh",
 *         scopes={}
 *     )
 * )
 */

/**
 * @OA\OpenApi(
 *   security={
 *     {
 *       "oauth2": {"read:oauth2"},
 *     }
 *   }
 * )
/**
 * @OA\Tag(
 *     name="project",
 *     description="Everything about your Projects",
 *     @OA\ExternalDocumentation(
 *         description="Find out more",
 *         url="http://swagger.io"
 *     )
 * )
 *
 * @OA\Tag(
 *     name="user",
 *     description="Operations about user",
 *     @OA\ExternalDocumentation(
 *         description="Find out more about",
 *         url="http://swagger.io"
 *     )
 * )
 * @OA\ExternalDocumentation(
 *     description="Find out more about Swagger",
 *     url="http://swagger.io"
 * )
 */

/**
 * @OA\Get(
 *      path="/projects",
 *      operationId="getProjectsList",
 *      tags={"Projects"},
 *      summary="Get list of projects",
 *      description="Returns list of projects",
 *      @OA\Response(
 *          response=200,
 *          description="successful operation"
 *       ),
 *       @OA\Response(response=400, description="Bad request"),
 *       security={
 *           {"api_key_security_example": {}}
 *       }
 *     )
 *
 * Returns list of projects
 */

/**
 * @OA\Get(
 *      path="/projects/{id}",
 *      operationId="getProjectById",
 *      tags={"Projects"},
 *      summary="Get project information",
 *      description="Returns project data",
 *      @OA\Parameter(
 *          name="id",
 *          description="Project id",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="successful operation"
 *       ),
 *      @OA\Response(response=400, description="Bad request"),
 *      @OA\Response(response=404, description="Resource Not Found"),
 *      security={
 *         {
 *             "oauth2_security_example": {"write:projects", "read:projects"}
 *         }
 *     },
 * )
 */

class UserController extends Controller
{
    public function plan() {
        return plan::all();
    }

    public function user() {
        return User::all();
    }

    public function create(Request $request) {
            $rules = [
                'name' => 'required',
                'email' => 'required|email',
            ];
    
            $validator = Validator::make($request->all(), $rules);
    
            if($validator->fails()){
                return response()->json($validator->errors(), 400);
            }
    
            $order = User::create($request->all());

            $user = new User;

            $user->save();
    
            return response()->json($order, 201);

    }

    public function subscribe(Request $request) {

        $rules = [
            'subscription_name' => 'required',
            'plan_id' => 'required',
            'user_id' => 'required',
            'active' => 'required',
            'start_time' => 'required',
            'subscription_cost' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $sub = new subscription;

        $sub->subscription_name = $request->subscription_name;
        $sub->plan_id = $request->plan_id;
        $sub->user_id = $request->user_id;
        $sub->active = true;
        $date = $request->start_time;
        $sub->start_time = Carbon::createFromFormat('d-m-Y', $date)->format('Y-m-d');
        $sub->subscription_cost = $request->subscription_cost;

        if ($sub->save()) {

            if ($sub->active == 1) {
                $status = 'active';
            }

            else {
                $status = 'Expired';
            }

            $subExpire = Carbon::parse( $sub->start_time)->addDays(30);

            $expire = $subExpire->format('l jS \\of F Y h:i:s A');

            $sub_summary = ' Your subscription plan is active: 
                              with subscription plan: '.$sub->subscription_name.'
                              and cost: '.$sub->subscription_cost.'
                              Status: '.$status.'
                              Your subscription expires in: '.$expire.' ';

            $order = json_encode($sub_summary);

            return response()->json($sub_summary, 201);

        }

    }
}
