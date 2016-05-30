# Mining-Similarity - Diploma Thesis

**Title:** “Information system mining user similarity from social media"  
**Student:** George Goutos  
**e-mail:** ggoutos@outlook.com  
**Semester:** 2014-2015  

**Advisor:** Professor, Athanasios K. Tsakalidis  
**Contact Info:** tsak@ceid.upatras.gr  


###Description  

The Information System of this Diploma Thesis consists of two parts according to the client-server model:  

1.	The first part is the mobile application acting as the client. As for our initial demands the application had to be cross-platform, so we chose to follow the hybrid web application strategy, using PhoneGap framework and its features. The result is a fully functional cross-platform mobile application, running on both Android and iOS devices. The user through the application, gives permission to his social graph (facebook personal data, preferences, friends list etc.) in order to check how similar he is to his friends. The result takes into account his tastes in music, movies, pages as well as his checkins. We used facebook graph api in order to get the data. His music is categorized via the LastFm api and his movies via omdb api.

2.	The second part is the Database Management System (DBMS). All the data being extracted from the user’s facebook account are transferred and stored into our database. Our initial demand was that the information system could support extensive amount of users. In order to make this happen, we took advantage of the capabilities NoSQL Graph Databases offer. So we used Neo4j for our database. The mobile application communicates with our database via a web server containing all necessary RESTful web services.

**Key words:** HTML, CSS, Javascript, RESTful web services, PHP, jQuery, Ajax, NoSQL, graph databases, Neo4j, PhoneGap, mobile development

**Mining Similarity (preview):** https://www.youtube.com/watch?v=IYEciNZ6SCo

**Workflow with PhoneGap Build & Neo4j:** https://www.youtube.com/watch?v=cu9-HYqFCNk

**Thesis:** https://www.dropbox.com/s/2rpiuwr9bhi5nzp/thesis.pdf?dl=0
