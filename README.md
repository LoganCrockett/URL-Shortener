# URL-Shortner
This project is designed to act similar to a URL Shortening service like bit.ly or tinyurl.com

# Purpose:
I created this project because I wanted to get an idea of how a URL shortner website like **bit.ly** or **tinyurl.com** worked. At the moment, I use the rand() function of php to generate the random URLs, but I might change it to use a hash function in the future.
# How to Run:
1. Install XAMPP from <a href="https://www.apachefriends.org/index.html">here</a> and follow the instructions for installion.
2. Once installed, download all of the files from the repo and put them into a folder. You can name it what you want, but I am going to use **URL Shortner** for my folder name.
3. Run XAMPP and click the "Explorer" icon. It will open a window showing where XAMPP was installed.
4. Go into the **htdocs** folder and place the **URL Shortner** folder into it.
5. Return to XAMPP. Click the **Start** Icon for Apache and for MySQL.
6. On the MySQL line, click **Admin.**
7. It will redirect to the **phpmyadmin** page. Click the **Import Tab**.
8. Select the **shortUrl.sql** file and click **Go** and the bottom of the page.
9. Once that finishes importing, click on the Short URL Table name on the left column, not the plus icon.
10. Click Import again. Select **newURLAccessUser.sql** and click **Go** at the bottom of the page.
11. Once that it imported, enter this URL in the address bar and hit enter: **localhost:/URL Shortner/urlShortner.html**
  - Note: If you used a different folder name, then use that name instead of what I have
12. Congrats!! It should now work!!
