import { Component, EventEmitter, OnInit, Output } from '@angular/core'
import { FormBuilder, FormGroup, Validators } from '@angular/forms'
import { Course } from 'src/app/interfaces/course'
import { CoursesService } from 'src/app/services/courses.service'
import { UsersService } from 'src/app/services/users.service'

@Component({
  selector: 'app-searchcourses',
  templateUrl: './searchcourses.component.html',
  styleUrls: ['./searchcourses.component.css'],
})
export class SearchcoursesComponent implements OnInit {
  form: FormGroup
  submitted = false
  selectedCriteria: any
  @Output() searchedCourses: any = new EventEmitter<Course>() // emit searched courses to parent (showcourses)

  // search criteria with parameters
  filterCategories = [
    { id: 1, name: 'Course Name', value: 'name' },
    { id: 2, name: 'Fee', value: 'fee' },
    { id: 3, name: 'Date (YYYY-MM-DD)', value: 'date' },
  ]
  
  // function to access form controls easily
  get f() {
    return this.form.controls
  }

  constructor(
    private formBuilder: FormBuilder,
    private coursesService: CoursesService,
    private usersService: UsersService,
  ) {}

  ngOnInit(): void {
    this.form = this.formBuilder.group({
      criteria: ['name'],
      min: [''],
      max: [''],
      value: [''],
    })

    this.selectedCriteria = '1: name' // default search criteria is by name
  }

  // when uses selects criteria toggle form fields to show respective input fields
  onChange(event: any) {
    this.selectedCriteria = event.target.value
    if(this.selectedCriteria == '2: fee' || this.selectedCriteria == '3: date') {
      this.form.controls['min'].setValue('');
      this.form.controls['max'].setValue('');
    }
  }

  // search matching courses using php api
  onSubmit() {
    this.coursesService.searchCourses(this.form.value).subscribe({
      next: (data: any) => {
        this.searchedCourses.emit(data) // pass result courses to the parent (showcourses)
      },
      error: (error: string) => {
        console.log('No courses found.')
      },
    })
  }
}
