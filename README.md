#Welcome to my simple library that i made for my task for TransferMate#

-> I tried to implement simple MVC design pattern for this project.

-> We have index page in which im outputting a table with all the books and their authors.

-> On the top the table i have section which contains x3 different inputs:
    - search bar where you can search for a book by name or author name
    - input to upload a file which we can use to upload a XML file to load more books
    - button which is causing automatic upload of the books stored in the project directory for that
        purpose(public->xmlFiles)
        
-> For database i use PostgreSQL database which is Running on Docker container, because i use Windows + XAMPP.

-> Im using native js to make XMLHttpRequest to the controller to get all the books from the database.

-> Im using js+css for the animation of the table output.

-> For the search functionality i did the following things:
    - added search indexes for the name of the book and the author to speedup the query
    - PostgreSQL supports GIN index for full-text-search. I added indexes for the columns that we use for search
        so this way we can have great performance even if there are a lot of database records.
        CREATE INDEX idx_books_name_fts ON books USING gin(to_tsvector('simple', name));
        CREATE INDEX idx_books_name ON books(name);
        
-> For the CRON part because i work on Windows i created container in Docker which have to run crontab.php
        on every 6 hours trough mycron file.
        
-> I use UTF-8 database to support all required symbols.

-> Database connection is established in Repository>DatabaseConnection.php.

-> Evey single value is validated before insert trough the DataValidationTrait.

-> I created LoggerService which i use to log Exceptions and to write the exception message
        in a file for debugging purposes.
        
-> The files that we try to upload are validated and only xml type is allowed to be uploaded.

-> I used CodeSniffer to fix the written code to match PSR-1 and PRS-2
