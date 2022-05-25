import { RegistrationService } from 'src/app/services/registration.service';
import { Component } from '@angular/core';
import { User } from './interfaces/user';
import { Router, ActivatedRoute } from '@angular/router';


@Component({ selector: 'app-root', templateUrl: 'app.component.html' })
export class AppComponent {
    user?: User;

    constructor(private registrationService: RegistrationService,
      private route: ActivatedRoute,
      private router: Router) {
        this.registrationService.user.subscribe(x => this.user = x);
    }

    // helper method to toggle between login & logout options in app.component.html
    isEmpty(object:any) {
      return Object.keys(object).length === 0;
    }

    // on click logout button in app.component.html
    logout() {
        this.registrationService.logout();
        // on logout navigate to /courses
        this.router.navigate(['/courses']);
    }
}
