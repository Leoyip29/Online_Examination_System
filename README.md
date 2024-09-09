First, log in to phpMyAdmin using your database username and password.

You already get three account: trainer, con and admin
With same password: 123456

create a database call "fdm".


Method 1:

Import fdm.sql to the database, after that tables are created


Method 2:

Click on the "SQL" tab at the top of the page.

Copy and paste the following SQL query separately into the query box:
1.
CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role_name VARCHAR(255) NOT NULL UNIQUE
);

2.
INSERT INTO roles (role_name)
VALUES ('admin'), ('trainer'), ('consultant'), ('examiner');

3.
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role_id INT,
    FOREIGN KEY (role_id) REFERENCES roles(id)
);

4.
CREATE TABLE exams (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    creator_id INT NOT NULL,
    FOREIGN KEY (creator_id) REFERENCES users(id)
);

5.
CREATE TABLE questions (
  id INT NOT NULL AUTO_INCREMENT,
  exam_id INT NOT NULL,
  question_text TEXT NOT NULL,
  answer_type ENUM('mc', 'short') NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (exam_id) REFERENCES exams(id) ON DELETE CASCADE
);

6.
CREATE TABLE options (
  id INT NOT NULL AUTO_INCREMENT,
  question_id INT NOT NULL,
  option_text TEXT NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE
);

7.
CREATE TABLE answers (
  id INT NOT NULL AUTO_INCREMENT,
  user_id INT NOT NULL,
  question_id INT NOT NULL,
  answer TEXT NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE
);

8.
CREATE TABLE feedbacks (
    id INT NOT NULL AUTO_INCREMENT,
    user_id INT NOT NULL,
    exam_id INT NOT NULL,
    feedback TEXT NOT NULL,
    score INT NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (exam_id) REFERENCES exams(id) ON DELETE CASCADE
);

9.
CREATE TABLE `messages` (
  `id` int(6) UNSIGNED NOT NULL,
  `message` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

Click the "Go" button to execute the query.

