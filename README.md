# cosmix

Views Structure:

-    /index.html: dashboard.html (if logged in) else login.html
    Dashboard shows Number of books issued, number of books overdue, number of registered users
    
-    /admin/records: Book records (Search/Add/update/delete)
    Categories: Available, Issued, Overdue
    Send bulk email to users where books overdue
    
-    /admin/import or export data -> Import Book details from CSV or Export to CSV
    
-   /admin/issue book: Bar code scanning --> Picks up book code from books table; Sets status as issued;
    sets customer username.
    In books table, if status = issued, show error, exit.
    
-    /admin/return book: Bar code scanning --> Reverse
Show fine if due date passed
    
-    /admin/customer records (Register new user, display details, Modify Deails, Remove Users/Search Users)
    
-    /admin/profile (Admin Account Settings)
    
Database Structure:
- Customers table

- admin details

- books book_id, username, bookname, status, If issued -> Date of Issue.
