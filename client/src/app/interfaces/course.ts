export class Course {
    courseId: number;
    courseTitle: string;
    courseLevel: string;
    courseDescription: string;
    courseFee: string;
    instructorId: string;
    startDate: string;
    endDate: string;
    startTime: string;
    endTime: string;
    frequency: string;
    created: string;
    studentLimit: number;
    enrolled: boolean | null;
    classFull: boolean;
}

export class SearchCourse{
    criteria: string;
    min: string;
    max: string;
    value: string;
}