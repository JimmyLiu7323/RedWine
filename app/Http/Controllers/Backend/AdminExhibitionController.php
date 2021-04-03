<?php
namespace App\Http\Controllers\Backend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\ExhibitionData;
use App\ExhibitionAttendeeData;

use Validator;
use Widget_Helper;
class AdminExhibitionController extends Controller
{
    public function __construct(){

    }

    public function image(Request $request){
        $data['events_image'] = '';
        $data['visits_image'] = '';

        $footer_guarantee_combos=DB::table('template_static_data')->whereIn('DataKey',array(
            'events_image',
            'visits_image'
        ))->get();
        foreach($footer_guarantee_combos as $combo){
            $data[$combo->DataKey]=$combo->Data;
        }

        if($request->isMethod('post')){
            $validator_rule_array=array(
                'events_image'=>'required|max:100',
                'visits_image'=>'required|max:100'
            );
            $validator_message=array(
                'events_image.max'=>'The image of event should not over 100 characters',                
                'events_image.required'=>'The image of event should not be empty',
                'visits_image.max'=>'The image of visits should not over 100 characters',                
                'visits_image.required'=>'The image of visits should not be empty',
            );
            $build_validator=Validator::make($request->all(),$validator_rule_array,$validator_message);
            if($build_validator->fails()){
                return redirect()->back()->withInput()->withErrors($build_validator->errors());
            }

            $events_image=trim(request('events_image'));
            $visits_image=trim(request('visits_image'));
            $image_update = array('events_image','visits_image');
            foreach($image_update as $updateKey){
                DB::table('template_static_data')->where('DataKey',$updateKey)->update(array(
                    'Data'=>${$updateKey},
                    'updated_at'=>date('Y-m-d H:i:s')
                ));
            }
            return redirect('/admin/exhibition/image');
        }
        $data['currentPage']='exhibition';
        $data['header_title']='Impact Image';
        return view('admin.exhibition.image',$data);
    }

    public function events(){
        $events = ExhibitionData::where('EventType',1)->paginate(10);
        $data['events'] = $events;
        $data['currentPage']='exhibition';
        $data['header_title']='Events';
        return view('admin.exhibition.events',$data);        
    }

    public function add_event(Request $request){
        // default data
        $data['EventId'] = '';
        $data['EventType'] = 1;
        $data['EventName'] = '';
        $data['EventNameEn'] = '';
        $data['BriefDesc'] = '';
        $data['BriefDescEn'] = '';
        $data['Description'] = '';
        $data['DescriptionEn'] = '';
        $data['EventDateStart'] = date('Y-m-d');
        $data['EventTimeStart'] = "10:00";
        $data['EventDateEnd'] = date('Y-m-d');
        $data['EventTimeEnd'] = "12:00";
        $data['AllCapacity'] = 0;
        $data['StandingCapacity'] = 0;
        $data['SeatingCapacity'] = 0;
        $data['Status'] = 0;
        $data['Free'] = 0;
        $data['Price'] = 0;
        $data['TicketImage'] = '';
        $data['ImpactImage'] = '';
        $data['Status'] = 0;
        $data['EventOnDate'] = date('Y-m-d');
        $data['EventOnTime'] = "00:00";
        $data['EventOffDate'] = date('Y-m-d');
        $data['EventOffTime'] = "00:00";


        if($request->isMethod('post')){
            $validator_rule_array=array(
                'ImpactImage'=>'required|max:100',
                'EventName'=>'required|max:100',
                'EventNameEn'=>'nullable|max:100',
                'BriefDesc'=>'required|max:500',
                'BriefDescEn'=>'nullable|max:500',
                'Description'=>'required|max:4294967295',
                'DescriptionEn'=>'nullable|max:4294967295',
                'EventDateStart'=>'required|date',
                'EventTimeStart'=>'required|date_format:H:i',
                'EventDateEnd'=>'required|date',
                'EventTimeEnd'=>'required|date_format:H:i',
                'AllCapacity'=>'required|integer',
                'StandingCapacity'=>'nullable|integer',
                'SeatingCapacity'=>'nullable|integer',
                'Status'=>'required|integer|in:0,1',
                'EventOnDate'=>'required|date',
                'EventOnTime'=>'required|date_format:H:i',
                'EventOffDate'=>'required|date',
                'EventOffTime'=>'required|date_format:H:i',
                'Free'=>'required|integer|in:0,1',
                'TicketImage'=>'required|max:100',
                'Price'=>'required|numeric|between:0,99999.99'
            );

            $niceNames = array(
                'ImpactImage'=>'Impact Image',
                'EventName'=>'Name',
                'EventNameEn'=>'Name(En)',
                'BriefDesc'=>'Brief Description',
                'BriefDescEn'=>'Brief Description(En)',
                'DescriptionEn'=>'Description(En)',
                'EventDateStart'=>'Date of event(Start)',
                'EventTimeStart'=>'Time of event(Start)',
                'EventDateEnd'=>'Date of event(End)',
                'EventTimeEnd'=>'Time of event(End)',                
                'AllCapacity'=>'Capacity',
                'StandingCapacity'=>'Standing Capacity',
                'SeatingCapacity'=>'Seating Capacity',
                'EventOnDate'=>'Enable from(Start date)',
                'EventOnTime'=>'Enable from(Start time)',
                'EventOffDate'=>'Enable from(End date)',
                'EventOffTime'=>'Enable from(End time)',
                'Free'=>'Free event',
                'TicketImage'=>'Ticket Image'
            );

            $build_validator=Validator::make($request->all(),$validator_rule_array);
            $build_validator->setAttributeNames($niceNames);
            if($build_validator->fails()){
                return redirect()->back()->withInput()->withErrors($build_validator->errors());
            }

            $EventId = Widget_Helper::createID();
            $ImpactImage = trim(request('ImpactImage'));
            $EventName = trim(request('EventName'));
            $EventNameEn = trim(request('EventNameEn'));
            $BriefDesc = trim(request('BriefDesc'));
            $BriefDescEn = trim(request('BriefDescEn'));
            $Description = trim(request('Description'));
            $DescriptionEn = trim(request('DescriptionEn'));
            $EventDateStart = trim(request('EventDateStart'));
            $EventTimeStart = trim(request('EventTimeStart'));
            $EventDateEnd = trim(request('EventDateEnd'));
            $EventTimeEnd = trim(request('EventTimeEnd'));
            $AllCapacity = intval(request('AllCapacity'));
            $StandingCapacity = intval(request('StandingCapacity'));
            $SeatingCapacity = intval(request('SeatingCapacity'));
            $Status = intval(request('Status'));

            $EventOnDate = trim(request('EventOnDate'));
            $EventOnTime = trim(request('EventOnTime'));
            $EventOffDate = trim(request('EventOffDate'));
            $EventOffTime = trim(request('EventOffTime'));
            $Free = intval(request('Free'));
            $Price = floatval(request('Price'));
            $TicketImage = trim(request('TicketImage'));

            $newEvent = array(
                'EventId'=>$EventId,
                'EventType'=>$data['EventType'],
                'ImpactImage'=>$ImpactImage,
                'TicketImage'=>$TicketImage,
                'EventName'=>$EventName,
                'EventNameEn'=>$EventNameEn,
                'BriefDesc'=>$BriefDesc,
                'BriefDescEn'=>$BriefDescEn,
                'Description'=>$Description,
                'DescriptionEn'=>$DescriptionEn,
                'EventDateTime_Start'=>$EventDateStart." ".$EventTimeStart,
                'EventDateTime_End'=>$EventDateEnd." ".$EventTimeEnd,
                'AllCapacity'=>$AllCapacity,
                'StandingCapacity'=>$StandingCapacity,
                'SeatingCapacity'=>$SeatingCapacity,
                'Status'=>$Status,
                'EventOnDateTime_Start'=>$EventOnDate." ".$EventOnTime,
                'EventOnDateTime_End'=>$EventOffDate." ".$EventOffTime,
                'Free'=>$Free,
                'Price'=>$Price,
                'created_at'=>date('Y-m-d H:i:s'),
                'updated_at'=>date('Y-m-d H:i:s')
            );

            if(ExhibitionData::newEvent($newEvent)){
                return redirect('/admin/exhibition/events');
            }
            else{
                return redirect()->back()->withInput()->withErrors($build_validator->errors());
            }
        }

        $data['currentPage'] = 'exhibition';
        $data['header_title'] = 'Add event';
        return view('admin.exhibition.add_mod_event',$data);
    }

    public function edit_event(Request $request){
        $data['EventId'] = '';
        $data['EventType'] = 1;
        $data['EventName'] = '';
        $data['EventNameEn'] = '';
        $data['BriefDesc'] = '';
        $data['BriefDescEn'] = '';
        $data['Description'] = '';
        $data['DescriptionEn'] = '';
        $data['EventDateStart'] = date('Y-m-d');
        $data['EventTimeStart'] = "10:00";
        $data['EventDateEnd'] = date('Y-m-d');
        $data['EventTimeEnd'] = "12:00";
        $data['AllCapacity'] = 0;
        $data['StandingCapacity'] = 0;
        $data['SeatingCapacity'] = 0;
        $data['Status'] = 0;
        $data['Free'] = 0;
        $data['Price'] = 0;
        $data['TicketImage'] = '';
        $data['ImpactImage'] = '';
        $data['Status'] = 0;
        $data['EventOnDate'] = date('Y-m-d');
        $data['EventOnTime'] = "00:00";
        $data['EventOffDate'] = date('Y-m-d');
        $data['EventOffTime'] = "00:00";

        $id = trim(request('id'));
        $eventInfo = ExhibitionData::where('EventType',1)->where('EventId',$id)->take(1)->first();
        if($eventInfo){
            if($request->isMethod('post')){
                $validator_rule_array=array(
                    'ImpactImage'=>'required|max:100',
                    'EventName'=>'required|max:100',
                    'EventNameEn'=>'nullable|max:100',
                    'BriefDesc'=>'required|max:500',
                    'BriefDescEn'=>'nullable|max:500',
                    'Description'=>'required|max:4294967295',
                    'DescriptionEn'=>'nullable|max:4294967295',
                    'EventDateStart'=>'required|date',
                    'EventTimeStart'=>'required|date_format:H:i',
                    'EventDateEnd'=>'required|date',
                    'EventTimeEnd'=>'required|date_format:H:i',
                    'AllCapacity'=>'required|integer',
                    'StandingCapacity'=>'nullable|integer',
                    'SeatingCapacity'=>'nullable|integer',
                    'Status'=>'required|integer|in:0,1',
                    'EventOnDate'=>'required|date',
                    'EventOnTime'=>'required|date_format:H:i',
                    'EventOffDate'=>'required|date',
                    'EventOffTime'=>'required|date_format:H:i',
                    'Free'=>'required|integer|in:0,1',
                    'TicketImage'=>'required|max:100',
                    'Price'=>'required|numeric|between:0,99999.99'
                );

                $niceNames = array(
                    'ImpactImage'=>'Impact Image',
                    'EventName'=>'Name',
                    'EventNameEn'=>'Name(En)',
                    'BriefDesc'=>'Brief Description',
                    'BriefDescEn'=>'Brief Description(En)',
                    'DescriptionEn'=>'Description(En)',
                    'EventDateStart'=>'Date of event(Start)',
                    'EventTimeStart'=>'Time of event(Start)',
                    'EventDateEnd'=>'Date of event(End)',
                    'EventTimeEnd'=>'Time of event(End)',                
                    'AllCapacity'=>'Capacity',
                    'StandingCapacity'=>'Standing Capacity',
                    'SeatingCapacity'=>'Seating Capacity',
                    'EventOnDate'=>'Enable from(Start date)',
                    'EventOnTime'=>'Enable from(Start time)',
                    'EventOffDate'=>'Enable from(End date)',
                    'EventOffTime'=>'Enable from(End time)',
                    'Free'=>'Free event',
                    'TicketImage'=>'Ticket Image'
                );

                $build_validator=Validator::make($request->all(),$validator_rule_array);
                $build_validator->setAttributeNames($niceNames);
                if($build_validator->fails()){
                    return redirect()->back()->withInput()->withErrors($build_validator->errors());
                }

                $ImpactImage = trim(request('ImpactImage'));
                $EventName = trim(request('EventName'));
                $EventNameEn = trim(request('EventNameEn'));
                $BriefDesc = trim(request('BriefDesc'));
                $BriefDescEn = trim(request('BriefDescEn'));
                $Description = trim(request('Description'));
                $DescriptionEn = trim(request('DescriptionEn'));
                $EventDateStart = trim(request('EventDateStart'));
                $EventTimeStart = trim(request('EventTimeStart'));
                $EventDateEnd = trim(request('EventDateEnd'));
                $EventTimeEnd = trim(request('EventTimeEnd'));
                $AllCapacity = intval(request('AllCapacity'));
                $StandingCapacity = intval(request('StandingCapacity'));
                $SeatingCapacity = intval(request('SeatingCapacity'));
                $Status = intval(request('Status'));

                $EventOnDate = trim(request('EventOnDate'));
                $EventOnTime = trim(request('EventOnTime'));
                $EventOffDate = trim(request('EventOffDate'));
                $EventOffTime = trim(request('EventOffTime'));
                $Free = intval(request('Free'));
                $Price = floatval(request('Price'));
                $TicketImage = trim(request('TicketImage'));

                $eventData = array(
                    'ImpactImage'=>$ImpactImage,
                    'TicketImage'=>$TicketImage,
                    'EventName'=>$EventName,
                    'EventNameEn'=>$EventNameEn,
                    'BriefDesc'=>$BriefDesc,
                    'BriefDescEn'=>$BriefDescEn,
                    'Description'=>$Description,
                    'DescriptionEn'=>$DescriptionEn,
                    'EventDateTime_Start'=>$EventDateStart." ".$EventTimeStart,
                    'EventDateTime_End'=>$EventDateEnd." ".$EventTimeEnd,
                    'AllCapacity'=>$AllCapacity,
                    'StandingCapacity'=>$StandingCapacity,
                    'SeatingCapacity'=>$SeatingCapacity,
                    'Status'=>$Status,
                    'EventOnDateTime_Start'=>$EventOnDate." ".$EventOnTime,
                    'EventOnDateTime_End'=>$EventOffDate." ".$EventOffTime,
                    'Free'=>$Free,
                    'Price'=>$Price,
                    'updated_at'=>date('Y-m-d H:i:s')
                );

                if(ExhibitionData::where('EventId',$id)->where('EventType',1)->update($eventData)){
                    return redirect('/admin/exhibition/events');
                }
                else{
                    return redirect()->back()->withInput()->withErrors($build_validator->errors());
                }
            }

            $eventInfo = $eventInfo->toArray();
            foreach($eventInfo as $key=>$val){
                $data[$key] = $val;
            }

            $data['EventDateStart'] = date('Y-m-d',strtotime($data['EventDateTime_Start']));
            $data['EventTimeStart'] = date('H:i',strtotime($data['EventDateTime_Start']));
            $data['EventDateEnd'] = date('Y-m-d',strtotime($data['EventDateTime_End']));
            $data['EventTimeEnd'] = date('H:i',strtotime($data['EventDateTime_End']));
            $data['EventOnDate'] = date('Y-m-d',strtotime($data['EventOnDateTime_Start']));
            $data['EventOnTime'] = date('H:i',strtotime($data['EventOnDateTime_Start']));
            $data['EventOffDate'] = date('Y-m-d',strtotime($data['EventOnDateTime_End']));
            $data['EventOffTime'] = date('H:i',strtotime($data['EventOnDateTime_End']));

            $data['currentPage'] = 'exhibition';
            $data['header_title'] = 'Edit event';
            return view('admin.exhibition.add_mod_event',$data);
        }
        return redirect('/admin/exhibition/events');
    }

    public function delete_event(Request $request){
        $id = trim(request('id'));
        ExhibitionData::where('EventId',$id)->delete();
        ExhibitionAttendeeData::where('EventId',$id)->delete();
        return redirect('/admin/exhibition/events');
    }

    public function visits(){
        $jsonEvent = array();
        $events = ExhibitionData::where('EventType',2)->get();
        foreach($events as $eventIdx=>$event){
            array_push($jsonEvent,array(
                'id'=>$event->EventId,
                'resourceId'=>$event->EventId,
                'title'=>$event->EventName,
                'start'=>$event->EventDateTime_Start,
                'end'=>$event->EventOnDateTime_End
            ));
        }

        $data['events'] = json_encode($jsonEvent);
        $data['currentPage']='exhibition';
        $data['header_title']='Visits';
        return view('admin.exhibition.visits',$data);        
    }

    public function add_visit(Request $request){
        $dateStart = trim(request('date'));
        $dateEnd = date('Y-m-d');
        if($dateStart===''){
            $dateStart = date('Y-m-d');            
        }
        else{
            $dateEnd = request('date');
        }

        // default data
        $data['EventId'] = '';
        $data['EventType'] = 2;
        $data['EventName'] = '';
        $data['EventNameEn'] = '';
        $data['BriefDesc'] = '';
        $data['BriefDescEn'] = '';
        $data['Description'] = '';
        $data['DescriptionEn'] = '';
        $data['EventDateStart'] = $dateStart;
        $data['EventTimeStart'] = "10:00";
        $data['EventDateEnd'] = $dateEnd;
        $data['EventTimeEnd'] = "12:00";
        $data['AllCapacity'] = 0;
        $data['StandingCapacity'] = 0;
        $data['SeatingCapacity'] = 0;
        $data['Status'] = 0;
        $data['Free'] = 0;
        $data['Price'] = 0;
        $data['TicketImage'] = '';
        $data['ImpactImage'] = '';
        $data['Status'] = 0;
        $data['EventOnDate'] = date('Y-m-d');
        $data['EventOnTime'] = "00:00";
        $data['EventOffDate'] = date('Y-m-d');
        $data['EventOffTime'] = "00:00";


        if($request->isMethod('post')){
            $validator_rule_array=array(
                'ImpactImage'=>'required|max:100',
                'EventName'=>'required|max:100',
                'EventNameEn'=>'nullable|max:100',
                'BriefDesc'=>'required|max:500',
                'BriefDescEn'=>'nullable|max:500',
                'Description'=>'required|max:4294967295',
                'DescriptionEn'=>'nullable|max:4294967295',
                'EventDateStart'=>'required|date',
                'EventTimeStart'=>'required|date_format:H:i',
                'EventDateEnd'=>'required|date',
                'EventTimeEnd'=>'required|date_format:H:i',
                'AllCapacity'=>'required|integer',
                'StandingCapacity'=>'nullable|integer',
                'SeatingCapacity'=>'nullable|integer',
                'Status'=>'required|integer|in:0,1',
                'EventOnDate'=>'required|date',
                'EventOnTime'=>'required|date_format:H:i',
                'EventOffDate'=>'required|date',
                'EventOffTime'=>'required|date_format:H:i',
                'Free'=>'required|integer|in:0,1',
                'TicketImage'=>'required|max:100',
                'Price'=>'required|numeric|between:0,99999.99'
            );

            $niceNames = array(
                'ImpactImage'=>'Impact Image',
                'EventName'=>'Name',
                'EventNameEn'=>'Name(En)',
                'BriefDesc'=>'Brief Description',
                'BriefDescEn'=>'Brief Description(En)',
                'DescriptionEn'=>'Description(En)',
                'EventDateStart'=>'Date of event(Start)',
                'EventTimeStart'=>'Time of event(Start)',
                'EventDateEnd'=>'Date of event(End)',
                'EventTimeEnd'=>'Time of event(End)',                
                'AllCapacity'=>'Capacity',
                'StandingCapacity'=>'Standing Capacity',
                'SeatingCapacity'=>'Seating Capacity',
                'EventOnDate'=>'Enable from(Start date)',
                'EventOnTime'=>'Enable from(Start time)',
                'EventOffDate'=>'Enable from(End date)',
                'EventOffTime'=>'Enable from(End time)',
                'Free'=>'Free event',
                'TicketImage'=>'Ticket Image'
            );

            $build_validator=Validator::make($request->all(),$validator_rule_array);
            $build_validator->setAttributeNames($niceNames);
            if($build_validator->fails()){
                return redirect()->back()->withInput()->withErrors($build_validator->errors());
            }

            $EventId = Widget_Helper::createID();
            $ImpactImage = trim(request('ImpactImage'));
            $EventName = trim(request('EventName'));
            $EventNameEn = trim(request('EventNameEn'));
            $BriefDesc = trim(request('BriefDesc'));
            $BriefDescEn = trim(request('BriefDescEn'));
            $Description = trim(request('Description'));
            $DescriptionEn = trim(request('DescriptionEn'));
            $EventDateStart = trim(request('EventDateStart'));
            $EventTimeStart = trim(request('EventTimeStart'));
            $EventDateEnd = trim(request('EventDateEnd'));
            $EventTimeEnd = trim(request('EventTimeEnd'));
            $AllCapacity = intval(request('AllCapacity'));
            $StandingCapacity = intval(request('StandingCapacity'));
            $SeatingCapacity = intval(request('SeatingCapacity'));
            $Status = intval(request('Status'));

            $EventOnDate = trim(request('EventOnDate'));
            $EventOnTime = trim(request('EventOnTime'));
            $EventOffDate = trim(request('EventOffDate'));
            $EventOffTime = trim(request('EventOffTime'));
            $Free = intval(request('Free'));
            $Price = floatval(request('Price'));
            $TicketImage = trim(request('TicketImage'));

            $newEvent = array(
                'EventId'=>$EventId,
                'EventType'=>$data['EventType'],
                'ImpactImage'=>$ImpactImage,
                'TicketImage'=>$TicketImage,
                'EventName'=>$EventName,
                'EventNameEn'=>$EventNameEn,
                'BriefDesc'=>$BriefDesc,
                'BriefDescEn'=>$BriefDescEn,
                'Description'=>$Description,
                'DescriptionEn'=>$DescriptionEn,
                'EventDateTime_Start'=>$EventDateStart." ".$EventTimeStart,
                'EventDateTime_End'=>$EventDateEnd." ".$EventTimeEnd,
                'AllCapacity'=>$AllCapacity,
                'StandingCapacity'=>$StandingCapacity,
                'SeatingCapacity'=>$SeatingCapacity,
                'Status'=>$Status,
                'EventOnDateTime_Start'=>$EventOnDate." ".$EventOnTime,
                'EventOnDateTime_End'=>$EventOffDate." ".$EventOffTime,
                'Free'=>$Free,
                'Price'=>$Price,
                'created_at'=>date('Y-m-d H:i:s'),
                'updated_at'=>date('Y-m-d H:i:s')
            );

            if(ExhibitionData::newEvent($newEvent)){
                return redirect('/admin/exhibition/visits');
            }
            else{
                return redirect()->back()->withInput()->withErrors($build_validator->errors());
            }
        }

        $data['currentPage'] = 'exhibition';
        $data['header_title'] = 'Add visit';
        return view('admin.exhibition.add_mod_event',$data);
    }

    public function edit_visit(Request $request){
        $data['EventId'] = '';
        $data['EventType'] = 2;
        $data['EventName'] = '';
        $data['EventNameEn'] = '';
        $data['BriefDesc'] = '';
        $data['BriefDescEn'] = '';
        $data['Description'] = '';
        $data['DescriptionEn'] = '';
        $data['EventDateStart'] = date('Y-m-d');
        $data['EventTimeStart'] = "10:00";
        $data['EventDateEnd'] = date('Y-m-d');
        $data['EventTimeEnd'] = "12:00";
        $data['AllCapacity'] = 0;
        $data['StandingCapacity'] = 0;
        $data['SeatingCapacity'] = 0;
        $data['Status'] = 0;
        $data['Free'] = 0;
        $data['Price'] = 0;
        $data['TicketImage'] = '';
        $data['ImpactImage'] = '';
        $data['Status'] = 0;
        $data['EventOnDate'] = date('Y-m-d');
        $data['EventOnTime'] = "00:00";
        $data['EventOffDate'] = date('Y-m-d');
        $data['EventOffTime'] = "00:00";

        $id = trim(request('id'));
        $eventInfo = ExhibitionData::where('EventType',2)->where('EventId',$id)->take(1)->first();
        if($eventInfo){
            if($request->isMethod('post')){
                $validator_rule_array=array(
                    'ImpactImage'=>'required|max:100',
                    'EventName'=>'required|max:100',
                    'EventNameEn'=>'nullable|max:100',
                    'BriefDesc'=>'required|max:500',
                    'BriefDescEn'=>'nullable|max:500',
                    'Description'=>'required|max:4294967295',
                    'DescriptionEn'=>'nullable|max:4294967295',
                    'EventDateStart'=>'required|date',
                    'EventTimeStart'=>'required|date_format:H:i',
                    'EventDateEnd'=>'required|date',
                    'EventTimeEnd'=>'required|date_format:H:i',
                    'AllCapacity'=>'required|integer',
                    'StandingCapacity'=>'nullable|integer',
                    'SeatingCapacity'=>'nullable|integer',
                    'Status'=>'required|integer|in:0,1',
                    'EventOnDate'=>'required|date',
                    'EventOnTime'=>'required|date_format:H:i',
                    'EventOffDate'=>'required|date',
                    'EventOffTime'=>'required|date_format:H:i',
                    'Free'=>'required|integer|in:0,1',
                    'TicketImage'=>'required|max:100',
                    'Price'=>'required|numeric|between:0,99999.99'
                );

                $niceNames = array(
                    'ImpactImage'=>'Impact Image',
                    'EventName'=>'Name',
                    'EventNameEn'=>'Name(En)',
                    'BriefDesc'=>'Brief Description',
                    'BriefDescEn'=>'Brief Description(En)',
                    'DescriptionEn'=>'Description(En)',
                    'EventDateStart'=>'Date of event(Start)',
                    'EventTimeStart'=>'Time of event(Start)',
                    'EventDateEnd'=>'Date of event(End)',
                    'EventTimeEnd'=>'Time of event(End)',                
                    'AllCapacity'=>'Capacity',
                    'StandingCapacity'=>'Standing Capacity',
                    'SeatingCapacity'=>'Seating Capacity',
                    'EventOnDate'=>'Enable from(Start date)',
                    'EventOnTime'=>'Enable from(Start time)',
                    'EventOffDate'=>'Enable from(End date)',
                    'EventOffTime'=>'Enable from(End time)',
                    'Free'=>'Free event',
                    'TicketImage'=>'Ticket Image'
                );

                $build_validator=Validator::make($request->all(),$validator_rule_array);
                $build_validator->setAttributeNames($niceNames);
                if($build_validator->fails()){
                    return redirect()->back()->withInput()->withErrors($build_validator->errors());
                }

                $ImpactImage = trim(request('ImpactImage'));
                $EventName = trim(request('EventName'));
                $EventNameEn = trim(request('EventNameEn'));
                $BriefDesc = trim(request('BriefDesc'));
                $BriefDescEn = trim(request('BriefDescEn'));
                $Description = trim(request('Description'));
                $DescriptionEn = trim(request('DescriptionEn'));
                $EventDateStart = trim(request('EventDateStart'));
                $EventTimeStart = trim(request('EventTimeStart'));
                $EventDateEnd = trim(request('EventDateEnd'));
                $EventTimeEnd = trim(request('EventTimeEnd'));
                $AllCapacity = intval(request('AllCapacity'));
                $StandingCapacity = intval(request('StandingCapacity'));
                $SeatingCapacity = intval(request('SeatingCapacity'));
                $Status = intval(request('Status'));

                $EventOnDate = trim(request('EventOnDate'));
                $EventOnTime = trim(request('EventOnTime'));
                $EventOffDate = trim(request('EventOffDate'));
                $EventOffTime = trim(request('EventOffTime'));
                $Free = intval(request('Free'));
                $Price = floatval(request('Price'));
                $TicketImage = trim(request('TicketImage'));

                $eventData = array(
                    'ImpactImage'=>$ImpactImage,
                    'TicketImage'=>$TicketImage,
                    'EventName'=>$EventName,
                    'EventNameEn'=>$EventNameEn,
                    'BriefDesc'=>$BriefDesc,
                    'BriefDescEn'=>$BriefDescEn,
                    'Description'=>$Description,
                    'DescriptionEn'=>$DescriptionEn,
                    'EventDateTime_Start'=>$EventDateStart." ".$EventTimeStart,
                    'EventDateTime_End'=>$EventDateEnd." ".$EventTimeEnd,
                    'AllCapacity'=>$AllCapacity,
                    'StandingCapacity'=>$StandingCapacity,
                    'SeatingCapacity'=>$SeatingCapacity,
                    'Status'=>$Status,
                    'EventOnDateTime_Start'=>$EventOnDate." ".$EventOnTime,
                    'EventOnDateTime_End'=>$EventOffDate." ".$EventOffTime,
                    'Free'=>$Free,
                    'Price'=>$Price,
                    'updated_at'=>date('Y-m-d H:i:s')
                );

                if(ExhibitionData::where('EventId',$id)->where('EventType',2)->update($eventData)){
                    return redirect('/admin/exhibition/visits');
                }
                else{
                    return redirect()->back()->withInput()->withErrors($build_validator->errors());
                }
            }

            $eventInfo = $eventInfo->toArray();
            foreach($eventInfo as $key=>$val){
                $data[$key] = $val;
            }

            $data['EventDateStart'] = date('Y-m-d',strtotime($data['EventDateTime_Start']));
            $data['EventTimeStart'] = date('H:i',strtotime($data['EventDateTime_Start']));
            $data['EventDateEnd'] = date('Y-m-d',strtotime($data['EventDateTime_End']));
            $data['EventTimeEnd'] = date('H:i',strtotime($data['EventDateTime_End']));
            $data['EventOnDate'] = date('Y-m-d',strtotime($data['EventOnDateTime_Start']));
            $data['EventOnTime'] = date('H:i',strtotime($data['EventOnDateTime_Start']));
            $data['EventOffDate'] = date('Y-m-d',strtotime($data['EventOnDateTime_End']));
            $data['EventOffTime'] = date('H:i',strtotime($data['EventOnDateTime_End']));

            $data['currentPage'] = 'exhibition';
            $data['header_title'] = 'Edit visit';
            return view('admin.exhibition.add_mod_event',$data);
        }
        return redirect('/admin/exhibition/visits');
    }

    public function delete_visit(){
        $id = trim(request('id'));
        ExhibitionData::where('EventId',$id)->delete();
        return redirect('/admin/exhibition/visits');        
    }

    public function attendees($findEventType="events"){
        $id = request('id');
        $EventType = $findEventType==='events'?1:2;
        $EventInfo = ExhibitionData::where('EventId',$id)->where('EventType',$EventType)->take(1)->first();
        if($EventInfo){
            $Attendees = ExhibitionAttendeeData::join('members','exhibition_attendees.MemberId','=','members.MemberId')->where('EventId',$id)->orderBy('Status','DESC')->get();
            $AttendeesCount = ExhibitionAttendeeData::groupby('EventId')->where('EventId',$id)->select(DB::raw('SUM(Quantity) as qty'))->take(1)->first();
            if($AttendeesCount){
                $AttendeesCount = $AttendeesCount->qty;
            }
            else{
                $AttendeesCount = 0;
            }
            
            $data['findEventType'] = $findEventType;
            $data['EventInfo'] = $EventInfo;
            $data['Attendees'] = $Attendees;
            $data['AttendeesCount'] = $AttendeesCount;
            $data['currentPage'] = 'exhibition';
            $data['header_title'] = 'Attendees of this exhibition';
            return view('admin.exhibition.attendees',$data);
        }
        return redirect('/admin/exhibition/'.$findEventType);
    }
}