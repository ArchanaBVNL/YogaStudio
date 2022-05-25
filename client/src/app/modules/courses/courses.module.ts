import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { CoursesRoutingModule } from './courses-routing.module';
import { ShowcoursesComponent } from './showcourses/showcourses.component';
import { EditcourseComponent } from './editcourse/editcourse.component';
import { ReactiveFormsModule } from '@angular/forms';
import { RouterModule } from '@angular/router';
import { AddcourseComponent } from './addcourse/addcourse.component';
import { SharedModule } from '../shared/shared.module';
import { SearchcoursesComponent } from './searchcourses/searchcourses.component';

@NgModule({
  declarations: [
    ShowcoursesComponent,
    EditcourseComponent,
    AddcourseComponent,
    SearchcoursesComponent
  ],
  imports: [
    CommonModule,
    ReactiveFormsModule,
    RouterModule,
    CoursesRoutingModule,
    SharedModule
  ]
})
export class CoursesModule { }
