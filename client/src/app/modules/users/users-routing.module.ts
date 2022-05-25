import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { AuthGuard } from 'src/app/helpers/auth.guard';
import { ShowusersComponent } from './showusers/showusers.component';

const routes: Routes = [
    {
        path: '',
        children: [
            { path: '', component: ShowusersComponent },
            { path: 'showusers', component: ShowusersComponent, canActivate: [AuthGuard] }
        ]
    }
];

@NgModule({
    imports: [RouterModule.forChild(routes)],
    exports: [RouterModule]
})
export class UsersRoutingModule { }
