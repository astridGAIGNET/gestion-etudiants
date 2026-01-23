<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Student;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with('classe')->latest()->paginate(20);
        return view('front.students.index', compact('students'));
    }

    public function show(Student $student)
    {
        return view('front.students.show', compact('student'));
    }
}
