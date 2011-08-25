create table notification (
  id int not null primary key auto_increment,
  created_on datetime,
  modified_on timestamp
  purchase_id int default NULL,
  google_serial_number varchar(64),
  google_order_number varchar(64),
  type varchar(32) not null,
  timestamp timestamp,
  xml text not null,
  is_handled tinyint(1) default 0,
);
