LANGUAGE LAB

This module is an audio recording module to replace the need for traditional language labs. It's goal is to offer a high quality system that is affordable.

-------------------------------
Prerequisites
-------------------------------
Obviously, you will need a functionnal version of Moodle 1.9+, full access to the root folder and admin rights within your Moodle installation.

You will also need a Red5 Server. We suggest either version 7 or 8. Red5 can be downloaded at the following site: http://code.google.com/p/red5/ 
Select the proper version for your OS. If installing version 8, you will need to install the ofla demo. Follow the instructions from Red5. Version 7 installs it by default.

Once you have your Red5 server installed, either write down the IP address of the server or get a FQDN for that server. You will need it in order for Red5 to work. Localhost will not work.

-------------------------------
Installation
-------------------------------

1.	copy this folder (languagelab) into the moodle(root)/mod folder.
2.	Login to Moodle with admin rights. 
3.	In the Site Administration block, click on Notifications. This will setup the database tables for the languagelab module
4.	In the Site Administration block, select Modules – Activities – Manage activities
5.	Locate Language lab and click on settings
6.	Enter the IP address or the FQDN that you noted previously in the field and save changes. Remember, you cannot use localhost, even if Red5 is installed on the same physical server.

Your done. Go into a course, turn editing on and add a language lab activity.



