<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        // Get all students
        $students = Student::all();

        // Return the view and pass students data
        return view('students', compact('students'));
    }
}
