import { Component, OnInit } from '@angular/core'
import { Course } from 'src/app/interfaces/course'
import { CoursesService } from 'src/app/services/courses.service'
import { RegistrationService } from 'src/app/services/registration.service'
import { UsersService } from 'src/app/services/users.service'
import { Router, ActivatedRoute } from '@angular/router'

@Component({
  selector: 'app-showusers',
  templateUrl: './showusers.component.html',
  styleUrls: ['./showusers.component.css'],
})
export class ShowusersComponent implements OnInit {
  courses: any
  customers: any
  showAlert = false
  alertType = ''
  alertMessage = ''

  userData: any
  userType: any
  selectedCourseId: any
  message: any

  constructor(
    private coursesService: CoursesService,
    private registrationService: RegistrationService,
    private usersService: UsersService,
    private route: ActivatedRoute,
    private router: Router,
  ) {
    const userType = this.registrationService.userValue.userType
    // show users only to admin
    if (userType != 'admin') {
      this.router.navigate(['/registration/login'])
    }
  }

  ngOnInit(): void {
    // get user information if present in local storage else empty
    let user = JSON.parse(localStorage.getItem('user') || '{}')
    this.userData = user
    this.userType = user?.userType
    // get customers list from the database using php api
    this.getCustomers()
  }

  getCustomers() {
    this.usersService.getCustomers().subscribe({
      next: (data) => {
        this.customers = data // load customer data
      },
      error: (error: string) => { 
        this.showAlert = true
        this.alertType = 'error'
        this.alertMessage = 'Unable to get Customers.'
      },
    })
  }

  // get courses for the given customer
  getCustomerCourses(id: string) {
    this.coursesService.getCoursesForCustomer(id).subscribe({
      next: (data) => {
        this.courses = data // get corresponding course(s) data
        let cardId = `card-body-${id}`
        let myContainer = document.getElementById(
          `${cardId}`,
        ) as HTMLInputElement
        if (this.courses.length > 0) { // if courses found then list the courses
          let courseNames = '<ul>'
          this.courses.forEach((course: Course) => {
            courseNames += `<li>
            <a href="/courses/editcourse/${course.courseId}">${course.courseTitle}</a>
            </li>`
          })
          courseNames += '</ul>'
          myContainer.innerHTML = courseNames // display on webpage
        } else {
          myContainer.innerHTML = 'No Courses found.' // if no course found
        }
      },
      error: (error: string) => {
        this.showAlert = true
        this.alertType = 'error'
        this.alertMessage = 'Unable to get Courses.'
      },
    })
  }
}
