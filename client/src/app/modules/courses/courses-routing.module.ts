import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { ShowcoursesComponent } from './showcourses/showcourses.component';
import { EditcourseComponent } from './editcourse/editcourse.component';
import { AddcourseComponent } from './addcourse/addcourse.component';
import { AuthGuard } from 'src/app/helpers/auth.guard';

const routes: Routes = [
    {
        path: '',
        children: [
            { path: '', component: ShowcoursesComponent },
            { path: 'showcourses', component: ShowcoursesComponent },
            { path: 'addcourse', component: AddcourseComponent, canActivate: [AuthGuard] },
            { path: 'editcourse/:id', component: EditcourseComponent, canActivate: [AuthGuard] }
        ]
    }
];

@NgModule({
    imports: [RouterModule.forChild(routes)],
    exports: [RouterModule]
})
export class CoursesRoutingModule { }
