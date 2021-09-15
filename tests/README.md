#How to run tests

__USE A TEST DATABASE TO RUN THE TESTS AS DATA WILL BE ALTERED__

#Setup Configuration

rename *bootstrap.php.dist* to *bootstrap.php*  
And add the path to the config file of the prestashop test instance in it

#Launch the tests

In the main directory run  

``composer test``