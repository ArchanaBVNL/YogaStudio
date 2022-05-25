import { Injectable } from '@angular/core';
import { Router } from '@angular/router';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { BehaviorSubject, empty, Observable } from 'rxjs';
import { map } from 'rxjs/operators';

import { environment } from '../../environments/environment';
import { Course, SearchCourse } from '../interfaces/course';
import { Weather } from '../interfaces/weather';



@Injectable({ providedIn: 'root' })
export class CoursesService {
    public course: Observable<Course>;
    public weather: Observable<Weather>;

    constructor(
        private router: Router,
        private http: HttpClient
    ) {
        
    }

    getAllCourses() {
        const headers = new HttpHeaders().set('Content-Type', 'application/json; charset=utf-8');
        let url = `${environment.apiUrl}/yogastudio/api/courses/getCourses.php`;
        return this.http.get<Course>(url, {headers: headers, withCredentials: true});
    }
    
    getCourses(id:string, userType:string) {
        const headers = new HttpHeaders().set('Content-Type', 'application/json; charset=utf-8');
        let url = `${environment.apiUrl}/yogastudio/api/courses/getCourses.php`;
        if(id !== undefined && userType !== undefined) {
            url = `${environment.apiUrl}/yogastudio/api/courses/getCourses.php?id=${id}&userType=${userType}`
        }
        return this.http.get<Course>(url, {headers: headers, withCredentials: true});
    }

    getCourseById(id:string) {
        const headers = new HttpHeaders().set('Content-Type', 'application/json; charset=utf-8');
        return this.http.get<Course>(`${environment.apiUrl}/yogastudio/api/courses/getCourseById.php?id=${id}`, {headers: headers});
    }

    enrollCourse(courseId:string) {
        let jsonObj = {"courseId":courseId};
        const headers = new HttpHeaders().set('Content-Type', 'application/json; charset=utf-8');
        return this.http.post<Course>(`${environment.apiUrl}/yogastudio/api/courses/addCoursesToCart.php`, JSON.stringify(jsonObj), {headers: headers, withCredentials: true});
    }

    withdrawCourse(courseId:string) {
        const headers = new HttpHeaders().set('Content-Type', 'application/json; charset=utf-8');
        return this.http.get<Course>(`${environment.apiUrl}/yogastudio/api/courses/withdrawCourse.php?id=${courseId}`, {headers: headers, withCredentials: true});
    }

    deleteCourse(courseId:string) {
        const headers = new HttpHeaders().set('Content-Type', 'application/json; charset=utf-8');
        return this.http.get<Course>(`${environment.apiUrl}/yogastudio/api/courses/removeCourse.php?id=${courseId}`, {headers: headers});
    }

    addCourse(course:Course) {
        const headers = new HttpHeaders().set('Content-Type', 'application/json; charset=utf-8');
        return this.http.post<Course>(`${environment.apiUrl}/yogastudio/api/courses/addCourse.php`, course, {headers: headers});
    }

    editCourse(course:Course) {
        const headers = new HttpHeaders().set('Content-Type', 'application/json; charset=utf-8');
        return this.http.put<Course>(`${environment.apiUrl}/yogastudio/api/courses/editCourse.php`, course, {headers: headers});
    }

    getCoursesForCustomer(id:string) {
        const headers = new HttpHeaders().set('Content-Type', 'application/json; charset=utf-8');
        return this.http.get<Course>(`${environment.apiUrl}/yogastudio/api/courses/getCoursesForCustomer.php?id=${id}`, {headers: headers});
    }

    getCoursesFromCart(){
        const headers = new HttpHeaders().set('Content-Type', 'application/json; charset=utf-8');
        return this.http.get<Course>(`${environment.apiUrl}/yogastudio/api/courses/getCoursesFromCart.php`, {headers: headers, withCredentials: true});
    }

    removeCourseFromCart(courseId:string) {
        const headers = new HttpHeaders().set('Content-Type', 'application/json; charset=utf-8');
        return this.http.get<Course>(`${environment.apiUrl}/yogastudio/api/courses/removeCourseFromCart.php?id=${courseId}`, {headers: headers, withCredentials: true});
    }

    confirmCourseEnrollment() {
        const headers = new HttpHeaders().set('Content-Type', 'application/json; charset=utf-8');
        return this.http.get<Course>(`${environment.apiUrl}/yogastudio/api/courses/registerAllCourses.php`, {headers: headers, withCredentials: true});
    }

    searchCourses(searchCourse:SearchCourse){
        const headers = new HttpHeaders().set('Content-Type', 'application/json; charset=utf-8');
        return this.http.post<Course>(`${environment.apiUrl}/yogastudio/api/courses/searchCourses.php`, searchCourse, {headers: headers, withCredentials: true});
    }

    getWeather() {
        const headers = new HttpHeaders().set('Content-Type', 'application/json; charset=utf-8');
        return this.http.get<Weather>(`http://api.weatherapi.com/v1/current.json?key=76dffc8ff43b4529b9614717220204&q=palatine&aqi=no`, {headers: headers});
    }
}