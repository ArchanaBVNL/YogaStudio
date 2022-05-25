import { Injectable } from '@angular/core'
import { Router } from '@angular/router'
import { HttpClient, HttpHeaders } from '@angular/common/http'
import { BehaviorSubject, Observable } from 'rxjs'
import { map } from 'rxjs/operators'

import { environment } from '../../environments/environment'
import { User } from '../interfaces/user'

@Injectable({ providedIn: 'root' })
export class RegistrationService {
  private userSubject: BehaviorSubject<User>
  public user: Observable<User>

  constructor(private router: Router, private http: HttpClient) {
    // If logged in then get user object (userId, userType, firstName & lastName)
    // else return empty object
    this.userSubject = new BehaviorSubject<User>(
      JSON.parse(localStorage.getItem('user') || '{}'),
    )
    this.user = this.userSubject.asObservable()
  }

  public get userValue(): User {
    // return user login information (userId, userType, firstName & lastName)
    return this.userSubject.value
  }

  // used to login a user into Yoga client when a post method is called using php api
  login(username: string, password: string) {
    return this.http
      .post<User>(
        `${environment.apiUrl}/yogastudio/api/login/authenticateUser.php`,
        { username, password },
        { withCredentials: true }, // transfer phpsessionid between client and server
      )
      .pipe(
        map((user) => {
          // add/update User object in the local storage  
          localStorage.setItem('user', JSON.stringify(user))
          // update User subject behavior with local storage data
          this.userSubject.next(user)
          // return the user object
          return user
        }),
      )
  }

  logout() {
    // on logout remove User object from local storage  
    localStorage.removeItem('user')
    // update User subject behavior with a empty user object 
    this.userSubject.next(new User())
  }

 // used to register a new user into Yoga client when a post method is called using php api
  register(user: User) {
    const headers = new HttpHeaders().set(
      'Content-Type',
      'application/json; charset=utf-8',
    )
    return this.http.post(
      `${environment.apiUrl}/yogastudio/api/users/addUser.php`,
      user,
      { headers: headers },
    )
  }
}
