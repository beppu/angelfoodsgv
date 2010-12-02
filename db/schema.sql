create table menu (
  id int not null primary key auto_increment,
  year int not null,
  month int not null,
  created_on datetime,
  modified_on timestamp,
  unique key (year, month)
);

create table menu_item (
  id int not null primary key auto_increment,
  menu_id int not null,
  day int not null,
  t enum('food', 'dismissal', 'holiday'),
  title varchar(32),
  body text,
  created_on datetime,
  modified_on timestamp
);
