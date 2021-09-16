## Welcome!

# Getting Started

1. Ensure you have Docker Installed [Get Docker](https://docs.docker.com/get-docker/)
2. Clone the Practical to Local via HTTPS `git clone https://github.com/JoeWrightNC/foolish.git`
3. Stand-Up Wordpress `docker-compose up`
4. Get the Name of your Container `docker ps`.  If this is your first time using docker, it will look something like hiring-practical_wordpress_1.
5. Copy Parent Theme to Docker `docker cp  YOUR-PATH/wp-content/themes/genesis YOUR-CONTAINER-NAME:/bitnami/wordpress/wp-content/themes/.`
6. Copy Child Theme to Docker `docker cp  YOUR-PATH/wp-content/themes/genesis-child YOUR-CONTAINER-NAME:/bitnami/wordpress/wp-content/themes/.`
7. Copy Typerocket v5 to Docker `docker cp  YOUR-PATH/wp-content/plugins/typerocket-v5 YOUR-CONTAINER-NAME:/bitnami/wordpress/wp-content/plugins/.`
8. Copy Custom Utility Plugin to Docker `docker cp  YOUR-PATH/wp-content/plugins/motley-fool YOUR-CONTAINER-NAME:/bitnami/wordpress/wp-content/plugins/.`
9. Enable your new Child Theme in the Admin:  [http://localhost/wp-admin](http://localhost/wp-admin)
    - Username: motleyfool
    - Password: StayFoolish!
10. Enable the Typerocket and the Motley Fool plugin in the Admin:  [http://localhost/wp-admin](http://localhost/wp-admin)
11. Navigate to [http://localhost/](http://localhost/).
12. There's a brief intro on the homepage that will introduce everything from there!