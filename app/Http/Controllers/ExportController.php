<?php

namespace App\Http\Controllers;

use Export;
use App\Models\Course;
use App\Models\Students;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function __construct()
    {

    }

    public function welcome()
    {
        return view('hello');
    }

    /**
     * View all students found in the database
     */
    public function viewStudents()
    {
        $students = Students::with('course')->get();
        return view('view_students', compact(['students']));
    }

    /**
     * Exports all student data to a CSV file
     */
    public function exportStudentsToCSV(Request $request)
    {
        if(!$request->get('studentId'))
            return redirect('view')->with('notice', 'You must select at least one student.');

        $ids = $request->get('studentId');
        $students = Students::whereIn('id', $ids)->get();
        $pathToFile = Export::students($students);
        return response()->download($pathToFile, 'students.csv', Export::getHeaders());
    }

    /**
     * Exports the total amount of students that are taking each course to a CSV file
     */
    public function exportCourseAttendenceToCSV($course_id)
    {
        $course = Course::FindOrFail($course_id);
        $students = $course->students;
        $pathToFile = Export::students($students);
        return response()->download($pathToFile, 'students.csv', Export::getHeaders());
    }
}
