# -*- mode: ruby -*-
# vi: set ft=ruby :

# This Vagrantfile was configured with strong inspiration from David Eyre's Vagrant file at https://altitude.otago.ac.nz/cosc349/vagrant-multivm/-/blob/master/Vagrantfile  

# All Vagrant configuration is done below. The "2" in Vagrant.configure
# configures the configuration version (we support older styles for
# backwards compatibility). Please don't change it unless you know what
# you're doing.
Vagrant.configure("2") do |config|
  # The most common configuration options are documented and commented below.
  # For a complete reference, please see the online documentation at
  # https://docs.vagrantup.com.

  # Every Vagrant development environment requires a box. You can search for
  # boxes at https://vagrantcloud.com/search.
  config.vm.box = "ubuntu/xenial64"

  # Disable automatic box update checking. If you disable this, then
  # boxes will only be checked for updates when the user runs
  # `vagrant box outdated`. This is not recommended.
  # config.vm.box_check_update = false

  # Create a forwarded port mapping which allows access to a specific port
  # within the machine from a port on the host machine. In the example below,
  # accessing "localhost:8080" will access port 80 on the guest machine.
  # NOTE: This will enable public access to the opened port
  # config.vm.network "forwarded_port", guest: 80, host: 8080

  # Create a forwarded port mapping which allows access to a specific port
  # within the machine from a port on the host machine and only allow access
  # via 127.0.0.1 to disable public access
  config.vm.network "forwarded_port", guest: 80, host: 8080, host_ip: "127.0.0.1"

  # Create a private network, which allows host-only access to the machine
  # using a specific IP.
  # config.vm.network "private_network", ip: "192.168.33.10"

  # Create a public network, which generally matched to bridged network.
  # Bridged networks make the machine appear as another physical device on
  # your network.
  # config.vm.network "public_network"

  # Share an additional folder to the guest VM. The first argument is
  # the path on the host to the actual folder. The second argument is
  # the path on the guest to mount the folder. And the optional third
  # argument is a set of non-required options.
  # config.vm.synced_folder "../data", "/vagrant_data"

  # Provider-specific configuration so you can fine-tune various
  # backing providers for Vagrant. These expose provider-specific options.
  # Example for VirtualBox:
  #
  # config.vm.provider "virtualbox" do |vb|
  #   # Display the VirtualBox GUI when booting the machine
  #   vb.gui = true
  #
  #   # Customize the amount of memory on the VM:
  #   vb.memory = "1024"
  # end
  #
  # View the documentation for the provider you are using for more
  # information on available options.

  # Enable provisioning with a shell script. Additional provisioners such as
  # Ansible, Chef, Docker, Puppet and Salt are also available. Please see the
  # documentation for more information about their specific syntax and use.
  config.vm.provision "shell", inline: <<-SHELL
    apt-get update
    apt-get install -y apache2 php libapache2-mod-php php-mysql
    
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

    
    # Change VM's webserver configuration to use the vagrant shared folder.
    cp /vagrant/tracer-website.conf /etc/apache2/sites-available/

    # install Covid-tracer website configuration and disable default configuration
    a2ensite tracer-website
    a2dissite 000-default
    service apache2 reload
  SHELL
end
