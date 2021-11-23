<?php

namespace App\Http\Controllers;
use MongoDB\Client as MongoDB_client_ki_class;
use Illuminate\Http\Request;
use Symfony\Contracts\Service\Attribute\Required;

class mongoController extends Controller
{
    public function insert(Request $request)
    {
        $request->validate([

            'user_id'=> 'required',
            'title'  => 'required|string',
            'body'  => 'required|string',
            // 'attachement'  => 'required|file',

        ]);

        $collection = (new MongoDB_client_ki_class())->db_ka_name->posts_yani_collection_yani_table_name;
        $insert = $collection->insertOne([
            'user_id' => $request->user_id,
            'title' =>  $request->title,
            'body' => $request->body,

        ]);
        if (isset($insert)) {
            return response([
                'message' => 'Successfully Inserted',
            ]);
        } else {
            return response([
                'message' => 'Error in Insertion',
            ]);
        }
}
    public function update(Request $request, $user_id)
    {
        $collection =(new MongoDB_client_ki_class())->db_ka_name->posts_yani_collection_yani_table_name;
        $findOne = $collection->findOne(['user_id' => $user_id]);
        // dd($findOne);
        $variable_jismay_update_data_hoga =[];
        foreach ($request->all() as $key => $value) {
            if (in_array($key, ['title','body'])) {
            $variable_jismay_update_data_hoga[$key]=$value; 
                
            }
        }

        if (isset($findOne)) {
            $collection->updateOne(
                ['user_id' => $user_id],
                ['$set' => $variable_jismay_update_data_hoga]
            );

            return response([
                'message'=>'Successfully Updated',
            ]);
        } else {
            return response([
                'message'=>'Document not founf',
            ]);
        }

    }


    public function read()
    {
        $collection = (new MongoDB_client_ki_class())->db_ka_name->posts_yani_collection_yani_table_name;
        $findAll = $collection->find()->toArray();
        if (!empty($findAll)) {
            return $findAll;
        }else {
            return response([
                'message'=>'document not found'
            ]);
        }
        
    }
    public function delete($user_id)
{
        $collection = (new MongoDB_client_ki_class())->db_ka_name->posts_yani_collection_yani_table_name;
        // $findOne = $collection->findOne(['username' => ]);
        $findOne = $collection->deleteOne(['user_id' => $user_id]);


    if (isset($findOne)) {
        // $collection->deleteOne(['name' => $name]);
        return response([
            'message' => 'Successfully Deleted',
        ]);
    } else {
        return response([
            'message' => 'This Document Not Found',
        ]);
}
}
}
