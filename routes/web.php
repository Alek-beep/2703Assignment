<?php

use Illuminate\Support\Facades\Route;


use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\SessionController;

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



// The rest of the routes
Route::get('/', function () {
    $sql1 = "select Project.id, projectName, partnerName
             from Project, IndPartner
             where Project.partner_id = IndPartner.id";
    /*
        This sql query generates a list of all projects and counts the number of
        occurences in the Student Application table. The COALESCE statement replaces
        any missing counts with 0 since some projects will have no applications.
    */
    $sql2 = "
                select  p.id,
                        COALESCE(s.applicationCount, 0) AS applicationCount
                from    Project p
                LEFT JOIN
                (
                    SELECT project_id, COUNT(*) AS applicationCount
                    FROM StudentApplication
                    GROUP BY project_id
                ) s
                    ON p.id = s.project_id
            ";
    $projectsCount = DB::select($sql2);
   
    $projects = DB::select($sql1);
    return view('home') ->with('projects',$projects)
                        ->with('projectCount',$projectsCount);
});

Route::get('/logOut', function(Request $request){
    $request->session()->forget('companyName');
    $request->session()->forget('companyLocation');
    return redirect("/");
    
});

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

Route::get('/top3', function () {

    $sql1 = "
                SELECT i.partnerName, COUNT(*) AS numberProjects
                FROM IndPartner i, Project p
                WHERE p.partner_id = i.id
                GROUP BY i.partnerName
                ORDER BY numberProjects DESC
            ";
    $partners = DB::select($sql1);

    return view('top3')->with('partners', $partners);
});

Route::get('/projectAssignment', function () {
    return view('projectAssignment');
});

Route::get('/studentsApplied', function () {
    $sql1 = "Select DISTINCT student_id, firstName, lastName from Student, StudentApplication where student_id = id";
    return view('studentsApplied')->with('students', DB::select($sql1));
});

Route::get('/projects/{studentId}/', function ($studentId) {
    $sql1 = "select projectName, partnerName, project_id from Project, StudentApplication, IndPartner where student_id = $studentId and project_id = Project.id and partner_id = IndPartner.id";
    return view('projects')->with('projects', DB::select($sql1));
                                                                                        
});

Route::get('/details/{projectId}/{errorMessage?}', function ($projectId, $errorMessage="") {
    $sql1 = "select * from Project where id = ?";
    $sql2 = "select partnerName from IndPartner, Project where Project.partner_id = IndPartner.id and Project.id = ? ";
    $sql3 = "select firstname, lastname from Student, StudentApplication where StudentApplication.project_id = ? and StudentApplication.student_id = Student.id";
    return view('details')->with('projectDetails',DB::select($sql1, array($projectId))) ->with('partnerName',DB::select($sql2, array($projectId))[0]->partnerName)
                                                                                        ->with('students',DB::select($sql3, array($projectId)))
                                                                                        ->with('errorMessage',$errorMessage);                                                                                    
});

Route::get('/justification/{firstName}/{lastName}/{project_id}', function ($firstName, $lastName, $project_id) {
    $sql1 = "select id from Student where firstName = ? and lastName = ?";
    $studentId = DB::select($sql1, array($firstName, $lastName))[0]->id;
    $sql2 = "select justification from StudentApplication where project_id = $project_id and student_id = $studentId";

    return view('justification')->with('justification', DB::select($sql2)[0]->justification);
                                                                                        
});
// Session routes
Route::get('session/get','App\Http\Controllers\SessionController@accessSessionData');
Route::get('session/remove','App\Http\Controllers\SessionController@deleteSessionData');

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

Route::post('/add_application', function () {
    $firstName = request('firstName');
    $lastName = request('lastName');
    $justification = request('justification');
    $projectId = request('id');

    $studentValid = add_application($firstName, $lastName, $justification, $projectId);
    if($studentValid)
    {
        return redirect("/details/$projectId");
    } else {
        $errorMessage = 'Invalid Student!';
        return redirect("/details/$projectId/$errorMessage");
    }
});

Route::post('/update_project', function () {
    $projectId = request('id');
    $projectTitle = request('projectTitle');
    $major = request('major');
    $projectDescription = request('projectDescription');
    $numberStudents = request('numberStudents');
    update_project($projectId, $projectTitle, $major, $projectDescription, $numberStudents);
    return redirect("/details/$projectId");
});

Route::post('/delete_project', function () {
    $projectId = request('id');
    delete_project($projectId);
    return redirect("/");
});

Route::post('/apply_project', function () {
    $projectId = request('id');
    delete_project($projectId);
    return redirect("/");
});

Route::post('/test', function () {
    $sql = "select * from StudentApplication";
    dd(DB::select($sql));
});

function add_project($companyName, $companyLocation, $projectTitle, $major, $projectDescription, $numberStudents){
   
    $sql1 = "select id from IndPartner where partnerName = '$companyName' and location = '$companyLocation'";
    $companyId = DB::select($sql1)[0]->id;
    
    $sql2 = "insert into Project(projectName, major, description, numberStudents, partner_id) values (?,?,?,?,?)";
    DB::insert($sql2, array($projectTitle, $major, $projectDescription, $numberStudents, $companyId));
    $projectId = DB::getPdo()->lastInsertId();
    return $projectId;
}

function add_application($firstName, $lastName, $justification, $projectId){
    $sql1 = "select id from Student where firstName = ? and lastName = ?";
    $student = DB::select($sql1, array($firstName, $lastName));
    // Check if student exists
    if($student == []){
        return false;
    }
    $studentId = $student[0]->id;
    $sql2 = "select student_id, project_id from StudentApplication where student_id = $studentId and project_id = $projectId";
    $studentInProj = DB::select($sql2);

    // Check if student aleady in project
    if($studentInProj != []){
        return false;
    }
    
    if($studentId){
        $sql2 = "insert into StudentApplication(project_id, student_id, justification) values (?,?,?);";
        DB::update($sql2, array($projectId, $studentId, $justification));
        return true;
    } else {
        return false;
    }
    
}

function update_project($id, $title, $major, $description, $students){
    $sql = "update Project set projectName = ?, major = ?, description = ?, numberStudents = ? where id = ?";
    DB::update($sql, array($title, $major, $description, $students, $id));
}

function delete_project($id){
    delete_application($id);
    $sql = "delete from Project where id = ?";
    DB::delete($sql, array($id));
}

function delete_application($id){
    $sql = "delete from StudentApplication where project_id = ?";
    DB::delete($sql, array($id));
}
