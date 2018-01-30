<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
class SiteQController extends Controller
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
       $data = Input::all();

       $record = \DB::connection('mysqlSiteQ')
           ->table('attendance')
           ->where('ATTENDANCE_KEY', Input::get('ATTENDANCE_KEY'))
           ->first();

       if($record == NULL)
       {
           \DB::connection('mysqlSiteQ')
               ->table('attendance')
               ->insert($data);

           return 'ok';
       }
       else if($record != NULL)
       {
           \DB::connection('mysqlSiteQ')
               ->table('attendance')
               ->where('ATTENDANCE_KEY', Input::get('ATTENDANCE_KEY'))
               ->delete();

           \DB::connection('mysqlSiteQ')
               ->table('attendance')
               ->insert($data);

           return 'ok';
       }
       
    }
    
    
    public function workshedulstore()
    {
     $data = Input::all();
      \DB::connection('mysqlSiteQ')->table('work_schedule')->insert($data);
         
         return  'ok' ;
    }
    
    public function clockingliststore()
    {
      $data = Input::all();
  
  
      \DB::connection('mysqlSiteQ')->table('clocking_temp_print')->insert($data);
         
         return  'ok' ;
    }
    
    
    
     public  function  index ()
    {

        $shedule= \DB::connection('mysqlSiteQ')->table('attendance')
            ->select(
                \DB::raw(
                    "
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
                            
                                      
                                "
                )
            )
            ->get();

        return json_encode($shedule) ;
    }
}
