import { NgModule } from '@angular/core'
import { CommonModule } from '@angular/common'
import { ShowusersComponent } from './showusers/showusers.component'
import { RouterModule } from '@angular/router'
import { UsersRoutingModule } from './users-routing.module'
import { SharedModule } from '../shared/shared.module'

@NgModule({
  declarations: [ShowusersComponent],
  imports: [CommonModule, RouterModule, UsersRoutingModule, SharedModule],
})
export class UsersModule {}
