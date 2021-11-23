<?php

namespace App\Http\Controllers;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Mail\sendmail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

use Illuminate\Http\Request;
use MongoDB\Client as MongoDB_client_ki_class; 

class userController extends Controller

{
    public function register(Request $request)
    {
        //Validate the fields
        $fields = $request->validate(
            [
                'name' => 'required|string',
                'email' => 'required|email',
                'password' => 'required|confirmed'
            ]
        );


        //Create the user
        $collection = (new MongoDB_client_ki_class())->db_ka_name->register_yani_collection_yani_table_name;
        $insert = $collection->insertOne([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => Hash::make($fields['password']),
        ]);

        // Mail::to($request['email'])->send(new Sendmail());
        //Generate token for the user
        // $token = $user->createToken('ProgrammersForce')->plainTextToken;

        $response = [
            'message' => 'User has been created successfully',
            // 'token' => $token
        ];

        //Return HTTP 201 status, call was successful and something was created
        return response($response, 201);
    }

    function createToken($data)
    {
        $key = "checkMe";
        $payload = array(
            "iss" => "http://127.0.0.1:8000",
            "aud" => "http://127.0.0.1:8000/api",
            "iat" => time(),
            "nbf" => 1357000000,
            "data" => $data,
        );
        $jwt = JWT::encode($payload, $key, 'HS256');
        return $jwt;
    }

    public function login(Request $request)
    {
        $request = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Check Student
        // $user = User::where('email', "=" , $request['email'])->first();
        $collection = (new MongoDB_client_ki_class())->db_ka_name->register_yani_collection_yani_table_name;

        // $POST = $collection->findOne(['_id' => new \MongoDB\BSON\ObjectId(OPERR SY JO POSTID)]);
        $user = $collection->findOne(['email' => $request['email']]);

        if(isset($user)){
            
            if (Hash::check($request['password'], $user['password'])) {
                
                // $isLoggedIn = Token::where('userID', $user->id)->first();
                // if($isLoggedIn){
                //     return response([
                //         "message" => "User already logged In"
                //     ], 400);
                // }   
               
                // Create Token
        $user = iterator_to_array($user);

                $token = $this->createToken((string)$user['_id']);
                
                // dd($token);
                // saving token table in db
                // $saveToken = Token::create([
                //     "userID"=>$user->id,
                //     "token" => $token
                // ]);
                $collection->updateOne(
                    ['email' => $request['email']], 
                    ['$set' => ['token' => $token],
                ]);
                $response = [
                    'status' => 1,
                    'message' => 'Logged in successfully',
                    'user' => $user,
                    'token' => $token
                ];
        
                return response($response, 201);
                
            }else{
                return response([
                    'message' => 'Invalid email or password'
                ], 401);
            }

        }else{
            return response()->json([
                "status"=>0,
                "message"=>"Student not found"
            ],404);
        
        } 
         
    }
//     public function (Request $request)
//     {
//         $request->validate([

//             'user_id'=> 'required',
//             'title'  => 'required|string',
//             'body'  => 'required|string',
//             // 'attachement'  => 'required|file',

//         ]);

//         $collection = (new MongoDB_client_ki_class())->db_ka_name->posts_yani_collection_yani_table_name;
//         $insert = $collection->insertOne([
//             'user_id' => $request->user_id,
//             'title' =>  $request->title,
//             'body' => $request->body,

//         ]);
//         if (isset($insert)) {
//             return response([
//                 'message' => 'Successfully Inserted',
//             ]);
//         } else {
//             return response([
//                 'message' => 'Error in Insertion',
//             ]);
//         }
// }
public function logout(Request $request)
    {
        $getToken = $request->bearerToken(); 

        $decoded = JWT::decode($getToken, new Key("checkMe","HS256"));
        $userID = $decoded->data;
        // dd($userID);
        $collection = (new MongoDB_client_ki_class())->db_ka_name->register_yani_collection_yani_table_name;
        $userExist = $collection->findOne(['token' => $getToken]);

        // $userExist = Token::where("userID",$userID)->first();
        if($userExist)
        {
            $collection->updateOne(['token' => $getToken],['$set'=>['token' => null]]);
        }
        else{
            return response([
                "message" => "This user is already logged out"
            ], 404);
        }

        return response([
            "message" => "logout successfull"
        ], 200);

    }
}
