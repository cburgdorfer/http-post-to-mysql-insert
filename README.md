# HTTP Post in PHP to insert into a MySQL Database

This PHP script takes a HTTP Post and inserts it into to a MySQL database. It checks with a checksum if the data is legit and created by the intended originator.

### Installation

Take the `credentials.php.sample` script, change the credentials to your credentials and rename it to `credentials.php`

### Creating the database table

For this example, we created the a database using the below SQL:

```sql
CREATE TABLE IF NOT EXISTS `gravio_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `AreaName` varchar(128) COLLATE latin1_general_ci NOT NULL,
  `LayerName` varchar(128) COLLATE latin1_general_ci NOT NULL,
  `DataKind` varchar(128) COLLATE latin1_general_ci NOT NULL,
  `PhysicalDeviceName` varchar(128) COLLATE latin1_general_ci NOT NULL,
  `PhysicalDeviceId` varchar(128) COLLATE latin1_general_ci NOT NULL,
  `DataId` varchar(128) COLLATE latin1_general_ci NOT NULL,
  `Timestamp` datetime NOT NULL COMMENT 'Original Sensor Timestamp',
  `Data` varchar(256) COLLATE latin1_general_ci NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'MySQL Database Timestamp',
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=8 ;
```

### Original purpose of this script

I created this script so we can send sensor data from the [Gravio IoT Edge Computing Platform](https://www.gravio.com) to a [MySQL Database](https://www.mysql.com/) that is accessible via the Internet, so we can connect [Google Data Studio](https://datastudio.google.com/) to it and visualise the data in a Google Data Studio Dashboard. Note, if you make your MySQL accessible via the open internet, ensure that you [enable SSL Certificates](https://dev.mysql.com/doc/mysql-security-excerpt/5.7/en/using-encrypted-connections.html) and use a firewall to limit access to authorized IP addresses, after you have set `bind-address = 127.0.0.1` to `bind-address = 0.0.0.0` in your MySQL config file!