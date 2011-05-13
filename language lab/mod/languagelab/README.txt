LANGUAGE LAB

This module is an audio recording module to replace traditional language labs. It's goal is to offer a high quality system that is affordable.

-------------------------------
Prerequisites
-------------------------------
Obviously, you will need a functional version of Moodle 2.0.x, full access to the root folder and admin rights within your Moodle installation.

You will need a Red5 Server. We suggest version 8. Red5 can be downloaded at the following site: http://code.google.com/p/red5/
Select the proper version for your OS. If installing version 8, you will need to install ofla demo. Follow the instructions from Red5.
You will also need an XML SOCKET SERVER. You can download Palabre at the follwoing site http://palabre.gavroche.net/.

Both Red5 and Palabre can live on the same server as they use different ports. Note that if opening ports on your firewall is an issue, install Palabre on a server in which you can
use port 80 and change the port in the Palabre config file.

Once you have both servers installed, either write down the IP address of your servers or get a FQDN for those servers. You will need it in order for Red5 and Palabre to work.
NOTE: Using localhost will NOT work.

-------------------------------
Installation
-------------------------------

1.	copy all folders (filter, mod, lib ) into the appropriate moodle(root) folder.
2.	Login to Moodle with admin rights. 
3.	In the Site Administration block, click on Notifications. This will setup the database tables for the languagelab module
4.	In the settings block, select Site administration -> Plugins ->  Activities Modules -> Manage activities -> Language lab -> settings
6.	Enter the IP address or the FQDN that you noted previously in the appropriate fields and save changes. 
        Note: If you changed the port number for Palabre, to enter in the appropriate field. 
        Also Remember, you cannot use localhost, even if Red5 and Palabre are installed on the same physical server as Moodle.

Your done. Go into a course, turn editing on and add a language lab activity.



