USE education_one;

    CREATE TABLE students (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(150) NOT NULL,
        birth_date DATE NOT NULL,
        cpf VARCHAR(11) NOT NULL UNIQUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE classrooms (
        id INT AUTO_INCREMENT PRIMARY KEY,
        description VARCHAR(100) NOT NULL,
        year INT NOT NULL,
        places INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE enrollments (
        id INT AUTO_INCREMENT PRIMARY KEY,
        student_id INT NOT NULL,
        classroom_id INT NOT NULL,
        enrollment_date DATE NOT NULL,
        UNIQUE (student_id),
        FOREIGN KEY (student_id) REFERENCES students(id),
        FOREIGN KEY (classroom_id) REFERENCES classrooms(id)
    );