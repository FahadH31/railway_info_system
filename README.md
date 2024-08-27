# railway_system
A web-based information system for our (fictional) railway network.<br>

<b>Website Screenshots:</b><br>
![1](https://github.com/FahadH31/railway-system-website/assets/129327022/b9f4f38d-d7b7-40c1-8f09-f9a8b8d0cf40)<br><br>
![2](https://github.com/FahadH31/railway-system-website/assets/129327022/3774cd13-4d13-47af-819b-cf35fae440c6)<br><br>
![3](https://github.com/FahadH31/railway-system-website/assets/129327022/d49fa8d5-2de8-4cbb-b583-cd594a4d1310)<br><br>
![4](https://github.com/FahadH31/railway-system-website/assets/129327022/53e2fbd1-9ca2-49e2-8505-1e5257b7990f)<br><br>
![5](https://github.com/FahadH31/railway-system-website/assets/129327022/402d90ea-9908-4a24-9ce2-04872458b2e2)<br><br>
![6](https://github.com/FahadH31/railway-system-website/assets/129327022/a8fc1c17-6b5e-4a61-94f5-79ebfcb990f6)<br><br>
![7](https://github.com/FahadH31/railway-system-website/assets/129327022/361bed92-a24e-4da7-a209-4acdfd9af2a8)<br><br>
![8](https://github.com/FahadH31/railway-system-website/assets/129327022/bf07429a-b73f-40a0-996c-20d0052fbfe9)<br><br>

<b>How to run our code:</b>
1. Download Wamp into your 'C:' Drive
<br><br>
2. Clone our git repository and save it under 'C:\wamp64\www\'
<br><br>
3. Download MySQL Workbench 8.0 and create a workspace under the name of "RailwaySystemWebsite" with the username 'root', the password {youruserpassword},
and under port 3306. Then create a schema with the name railwaysystemwebsite and open the schema.
<br><br>
4. Copy the tables from tablesndata.txt and run them as a query within the 'railwaysystemwebsite' schema in the order of tables then views.
<br><br>
5. Open the code and under the file db-connection.php in line 4 change the password to your MySql Workbench 8.0 password. Then do the same for rest.php line 3.
<br><br>
6. Run wamp.exe on your computer to ensure that the server is connected to the database and php is able to make queries.
<br><br>
7. Open google and type in http://localhost/RailwaySystemWebsite/Home%20Page/ to access our website and interact with the database.
<br><br>
