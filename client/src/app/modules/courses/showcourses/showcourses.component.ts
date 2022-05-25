import { Component, OnInit } from '@angular/core'
import { Course } from 'src/app/interfaces/course'
import { CoursesService } from 'src/app/services/courses.service'
import { RegistrationService } from 'src/app/services/registration.service';

@Component({
  selector: 'app-showcourses',
  templateUrl: './showcourses.component.html',
  styleUrls: ['./showcourses.component.css'],
})
export class ShowcoursesComponent implements OnInit {
  constructor(private coursesService: CoursesService,
    private registrationService: RegistrationService) {}

  courses: any;
  weather: any;
  userData:any;
  userType:any;
  displayDeleteModalStyle="none";
  displayMessageModalStyle="none";
  displayWithdrawModalStyle="none";
  displayWithdrawMessageModalStyle="none";
  selectedCourseId:any;
  message:any;

  coursesFromCart:any;

  showAlert = false;
  alertType = '';
  alertMessage = '';
  
  ngOnInit(): void {
    // get user data from local storage if present
    let user = JSON.parse(localStorage.getItem('user') || '{}');
    this.userData = user;
    this.userType = user?.userType;
    // load courses from the database using php api
    this.getCourses();
    // retrieve courses from the user session cart if present using php api
    this.getCoursesFromCart();
    this.getWeatherInfo();
  }

   isObjectEmpty(obj:Object) {
    return (obj) && (Object.keys(obj).length === 0);
  }

  // get courses from the database using php api
  getCourses() {
    this.coursesService.getCourses(this.userData?.userId, this.userData?.userType).subscribe({
      next: (data) => {
        this.courses = data;
        if(this.courses == null || (Array.isArray(this.courses) && this.courses.length == 0)) {
          this.getAllCourses();
        }
      },
      error: (error: string) => {
        this.showAlert = true;
        this.alertType = 'error';
        this.alertMessage = 'Unable to get courses.';
      },
    })
  }

  // get all courses for the user using php api
  getAllCourses() {
    this.coursesService.getAllCourses().subscribe({
      next: (data) => {
        this.courses = data;
      },
      error: (error: string) => {
        this.showAlert = true;
        this.alertType = 'error';
        this.alertMessage = 'Unable to get all courses.';
      },
    })
  }

  getWeatherInfo() {
    this.coursesService.getWeather().subscribe({
      next: (data) => {
        this.weather = data;
        console.log(this.weather)
      },
      error: (error: string) => {
        this.showAlert = true;
        this.alertType = 'error';
        this.alertMessage = 'Unable to get weather info.';
      },
    })
  }

  // delete a selected course with courseId and update the table using php api
  deleteCourse(courseId: string) {
  this.displayDeleteModalStyle = "none";
  this.displayMessageModalStyle = "block";
  this.coursesService.deleteCourse(courseId).subscribe({
      next: () => {
        this.message = "Course deleted successfully."
      },
      error: (error: string) => {
        this.message = "Unable to delete course."
      },
    })
  }

  // enroll a customer to a course using php api
  enrollCourse(courseId: string) {
    this.coursesService.enrollCourse(courseId).subscribe({
        next: () => {
          this.getCoursesFromCart(); // get user cart info to get list of courses pending enrollment
          this.getCourses(); // load courses info
          this.showAlert = false;
          this.alertType = '';
          this.alertMessage = '';
        },
        error: (error: string) => {
          this.showAlert = true;
          this.alertType = 'error';
          this.alertMessage = 'Unable to enroll in course.';
        },
      })
  }

  // withdraw a customer from a course he/she is enrolled in 
  withdrawCourse(courseId: string) {
    this.displayWithdrawModalStyle = "none";
    this.displayWithdrawMessageModalStyle = "block";
    this.coursesService.withdrawCourse(courseId).subscribe({
        next: () => {
          this.message = "Course withdrawn successfully."
        },
        error: (error: string) => {
          this.message = "Unable to withdraw course."
        },
      })
    }  

  // remove course that is pending enrollment from a user cart
  removeCourseFromCart(courseId:string) {
    this.coursesService.removeCourseFromCart(courseId).subscribe({
      next: () => {
        this.getCoursesFromCart(); // get user cart info to get list of courses pending enrollment
        this.getCourses();
      },
      error: (error: string) => {
        this.showAlert = true;
        this.alertType = 'error';
        this.alertMessage = 'Unable to remove course from cart.';
      },
    })
  }

  // get user cart info to get list of courses pending enrollment
  getCoursesFromCart(){
    this.coursesService.getCoursesFromCart().subscribe({
      next: (data) => {
        this.coursesFromCart = data;
      },
      error: (error: string) => {
        this.showAlert = true;
        this.alertType = 'error';
        this.alertMessage = 'Unable to get courses from cart.';
      },
    })
  }

  // enroll a user into the courses in cart after confirmation
  confirmCourseEnrollment() {
    this.coursesService.confirmCourseEnrollment().subscribe({
      next: (data) => {
        this.getCoursesFromCart()
        this.getCourses();
        this.showAlert = true;
        this.alertType = 'success';
        this.alertMessage = 'All selected courses enrolled successfully.';
      },
      error: (error: string) => {
        this.showAlert = true;
        this.alertType = 'error';
        this.alertMessage = 'Unable to confirm course enrollment.';
      },
    })
  }

  showDeleteModal(courseId: string) {
    this.selectedCourseId = courseId;
    this.displayDeleteModalStyle = "block";
    this.displayMessageModalStyle = "none";
  }

  closeDeleteModal() {
    this.displayDeleteModalStyle = "none";
  }

  closeMessageModal() {
    this.displayMessageModalStyle = "none";
    window.location.reload();
  }

  showWithdrawModal(courseId: string) {
    this.selectedCourseId = courseId;
    this.displayWithdrawModalStyle = "block";
    this.displayWithdrawMessageModalStyle = "none";
  }

  closeWithdrawModal() {
    this.displayWithdrawModalStyle = "none";
  }

  closeWithdrawMessageModal() {
    this.displayWithdrawMessageModalStyle = "none";
    window.location.reload();
  }

  // filter and display courses corresponding to search criteria
  onReceivingSearchedCourses(searchedCourses:Course){
    if(Array.isArray(searchedCourses) && searchedCourses.length > 0) {
      this.courses = searchedCourses;
    } else {
      this.getCourses(); // show all courses if no matching course found
    }
  }
}
