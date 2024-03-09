<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Model\Admin;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Exception;
class ContactMessageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'type' => ['required', Rule::in(['customer_service','seller_service','products and offers','general','complaint','join as a seller','wholesale sales'])],
            'title' => 'required|string',
            'message' => 'required|string',
            'attachment' => 'file|mimes:png,jpg,jpeg,webp,doc,docx,xls,xlsx,pdf|max:2048',
            ];
    }

    // private function adminEmails(){
    //     $emails = Admin::whereHas('role',function($query){
    //         $query->where('job',"!=", 'admin');
    //     })->pluck('email');

    //     return $emails;
    // }

    // private function messageTypes(){

    //     $tableName = 'contact_messages';
    //     $columnName = 'type';

    //     try{
            
    //         $result = DB::select("SHOW COLUMNS FROM $tableName LIKE '$columnName'");
        
    //     }catch(Exception $e){

    //         return response()->json(['DB error' => $e->getMessage()], 500);
    //     }

    //     $enumValues = [];

    //     if (!empty($result)) {
    //         $enumMatch = [];
    //         preg_match("/^enum\((.*)\)$/", $result[0]->Type, $enumMatch);

    //         if (!empty($enumMatch[1])) {
    //             $enumValues = explode(',', str_replace("'", "", $enumMatch[1]));
    //         }
    //     }

    //     return $enumValues;
    // }
}
