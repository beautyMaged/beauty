<?php

namespace App\Http\Controllers;

use App\Model\ContactMessage;
use Illuminate\Http\Request;
use App\Http\Requests\ContactMessageRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Model\Admin;
use Illuminate\Support\Facades\Mail;
use App\Model\BusinessSetting;
use App\Mail\ContactMessageMailToAdmins;
use Exception;

class ContactMessageController extends Controller
{

    public function __construct(){
        $this->middleware('auth:admin')->except(['store','messageTypes']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contactMessages = ContactMessage::all();
        return response()->json(['contactMessages' => $contactMessages]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ContactMessageRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();

            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $filePath = $file->store('contact-messages-attachments', 'public');
                $data['attachment'] = $filePath;
            }

            $contactMessage = ContactMessage::create($data);
            
            Mail::to($this->recepient($request->type))
            ->send(new ContactMessageMailToAdmins($contactMessage));


            DB::commit();
            return response()->json(['data' => $contactMessage], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $contactMessages = ContactMessage::find($id);

        if (!$contactMessages) {
            return response()->json(['error' => 'ContactMessage not found'], 404);
        }

        return response()->json(['contactMessages' => $contactMessages]);
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        DB::beginTransaction();
        try {
            $contactMessage = ContactMessage::find($id);

            if (!$contactMessage) {

                return response()->json(['error' => 'ContactMessage not found'], 404);
            }

            if ($contactMessage->attachment) {

                try{    
                    
                    Storage::disk('public')->delete($contactMessage->attachment);

                }catch(\Exception $e){
                    Log::alert('no attachements was available');
                }
            }

            $contactMessage->delete();

            DB::commit();
            return response()->json(['message' => 'ContactMessage deleted successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in destroy method: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to delete contact message.'], 500);
        }
    }

    // public static function messageTypes(){

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

    private function getEmail($job){
        $email  = Admin::whereHas('role',function($query) use ($job) {
                    $query->where('job',"=", $job);
                })->pluck('email')->first();
        return $email;
    }

    private function recepient($type){

        $generalEmail = BusinessSetting::where('type','company_email')->first()->value;
        
        $recepients = [$generalEmail];
        switch ($type) {
            case "customer_service":
                $email = $this->getEmail("care");
                $email ? array_push($recepients,$email): false;
            break;
            
            case "seller_service":
                $email = $this->getEmail("affservice");
                $email ? array_push($recepients,$email): false;
            break;

            case "products and offers":
                $email = $this->getEmail("care");
                $email ? array_push($recepients,$email): false;
            break;

            case "general":
                $email = $this->getEmail("care");
                $email ? array_push($recepients,$email): false;
            break;

            case "complaint":
                $email = $this->getEmail("info");
                $email ? array_push($recepients,$email): false;
            break;

            case "join as a seller":
                $email = $this->getEmail("affiliate");
                $email ? array_push($recepients,$email): false;
            break;

            case "wholesale sales":
                $email = $this->getEmail("sales");
                $email ? array_push($recepients,$email): false;
            break;

        }
        return $recepients;
    }
}
