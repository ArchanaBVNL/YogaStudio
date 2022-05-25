import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

const registrationModule = () => import('./modules/registration/registration.module').then(x => x.RegistrationModule);
const coursesModule = () => import('./modules/courses/courses.module').then(x => x.CoursesModule);
const usersModule = () => import('./modules/users/users.module').then(x => x.UsersModule);

const routes: Routes = [
    { path: '', loadChildren: coursesModule }, // default module loaded on index.html
    { path: 'users', loadChildren: usersModule }, // lazy load users module when path is /users
    { path: 'registration', loadChildren: registrationModule }, // lazy load registration module when path is /registration
    { path: 'courses', loadChildren: coursesModule }, // lazy load courses module when path is /courses

    // if path not found, redirect to /courses
    { path: '**', redirectTo: '' }
];

@NgModule({
    imports: [RouterModule.forRoot(routes)],
    exports: [RouterModule]
})
export class AppRoutingModule { }
