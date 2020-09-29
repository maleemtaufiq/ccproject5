#!/bin/bash

# Licensed under the Apache License, Version 2.0 (the "License"); you may
# not use this file except in compliance with the License. You may obtain
# a copy of the License at
#
#      http://www.apache.org/licenses/LICENSE-2.0
#
# Unless required by applicable law or agreed to in writing, software
# distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
# WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
# License for the specific language governing permissions and limitations
# under the License.

if [[ -e /etc/os-release ]]; then

    # NOTE(berendt): support for CentOS/RHEL/openSUSE/SLES will be added in the future

    source /etc/os-release

    INSTALL_DATABASE=0
    INSTALL_FAAFO=0
    INSTALL_MESSAGING=0
    RUN_API=0
    RUN_DEMO=0
    RUN_WORKER=0
    URL_DATABASE='sqlite:////tmp/sqlite.db'
    URL_ENDPOINT='http://127.0.0.1'
    URL_MESSAGING='amqp://guest:guest@localhost:5672/'

    while getopts e:m:d:i:r: FLAG; do
        case $FLAG in
            i)
                case $OPTARG in
                    messaging)
                        INSTALL_MESSAGING=1
                    ;;
                    database)
                        INSTALL_DATABASE=1
                    ;;
                    faafo)
                        INSTALL_FAAFO=1
                    ;;
                esac
            ;;
            r)
                case $OPTARG in
                    demo)
                        RUN_DEMO=1
                    ;;
                    api)
                        RUN_API=1
                    ;;
                    worker)
                        RUN_WORKER=1
                    ;;
                esac
            ;;
            e)
                URL_ENDPOINT=$OPTARG
            ;;

            m)
                URL_MESSAGING=$OPTARG
            ;;

            d)
                URL_DATABASE=$OPTARG
            ;;

            *)
                echo "error: unknown option $FLAG"
                exit 1
            ;;
        esac
    done

    if [[ $ID = 'ubuntu' || $ID = 'debian' ]]; then
        sudo apt-get update
    elif [[ $ID = 'fedora' ]]; then
        sudo dnf update -y
    fi

    if [[ $INSTALL_DATABASE -eq 1 ]]; then
        if [[ $ID = 'ubuntu' || $ID = 'debian' ]]; then
            sudo DEBIAN_FRONTEND=noninteractive apt-get install -y mysql-server php-mysql php
            # HSFD changes for Ubuntu 18.04
            sudo sed -i -e "/bind-address/d" /etc/mysql/mysql.conf.d/mysqld.cnf
            #sudo sed -i -e "/bind-address/d" /etc/mysql/my.cnf
            sudo service mysql restart
        elif [[ $ID = 'fedora' ]]; then
            sudo dnf install -y mariadb-server python-mysql
            printf "[mysqld]\nbind-address = 127.0.0.1\n" | sudo tee /etc/my.cnf.d/faafo.conf
            sudo systemctl enable mariadb
            sudo systemctl start mariadb
        else
            echo "error: distribution $ID not supported"
            exit 1
        fi
        sudo mysqladmin password password
        sudo mysql -uroot -ppassword mysql -e "CREATE DATABASE IF NOT EXISTS portfolio;CREATE USER 'aleem'@'localhost' IDENTIFIED BY 'password'; GRANT ALL PRIVILEGES ON portfolio.* TO 'aleem'@'localhost';"
        URL_DATABASE='mysql://root:password@localhost/portfolio'
        sudo chmod -R 777 /var/www/html
    fi

    if [[ $INSTALL_MESSAGING -eq 1 ]]; then
        if [[ $ID = 'ubuntu' || $ID = 'debian' ]]; then
            sudo apt-get install -y rabbitmq-server
        elif [[ $ID = 'fedora' ]]; then
            sudo dnf install -y rabbitmq-server
            sudo systemctl enable rabbitmq-server
            sudo systemctl start rabbitmq-server
        else
            echo "error: distribution $ID not supported"
            exit 1
        fi
    fi

    if [[ $INSTALL_FAAFO -eq 1 ]]; then
        if [[ $ID = 'ubuntu' || $ID = 'debian' ]]; then
            sudo apt-get install -y php libapache2-mod-php apache2
            # Following is needed because of
            # https://bugs.launchpad.net/ubuntu/+source/supervisor/+bug/1594740
            if [ $(lsb_release --short --codename) = xenial ]; then
                # Make sure the daemon is enabled.
                if ! systemctl --quiet is-enabled supervisor; then
                    systemctl enable supervisor
                fi
                # Make sure the daemon is started.
                if ! systemctl --quiet is-active supervisor; then
                    systemctl start supervisor
                fi
            fi
        elif [[ $ID = 'fedora' ]]; then
            sudo apt-get install -y php libapache2-mod-php apache2
            sudo systemctl enable supervisord
            sudo systemctl start supervisord
        #elif [[ $ID = 'opensuse' || $ID = 'sles' ]]; then
        #    sudo zypper install -y python-devel python-pip
        else
            echo "error: distribution $ID not supported"
            exit 1
        fi

        # HSFD changed to local repo
        git clone https://github.com/maleemtaufiq/ccproject5
        sudo mv ccproject5 /var/www/html
        # following line required by bug 1636150
    fi
    
else
    exit 1
fi