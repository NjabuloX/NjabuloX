<?php

namespace App\Http\Controllers;

use App\EndPoint;
use Illuminate\Http\Request;
use App\User;
use App\Site;
use App\role;
use App\Company;
use mysqli;
use App\classes\xmlapi;
class addSiteController extends Controller
{


    public  function index()
    {

        $companies=Company::all();
        $selectedCompany=User::first()->company_id;


        return view('addsite.addsite',compact('companies','selectedCompany'));

    }


    public  function  siteconfiguration  (Request $request) {
        // Variable  Declation
        $shedule  = "shedule" ;
        $data   = "data" ;
        $record = "record";

        $SiteName   = $request->input('SITE_NAME');
        $SiteName_conif = "_".$request->input('SITE_NAME');
        $SiteController_conifg = $request->input('SITE_NAME')."Controller";
        $SiteUrl = $request->input('SITE_NAME');
        $site_api_ulr = strtolower($SiteUrl) ;
        $dbName = ''.$SiteName.'_db';
        // Function  to  configure  the  Datatabase  connection


     
        $db_config    =  "/home/shakasabre/biometrico.shaka.cloud/config/database.php" ;
        $connection   = "'mysql$SiteName' => [
             'driver' => 'mysql',
             'host' => env('DB_HOST', '127.0.0.1'),
             'port' => env('DB_PORT', '3306'),
             'database' => env('DB_DATABASE$SiteName_conif', 'shakasab_$SiteName'),
             'username' => env('DB_USERNAME$SiteName_conif', 'shakasab_$SiteName'),
             'password' => env('DB_PASSWORD$SiteName_conif', 'biometri12B'),
             'unix_socket' => env('DB_SOCKET', ''),
             'charset' => 'utf8mb4',
             'collation' => 'utf8mb4_unicode_ci',
             'prefix' => '',
             'strict' => true,
             'engine' => null,
         ],";

        $replacement = $connection;
        $specific_line = 49; // sample value squeeze it on this line
        $contents = file($db_config, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if($specific_line > sizeof($contents)) {
            $specific_line = sizeof($contents) + 1;
        }
        array_splice($contents, $specific_line-1, 0, array($replacement)); // arrays start at zero index
        $contents = implode("\n", $contents);
        file_put_contents($db_config, $contents);

        // End  Function


        // Function   to   write  on  the Eviromental  File
        $fille  = "/home/shakasabre/biometrico.shaka.cloud/.env" ;
        $space  =   "\r\n";
        $DB_CONNECTION        ="DB_CONNECTION=" ."mysql"."\n";

       $DB_HOST              ="DB_HOST =" . $request->input('DB_HOST')."\n";
         $DB_DATABAS           = "DB_DATABAS$SiteName_conif =" ."shakasab_".$SiteName."\n";
         $DB_USERNAME          = "DB_USERNAME$SiteName_conif =" ."shakasab_".$SiteName."\n";
         $DB_PASSWORD          = "DB_PASSWORD$SiteName_conif=" .'biometri12B'."\n";

        file_put_contents($fille ,$space , FILE_APPEND);
        file_put_contents($fille , $DB_CONNECTION , FILE_APPEND);
        file_put_contents($fille , $DB_HOST  , FILE_APPEND);
        file_put_contents($fille , $DB_DATABAS  , FILE_APPEND);
        file_put_contents($fille , $DB_USERNAME  , FILE_APPEND);
        file_put_contents($fille , $DB_PASSWORD  , FILE_APPEND);

        //End  Function

        // Function  to  create  a  Controller
        $fn = "/home/shakasabre/biometrico.shaka.cloud/app/Http/Controllers/AttendecyController.php";

        $Create_controller = fopen("/home/shakasabre/biometrico.shaka.cloud/app/Http/Controllers/".$SiteController_conifg.".php", "w") ;

        $methode  = "<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
class $SiteController_conifg extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function attendencystore()
    {
       $$data = Input::all();
       
       $$record = \DB::connection('mysql$SiteName')
           ->table('attendance')
           ->where('ATTENDANCE_KEY', Input::get('ATTENDANCE_KEY'))
           ->first();

       if($$record == NULL)
       {
           \DB::connection('mysql$SiteName')
               ->table('attendance')
               ->insert($$data);

           return 'ok';
       }
       else if($$record != NULL)
       {
           \DB::connection('mysql$SiteName')
               ->table('attendance')
               ->where('ATTENDANCE_KEY', Input::get('ATTENDANCE_KEY'))
               ->delete();

           \DB::connection('mysql$SiteName')
               ->table('attendance')
               ->insert($$data);

           return 'ok';
       }
       
    }
    
    
    public function workshedulstore()
    {
     $$data = Input::all();
     
     $$record = \DB::connection('mysql$SiteName')
           ->table('work_schedule')
           ->where('WORK_SCHEDULE_KEY', Input::get('WORK_SCHEDULE_KEY'))
           ->first();

       if($$record == NULL)
       {
           \DB::connection('mysql$SiteName')
               ->table('work_schedule')
               ->insert($$data);

           return 'ok';
       }
       else if($$record != NULL)
       {
           \DB::connection('mysql$SiteName')
               ->table('work_schedule')
               ->where('WORK_SCHEDULE_KEY', Input::get('WORK_SCHEDULE_KEY'))
               ->delete();

           \DB::connection('mysql$SiteName')
               ->table('work_schedule')
               ->insert($$data);

           return 'ok';
       }
    
    }
    
    public function clockingliststore()
    {
      $$data = Input::all();
  
           \DB::connection('mysql$SiteName')
               ->table('clocking_temp_print')
               ->insert($$data);

           return 'ok';
       
    }
    
    
    
     public  function  index ()
    {

        $$shedule= \DB::connection('mysql$SiteName')->table('attendance')
            ->select(
                \DB::raw(
                    \"
                                attendance.ATTENDANCE_KEY     ,             
                                attendance.COMPANY_KEY      ,                       
                                attendance.TERMINAL_KEY      ,
                                attendance.TERMINAL_REC_NO  ,
                                attendance.DEPARTMENT_KEY    ,
                                attendance.ATTENDANCE_DATE  ,
                                attendance.ATTENDANCE_TIME  ,
                                attendance.EVENT_KEY      ,
                                
                                attendance.DEVICE_CONFIGURATION_KEY    ,
                                attendance.CALCULATED  ,
                                attendance.VERIFIED       ,
                               
                                attendance.OPERATOR_KEY        ,
                                attendance.ENROLL_ID        
                            
                                      
                                \"
                )
            )
            ->get();

        return json_encode($$shedule) ;
    }
}
" ;

        fwrite($Create_controller, $methode);
        fclose($Create_controller);
//C:\xampp\htdocs\Biometrico\routes\web.php
        // add   Resource  Route
       $route  = "/home/shakasabre/biometrico.shaka.cloud/routes/web.php" ;



        $routes  ="Route::group(array('prefix' => 'api/v1'), function() {"."\n"."\n".

            "Route::post('attendency$site_api_ulr', '$SiteController_conifg@attendencystore')->name('attendency$site_api_ulr');"."\n".
            "Route::post('clockinglist$site_api_ulr', '$SiteController_conifg@clockingliststore')->name('clockinglist$site_api_ulr');"."\n".
            "Route::post('workshedul$site_api_ulr', '$SiteController_conifg@workshedulstore')->name('workshedul$site_api_ulr');
   

         });
";

        file_put_contents($route ,$space , FILE_APPEND);
        file_put_contents($route ,$routes , FILE_APPEND);


        // Function  to  save  to  Database


        $newSite = New Site();
        $newSite->company_id = $request['company_id'];
        $newSite->site_name = $SiteName;

        $dbName = ''.$SiteName.'_db';

        $newSite->db_name = strtolower($dbName);
        $newSite->site_code = '001'.$SiteName;
        $newSite->	connection_name = "mysql".$SiteName;
//        $newSite->end_point =   $_ENV['APP_URL']."api/v1".$site_api_ulr ;

                $db_host = '154.0.171.123'; 
                $cpaneluser = 'shakasabre';
                $cpanelpass = 'Sh@k@S@bre2018'; 
                
                $databasename = 'shakasab_'.$SiteName;
                $databaseuser = 'shakasab_'.$SiteName; // Warning: in most of cases this can't be longer than 8 characters
                $databasepass = 'biometri12B'; // Warning: be sure the password is strong enough, else the CPanel will reject it
                
                $xmlapi = new xmlapi($db_host); 
                
                
                $xmlapi->password_auth("".$cpaneluser."","".$cpanelpass."");    
                $xmlapi->set_port(2083);
                $xmlapi->set_debug(1);//output actions in the error log 1 for true and 0 false  
                $xmlapi->set_output('array');//set this for browser output  
                //create database    
                $createdb = $xmlapi->api1_query($cpaneluser, "Mysql", "adddb", array($databasename));   
                //create user 
                $usr = $xmlapi->api1_query($cpaneluser, "Mysql", "adduser", array($databaseuser, $databasepass));   
                
                
                 //add user 
                $addusr = $xmlapi->api1_query($cpaneluser, "Mysql", "adduserdb", array("".$cpaneluser."_".$databasename."", "".$cpaneluser."_".$databaseuser."", 'all')); 


       $newSite->save();

        $newEndpoint = new EndPoint();
        $newEndpoint->site_id = $newSite->id;
        $newEndpoint->name = "attendency$site_api_ulr";
        $newEndpoint->end_point = $_ENV['APP_URL']."api/v1/attendency".$site_api_ulr;
        $newEndpoint->save();

        $newEndpoint = new EndPoint();
        $newEndpoint->site_id = $newSite->id;
        $newEndpoint->name = "clockinglist$site_api_ulr";
        $newEndpoint->end_point = $_ENV['APP_URL']."api/v1/clockinglist".$site_api_ulr;
        $newEndpoint->save();

        $newEndpoint = new EndPoint();
        $newEndpoint->site_id = $newSite->id;
        $newEndpoint->name = "workshedul$site_api_ulr";
        $newEndpoint->end_point = $_ENV['APP_URL']."api/v1/workshedul".$site_api_ulr;
        $newEndpoint->save();
       // \DB::statement(\DB::raw('CREATE DATABASE '.$dbName.''));

      $conn =new mysqli('localhost', $cpaneluser,$cpanelpass , ''.$databasename.'');

        $query = '';
       $sqlScript = file("/home/shakasabre/biometrico.shaka.cloud/public/Biometricodb.sql");
        foreach ($sqlScript as $line)	{

            $startWith = substr(trim($line), 0 ,2);
            $endWith = substr(trim($line), -1 ,1);

            if (empty($line) || $startWith == '--' || $startWith == '/*' || $startWith == '//') {
                continue;
            }

            $query = $query . $line;
            if ($endWith == ';') {
                mysqli_query($conn,$query) or die('<div class="error-response sql-import-response">Problem in executing the SQL query <b>' . $query. '</b></div>');
                $query= '';
            }
        }

     

        return redirect('/sites');



    }

}