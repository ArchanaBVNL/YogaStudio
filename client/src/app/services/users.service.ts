import { Injectable } from '@angular/core';
import { Router } from '@angular/router';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { BehaviorSubject, Observable } from 'rxjs';
import { map } from 'rxjs/operators';

import { environment } from '../../environments/environment';
import { User, Instructor } from '../../app/interfaces/user'


@Injectable({ providedIn: 'root' })
export class UsersService {
    public Instructor: Observable<Instructor> | undefined;

    constructor(
        private router: Router,
        private http: HttpClient
    ) {
        
    }

    getInstructors() {
        const headers = new HttpHeaders().set('Content-Type', 'application/json; charset=utf-8');
        return this.http.get<Instructor>(`${environment.apiUrl}/yogastudio/api/users/getInstructors.php`, {headers: headers});
    }

    // get the list of customers from users table using php api
    getCustomers() {
        const headers = new HttpHeaders().set('Content-Type', 'application/json; charset=utf-8');
        return this.http.get<Instructor>(`${environment.apiUrl}/yogastudio/api/users/getCustomers.php`, {headers: headers});
    }
}