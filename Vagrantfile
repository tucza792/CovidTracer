# -*- mode: ruby -*-
# vi: set ft=ruby :

class Hash
  def slice(*keep_keys)
    h = {}
    keep_keys.each { |key| h[key] = fetch(key) if has_key?(key) }
    h
  end unless Hash.method_defined?(:slice)
  def except(*less_keys)
    slice(*keys - less_keys)
  end unless Hash.method_defined?(:except)
end

# This Vagrantfile was configured with strong inspiration from David Eyers' Vagrant file at https://altitude.otago.ac.nz/cosc349/vagrant-multivm/-/blob/master/Vagrantfile  

Vagrant.configure("2") do |config|

  #Specify to use dummy box
  config.vm.box = "dummy"

  config.vm.define "webserver" do |webserver|
    webserver.vm.hostname = "webserver"
    #webserver.vm.network "forwarded_port", guest: 80, host: 8080, host_ip: "127.0.0.1"
    #webserver.vm.synced_folder ".", "/vagrant", owner: "vagrant", group: "vagrant", mount_options: ["dmode=775,fmode=777"]

    webserver.vm.provider :aws do |aws, override|

      aws.access_key_id = "AKIAJDT2WZGOSJWFIG3A"
      aws.secret_access_key = "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"

      aws.region = "us-east-1"

      override.nfs.functional = false
      override.vm.allowed_synced_folder_types = :rsync

      aws.keypair_name = "cosc-349-pair"
      override.ssh.private_key_path = "~/.ssh/cosc-349-pair.pem"

      aws.instance_type = "t2.micro"

      aws.subnet_id = "subnet-0e5990a11fa2c0164"
      aws.security_groups = ["sg-0ba193dc35ffadc85"]

      aws.associate_public_ip = true

      aws.ami = "ami-0f40c8f97004632f9"

      override.ssh.username = "ubuntu"
    end

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
    #alertserver.vm.network "private_network", ip: "192.168.2.13"
    #alertserver.vm.synced_folder ".", "/vagrant", owner: "vagrant", group: "vagrant", mount_options: ["dmode=775,fmode=777"]

    alertserver.vm.provider :aws do |aws, override|

      aws.access_key_id = "AKIAJDT2WZGOSJWFIG3A"
      aws.secret_access_key = "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"

      aws.region = "us-east-1"

      override.nfs.functional = false
      override.vm.allowed_synced_folder_types = :rsync

      aws.keypair_name = "cosc-349-pair"
      override.ssh.private_key_path = "~/.ssh/cosc-349-pair.pem"

      aws.instance_type = "t2.micro"

      aws.subnet_id = "subnet-0e5990a11fa2c0164"
      aws.security_groups = ["sg-0ba193dc35ffadc85"]

      aws.private_ip_address = "10.0.0.97"
      aws.associate_public_ip = true

      aws.ami = "ami-0f40c8f97004632f9"

      override.ssh.username = "ubuntu"
    end

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
      mv /vagrant/app.py /home/ubuntu/flask_rest/app.py
      python app.py
      #mv /vagrant/wsgi.py /home/ubuntu/flask_rest/wsgi.py
      
      # Deactivate python virtual environment
      deactivate 


      #mv /vagrant/gunicorn_config.py /home/ubuntu/flask_rest/gunicorn_config.py
      #mv /vagrant/flaskrest.service /etc/systemd/system/flaskrest.service

      # Start the REST API
      #systemctl start flaskrest.service
      #systemctl enable flaskrest.service
      
      # Start the webserver for the REST API
      #mv /vagrant/flaskrest.conf /etc/apache2/sites-available/flaskrest.conf
      #a2ensite flaskrest.conf
      #a2dissite 000-default
      #a2enmod proxy_http
      #service apache2 reload
    SHELL
  end

end
   
  
