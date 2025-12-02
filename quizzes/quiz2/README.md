3.1
On the home page I chose to have the add/display project buttons in a menu at the top of the page as it's not enough content to fill a whole page, and it makes the website easily expandable. On the projects page I chose to go with checkboxes so it's easiest for the user to see who they already have selected. I experimented with a dropdown window with all of the users but couldn't get it to function how I wanted.

3.2
If a user came to the site and no database existed, I would have the database created after they hit register, followed by the users table including the new user. I would include the SQL statements in the PHP for the register page, and same idea with adding projects. I would add error checking to each page to catch if a database or table doesn't exist yet, make the site behave differently.

3.3
I already have a statement to prevent duplicate project entries. Before a new project is inserted, I have a SQL statement select from the table where the project name is the same as the one trying to be added. If one exists, it displays an error message to the user telling them that.

3.4.1&2
I would create a votes table with a unique ID, a foreign key to projectsID, a foreign key to userID, and a score.

3.4.3
You could prevent users from voting for their own project by finding the project they voted for in the projects table and making an array of the members of that project. After that you just loop through, checking each userID against the current session userID. You should also prevent users from voting twice. This could be done with a select query on the votes table where the userID and projectID of a vote match the one trying to be submitted.


I didn't have too much trouble with this quiz. The biggest blocker for me was figuring out how to set up Apache, MySQL, and PHPMyAdmin on my computer, as it was all done through our VM last year. I also didn't know how to hash a password in PHP, but I found out it was relatively easy. After that, I think it went pretty well.