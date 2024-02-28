<?php
namespace App\Services\Classes;

class Export 
{

    /**
     * Export students.
     * 
     * @param type $students 
     * @return type
     */
    public function students($students)
    {
        $filename = storage_path("app/students.csv");
        
        $file = fopen($filename,"w");
        fputcsv($file, $this->getStudentsHeadings()); 

        foreach($students as $student)
        {
            fputcsv($file,[
                $student->firstname,
                $student->surname,
                $student->email,
                ($student->course)?$student->course->university:'', //in case student is not enrolled in any course
                ($student->course)?$student->course->course_name:'', //in case student is not enrolled in any course
            ]); 
        }
        fclose($file);
        return $filename;
    }

    /**
     * Students csv headings.
     * 
     * @return type
     */
    protected function getStudentsHeadings()
    {
        return ['Forename', 'Surname', 'Email', 'University', 'Course'];
    }

    /**
     * Response download headers.
     * 
     * @return type
     */
    public function getHeaders()
    {
        return [
              'Content-Type: application/csv',
            ];
    }

}

?>