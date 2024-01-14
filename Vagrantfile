Vagrant.configure("2") do |config|
	config.vm.box = "bento/ubuntu-22.04"
  config.vm.network "private_network", ip: "192.168.33.10"
  config.vm.network "forwarded_port", guest: 8000, host: 8000
  config.vm.synced_folder ".", "/var/www/html"  # Sync your project folder

  config.vm.provider "virtualbox" do |vb|
    vb.memory = "1024"  # Adjust memory if needed
  end

  config.vm.provision "shell", inline: <<-SHELL
    sudo apt-get update
    sudo apt-get install -y php php-cli php-mbstring php-xml
    sudo apt-get install -y composer
    sudo apt-get install -y git
    sudo apt install net-tools
    sudo apt-get install -y supervisor
    sudo apt install dos2unix
    apt-get -y install php8.1-fpm php8.1-mysql php8.1-curl zip unzip php8.1-zip php8.1-xdebug php8.1-xml php8.1-mbstring php8.1-gd php8.1-apcu php8.1-intl php8.1-soap php8.1-bcmath

    curl -LsS https://r.mariadb.com/downloads/mariadb_repo_setup | sudo bash -s -- --mariadb-server-version="mariadb-10.11"
    apt-get -y install mariadb-server mariadb-client

    echo -e "[mariadb]\ngeneral_log_file=/var/log/mysql/general.log\ngeneral_log=0" >> /etc/mysql/mariadb.cnf

    

    # add user for the database
    sudo mysql --user=root --execute="CREATE USER 'swiftsales'@'localhost' IDENTIFIED BY 'test'; GRANT ALL PRIVILEGES ON *.* TO 'swiftsales'@'localhost' WITH GRANT OPTION;"

    # Settings from 000_databaseSettings.php
    sudo mysql --user=root --execute="SET GLOBAL group_concat_max_len = 370000;"
    sudo service mysql restart

    # Migrate and seed
    cd /var/www/html/
    composer install

    sudo dos2unix initialize_local_db.sh
    sudo dos2unix after-up.sh
    sudo bash initialize_local_db.sh

    # Additional setup steps for your Lumen project
    # Navigate to your project directory, install dependencies, etc.
  SHELL

  config.vm.define "swiftsales-api-local" do |mt|
		mt.trigger.after :up do |trigger|
			trigger.info = "Running after-up..."
			trigger.run_remote = {inline: "bash /var/www/html/after-up.sh"}
		end
	end

  config.vm.hostname = "swiftsales-api-local"
	config.vm.provider :virtualbox do |vb|
		vb.customize ["modifyvm", :id, "--natdnshostresolver1", "on"]
		vb.customize ["modifyvm", :id, "--natdnsproxy1", "on"]
		vb.customize [ "guestproperty", "set", :id, "/VirtualBox/GuestAdd/VBoxService/--timesync-set-threshold", 10000 ]
		vb.name = "swiftsales-api-local"
		vb.memory = 1024
		vb.cpus = 1
	end
end
