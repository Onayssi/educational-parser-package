<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use DB;

class UploadFileController extends Controller{
    /*
     * Upload an 'xml' file format
     * Store it onto the server (as a history)
     * Check if the same file name already exist, then give it a new name (based on timestamp)
     * Move the final file to the /resources/uploads folder
     */
    public function upload(Request $request){
      $file = $request->file('xmlconfig');
      if(!$file->getRealPath()){
          /* Error found (no file provided) */
          $this->display_flash("error","Please, provide an xml file to proceed.");
      }else{
          /* Check the extension of the provided file */
          $file_ext = $file->getClientOriginalExtension();
          if(strtolower($file_ext)!=="xml"){
              /* Error found (file extension) */
              $this->display_flash("error","Invalid file type! Please, provide an 'xml' file format.");
          }else{
              /* get resource path of the uploads folder */
              $destinationPath = resource_path("uploads");            
          }
          if(!session()->has('error')){
              /* Proper name of the provided file */
              $uploaded_filename = $this->proper_filename($destinationPath,$file->getClientOriginalName());
              /* Push the uploaded file to the server */
              $file->move($destinationPath,$uploaded_filename);              
              /* Parse the xml file based on the provided requirements */
              $fields_elements = $this->parse_xml("../resources/uploads/".$uploaded_filename);
              /* Check if the extracted data match the request */
              if(isset($fields_elements["title"]) && isset($fields_elements["description"]) 
                      && isset($fields_elements["launch_url"]) && isset($fields_elements["icon_url"])){
                  /* Check if a similar record exists already in the storage (just to prevent redundancy) */
                  $retrieve_data = DB::table('output')->where('title', $fields_elements["title"])
                          ->where('description', $fields_elements["description"])->where('launch_url', $fields_elements["launch_url"])
                          ->where('icon_url', $fields_elements["icon_url"])->first();
                  if(count($retrieve_data)){
                       /* Same record exists already in the storage */
                        $this->display_flash("error","Error occurred! The same parsed elements exist already in the storage.");
                  }else{
                      /* Every thing is right, store the extracted fields */
                      DB::table('output')->insert($fields_elements);
                      $this->display_flash("success","The file has been uploaded successfully. Elements have been added to the storage.");
                  }
              }else{   
                  /* Error found (provided file does not match the suitable nodes structure) */
                  $this->display_flash("error","Error occurred! Invalid xml file provided. The uploaded file does not match the requirements.");
              }              
          }
      }
      return redirect('/');
    }
    /*
     * Check for the uploaded file name 
     * If already exist on the (uploads) repository 
     * Then give it a new name based on timestamp
     * Or keep it with its name
     */
    public function proper_filename($source,$filename){
        if(file_exists($source . "/" . $filename)) {
            /* give a new name for the uplaoded file */
            $uploaded_filename = preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename) . "-" . time(). ".xml";
        }else{
            /* keep the file with the current name */
            $uploaded_filename = $filename;
        }  
        return $uploaded_filename;
    }
    /*
     * Parse XML Content URL, File or Object
     * Based on the requirements set
     * Return an array of the extracted files (required files to be stored)
     * If the provided file does not exist, the method returns an empty array
     */
    public function parse_xml($path_url){
        if(file_exists($path_url)){
              /* File exist */
              $xml_data = file_get_contents($path_url);
              /* Using SimpleXMLElement for xml document with namespace */
              $cartridge_basiclti_link = new \SimpleXMLElement(($xml_data));
              $btlis = $cartridge_basiclti_link->children('blti', true);
              $blti_title = trim((string) $btlis->title);
              $blti_description = trim((string) $btlis->description);
              $blti_launch_url = trim((string) $btlis->launch_url);
              $lticms = $btlis->extensions->children('lticm',true);
              $icon_url = null;
              /* Get the child node value for the proper attribute */
              foreach($lticms as $property) {
                  $attribute_name = $property->attributes()["name"];
                  if((string)$attribute_name==="icon_url"){
                      $icon_url = trim((string) $property);
                  }
              }
              return ['title' => ($blti_title), 'description' => ($blti_description), 
                  'launch_url' => ($blti_launch_url), 'icon_url' => ($icon_url)];
        }
        return [];
    }
    /*
     * Display a flash message (error or success)
     * With providing a flash session to the view
     * An an indication of error found or success
     * The method has a default parameter value and can be customized
     */
    public function display_flash($type="",$message=""){
        if($type==="error"){
          if(empty($message)){ 
              /* Default error message */ 
              $message = "Error!";               
          }
          session()->flash('error', true);    
          session()->flash('notification_msg', $message);            
        }else{
          if(empty($message)){ 
              /* Default success message */ 
              $message = "Success";               
          }            
           session()->flash('notification_msg', $message);  
        }
    }
}