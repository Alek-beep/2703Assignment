<?php

use Illuminate\Support\Facades\Route;


use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\SessionController;
require_once("../public/defs/defs.php");

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route for the home page
Route::get('/', function () {
    $homeArray = home_display();
    return view('home') ->with('projects',$homeArray[1])
                        ->with('projectCount',$homeArray[0]);
});
// Route for the documentation page
Route::get('/documentation', function () {
    return view('documentation');
});

//Route for logging out and resetting the session details
Route::get('/logOut', function(Request $request){
    $request->session()->forget('companyName');
    $request->session()->forget('companyLocation');
    return redirect("/");
    
});

//Route for navigating to the advertise form, taking the stored company name and location from the session storage with it
Route::get('/advertise/{errorMessage?}', function ($errorMessage="", Request $request) {
    if($request->session()->has('companyName') && $request->session()->has('companyLocation')){
        $companyName = $request->session()->get('companyName');
        $companyLocation = $request->session()->get('companyLocation');
    } else {
        $companyName = "";
        $companyLocation = "";
    }
    return view('advertise')->with('errorMessage', $errorMessage)
                            ->with('name', $companyName)
                            ->with('location', $companyLocation);
});

//Route for navigating to the page displaying the top 3 industry partners by order of the amount of projects advertised
Route::get('/top3', function () {
    $partners = top3_display();
    return view('top3')->with('partners', $partners);
});

//Route for navigating to the page displaying the assignment of projects to students
Route::get('/projectAssignment', function () {
    $projects = project_assignment();
    return view('projectAssignment')->with('projects',$projects);
});

//Route for displaying the students who have applied to a project
Route::get('/studentsApplied', function () {
    return view('studentsApplied')->with('students', students_applied());
});

//Route for displaying the projects a student has applied to
Route::get('/projects/{studentId}/', function ($studentId) {
    return view('projects')->with('projects', projects_student($studentId));
                                                                                        
});

//Route for displaying the details of a selected project
Route::get('/details/{projectId}/{errorMessage?}', function ($projectId, $errorMessage="") {
    $result = details_display($projectId);
    return view('details')->with('projectDetails', $result[0]) ->with('partnerName', $result[1])
                                                                                        ->with('students', $result[2])
                                                                                        ->with('errorMessage',$errorMessage);                                                                                    
});

//Route for displaying the justification a student has entered for their application to a project
Route::get('/justification/{firstName}/{lastName}/{project_id}', function ($firstName, $lastName, $project_id) {
    return view('justification')->with('justification', justification_get($firstName, $lastName, $project_id));                                                                                     
});

//Route for an industry partner to advertise a new project. Using the session functionality, the company name and location are read from storage if it is in there
Route::post('/add_project', function (Request $request) {
    $companyName = request('companyName');
    $companyLocation = request('companyLocation');
    $request->session()->put('companyName', $companyName);
    $request->session()->put('companyLocation', $companyLocation);
    $projectTitle = request('projectTitle');
    $major = request('major');
    $projectDescription = request('projectDescription');
    $numberStudents = request('numberStudents');
    if($companyName && $companyLocation && $projectTitle && $major && $projectDescription && $numberStudents){
        if($numberStudents>=3 && $numberStudents<=8){
            $id = add_project($companyName, $companyLocation, $projectTitle, $major, $projectDescription, $numberStudents);
        } else {
            return view('advertise')->with('errorMessage', 'Number of students must be between 3 and 8');
        }
    } else {
        return view('advertise')->with('errorMessage', 'All fields must contain a value!');
    }
    if($id)
    {
        return redirect("/details/$id");
    } else {
        die("Error adding item");
    }
});

//Route for a student to submit an application to work on a project
Route::post('/add_application', function () {
    $firstName = request('firstName');
    $lastName = request('lastName');
    $justification = request('justification');
    $preference = request('preference');
    $projectId = request('id');

    $studentValid = add_application($firstName, $lastName, $justification, $preference, $projectId);
    if($studentValid == "Successful")
    {
        return redirect("/details/$projectId");
    } else {
        return redirect("/details/$projectId/$studentValid");
    }
});

//Route for an industry partner to update a project
Route::post('/update_project', function () {
    $projectId = request('id');
    $projectTitle = request('projectTitle');
    $major = request('major');
    $projectDescription = request('projectDescription');
    $numberStudents = request('numberStudents');
    update_project($projectId, $projectTitle, $major, $projectDescription, $numberStudents);
    return redirect("/details/$projectId");
});

//Route for deleting a project
Route::post('/delete_project', function () {
    $projectId = request('id');
    delete_project($projectId);
    return redirect("/");
});

//Route for a student to apply to
Route::post('/apply_project', function () {
    $projectId = request('id');
    delete_project($projectId);
    return redirect("/");
});

?>