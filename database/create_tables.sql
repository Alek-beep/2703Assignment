drop table if exists IndPartner;
create table IndPartner (    
    id integer not null primary key autoincrement,    
    partnerName varchar(40) not null,
    location varchar(40) not null 
); 
insert into IndPartner(partnerName, location) values ("Google",  "Gold Coast");
insert into IndPartner(partnerName, location) values ("Microsoft",  "Redcliff");
insert into IndPartner(partnerName, location) values ("Apple",  "Toowoomba");
insert into IndPartner(partnerName, location) values ("Sony",  "Sunshine Coast");
insert into IndPartner(partnerName, location) values ("NSW Government",  "Brisbane");
insert into IndPartner(partnerName, location) values ("QLD Government",  "Brisbane");

drop table if exists Student;
create table Student (    
    id integer not null primary key autoincrement,    
    firstName varchar(40),
    lastName varchar(40)
); 

insert into Student(firstName, lastName) values ("Aleksander", "Karoli");
insert into Student(firstName, lastName) values ("Riley", "Woltmann");
insert into Student(firstName, lastName) values ("Lachlan", "McIntyre");
insert into Student(firstName, lastName) values ("Ally", "Ellis");
insert into Student(firstName, lastName) values ("John", "Smith");
insert into Student(firstName, lastName) values ("Hans", "Zimmerman");
insert into Student(firstName, lastName) values ("Josh", "Potter");
insert into Student(firstName, lastName) values ("Nelson", "Binney");
insert into Student(firstName, lastName) values ("Sarah", "Bradford");
insert into Student(firstName, lastName) values ("Justin", "Thyne");

drop table if exists Project;
create table Project (    
    id integer not null primary key autoincrement,    
    projectName varchar(40) not null ,
    major varchar(40) not null,
    description TEXT,
    numberStudents INT not null,
    partner_id INTEGER NOT NULL REFERENCES IndPartner(id)
);

insert into Project(projectName, major, description, numberStudents, partner_id) 
    values
    ("Android task monitoring","Software Development", "Monitoring app for android", 4, 1);
insert into Project(projectName, major, description, numberStudents, partner_id) 
    values
    ("Sentiment analysis for product rating","Information Systems", "Application for the analysis of ratings of products given to various items", 3, 2);
insert into Project(projectName, major, description, numberStudents, partner_id) 
    values
    ("Fingerprint-based ATM system","Networks and Security", "Application for fingerprint based login for ATMs focusing on security", 4, 1);
insert into Project(projectName, major, description, numberStudents, partner_id) 
    values
    ("Advanced employee management system","Information Systems", "Application for the efficient management of employees at apple", 3, 3);
insert into Project(projectName, major, description, numberStudents, partner_id) 
    values
    ("Image encryption using AES algorithm","Networks and Security", "Development of an algorithm for the efficient encryption of images", 4, 4);
insert into Project(projectName, major, description, numberStudents, partner_id) 
    values
    ("Fingerprint voting system","Networks and Security", "Improved voting system to make the identification of voters easier and more accurate", 5, 5);
insert into Project(projectName, major, description, numberStudents, partner_id) 
    values
    ("Weather forecasting system","Software Development", "A system for more accurate weather forecasting in the SEQ region", 3, 6);

drop table if exists StudentApplication;
create table StudentApplication (    
    project_id INTEGER NOT NULL REFERENCES Project(id),
    student_id INTEGER NOT NULL REFERENCES Student(id),
    preference INTEGER NOT NULL,
    justification TEXT,
    PRIMARY KEY (project_id, student_id)
);

insert into StudentApplication(project_id, student_id, justification, preference)
    values
    (1, 1, "Third year software devolopment student", 1);
insert into StudentApplication(project_id, student_id, justification, preference)
    values
    (1, 2, "Third year software devolopment student", 1);
insert into StudentApplication(project_id, student_id, justification, preference)
    values
    (1, 3, "Third year software devolopment student", 2);
insert into StudentApplication(project_id, student_id, justification, preference)
    values
    (1, 4, "Third year software devolopment student", 3);
insert into StudentApplication(project_id, student_id, justification, preference)
    values
    (1, 5, "Third year software devolopment student", 2);
insert into StudentApplication(project_id, student_id, justification, preference)
    values
    (1, 6, "Third year software devolopment student", 3);

insert into StudentApplication(project_id, student_id, justification, preference)
    values
    (2, 1, "Third year information systems student", 2);
insert into StudentApplication(project_id, student_id, justification, preference)
    values
    (2, 2, "Third year information systems student", 2);
insert into StudentApplication(project_id, student_id, justification, preference)
    values
    (2, 6, "Third year information systems student", 1);
insert into StudentApplication(project_id, student_id, justification, preference)
    values
    (2, 7, "Third year information systems student", 1);
insert into StudentApplication(project_id, student_id, justification, preference)
    values
    (2, 8, "Third year information systems student", 1);

insert into StudentApplication(project_id, student_id, justification, preference)
    values
    (3, 4, "Third year Networks and Security student", 1);
insert into StudentApplication(project_id, student_id, justification, preference)
    values
    (3, 5, "Third year Networks and Security student", 1);
insert into StudentApplication(project_id, student_id, justification, preference)
    values
    (4, 6, "Third year information systems student", 1);
insert into StudentApplication(project_id, student_id, justification, preference)
    values
    (4, 7, "Third year information systems student", 2);
insert into StudentApplication(project_id, student_id, justification, preference)
    values
    (5, 8, "Third year Networks and Security student", 2);
insert into StudentApplication(project_id, student_id, justification, preference)
    values
    (6, 9, "Third year Networks and Security student", 1);
insert into StudentApplication(project_id, student_id, justification, preference)
    values
    (7, 10, "Third year software devolopment student", 1);