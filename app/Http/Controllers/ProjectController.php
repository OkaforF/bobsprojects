<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Project;
use App\Models\WorkSession;
use App\Models\InvoicesToWorksession;
use App\Models\Invoice;


class ProjectController extends Controller
{
  //GET AALL PROJECTS (NOT COMPLETED)
  public function getAllProjects()
  {
    $projects = Project::with('work_session')->where('is_completed', '=', false)->get();

    return view('projects', [
    'data' => $projects]);
  }


    //GET ALL COMPLETED PROJECTS
    public function getCompletedProjects()
    {
      $archivedProjects = Project::where('is_completed', '=', true)->get();

      return view('archive', [
      'data' => $archivedProjects]);
      }


      //CREATE PROJECT 
      public function createProject(Request $request)
      {
      if (!$request->title) {
        return back()->withErrors(['Enter a title']);
      }
      if (!$request->client) {
        return back()->withErrors(['Enter a client']);
      }
      if (!$request->email) {
        return back()->withErrors(['Enter a email']);
      }
      if (!$request->phone) {
        return back()->withErrors(['Enter a phone number']);
      }
      $project = new Project([
          'title' => $request->title,
          'client' => $request->client,
          'email' => $request->email,
          'phone' => $request->phone
      ]);

      $project_exists = Project::where('title', '=', $request->title)->get();

      if(count($project_exists) !== 1) {
          $project->  save();
          return back()->withInput();
      } else {
        return back()->withErrors(['A project with that title already exists']);
      }
    }


  //DELETE PROJECT
  public function deleteProject(Request $request)
  { 
    $projectId = $request->id;
    Project::where('id', $projectId)->delete();

    return back()->withInput();
  }


  //MARK PROJECT AS COMPLETED
  public function markAsCompleted(Request $request)
  {
    $projectId = $request->id;
    Project::where('id', $projectId)->update(['is_completed' => 1]);

    return back()->withInput();
  }


  //GET PROJECT DETAILS BY ID
  public function getProject(Request $request)
  {
    $projectId = $request->id;
    $project = Project::with('work_session')->where('id', '=', $projectId)->first();
    $sessions = WorkSession::where('project_id', '=', $projectId)->get();
    $totalMinutes = WorkSession::where('project_id', '=', $projectId)->sum('minutes_worked');
    $invoices = Invoice::where('project_id', '=', $projectId)->get();
    $hours = floor($totalMinutes / 60);
    $min = $totalMinutes - ($hours * 60);
    
    return view('project', [
      'data' => $project,
      'hours' => $hours.'hours '.$min.'mins',
      'sessions' => $sessions,
      'invoices' => $invoices
      ]);
  }


    //LOG HOURS
    public function logHours(Request $request)
    {
      if (!$request->start_time) {
        return back()->withErrors(['Enter a start time']);
      }
      if (!$request->end_time) {
        return back()->withErrors(['Enter an end time']);
      }
      if (!$request->description) {
        return back()->withErrors(['Enter a description']);
      }

      $startTime = Carbon::parse($request->start_time);
      $endTime = Carbon::parse($request->end_time);
      $totalDuration = $endTime->diff($startTime)->format('%H:%I:%S');
      
      function minutes($time){
        $time = explode(':', $time);
        return ($time[0]*60) + ($time[1]) + ($time[2]/60);
      }
      $diffInMinutes =  minutes($totalDuration);
      
      $projectSession = new WorkSession([
        'project_id' => $request->project_id,
        'minutes_worked' => $diffInMinutes,
        'description' => $request->description,
      ]);

      $projectSession->save();
      return back();
    }


  //CREATE INVOICE
  public function createInvoice(Request $request)
  {
    $projectId = $request->id;
    $dueDate=strtotime("+1 Months");
    $uninvoiced_hours = WorkSession::where('project_id', '=', $projectId)->where('is_invoiced', '=', false)->get();
    $totalMinutes = array();

    foreach ($uninvoiced_hours as $invoiced_hour){
    $invoiced_hour->minutes_worked;
      array_push($totalMinutes, $invoiced_hour->minutes_worked);
    }

    if(count($uninvoiced_hours) > 0) {
      $invoice = new Invoice([
        'project_id' => 1,
        'due_date' => date("Y-m-d h:i:s", $dueDate),
        'total_cost' => array_sum($totalMinutes)
      ]);

    $invoice->save();

    foreach ($uninvoiced_hours as $uninvoiced_hour) {
      $data = [
        'invoice_id' => $invoice->id,
        'work_session_id' => $uninvoiced_hour->id
      ];
      WorkSession::where('id', $uninvoiced_hour->id)->update(['is_invoiced' => 1]);
    };

    InvoicesToWorksession::insert($data);
    return back();
    } else {
      return back()->withErrors(['No hours to invoice']);
    }
  }

  //GET INVOICE
  public function getInvoice(Request $request) {
  $projectId = $request->id;
  
  $invoices = InvoicesToWorksession::with('invoices')->get();
  echo $invoices;
  }
}
