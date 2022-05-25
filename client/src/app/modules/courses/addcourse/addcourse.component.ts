import { Component, ElementRef, OnInit, ViewChild } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { first } from 'rxjs/operators';
import { CoursesService } from 'src/app/services/courses.service';
import { UsersService } from 'src/app/services/users.service';
import { RegistrationService } from 'src/app/services/registration.service';

@Component({
  selector: 'app-addcourse',
  templateUrl: './addcourse.component.html',
  styleUrls: ['./addcourse.component.css']
})
export class AddcourseComponent implements OnInit {

    @ViewChild('addCourseCard')
    addCourseCardRef!: ElementRef;

    form: FormGroup;
    loading = false;
    submitted = false;
    showAlert = false;
    alertType = '';
    alertMessage = '';
    courseId:any;
    courseDetails:any;
    instructors:any;
    courseLevels = ['Beginner', 'Intermediate', 'Expert'];
    courseFrequency = ['Daily', 'Weekly'];

    constructor(
        private formBuilder: FormBuilder,
        private route: ActivatedRoute,
        private router: Router,
        private coursesService: CoursesService,
        private usersService: UsersService,
        private registrationService: RegistrationService
    ) { 
      this.route.params.subscribe((res) => (this.courseId = res['id']))
      const userType = this.registrationService.userValue.userType;
        if(userType != 'admin') {
            this.router.navigate(['/registration/login']);
        }
    }

    ngOnInit() {
        this.form = this.formBuilder.group({
          courseTitle: ['', Validators.required],
          courseLevel: [null, Validators.required],
          courseDescription: ['', Validators.required],
          courseFee: ['', Validators.required],
          instructorId: [null, Validators.required],
          startDate: ['', Validators.required],
          endDate: ['', Validators.required],
          startTime: ['', Validators.required],
          endTime: ['', Validators.required],
          frequency: [null, Validators.required],
          studentLimit: ['', Validators.required]
        });
        this.getInstructors();
    }

    // function to access form controls easily
    get f() { return this.form.controls; }

    // load all the instructors from the users table using php api
    getInstructors() {
      this.usersService.getInstructors()
          .pipe(first())
          .subscribe({
              next: (data: any) => {
                  this.instructors = data;
              },
              error: (error: string) => {
                  this.loading = false;
              }
          });
    }

    // add new course data into database using php api
    onSubmit() {
        this.submitted = true;
        this.addCourseCardRef.nativeElement.scrollIntoView(); // scroll to top anchor

        // return if form is invalid
        if (this.form.invalid) {
            return;
        }

        this.loading = true; // loading spinner
        
        this.coursesService.addCourse(this.form.value) // add course to database using php api
            .pipe(first())
            .subscribe({
                next: () => {
                    this.showAlert = true;
                    this.alertType = 'success';
                    this.alertMessage = 'Course added successfully.';
                    this.loading = false;
                },
                error: (error:any) => {
                    this.showAlert = true;
                    this.alertType = 'error';
                    this.alertMessage = 'Unable to add course.';
                    this.loading = false;
                }
            });
    }
}