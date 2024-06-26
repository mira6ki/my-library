--
-- PostgreSQL database dump
--

-- Dumped from database version 16.2 (Debian 16.2-1.pgdg120+2)
-- Dumped by pg_dump version 16.2 (Debian 16.2-1.pgdg120+2)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: authors; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.authors (
    id integer NOT NULL,
    name character varying(128)
);


ALTER TABLE public.authors OWNER TO root;

--
-- Name: authors_id_seq; Type: SEQUENCE; Schema: public; Owner: root
--

CREATE SEQUENCE public.authors_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.authors_id_seq OWNER TO root;

--
-- Name: authors_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: root
--

ALTER SEQUENCE public.authors_id_seq OWNED BY public.authors.id;


--
-- Name: books; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.books (
    id integer NOT NULL,
    name character varying(128) NOT NULL,
    author integer,
    date date
);


ALTER TABLE public.books OWNER TO root;

--
-- Name: books_id_seq; Type: SEQUENCE; Schema: public; Owner: root
--

CREATE SEQUENCE public.books_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.books_id_seq OWNER TO root;

--
-- Name: books_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: root
--

ALTER SEQUENCE public.books_id_seq OWNED BY public.books.id;


--
-- Name: authors id; Type: DEFAULT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.authors ALTER COLUMN id SET DEFAULT nextval('public.authors_id_seq'::regclass);


--
-- Name: books id; Type: DEFAULT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.books ALTER COLUMN id SET DEFAULT nextval('public.books_id_seq'::regclass);


--
-- Name: authors authors_pkey; Type: CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.authors
    ADD CONSTRAINT authors_pkey PRIMARY KEY (id);


--
-- Name: books books_pkey; Type: CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.books
    ADD CONSTRAINT books_pkey PRIMARY KEY (id);


--
-- Name: books unique_name_author_id; Type: CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.books
    ADD CONSTRAINT unique_name_author_id UNIQUE (name, author);


--
-- Name: idx_authors_name; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX idx_authors_name ON public.authors USING btree (name);


--
-- Name: idx_authors_name_fts; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX idx_authors_name_fts ON public.authors USING gin (to_tsvector('simple'::regconfig, (name)::text));


--
-- Name: idx_books_name; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX idx_books_name ON public.books USING btree (name);


--
-- Name: idx_books_name_fts; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX idx_books_name_fts ON public.books USING gin (to_tsvector('simple'::regconfig, (name)::text));


--
-- Name: books fk_author; Type: FK CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.books
    ADD CONSTRAINT fk_author FOREIGN KEY (author) REFERENCES public.authors(id);


--
-- PostgreSQL database dump complete
--

