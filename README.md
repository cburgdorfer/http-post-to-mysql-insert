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

### Post Data to the Script

You can use a software similar to [Postman](https://www.postman.com/) to send HTTP POST data to test the script. You will need to send a JSON document that matches the respective fields from the script. In this case, that's:

```json
{
   "AreaName":"Alarm Area",
   "Checksum":"965b1a6bfb79ae6de698db3c4d787b90",
   "Data":1,
   "DataId":"39a8140292d2469b837d2a1fe6018cab",
   "KindName":"Aqara-SingleButton",
   "LayerName":"Button",
   "PhysicalDeviceId":"47-C7-83-02-00-8D-15-00",
   "PhysicalDeviceName":"lumi.remote.b1acn01-sensor6",
   "Timestamp":1642830717
}
``` 

### Original purpose of this script

I created this script so we can send IoT sensor data from the [Gravio IoT Edge Computing Platform](https://www.gravio.com) to a [MySQL Database](https://www.mysql.com/) that is accessible via the Internet, so we can connect [Google Data Studio](https://datastudio.google.com/) to it and visualise the data in a Google Data Studio Dashboard. 

* For more information about Gravio, please visit [gravio.com](www.gravio.com) 
* For more information about how to HTTP Post sensor data from Gravio to this script, visit [doc.gravio.com/manuals/gravio4/1/en/topic/httprequest](https://doc.gravio.com/manuals/gravio4/1/en/topic/httprequest)

### Securing your Connection 
Note, if you make your MySQL accessible via the open internet, ensure that you [enable SSL Certificates](https://dev.mysql.com/doc/mysql-security-excerpt/5.7/en/using-encrypted-connections.html) and use a firewall to limit access to [authorized IP addresses](https://support.google.com/datastudio/answer/7088031?hl=en#zippy=%2Cin-this-article%2Cshow-the-list-of-ip-addresses), after you have set `bind-address = 127.0.0.1` to `bind-address = 0.0.0.0` in your MySQL config file!

To SSL secure your connection, you will need:

* The Server Certificate
* The Client Certificate
* The Client Private Key

How you retrieve these files depends on your hosting provider and/or your MySQL version. Please consult the manual of your MySQL version or hosting provider to find out more.