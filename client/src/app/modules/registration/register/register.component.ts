import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { first } from 'rxjs/operators';
import { RegistrationService } from 'src/app/services/registration.service';


@Component({
  selector: 'app-register',
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.css'],
})
export class RegisterComponent implements OnInit {
    form: FormGroup;
    loading = false;
    submitted = false;
    showAlert = false;
    alertType = '';
    alertMessage = '';
    userTypes = ['customer', 'admin'];
    constructor(
        private formBuilder: FormBuilder,
        private route: ActivatedRoute,
        private router: Router,
        private registrationService: RegistrationService
    ) { }

    ngOnInit() {
        this.form = this.formBuilder.group({
            firstName: ['', Validators.required],
            lastName: ['', Validators.required],
            phoneNumber: ['', Validators.required],
            emailId: ['', Validators.required],
            userType: [null, Validators.required],
            username: ['', Validators.required],
            password: ['', [Validators.required, Validators.minLength(6)]]
        });
    }

    // convenience getter for easy access to form fields
    get f() { return this.form.controls; }

    onSubmit() {
        this.submitted = true;

        // stop here if form is invalid
        if (this.form.invalid) {
            return;
        }

        this.loading = true;
        this.registrationService.register(this.form.value)
            .pipe(first())
            .subscribe({
                next: () => {
                    this.showAlert = true;
                    this.alertType = 'success';
                    this.alertMessage = 'Registration successful.';
                    this.loading = false;
                },
                error: (error:any) => {
                    this.showAlert = true;
                    this.alertType = 'error';
                    this.alertMessage = 'Unable to register user.';
                 
                    this.loading = false;
                }
            });
    }
}