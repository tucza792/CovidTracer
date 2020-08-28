# -*- mode: ruby -*-
# vi: set ft=ruby :

# This Vagrantfile was configured with strong inspiration from David Eyre's Vagrant file at https://altitude.otago.ac.nz/cosc349/vagrant-multivm/-/blob/master/Vagrantfile  

Vagrant.configure("2") do |config|

  #Specify to use Ubuntu box
  config.vm.box = "ubuntu/xenial64"

  config.vm.define "webserver" do |webserver|
    webserver.vm.hostname = "webserver"
    webserver.vm.network "forwarded_port", guest: 80, host: 8080, host_ip: "127.0.0.1"
    #webserver.vm.network "private_network", ip: "192.168.2.11"

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

  config.vm.define "dbserver" do |dbserver|
    dbserver.vm.hostname = "dbserver"
    dbserver.vm.network "private_network", ip: "192.168.2.12"

    dbserver.vm.provision "shell", inline: <<-SHELL
      apt-get update

      # Create a shell variable containing the MYSQL root password
      export MYSQL_PWD='insecure_mysqlroot_pw'

      # Set up responses to the mysql-server package installer's questions so that the automated provisioning script is not interrupted
      echo "mysql-server mysql-server/root_password password $MYSQL_PWD" | debconf-set-selections 
      echo "mysql-server mysql-server/root_password_again password $MYSQL_PWD" | debconf-set-selections

      # Install the MYSQL database server.
      apt-get -y install mysql-server

      # Database setup commands
      # Create the database
      echo "CREATE DATABASE fvision;" | mysql

      # Create a database user and grant all permissions regarding the new database
      echo "CREATE USER 'webuser'@'%' IDENTIFIED BY 'insecure_db_pw';" | mysql
      echo "GRANT ALL PRIVILEGES ON fvision.* TO 'webuser'@'%'" | mysql

      # Set the MYSQL_PWD that the shell mysql command will use as the database password
      export MYSQL_PWD='insecure_db_pw'

      # Run the sql in the database setup script
      cat /vagrant/setup-database.sql | mysql -u webuser fvision
   
      # Configure MySQL to listen for requests from any network interface
      sed -i'' -e '/bind-address/s/127.0.0.1/0.0.0.0/' /etc/mysql/mysql.conf.d/mysqld.cnf

      # Restart the MySQL server to ensure that it picks up the configuration changes
      service mysql restart
    SHELL
  end
  
  
end
   
  
