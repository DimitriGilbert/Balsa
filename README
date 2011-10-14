Balsa is a PHP "framework", the " are there because Balsa is really small, and limited on itself.
It defines a simple structure for your project with places to put each things :)
finally the installer is working
to install balsa browse your www/ folder and execute install.php from your browser
fill in the field
you should be good to go ^^

the controll panel is accessible via the admin.php page.

about the structure :
there are 2 main folders : www/ and nw/ , as there names should evoque the first one is whats accessible on the web and the second is the non-web folder :)

__________________________WWW FOLDER________________________________
start with the easy one the www/:
there's few files and a folder here : index.php, goulot.php, admin.php and the media/ folder
the index.php is the main controller for pages, it includes, if it exists, the page called by the get parameter 'page'
the goulot.php does the same with the ajax call, and includes, if it exists, the pae called by the get parameter 'page'
the admin.php  plays the same role with admin requests :)

in the media/ folder you'll find few sub-folders :
css/, which contain css.php, that serve a compressed version of the css, and the lib folder, wwhich contain the css libs
js/, which work the same way as css/
img/, which contain all the images
font/, wich contain the fonts 
and dl/ wich contain all the file to download


__________________________NW FOLDER________________________________
and now the main course, the nw/ folder :
here you'll find init.php and the following folders :
admin, ajax, data, fonction, install, media, page
the init.php file is the first part of the soft, it initialize the different constants that will be use, that's here you got to modify the different pth th the nw/ and www/ folder

the ajax/ folder contain all the controller called by goulot.php with the get parameter 'page' containing the name of the controller
to add an ajax controller simply put a file with the desired name in it and you can call it with the built-in ajax call function

the data/ folder will contain all the data of the different scripts

the fonction/ folder will contain all your scripts (functions and classes) that will be used by the different controllers (page and ajax)
it also contain a lib folder for all the lib you'll be using, for example you can allready find phpmailer here :)
the fonction.php file contains all the basic functins of the system, please don't modify it, it will be one of the most updated files :)
if you want to make modifications to this file please send them or fork on github :) like that i coud implement your work if it's useful for more ^^

the install folder is containing all the files relatives to the install process

the media/ folder contains two subfoders :
js/ where you put the js which will be compressed
css/ wich is the same for css

the page/ folder will contain all the controllers for the pages calles by index.php with the get parameter 'page', it works the same as the ajax/ folder :)

i deliberatly forgot the admin/ folder cause t's a sub system in it self, and i'll detail it when it will be more advanced ^^'

as told before when you want to add funcionnalities, code them and put them in a file in the nw/function/ folder then add the needed controllers in the nw/ajax/ or nw/page/ to use the functionnalities :)

this is it :),
in it self the project wont evove much, the structure should stay quiet identical
only the admin part will evolove.

i 've already code an installer for plugins, and will try to code a packager to make plugins with your codes :D (if you want ^^)

you can help by forking on github to bring your modifications to the system or send me the different modifictions you maid :)
i'm willing to get any help, even for comments ^^ as i'm not good for them :P

I think most of the work there is still to do is on the admin part as Balsa intend to be as light as possible on the client part.



__________________________NW/ADMIN FOLDER________________________________
let's try to detail the admin part

first of  all the auth.php wich contain all the functions to id and log in an admin, it also gives access to the different scripts present in the admin part
please dont modify this file as it will probably be updated in further versions, if you need modification there as always, fork or send them to me that i can include them :)

the the fonction/ folder wich will contain all the different functions and class files for the admin

the page/ .


the plugin/ folder will contain all the different main files for plugins in Balsa,   
	this is how work the plugin installer :
	first you create a folder with you're plugin name,
	then create fonction/, ajax/, page/ and media/ sub-folders,
	create also the js/, css/ and img/ subfolders in the media/ folder created before
	put the file you use in your plugin in the right folders (as they where in the nw/ sb-folders)
	once the structure is filled copy the void_install.xml file in your main folder and rename it install.xml
	fill the xml up as it's shown (speak for itself i think but if you need more precisions let me know :) )
	you can add more files in it like i did for the editor pluging shipped with Balsa to use it, the only constraint you got here is to put a index.php file which will serve as main controller for your plugin
	give a look at the editor plugin file and you'll get it really quickly, 
	but as said you can install you js and css file with the previously described method :)

	there's a HelloWorld plugin already shipped with Balsa so you'll see how it can work on a small plugin :)

i've allready planned to make a packager to build your plugins more simply by automating most of the task,
you'll choose the file with the explorer shiped with Balsa (and used in the editor), give a name to your package and it will do the rest :)
for further installation, as DB tables or built-in data files you'll have to script it and declenche it via your index file in your folder by using the url
admin.php?page_admin=1&module=<your_plugin_name>&<your_trigger_variable>=<your_trigger_value>
for example with a plugin named plop we could have
admin.php?page_admin=1&module=plop&install=true


As its name wants to show, Balsa is just a basic structure and a bunch of tools you can use to shape your own desire on the web and redistribute it really easily :)













