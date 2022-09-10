<?php

use Illuminate\Support\Facades\Route;


use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\SessionController;

//This function returns the details for a project to be displayed on the project details page
function details_display($projectId){
    $sql1 = "select * from Project where id = ?";
    $sql2 = "select partnerName from IndPartner, Project where Project.partner_id = IndPartner.id and Project.id = ? ";
    $sql3 = "select firstname, lastname from Student, StudentApplication where StudentApplication.project_id = ? and StudentApplication.student_id = Student.id";
    //Return index 0 is all of the details in the project table for the specified project id, index 1 is the partner name for the project and index 2 is the first
    //and last names of the students that have applied to the project
    return [DB::select($sql1, array($projectId)), 
            DB::select($sql2, array($projectId))[0]->partnerName, 
            DB::select($sql3, array($projectId))];
}

//This function returns the project name, partner name and project id for each project a specified student has applied to
function projects_student($studentId){
    $sql1 = "select projectName, partnerName, project_id 
            from Project, StudentApplication, IndPartner 
            where student_id = ? and project_id = Project.id and partner_id = IndPartner.id";
    return DB::select($sql1, array($studentId));
}

// This function returns all of the students who have applied to a project
function students_applied(){
    $sql1 = "Select DISTINCT student_id, firstName, lastName from Student, StudentApplication where student_id = id";
    return DB::select($sql1);
}

//This function returns all the partners by partner name along with the number of projects they have advertised
function top3_display(){
    $sql1 = "
                SELECT i.partnerName, COUNT(*) AS numberProjects
                FROM IndPartner i, Project p
                WHERE p.partner_id = i.id
                GROUP BY i.partnerName
                ORDER BY numberProjects DESC
            ";
    return DB::select($sql1);
}

//this function returns all of the projects with their project names,
//partner names, project ids and also the number of applicants each project has gotten
function home_display(){
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
    //Index 0 is the project count array which contains all the projects and their number of applicants,
    //index 1 is the projects with their project names, partner names, project ids
    return [DB::select($sql2), DB::select($sql1)];
}

// This function returns the justification that a student has supplied for an applictation to a project
function justification_get($firstName, $lastName, $project_id){
    $sql1 = "select id from Student where firstName = ? and lastName = ?";
    $studentId = DB::select($sql1, array($firstName, $lastName))[0]->id;
    $sql2 = "select justification from StudentApplication where project_id = ? and student_id = ?";
    return DB::select($sql2, array($project_id, $studentId))[0]->justification;
}

// This function returns an array of projects with assigned students. First the function generates an array for all first, 
// second and third preferences. Then it generates an array for all of the projects in order with the total number of students
// able to be assigned to it. Then it iterates through the projects array assigned students to the project, first by first preference,
// then second, then third. It stops the loop for each project if it has assigned the maximum number of students.
function project_assignment(){
    $firstPreferences =     "
                            SELECT project_id, student_id
                            FROM StudentApplication
                            WHERE preference = 1
                            ORDER BY project_id ASC
                            ";
    $secondPreferences =    "
                            SELECT project_id, student_id
                            FROM StudentApplication
                            WHERE preference = 2
                            ORDER BY project_id ASC
                            ";
    $thirdPreferences =     "
                            SELECT project_id, student_id
                            FROM StudentApplication
                            WHERE preference = 3
                            ORDER BY project_id ASC
                            ";
    $sqlProjects =          "
                            SELECT id as project_id, numberStudents
                            FROM Project
                            ORDER BY project_id ASC
                            ";
    $assignedStudents;
    $index=0;

    $projects = DB::select($sqlProjects);
    
    foreach ($projects as $project){
        $assignedStudents=0;
        foreach(DB::select($firstPreferences) as $first){
            if($assignedStudents < $project->numberStudents && $first->project_id == $project->project_id){
                $projects[$index]->student_id[$assignedStudents] = $first->student_id;
                $assignedStudents += 1;
            }  
        }
        foreach(DB::select($secondPreferences) as $second){
            if($assignedStudents < $project->numberStudents && $second->project_id == $project->project_id){
                $projects[$index]->student_id[$assignedStudents] = $second->student_id;
                $assignedStudents += 1;
            }  
        }
        foreach(DB::select($thirdPreferences) as $third){
            if($assignedStudents < $project->numberStudents && $third->project_id == $project->project_id){
                $projects[$index]->student_id[$assignedStudents] = $third->student_id;
                $assignedStudents += 1;
            }  
        }
        $index++;
    }
    return $projects;
}

// This function takes in all the necessary parameters to insert a new project into the project table. Once the project has been inserted,
// then the function returns the previously inserted id so that the newly created project can be navigated to
function add_project($companyName, $companyLocation, $projectTitle, $major, $projectDescription, $numberStudents){
    $sql1 = "select id from IndPartner where partnerName = ? and location = ?";
    $companyId = DB::select($sql1, array($companyName, $companyLocation))[0]->id;
    
    $sql2 = "insert into Project(projectName, major, description, numberStudents, partner_id) values (?,?,?,?,?)";
    DB::insert($sql2, array($projectTitle, $major, $projectDescription, $numberStudents, $companyId));
    $projectId = DB::getPdo()->lastInsertId();
    return $projectId;
}

// This function is for adding an application for a project. It takes in the necessary parameters to insert a new application, then performs
// validation checks on the data to make sure it is ok to create the new application. The function returns either a specific error string, or
// successful which is used to display error messages on the page through the route.
function add_application($firstName, $lastName, $justification, $preference, $projectId){
    $sql1 = "select id from Student where firstName = ? and lastName = ?";
    $student = DB::select($sql1, array($firstName, $lastName));
    // Check if student exists
    if($student == []){
        return "Student Doesn't Exist!";
    }
    $studentId = $student[0]->id;
    $sql2 = "select student_id, project_id from StudentApplication where student_id = $studentId and project_id = $projectId";
    $studentInProj = DB::select($sql2);

    // Check if student aleady in project
    if($studentInProj != []){
        return "User already in project!";
    }

    //Check number of applications
    $sqlCheckNumber =   "
                            SELECT sa.student_id, COUNT(*) as numberApplications
                            FROM StudentApplication sa, Student s
                            WHERE s.id = sa.student_id and student_id = ?
                        ";
    $numberOfApplications = DB::select($sqlCheckNumber, array($studentId));
    // if number of applications is 3 then dont continue inserting application
    if($numberOfApplications[0]->numberApplications == 3){
        return "Maximum User Applications!";
    }
    // if preference out of range dont continue
    if($preference < 1 || $preference > 3){
        return "Preference Out of Range!";
    }
    // Check if preference already exists
    $sqlPreferenceCheck =   "
                                SELECT student_id, preference
                                FROM StudentApplication
                                WHERE student_id = ?
                                ORDER BY student_id ASC
                            ";
    foreach(DB::select($sqlPreferenceCheck, array($studentId)) as $pref){
        if($pref->preference == $preference){
            return "Preference exists!";
        }
    }

    // if there is a student id, then insert a new application into the StudentApplication table
    if($studentId){
        $sql2 = "insert into StudentApplication(project_id, student_id, justification, preference) values (?,?,?, ?);";
        DB::update($sql2, array($projectId, $studentId, $justification, $preference));
        return "Successful";
    } else {
        return "Unsuccessful!";
    }
    
}

// This function is for updating a project. it simply updates a project using the appropriate input parameters.
function update_project($id, $title, $major, $description, $students){
    $sql = "update Project set projectName = ?, major = ?, description = ?, numberStudents = ? where id = ?";
    DB::update($sql, array($title, $major, $description, $students, $id));
}

// This function is for deleting a project. It first deletes all of the dependant applications, then proceeds to
// delete the specified project
function delete_project($id){
    delete_application($id);
    $sql = "delete from Project where id = ?";
    DB::delete($sql, array($id));
}

// This function deletes dependant applications on a project id
function delete_application($id){
    $sql = "delete from StudentApplication where project_id = ?";
    DB::delete($sql, array($id));
}
?>
