sudo debconf-set-selections <<< 'mysql-server-5.5 mysql-server/root_password password depersonalizedfarsightedbaby'
sudo debconf-set-selections <<< 'mysql-server-5.5 mysql-server/root_password_again password depersonalizedfarsightedbaby'
sudo apt-get update
sudo apt-get -y install mysql-server-5.5 php-pear php5-mysql apache2 php5
if [ ! -h /var/www ]; 
then 
    mkdir /vagrant/public
    rm -rf /var/www 
    ln -s /vagrant/public /var/www
    a2enmod rewrite
    sed -i '/AllowOverride None/c AllowOverride All' /etc/apache2/sites-available/default
    service apache2 restart
fi