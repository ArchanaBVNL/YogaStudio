import { Injectable } from '@angular/core';
import { Router, CanActivate, ActivatedRouteSnapshot, RouterStateSnapshot } from '@angular/router';
import { RegistrationService } from '../services/registration.service';


@Injectable({ providedIn: 'root' })
export class AuthGuard implements CanActivate {
    constructor(
        private router: Router,
        private registrationService: RegistrationService
    ) {}

    canActivate(route: ActivatedRouteSnapshot, state: RouterStateSnapshot) {
        const user = this.registrationService.userValue;
        if (Object.keys(user).length > 0) {
            return true;
        }

        // redirect to login page if no user info found
        this.router.navigate(['/registration/login'], { queryParams: { returnUrl: state.url }});
        return false;
    }
}