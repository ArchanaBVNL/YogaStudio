import { ComponentFixture, TestBed } from '@angular/core/testing';

import { SearchcoursesComponent } from './searchcourses.component';

describe('SearchcoursesComponent', () => {
  let component: SearchcoursesComponent;
  let fixture: ComponentFixture<SearchcoursesComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ SearchcoursesComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(SearchcoursesComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
