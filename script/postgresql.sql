
SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET search_path = public;
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

DROP TYPE IF EXISTS course_type CASCADE;
DROP TYPE IF EXISTS course_status CASCADE;
DROP TYPE IF EXISTS user_status CASCADE;

-- Create ENUMs
CREATE TYPE course_type AS ENUM ('video', 'document');
CREATE TYPE course_status AS ENUM ('pending', 'accepted', 'ban');
CREATE TYPE user_status AS ENUM ('BANED', 'SAFE', 'ACCEPTED');

--
-- Name: categories; Type: TABLE; Schema: public;
--

CREATE TABLE public.categories (
    id_categorie SERIAL PRIMARY KEY,
    name varchar(50) NOT NULL UNIQUE
);

--
-- Name: courses; Type: TABLE; Schema: public;
--

CREATE TABLE public.courses (
    id SERIAL PRIMARY KEY,
    title varchar(100) NOT NULL,
    description text,
    teacher_id integer,
    category_id integer,
    created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    type course_type,
    document_url text,
    course_status course_status DEFAULT 'pending'
);

--
-- Name: course_tag; Type: TABLE; Schema: public;
--

CREATE TABLE public.course_tag (
    course_id integer NOT NULL,
    tag_id integer NOT NULL,
    PRIMARY KEY (course_id, tag_id)
);

--
-- Name: enrollments; Type: TABLE; Schema: public;
--

CREATE TABLE public.enrollments (
    id SERIAL PRIMARY KEY,
    student_id integer,
    course_id integer,
    enrolled_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
);

--
-- Name: tags; Type: TABLE; Schema: public;
--

CREATE TABLE public.tags (
    id_tag SERIAL PRIMARY KEY,
    tag_name varchar(50) NOT NULL UNIQUE
);

--
-- Name: users; Type: TABLE; Schema: public;
--

CREATE TABLE public.users (
    id_user SERIAL PRIMARY KEY,
    username varchar(50) NOT NULL UNIQUE,
    email varchar(100) NOT NULL UNIQUE,
    password varchar(255) NOT NULL,
    role varchar(10) NOT NULL,
    is_active boolean DEFAULT true,
    created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    status user_status,
    CONSTRAINT users_role_check CHECK (role IN ('student', 'teacher', 'admin'))
);

-- Data insertion
INSERT INTO public.categories (id_categorie, name) VALUES
(2, 'art'),
(7, 'Fay Alford'),
(9, 'i'),
(5, 'naiga'),
(4, 'test');

INSERT INTO public.users (id_user, username, email, password, role, is_active, created_at, status) VALUES
(1, 'Youness', 'bms@gmail.com', '$2y$10$0s0tS39vBUHpyMtVFPlhIOoyceh0ddAdWHf7JuTYnId1l9zaADjtu', 'teacher', true, '2025-01-11 21:40:14', 'ACCEPTED'),
(3, 'test', 'test@gmail.com', '$2y$10$xANT9viIAA0ra0953o2/uOtXWZzOF/s/fK4RHmne7IeRtoMPZ9Yt2', 'student', true, '2025-01-15 21:02:41', NULL),
(4, 'etd', 'w53573237@gmail.com', '$2y$10$4TzCocROavO7vuy3GVSgLuelLSG.W04Fii41rgMoe84zrPOPC.0Ny', 'admin', true, '2025-01-16 10:28:25', NULL),
(5, 'Testetd', 'fewab@mailinator.com', '$2y$10$o08dro0BBp7ejYX.UXYEF.lfWMnpw2gk7zWWXbnSFtsYEniGWadu6', 'student', true, '2025-01-22 12:37:08', 'SAFE'),
(6, 'etd2', 'pierre@example.com', '$2y$10$3/uS3sfIzOoIb447XPi5BOegTYCskyUSKRwyY2ZFoylcJxsCJHd16', 'teacher', true, '2025-01-22 15:55:45', 'ACCEPTED'),
(7, 'wehuzeh', 'joqar@mailinator.com', '$2y$10$aVoc7/jiy4Rc6h4mfbsZU.DgS7kY5FVVs5kGI138RGD.J4KV8R9ZG', 'student', true, '2025-01-28 17:23:00', 'SAFE');

INSERT INTO public.courses (id, title, description, teacher_id, category_id, created_at, type, document_url, course_status) VALUES
(2, 'Math', 'maths', 3, NULL, '2025-01-12 14:04:43', 'document', NULL, 'accepted'),
(3, 's', 's', 3, 2, '2025-01-12 14:12:25', 'document', NULL, 'accepted'),
(5, 'OOP', 'learn oriented programation object to be the best of the best', 3, NULL, '2025-01-12 14:16:27', 'document', NULL, 'accepted'),
(24, 'Blanditiis cillum ut', 'Anim culpa eius sed ', 1, NULL, '2025-01-25 13:25:03', 'document', 'Aut ut eaque officia', 'accepted'),
(25, 'course2', 'Reprehenderit corpo', 1, NULL, '2025-01-25 13:24:01', 'video', 'https://www.tytu.net', 'accepted'),
(36, 'Sint aliqua Laboris', 'Recusandae Earum ma', 1, NULL, '2025-01-22 16:06:46', 'video', 'https://www.tytu.net', 'accepted'),
(39, 'cours2', 'baba\r\nimpedit rec', 6, 2, '2025-01-22 16:08:01', 'video', 'https://www.buwykuny.me.uk', 'accepted'),
(40, 'Aute repudiandae per', 'Dicta earum aut sit ', 1, NULL, '2025-01-25 13:25:33', 'document', 'https://www.menezuj.org.uk', 'accepted');

INSERT INTO public.tags (id_tag, tag_name) VALUES
(1, 'C++'),
(3, 'golf'),
(2, 'JS'),
(13, 'test');

INSERT INTO public.course_tag (course_id, tag_id) VALUES
(25, 3),
(36, 3),
(39, 1),
(40, 1),
(40, 13);

INSERT INTO public.enrollments (id, student_id, course_id, enrolled_at) VALUES
(5, 3, 5, '2025-01-22 10:20:53'),
(6, 3, 2, '2025-01-22 10:21:02'),
(7, 3, 3, '2025-01-22 10:26:16'),
(9, 5, 3, '2025-01-22 12:39:25'),
(10, 3, 36, '2025-01-23 13:55:55'),
(11, 3, 25, '2025-01-25 13:26:50');

-- Reset sequences
SELECT setval('public.categories_id_categorie_seq', (SELECT MAX(id_categorie) FROM public.categories));
SELECT setval('public.courses_id_seq', (SELECT MAX(id) FROM public.courses));
SELECT setval('public.enrollments_id_seq', (SELECT MAX(id) FROM public.enrollments));
SELECT setval('public.tags_id_tag_seq', (SELECT MAX(id_tag) FROM public.tags));
SELECT setval('public.users_id_user_seq', (SELECT MAX(id_user) FROM public.users));

-- Add foreign key constraints
ALTER TABLE public.courses
    ADD CONSTRAINT courses_teacher_id_fkey FOREIGN KEY (teacher_id) REFERENCES public.users(id_user) ON DELETE SET NULL,
    ADD CONSTRAINT fk_category FOREIGN KEY (category_id) REFERENCES public.categories(id_categorie) ON DELETE SET NULL;

ALTER TABLE public.course_tag
    ADD CONSTRAINT course_tag_course_id_fkey FOREIGN KEY (course_id) REFERENCES public.courses(id) ON DELETE CASCADE,
    ADD CONSTRAINT course_tag_tag_id_fkey FOREIGN KEY (tag_id) REFERENCES public.tags(id_tag) ON DELETE CASCADE;

ALTER TABLE public.enrollments
    ADD CONSTRAINT enrollments_student_id_fkey FOREIGN KEY (student_id) REFERENCES public.users(id_user) ON DELETE CASCADE,
    ADD CONSTRAINT enrollments_course_id_fkey FOREIGN KEY (course_id) REFERENCES public.courses(id) ON DELETE CASCADE;
	