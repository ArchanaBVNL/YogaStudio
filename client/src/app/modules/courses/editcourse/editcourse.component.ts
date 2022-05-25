import { Component, ElementRef, OnInit, ViewChild } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { first } from 'rxjs/operators';
import { CoursesService } from 'src/app/services/courses.service';
import { UsersService } from 'src/app/services/users.service';
import { RegistrationService } from 'src/app/services/registration.service';
 
@Component({
  selector: 'app-editcourse',
  templateUrl: './editcourse.component.html',
  styleUrls: ['./editcourse.component.css']
})
export class EditcourseComponent implements OnInit {

    @ViewChild('editCourseCard') // anchor for scroll to top
    editCourseCardRef!: ElementRef;

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
      this.route.params.subscribe((res) => (this.courseId = res['id'])); // route parameter courseId
      const userType = this.registrationService.userValue.userType;
        if(userType != 'admin') {
            this.router.navigate(['/registration/login']);
        }
    }

    ngOnInit() {
        this.form = this.formBuilder.group({
          courseId: [''],
          courseTitle: ['', Validators.required],
          courseLevel: ['', Validators.required],
          courseDescription: ['', Validators.required],
          courseFee: ['', Validators.required],
          instructorId: [''],
          startDate: ['', Validators.required],
          endDate: ['', Validators.required],
          startTime: ['', Validators.required],
          endTime: ['', Validators.required],
          frequency: [null, Validators.required],
          studentLimit: ['', Validators.required]
        });
        this.getInstructors(); // get instructor info
        this.getCourseInfo(); // get course info
    }

    get f() { return this.form.controls; }

    // get all instructors to update instructor assigned for the course
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
    
    // load course information from courses table using php api
    getCourseInfo() {
      this.loading = true;
        this.coursesService.getCourseById(this.courseId)
            .pipe(first())
            .subscribe({
                next: (data: any) => {
                    this.courseDetails = data;
                    this.form.patchValue({
                      courseId: this.courseId,
                      courseTitle: data.courseTitle, 
                      courseLevel: data.courseLevel, 
                      courseFee: data.courseFee,
                      courseDescription: data.courseDescription,
                      instructorId: data.instructorId,
                      startDate:data.startDate,
                      endDate:data.endDate,
                      startTime:data.startTime,
                      endTime:data.endTime,
                      frequency:data.frequency,
                      studentLimit:data.studentLimit
                    })
                    this.loading = false;
                },
                error: (error: string) => {
                    this.loading = false;
                }
            });
    }

    // on change and submit, update the corresponding row of courseId in the courses table using php api
    onSubmit() {
        this.submitted = true;
        this.editCourseCardRef.nativeElement.scrollIntoView(); // scroll to top

        if (this.form.invalid) {
            return;
        }

        this.loading = true;
        this.coursesService.editCourse(this.form.value) // update courseId using php api
            .pipe(first())
            .subscribe({
                next: () => {
                    this.showAlert = true;
                    this.alertType = 'success';
                    this.alertMessage = 'Course modified successfully.';
                    this.loading = false;
                },
                error: (error:any) => {
                    this.showAlert = true;
                    this.alertType = 'error';
                    this.alertMessage = 'Unable to modify course.';
                    this.loading = false;
                }
            });
    }
}