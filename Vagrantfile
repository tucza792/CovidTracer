# -*- mode: ruby -*-
# vi: set ft=ruby :

# This Vagrantfile was configured with strong inspiration from David Eyers' Vagrant file at https://altitude.otago.ac.nz/cosc349/vagrant-multivm/-/blob/master/Vagrantfile  

Vagrant.configure("2") do |config|

  #Specify to use Ubuntu box
  config.vm.box = "ubuntu/xenial64"

  config.vm.define "webserver" do |webserver|
    webserver.vm.hostname = "webserver"
    webserver.vm.network "forwarded_port", guest: 80, host: 8080, host_ip: "127.0.0.1"
    webserver.vm.synced_folder ".", "/vagrant", owner: "vagrant", group: "vagrant", mount_options: ["dmode=775,fmode=777"]


    webserver.vm.provision "shell", inline: <<-SHELL
      apt-get update
      apt-get install -y apache2 php libapache2-mod-php php-mysql
    
      #Change VM's webserver configuration to use the vagrant shared folder.
      cp /vagrant/tracer-website.conf /etc/apache2/sites-available/
      #install Covid-tracer website configuration and disable default configuration
      a2ensite tracer-website
      a2dissite 000-default
      service apache2 reload
    SHELL
  end

  config.vm.define "alertserver" do |alertserver|
    alertserver.vm.hostname = "alertserver"
    alertserver.vm.network "private_network", ip: "192.168.2.13"
    alertserver.vm.synced_folder ".", "/vagrant", owner: "vagrant", group: "vagrant", mount_options: ["dmode=775,fmode=777"]

    alertserver.vm.provision "shell", inline: <<-SHELL
      apt-get update
      apt-get install -y python python3-venv apache2
    
      # The following block of shell code is for building a python rest api and was created with reference to 
      # https://medium.com/@thishantha17/build-a-simple-python-rest-api-with-apache2-gunicorn-and-flask-on-ubuntu-18-04-c9d47639139b
      mkdir flask_rest
      cd flask_rest

      #Create python virtual environment and activate it
      python3.5 -m venv flaskvenv
      source flaskvenv/bin/activate

      # Install necessary packages
      pip install flask
      pip install gunicorn
      pip3 install mysql-connector-python

      # Copy app.py and wsgi.py into the REST API's folder
      cp /vagrant/app.py /home/vagrant/flask_rest/
      cp /vagrant/wsgi.py /home/vagrant/flask_rest/
      
      # Deactivate python virtual environment
      deactivate 


      cp /vagrant/gunicorn_config.py /home/vagrant/flask_rest/
      cp /vagrant/flaskrest.service /etc/systemd/system/

      # Start the REST API
      systemctl start flaskrest.service
      systemctl enable flaskrest.service
      
      # Start the webserver for the REST API
      cp /vagrant/flaskrest.conf /etc/apache2/sites-available/
      a2ensite flaskrest.conf
      a2dissite 000-default
      a2enmod proxy_http
      service apache2 reload
    SHELL
  end

end
   
  
